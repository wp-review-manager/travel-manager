<?php
namespace WPTravelManager\Classes\Modules\Payments\PaymentMethods\Sslcommerz;
use WPTravelManager\Classes\ArrayHelper as Arr;
use WPTravelManager\Classes\Modules\Payments\PaymentHelper;
use WPTravelManager\Classes\Modules\Payments\PaymentMethods\BasePaymentMethod;
use WPTravelManager\Classes\Models\Transaction;
use WPTravelManager\Classes\Models\Booking;
use WPTravelManager\Classes\Models\Trips;

class Sslcommerz extends BasePaymentMethod {
    public $method = 'sslcommerz';

    public function __construct()
    {
        (new SslcommerzSettings())->init();
        parent::__construct(
            'SSLCommerz',
            'sslcommerz',
            'SslCommerz is the faster, safer way to send money, make an online payment, receive money or set up a merchant account.',
            'sslcommerz.svg'
        );

        add_action('trm_make_payment_' . $this->method, array($this, 'makePayment'), 10, 4);
        add_action('trm_payment_success_' . $this->method, array($this, 'updateStatus'), 10, 2);
        add_action('trm_ipn_endpoint_' . $this->method, function () {
            $this->verifyIPN();
            exit(200);
        });
        add_filter('trm/transaction_data_sslcommerz', array($this, 'modifyTransaction'), 10, 1);
    }

    public function verifyIPN() {
        $payId = Arr::get($_POST, 'val_id');
        $payId = $payId || Arr::get($_REQUEST, 'val_id');
        $keys = $this->getApiKeys($this->method);
        $Api = new API();
        $vendorTransaction = $Api->validation($keys, $payId, $this->getPaymentMode($this->method));
        if (empty($vendorTransaction)) {
            return;
        }

        $reference = Arr::get($vendorTransaction, 'tran_id');
        $transaction = (new Transaction())->getTransaction($reference);
        $this->handleStatus($transaction, $vendorTransaction);
    }

    public function handleStatus($transaction, $vendorTransaction)
    {
        // check if already paid return
        if (!$transaction || $transaction->payment_method != $this->method || $transaction->status == 'paid') {
            return;
        }

        $bookingModel = new Booking();
        $booking = $bookingModel->getBooking($transaction->booking_id);

        $status = $vendorTransaction['status'];
        if ($status == 'VALID' || $status == 'VALIDATED') {
            $status = 'paid';
        } else {
            do_action('trm/booking_payment_failed', $booking, $booking->trip_id, $transaction, 'sslcommerz');
            return;
        }

        $updateData = array(
            'charge_id'     => Arr::get($vendorTransaction, 'val_id'),
            'payment_total' => intval(Arr::get($vendorTransaction, 'currency_amount') * 100),
            'status'        => $status,
            'card_brand'    => Arr::get($vendorTransaction, 'card_brand'),
            'payment_note'  => maybe_serialize($vendorTransaction),
        );

        $cardNo = Arr::get($vendorTransaction, 'card_no', false);
        if ($cardNo) {
            $updateData['card_last_4'] = substr($cardNo, -4);
        }

        $this->markAsPaid($status, $updateData, $transaction);
    }

    public function markAsPaid($status, $updateData, $transaction)
    {
        $bookingModel = new Booking();
        $booking = $bookingModel->getBooking($transaction->booking_id);
        $bookingData = array(
            'payment_status' => $status,
            'updated_at' => current_time('Y-m-d H:i:s')
        );

        $bookingModel->where('id', $transaction->booking_id)->update($bookingData);
        $transactionModel = new Transaction();
        $updateData['updated_at'] = current_time('Y-m-d H:i:s');
        $transaction = $transactionModel->getTransaction($transaction->id);
        $transactionModel->updateTransaction($transaction->id, $updateData);

        do_action('trm_log_data', [
            'transaction_id' => $transaction->id,
            'booking_id' => $transaction->booking_id,
            'type' => 'info',
            'created_by' => 'TRM Bot',
            'content' => sprintf(__('Transaction Marked as %s and SSLCOMMERZ Transaction ID: %s', 'travel-manager'), $status, $updateData['charge_id'])
        ]);

        if ($status === 'paid') {
            do_action('trm/booking_payment_success_sslcommerz', $booking, $transaction, $transaction->trip__id, $updateData);
            do_action('trm/booking_payment_success', $booking, $transaction, $transaction->trip_id, $updateData);
        }
    }

    public function getPaymentSettings()
    {
        $settings = PaymentHelper::mapper(
            SslcommerzSettings::globalFields(), 
            $this->getSettings(),
        );

        wp_send_json_success(array(
            'settings' => $settings,
            'webhook_url' => site_url() . '?trm_ipn_listener=1&method=sslcommerz',
        ), 200);
    }

    public function getSettings($key = null)
    {
        return SslcommerzSettings::get($key);
    }

    public function sanitize($settings)
    {
        foreach ($settings as $key => $value) {
            if (is_array($value)) {
               foreach ($value as $k => $v) {
                    if ($key === 'desc') {
                        $settings[$key][$k] = $value;
                    } else {
                        $settings[$key][$k] = sanitize_text_field($v);
                    }
               }
            } else {
                if ($key === 'desc') {
                    $settings[$key] = $value;
                } else {
                    $settings[$key] = sanitize_text_field($value);
                }
            }
        }

        return $settings;
    }

    public function modifyTransaction($transaction)
    {
        // if ($transaction->charge_id) {
        //     $sandbox = 'test' == $transaction->payment_mode ? 'sandbox.' : '';
        //     $transaction->action_url =  'https://www.' . $sandbox . 'paypal.com/activity/payment/' . $transaction->charge_id;
        // }

        // if ($transaction->status == 'requires_capture') {
        //     $transaction->additional_note = __('<b>Action Required: </b> The payment has been authorized but not captured yet. Please <a target="_blank" rel="noopener" href="' . $transaction->action_url . '">Click here</a> to capture this payment in stripe.com', 'travel-manager');
        // }

        return $transaction;
    }

