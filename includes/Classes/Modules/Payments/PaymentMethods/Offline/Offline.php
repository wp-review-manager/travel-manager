<?php
namespace WPTravelManager\Classes\Modules\Payments\PaymentMethods\Offline;
use WPTravelManager\Classes\ArrayHelper as Arr;
use WPTravelManager\Classes\Modules\Payments\PaymentHelper;
use WPTravelManager\Classes\Modules\Payments\PaymentMethods\BasePaymentMethod;
use WPTravelManager\Classes\Models\Transaction;
use WPTravelManager\Classes\Models\Booking;
use WPTravelManager\Classes\Models\Trips;

class Offline extends BasePaymentMethod {
    public $method = 'offline';

    public function __construct()
    {
        (new OfflineSettings())->init();
        parent::__construct(
            'Offline',
            'offline',
            'Cash On Delivery or Bank Transfer',
            'offline.svg'
        );

        add_action('trm_make_payment_' . $this->method, array($this, 'makePayment'), 10, 4);
    }


    public function getPaymentSettings()
    {
        $settings = PaymentHelper::mapper(
            OfflineSettings::globalFields(), 
            $this->getSettings(),
        );

        wp_send_json_success(array(
            'settings' => $settings,
            'webhook_url' => site_url() . '?trm_ipn_listener=1&method=sslcommerz',
        ), 200);
    }

    public function getSettings($key = null)
    {
        return OfflineSettings::get($key);
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

    public function render()
    {
     

        $id = $this->uniqueId('offline');

        ?>
        <label class="trm_sslcommerz_card_label" for="<?php echo esc_attr($id); ?>">
            <input
                style="outline: none;"
                type="radio" name="trm_payment_method" class="trm_sslcommerz_card" id="<?php echo esc_attr($id); ?>"
                value="offline"
            />
            <img width="72px" src="<?php echo esc_url($this->assetUrl . 'offline.svg'); ?>" alt="">
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
        $redirectUrl = Arr::get($checkoutData, '__trm_current_url') ? Arr::get($checkoutData, '__trm_current_url') : site_url();

        wp_send_json_success([
            'message' => __('You are redirecting to sslcommerz.com to complete the purchase. Please wait while you are redirecting....', 'wp-payment-form-pro'),
            'call_next_method' => 'normalRedirect',
            'redirect_url' => $redirectUrl,
        ], 200);
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

}

