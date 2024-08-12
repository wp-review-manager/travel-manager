<?php

namespace WPTravelManager\Classes\Modules\Payments\PaymentMethods\PayPal;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use WPTravelManager\Classes\ArrayHelper;
use WPTravelManager\Classes\Modules\Payments\PaymentMethods\PayPal\API\IPN;
use WPTravelManager\Classes\Modules\Payments\PaymentHelper;
use WPTravelManager\Classes\Modules\Payments\PaymentMethods\BaseProcessor;
use WPTravelManager\Classes\Models\Transaction;
use WPTravelManager\Classes\Models\Booking;
use WPTravelManager\Classes\Modules\Payments\PaymentMethods\BasePaymentMethod;

class PayPal extends BasePaymentMethod
{
    public function __construct()
    {
        (new PayPalSettings())->init();
        parent::__construct(
            'PayPal',
            'paypal',
            'PayPal is the faster, safer way to send money, make an online payment, receive money or set up a merchant account.',
            'paypal.svg'
        );
        add_action('trm_make_payment_paypal', array($this, 'makePayment'), 10, 3);
        add_action('trm_paypal_action_web_accept', array($this, 'updateStatus'), 10, 2);
        add_action("trm_ipn_endpoint_paypal", array($this, 'verifyIpn'), 10, 2);
        add_filter('trm/transaction_data_paypal', array($this, 'modifyTransaction'), 10, 1);
    }

    public function sanitize($settings)
    {
        foreach ($settings as $key => $value) {
            if ($key === 'paypal_email') {
                $settings[$key] = sanitize_email($value);
            } else {
                $settings[$key] = sanitize_text_field($value);
            }
        }
        return $settings;
    }

    public function paymentConfirmation()
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $chargeId = isset($_REQUEST['charge_id']) ? sanitize_text_field($_REQUEST['charge_id']) : '';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $hash = isset($_REQUEST['hash']) ?  sanitize_text_field($_REQUEST['hash']) : '';