    public function render($template)
    {
        $id = $this->uniqueId('sslcommerz');

        ?>
        <label class="trm_sslcommerz_card_label" for="<?php echo esc_attr($id); ?>">
            <img width="60px" src="<?php echo esc_url($this->assetUrl . 'paypal.svg'); ?>" alt="">
            <input
                    style="outline: none;"
                    type="radio" name="trm_payment_method" class="trm_sslcommerz_card" id="<?php echo esc_attr($id); ?>"
                    value="paypal"/>
        </label>
        <?php
    }

    public function isEnabled()
    {
        // TODO: Implement isEnabled() method.
        $settings = $this->getSettings();
        return $settings['is_active'] === 'yes';
    }

    public function makePayment($transactionId, $bookingId, $checkoutData, $totalPayable)
    {
        $paymentMode = $this->getPaymentMode($this->method);
        $transactionModel = new Transaction();

        if ($transactionId) {
            $transactionModel->updateTransaction($transactionId, array(
                'payment_mode' => $paymentMode
            ));
        }
        $transaction = $transactionModel->getTransaction($transactionId);

        $booking = (new Booking())->getBooking($bookingId);
        if ($transaction && $booking) {
            $this->redirect($transaction, $booking, $checkoutData, $totalPayable);
        }
    }

    public function redirect($transaction, $booking, $data, $totalPayable)
    {
        $globalSettings = $this->getApiKeys($this->method);
        if (!isset($globalSettings['api_key']) || !isset($globalSettings['api_secret'])) {
            return;
        }

        $paymentIntent = $this->makePaymentData($transaction, $booking, $data, $totalPayable);
        
        if (Arr::get($paymentIntent, 'status') === 'FAILED') {
            wp_send_json_error(array(
                'message' => __(Arr::get($paymentIntent, 'failedreason'), 'travel-manager')
            ), 423);
        }

        if (is_wp_error($paymentIntent)) {
            wp_send_json_error(array(
                'message' => __($paymentIntent->get_error_message(), 'travel-manager')
            ), 423);
        }

        // update charge_id of transaction by sessionkey here, then redirect

        if (Arr::get($paymentIntent, 'GatewayPageURL')) {
            wp_send_json_success(array(
                'redirect' => Arr::get($paymentIntent, 'GatewayPageURL'),
                'status' => 'success'
            ), 200);
        }
        
    }

    public function makePaymentData($transaction, $booking, $checkoutData, $totalPayable)
    {
        $Api = new API();

        $webhook_url = add_query_arg([
            'trm_payment_api_notify' => '1',
            'payment_method' => 'sslcommerz'
        ], site_url('index.php'));
        
        if ($totalPayable < 1) {
            return;
        }
        $transaction = Arr::get($transaction, '0', null);
        $trip_id = $transaction->trip_id;
        $trip = (new Trips())->getTrip($trip_id);
        if(!$trip) {
            wp_send_json_error( array(
                'message' => 'Trip Not Found'
            ), 400 );
        }
   
        $args = [
            'total_amount' => floatval($transaction->payment_total),
            'currency' =>  "BDT",
            'tran_id' => $transaction->transaction_hash,
            'product_category' => 'travel_manager',
            'product_profile' => 'general',
            'product_name' => $trip->post_title,
            'cus_name' => Arr::get($checkoutData, 'traveler_name', "Not collected"),
            'cus_email' => Arr::get($checkoutData, 'traveler_email', "Not collected"),
            'success_url' => $this->redirectUrl($booking),
            'fail_url' => Arr::get($checkoutData, '__trm_current_url'), // need to have current url
            'cancel_url' => Arr::get($checkoutData, '__trm_current_url'),

            'cus_add1' => Arr::get($checkoutData, 'traveler_address', 'Not collected'),
            'cus_city' => Arr::get($checkoutData, 'traveler_country', 'Not collected'),
            'cus_country' => Arr::get($checkoutData, 'traveler_country', 'Not collected'),
            'cus_phone' => Arr::get($checkoutData, 'traveler_phone', 'Not collected'), // currently we don't have phone number, but we need to have it
            'shipping_method' => 'NO',
            'ipn_url' => $webhook_url
        ];

        $keys = $this->getApiKeys($this->method);
        $keys['api_path'] = $keys['api_path'] . '/gwprocess/v4/api.php';
        return $Api->makeApiCall($keys, $args, 'POST');
    }


    public function redirectUrl($booking)
    {
        // $confirmation = ConfirmationHelper::getFormConfirmation($formId, $booking); get confirmation url
        $confirmation = array(); // temporary
        if (empty($confirmation['customUrl'])) {
            $confirmation['customUrl'] = site_url(); // Arr::get($booking, 'form_data_raw.__wpf_current_url')
        }
        return $confirmation['customUrl'];
    }

    public function getApiKeys($method) {
        $isLive = self::isLive($method);
        $settings = get_option('trm_payment_settings_' .  $method, array());

        if($isLive) {
            return array(
                'api_key' => Arr::get($settings, 'live_store_id'),
                'api_secret' => Arr::get($settings, 'live_store_pass'),
                'api_path' => 'https://securepay.sslcommerz.com'
            );
        } else {
            return array(
                'api_key' => Arr::get($settings, 'test_store_id'),
                'api_secret' => Arr::get($settings, 'test_store_pass'),
                'api_path' => 'https://sandbox.sslcommerz.com'
            );
        }
    }

}

