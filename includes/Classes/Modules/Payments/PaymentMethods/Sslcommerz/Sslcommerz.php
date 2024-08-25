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
        add_action('trm_payment_success_' . $this->method, array($this, 'handlePaymentSuccess'), 10, 1);
        add_action('trm_payment_failed_' . $this->method, array($this, 'handlePaymentFailed'), 10, 1);
        add_action('trm_payment_cancelled_' . $this->method, array($this, 'handlePaymentCancelled'), 10, 1);
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

    public function handlePaymentSuccess($data)
    {
        $bookingModel = new Booking();
        $transactionModel = new Transaction();

        $bookingId = intval(Arr::get($data, 'trm_payment'));
        $transaction = (new Transaction())->getTransactionByBookingId($bookingId);
        $booking = $bookingModel->getBooking($bookingId);

        $bookingData = array(
            'booking_status' => 'completed',
            'updated_at' => current_time('Y-m-d H:i:s')
        );
        // right now we don't know what exact data we will get from sslcommerz successUrl beside our given data
        $transactionData = array(
            'status' => 'paid',
            'updated_at' => current_time('Y-m-d H:i:s')
        );

    
        $transactionModel->updateTransaction($transaction->id, $transactionData);
        $bookingModel->where('id', $transaction->booking_id)->update($bookingData);

        do_action('trm/booking_payment_success', $booking, $transaction);
        do_action('trm/booking_payment_success_sslcommerz', $booking, $transaction);
        do_action('trm/booking_completed', $booking, $transaction);

    }

    public function markAsPaid($status, $updateData, $transaction)
    {
        $bookingModel = new Booking();
        $booking = $bookingModel->getBooking($transaction->booking_id);
        $bookingData = array(
            'booking_status' => 'completed',
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
            do_action('trm/booking_payment_success', $booking, $transaction);
            do_action('trm/booking_payment_success_sslcommerz', $booking, $transaction);
            do_action('trm/booking_completed', $booking, $transaction);
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

    public function render()
    {
     

        $id = $this->uniqueId('sslcommerz');

        ?>
        <label class="trm_sslcommerz_card_label" for="<?php echo esc_attr($id); ?>">
            <input
                style="outline: none;"
                type="radio" name="trm_payment_method" class="trm_sslcommerz_card" id="<?php echo esc_attr($id); ?>"
                value="sslcommerz"
            />
            <img width="72px" src="<?php echo esc_url($this->assetUrl . 'sslcommerz.svg'); ?>" alt="">
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

    public function makeRefund($transactionId, $amount)
    {
        $transactionModel = new Transaction();
        $transaction = $transactionModel->getTransaction($transactionId);
        $keys = $this->getApiKeys($this->method);
        $Api = new API();

        $refundArgs = array(
            'bank_tran_id' => $transaction->bank_tran_id,
            'refund_amount' => $amount,
            'refund_ref_id' => $transaction->transaction_hash,
            'refund_remarks' => 'Refund for ' . $transaction->transaction_hash
        );

        $refund = $Api->refundApi($keys, $refundArgs, 'refund');
        if (is_wp_error($refund)) {
            wp_send_json_error(array(
                'message' => $refund->get_error_message()
            ), 423);
        }

        // query refund status
        $queryArgs = array(
            'refund_ref_id' => $refund['refund_ref_id']
        );

        $refundQueryResponse = $Api->refundApi($keys, $queryArgs, 'query');

        $transactionModel->updateTransaction($transactionId, array(
            'status' => Arr::get($refundQueryResponse, 'status'),
            'updated_at' => current_time('Y-m-d H:i:s')
        ));

        do_action('trm_log_data', [
            'transaction_id' => $transactionId,
            'booking_id' => $transaction->booking_id,
            'type' => 'info',
            'created_by' => 'TRM Bot',
            'content' => sprintf(__('Refund status for transaction %s is %s', 'travel-manager'), $transaction->transaction_hash, Arr::get($refundQueryResponse, 'status'))
        ]);


        if (Arr::get($refundQueryResponse, 'status') == 'refunded') {
           do_action('trm/booking_payment_refunded', $transaction->booking_id, $transactionId, $amount);
        }

        return $refund;
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
            do_action('trm_log_data', [
                'trip_id' => $booking->trip_id,
                'booking_id' => $booking->id,
                'type' => 'activity',
                'created_by' => 'TRM BOT',
                'title' => 'Sslcommerz Payment Webhook Error',
                'content' => $paymentIntent->get_error_message()
            ]);
            wp_send_json_error(array(
                'message' => __($paymentIntent->get_error_message(), 'travel-manager')
            ), 423);
        }

        // update charge_id of transaction by sessionkey here, then redirect, to query the transaction status any time (if required).
        // we may add another column in transaction table to store the sessionkey, so that we can query the transaction status any time.
        $transactionModel = new Transaction();
        $transactionModel->updateTransaction($transaction->id, array(
            'session_key' => Arr::get($paymentIntent, 'sessionkey')
        ));

        do_action('trm_log_data', [
            'transaction_id' => $transaction->id,
            'booking_id' => $transaction->booking_id,
            'type' => 'info',
            'created_by' => 'TRM Bot',
            'content' => sprintf(__('User redirect to Sslcommerz for completing the payment. Session Key: %s', 'travel-manager'), Arr::get($paymentIntent, 'sessionkey'))
        ]);

        if (Arr::get($paymentIntent, 'GatewayPageURL')) {
            wp_send_json_success([
                'message' => __('You are redirecting to sslcommerz.com to complete the purchase. Please wait while you are redirecting....', 'wp-payment-form-pro'),
                'call_next_method' => 'normalRedirect',
                'redirect_url' => Arr::get($paymentIntent, 'GatewayPageURL')
            ], 200);
        }
        
    }

    public function makePaymentData($transaction, $booking, $checkoutData, $totalPayable)
    {
        $Api = new API();

        $webhook_url = add_query_arg([
            'trm_ipn_listener' => '1',
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

        // get necessary urls
        $success_url = $this->redirectUrl($booking, Arr::get($checkoutData, '__trm_current_url'));
        $fail_url = $this->failureUrl($booking, Arr::get($checkoutData, '__trm_current_url'));
        $cancel_url = $this->cancelUrl($booking, Arr::get($checkoutData, '__trm_current_url'));
   
        $args = [
            'total_amount' => floatval($transaction->payment_total),
            'currency' =>  "BDT",
            'tran_id' => $transaction->transaction_hash,
            'product_category' => 'travel_manager',
            'product_profile' => 'general',
            'product_name' => $trip->post_title,
            'cus_name' => Arr::get($checkoutData, 'traveler_name', "Not collected"),
            'cus_email' => Arr::get($checkoutData, 'traveler_email', "Not collected"),
            'success_url' => $success_url,
            'fail_url' => $fail_url,
            'cancel_url' => $cancel_url,

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


    public function redirectUrl($booking, $currentUrl)
    {
        $confirmation = array(); // temporary
        // $confirmation = ConfirmationHelper::getFormConfirmation($formId, $booking); get confirmation url

        $confirmation['successUrl'] = Arr::get($confirmation, 'successUrl') ? Arr::get($confirmation, 'successUrl') : $currentUrl;
        return add_query_arg(array(
            'payment_status' => 'success',
            'trm_payment' => $booking->id,
            'payment_method' => $this->method,
            'booking_hash' => $booking->booking_hash
        ), $confirmation['successUrl']);
 
    }

    public function failureUrl($booking, $currentUrl)
    {
        $confirmation = array(); // temporary
        // $confirmation = ConfirmationHelper::getFormConfirmation($formId, $booking); get confirmation url
        $confirmation['failureUrl'] = Arr::get($confirmation, 'failureUrl') ? Arr::get($confirmation, 'failureUrl') : $currentUrl;

        return add_query_arg(array(
            'payment_status' => 'failed',
            'trm_payment' => $booking->id,
            'payment_method' => $this->method,
            'booking_hash' => $booking->booking_hash
        ), $confirmation['failureUrl']);
    }

    public function cancelUrl($booking, $currentUrl)
    {
        $confirmation = array(); // temporary
        // $confirmation = ConfirmationHelper::getFormConfirmation($formId, $booking); get confirmation url
        $confirmation['cancelUrl'] = Arr::get($confirmation, 'cancelUrl') ? Arr::get($confirmation, 'cancelUrl') : $currentUrl;

        return add_query_arg(array(
            'payment_status' => 'cancelled',
            'trm_payment' => $booking->id,
            'payment_method' => $this->method,
            'booking_hash' => $booking->booking_hash
        ), $confirmation['cancelUrl']);
    }

    public function getApiKeys($method) {
        $isLive = self::isLive($method);
        $settings = get_option('trm_payment_settings_' .  $method, array());

        if($isLive) {
            return array(
                'api_key' => Arr::get($settings, 'live_store_id'),
                'api_secret' => Arr::get($settings, 'live_store_pass'),
                'api_path' => 'https://securepay.sslcommerz.com',
                'payment_mode' => 'live'
            );
        } else {
            return array(
                'api_key' => Arr::get($settings, 'test_store_id'),
                'api_secret' => Arr::get($settings, 'test_store_pass'),
                'api_path' => 'https://sandbox.sslcommerz.com',
                'payment_mode' => 'test'
            );
        }
    }

    public function addTransactionUrl($transactions, $bookingId)
    {
        $paymentMode = $this->getPaymentMode($this->method);
        $transPath = 'https://sandbox.sslcommerz.com/manage/';
        if ($paymentMode == 'live') {
            $transPath = 'https://securepay.sslcommerz.com/manage/';
        };

        foreach ($transactions as $transaction) {
            if ($transaction->charge_id) {
                $transaction->transaction_url =  $transPath;
                $payNote = maybe_unserialize($transaction->payment_note);
                if (!empty($payNote)) {
                    $currency = Arr::get($payNote, 'currency', '-');
                    $converted = [
                        'Conversion Type' => 'From ' . Arr::get($payNote, 'currency_type', '-') . ' to ' . $currency,
                        'Rate' => Arr::get($payNote, 'currency_rate') . ' ' . $currency,
                        'Calculated Total' => Arr::get($payNote, 'amount') . ' ' . $currency,
                        'Store amount' => '<strong>' . Arr::get($payNote, 'store_amount') . ' ' . $currency . '</strong>',
                        'Card Issuer' => Arr::get($payNote, 'card_issuer')
                    ];
                    $transaction->converted_currency = $converted;
                }
            }
        }
        return $transactions;
    }

    public function getLastTransaction($bookingId)
    {
        $transactionModel = new Transaction();
        $transaction = $transactionModel->where('booking_id', $bookingId)
            ->first();
        return $transaction;
    }

}

