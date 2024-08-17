<?php
namespace WPTravelManager\Classes\Modules\Payments\PaymentMethods\Sslcommerz;
use WPPayForm\Framework\Support\Arr;
use WPTravelManager\Classes\Modules\Payments\PaymentHelper;
use WPTravelManager\Classes\Modules\Payments\PaymentMethods\BasePaymentMethod;
use WPTravelManager\Classes\Models\Transaction;

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
        add_action("trm_ipn_endpoint_paypal", array($this, 'verifyIpn'), 10, 2);
        add_filter('trm/transaction_data_paypal', array($this, 'modifyTransaction'), 10, 1);
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
        dd($transaction);
    }

}

