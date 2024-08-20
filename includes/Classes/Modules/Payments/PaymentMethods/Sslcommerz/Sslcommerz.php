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
            'PayPal is the faster, safer way to send money, make an online payment, receive money or set up a merchant account.',
            'sslcommerz.svg'
        );

        add_action('trm_make_payment_' . $this->method, array($this, 'makePayment'), 10, 4);
        add_action('trm_paypal_action_web_accept', array($this, 'updateStatus'), 10, 2);
        add_action('trm_ipn_endpoint_' . $this->method, function () {
            $this->verifyIPN();
            exit(200);
        });
        add_filter('trm/transaction_data_paypal', array($this, 'modifyTransaction'), 10, 1);
    }

    public function verifyIPN() {
        error_log('debug', $_POST['id'] );
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

    public function makePayment($transactionId, $bookingId, $data, $totalPayable)
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
            $this->redirect($transaction, $booking, $data, $totalPayable);
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

        if (Arr::get($paymentIntent, 'redirectGatewayURL')) {
            wp_send_json_success(array(
                'redirect' => Arr::get($paymentIntent, 'redirectGatewayURL'),
                'status' => 'success'
            ), 200);
        }
        
    }

    public function makePaymentData($transaction, $booking, $form_data, $totalPayable)
    {
        $Api = new API();

        $success_url = TRM_URL . '/booking_success';

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
            'total_amount' => floatval($totalPayable),
            'currency' =>  'USD',
            'tran_id' => $transaction->id,
            'product_category' => 'travel_manager',
            'product_profile' => 'general',
            'product_name' => $trip->post_title,
            'cus_name' => Arr::get($form_data, 'traveler_name', "Not collected"),
            'cus_email' => Arr::get($form_data, 'traveler_email', "Not collected"),
            'success_url' => $success_url,
            'fail_url' => $success_url,
            'cancel_url' => $success_url,

            'cus_add1' => Arr::get($form_data, 'traveler_address', 'Not collected'),
            'cus_city' => Arr::get($form_data, 'traveler_country', 'Not collected'),
            'cus_country' => Arr::get($form_data, 'traveler_country', 'Not collected'),
            'cus_phone' => "Not collected",
            'shipping_method' => 'NO',
            'ipn_url' => $webhook_url
        ];

        $keys = $this->getApiKeys($this->method);
        $keys['api_path'] = $keys['api_path'] . '/gwprocess/v4/api.php';
        return $Api->makeApiCall($keys, $args, 'POST');
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