        $this->updatePayment($chargeId, $hash);
    }
    public function verifyIpn()
    {
        (new IPN())->ipnVerificationProcess();
    }

    public function getPaymentSettings()
    {
        $settings = PaymentHelper::mapper(
            PayPalSettings::globalFields(), 
            $this->getSettings()
        );

        wp_send_json_success(array(
            'settings' =>$settings,
            'webhook_url' => site_url() . '?trm_ipn_listener=1&method=paypal'
        ), 200);
    }

    public function getSettings($key = null)
    {
        return PayPalSettings::get($key);
    }

    public function maybeShowModal($transactions, $paypalSettings)
    {
        $paymentIntent = $this->modalPaymentIntent($transactions);
        $responseData = [
            'nextAction' => 'paypal',
            'hash' => $transactions->entry_hash,
            'actionName' => 'custom',
            'buttonState' => 'hide',
            'purchase_units' => $paymentIntent,
            'confirmation_url' => $this->successUrl($transactions),
            'message_to_show' => __('Payment Modal is opening, Please complete the payment', 'buy-me-coffee'),
        ];

        wp_send_json_success($responseData, 200);
    }

    public function modalPaymentIntent($transactions)
    {
        $total = $transactions->payment_total ? $transactions->payment_total : 5;
        $total = $total / 100;
        $currencyCode = $transactions->currency;
        $intent = [
            'reference_id' => $transactions->entry_hash,
            'amount' => [
                'value' => $total,
                'breakdown' => [
                    'item_total' => [
                        'currency_code' => $currencyCode,
                        'value' => $total,
                    ]
                ]
            ],
            'items' => array([
                'name' => 'Buy coffee for you',
                'unit_amount' => [
                    'currency_code' => $currencyCode,
                    'value' => $total,
                ],
                'quantity' => '1',
            ])
        ];

        return apply_filters('buymecoffee_paypal_modal_payment_intent', $intent, $transactions);
    }

    public function makePayment($transactionId, $booking, $form_data)
    {
        $paypalSettings = $this->getSettings();
        $transactionModel = new Transaction();
        $transaction = $transactionModel->find($transactionId);

        if ($paypalSettings['payment_type'] === 'pro') {
            $this->maybeShowModal($transaction, $paypalSettings);
        }

        $paypal_redirect = 'https://www.paypal.com/cgi-bin/webscr/?';

        if ($paypalSettings['payment_mode'] === 'test') {
            $paypal_redirect = 'https://www.sandbox.paypal.com/cgi-bin/webscr/?';
        }

        $listener_url = apply_filters('trm_paypal_ipn_url', site_url('?trm_ipn_listener=1&method=paypal'), $booking);

        $customArgs =  array(
            'trm_id'  => $booking->id
        );

        if ($transaction) {
            $customArgs['transaction_hash'] = $transaction->transaction_hash;
        } else {
            $customArgs['entry_uid'] = $booking->id;
        }

        $paypal_args = array(
            'cmd' => '_cart',
            'upload' => '1',
            'business' => $paypalSettings['paypal_email'],
            'email' => $form_data->payer_email,
            'no_shipping' => '1',
            'no_note' => '1',
            'currency_code' => $form_data->currency ?? 'USD',
            'charset' => 'UTF-8',
            'custom' => $customArgs,
            'return' => $this->successUrl($booking),
            'notify_url' => $listener_url,
            'cancel_return' => $this->failedUrl($booking),
            'image_url' => '',
        );

        $payment_item = $this->cartItems($transaction);

        if (!$payment_item) {
            return;
        }
        $paypal_args = array_merge($payment_item, $paypal_args);

        $paypal_args = apply_filters('buymecoffee_paypal_payment_args', $paypal_args);

        if (!$payment_item && $paypal_args['cmd'] == '_cart') {
            return;
        }

        $booking->update($booking->id, array(
            'payment_mode' => $paypalSettings['payment_mode']
        ));

        if ($transactionId) {
            $transactionModel->update($transactionId, array(
                'payment_mode' => $paypalSettings['payment_mode']
            ));
        }

        $paypal_redirect .= http_build_query($paypal_args);

        wp_send_json_success(array(
            'message' => __('You are redirected for payment.', 'buy-me-coffee'),
            'id' => $booking->id,
            'redirectTo' => $paypal_redirect,
            'messageToShow' => __('Your are redirecting to paypal now', 'buy-me-coffee')
        ), 200);
        exit;
    }

    public function cartItems($item)
    {
        $paypal_args = array(
            'item_name_1' => 'coffee_payment',
            'quantity_1' => 1,
            'amount_1' => round($item->payment_total / 100, 2)
        );

        return $paypal_args;
    }

    public function successUrl($booking)
    {
        return add_query_arg(array(
            'share_coffee' => '',
            'buymecoffee_success' => 1,
            'hash' => $booking->booking_hash,
            'payment_method' => 'paypal'
        ), home_url());
    }

    public function failedUrl($booking)
    {
        return add_query_arg(array(
            'buymecoffee_failed' => 1,
            'share_coffee' => '',
            'buymecoffee_submission' => $booking->booking_hash,
            'payment_method' => 'paypal'
        ), home_url());
    }

    public function updateStatus($data, $payment_id)
    {
        if ($data['txn_type'] != 'web_accept' && $data['txn_type'] != 'cart' && $data['payment_status'] != 'Refunded') {
            return;
        }

        if (empty($payment_id)) {
            return;
        }

        $transaction = (new Transaction())->find($payment_id);

        if (defined('TRM_PAYPAL_IPN_DEBUG')) {
            error_log('IPN For Transaction: ' . wp_json_encode($transaction));
        }

        if (!$transaction) {
            return;
        }

        if ($transaction->payment_method != 'paypal') {
            return;
        }

        $currency_code = strtolower($data['mc_currency']);

        if ($currency_code != strtolower($transaction->currency)) {
            $this->changeStatus('failed', $transaction);
            return;
        }

        $payment_status = strtolower($data['payment_status']);

        $paypal_amount = $data['mc_gross'];
        $isMismatchAmount = false;
        if (number_format((float)($transaction->payment_total / 100), 2) - number_format((float)$paypal_amount, 2) > 1) {
            $isMismatchAmount = true;
        }

        if ($isMismatchAmount) {
            $this->changeStatus('failed', $transaction);
            return;
        }

        if ('completed' == $payment_status || $transaction->payment_mode == 'test') {
            $this->changeStatus('paid', $transaction, $data);
            return;
        }

        if ('pending' == $payment_status && isset($data['pending_reason'])) {
            $this->changeStatus('processing', $transaction, $data);
        }
    }

    public function changeStatus($status, $transaction = null,  $data = array())
    {
        $bookingModel = new Booking();
        $transactionModel = new Transaction();

        $updateData = array(
            'payment_status' => $status,
            'updated_at' => current_time('mysql')
        );

        $bookingModel->update($transaction->entry_id, $updateData);

        if (!empty($data) && isset($data['txn_id'])) {
            $updateData = [
                'status' => $status,
                'updated_at' => current_time('mysql'),
                'payment_note' => wp_json_encode($data),
                'charge_id' => sanitize_text_field($data['txn_id'])
            ];
        }

        $transactionModel->update($transaction->id, $updateData);

    }

    public function maybeLoadModalScript()
    {
        $settings = $this->getSettings();
        if ($settings['payment_type'] == 'standard') {
            return;
        };

        $mode = $settings['payment_mode'];
        $clientId = $mode === 'test' ? $settings['test_public_key'] : $settings['live_public_key'];

        //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
        wp_enqueue_script('travel-manager-checkout-sdk-' . $this->method, 'https://www.paypal.com/sdk/js?client-id=' . $clientId, [], null, false);

        wp_enqueue_script('travel-manager-checkout-handler-' . $this->method, 'js/PaymentMethods/paypal-checkout.js', ['travel-manager-checkout-sdk-paypal', 'jquery'], TRM_VERSION, false);

    }

    public function render($template)
    {
        $this->maybeLoadModalScript();
        $id = $this->uniqueId('paypal_card');

        ?>
        <label class="trm_paypal_card_label" for="<?php echo esc_attr($id); ?>">
            <img width="60px" src="<?php echo esc_url($this->assetUrl . 'paypal.svg'); ?>" alt="">
            <input
                    style="outline: none;"
                    type="radio" name="trm_payment_method" class="trm_paypal_card" id="<?php echo esc_attr($id); ?>"
                    value="paypal"/>
        </label>
        <?php
    }

    public function modifyTransaction($transaction)
    {
        if ($transaction->charge_id) {
            $sandbox = 'test' == $transaction->payment_mode ? 'sandbox.' : '';
            $transaction->action_url =  'https://www.' . $sandbox . 'paypal.com/activity/payment/' . $transaction->charge_id;
        }

        if ($transaction->status == 'requires_capture') {
            $transaction->additional_note = __('<b>Action Required: </b> The payment has been authorized but not captured yet. Please <a target="_blank" rel="noopener" href="' . $transaction->action_url . '">Click here</a> to capture this payment in stripe.com', 'travel-manager');
        }

        return $transaction;
    }


    public function updatePayment($chargeId, $hash)
    {

        $transactionModel = new Transaction();
        $bookingModel = new Booking();

        if ($chargeId == '' || $hash == '') {
            wp_send_json_error(array(
                'message' => __('Invalid request', 'buy-me-coffee'),
            ), 400);
        }

        $transaction = array(
            'charge_id' => $chargeId,
            'status' => 'paid-initially',
            'updated_at' => current_time('mysql'),
        );

        $transactionId = $transactionModel->where('charge_id', $chargeId)
            ->update($transaction);

        $bookingModel->where('booking_hash', $hash)
            ->where('entry_hash', $hash)
            ->update(['payment_status' => 'paid-initially']);

        wp_send_json_success(array(
            'message' => __('Payment updated successfully', 'buy-me-coffee'),
            'data' => $transactionId
        ), 200);
    }

    public function isEnabled()
    {
        // TODO: Implement isEnabled() method.
        $settings = $this->getSettings();
        return $settings['is_active'] === 'yes';
    }
}