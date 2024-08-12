<?php

namespace WPTravelManager\Classes\Modules\Payments\PaymentMethods\PayPal\API;

if (!defined('ABSPATH')) {
    die();
}

use WPTravelManager\Classes\Models\Transaction;
use WPTravelManager\Classes\Models\Booking;
use WPTravelManager\Classes\ArrayHelper;

class IPN
{
    public function ipnVerificationProcess()
    {
        if (isset($_SERVER['REQUEST_METHOD']) && sanitize_text_field($_SERVER['REQUEST_METHOD']) != 'POST') {
            return;
        }

        $bookingId = intval(ArrayHelper::get($_GET, 'booking_id'));

        header("HTTP/1.1 200 OK");

        $post_data = '';
        if (ini_get('allow_url_fopen')) {
            $post_data = file_get_contents('php://input');
        } else {
            ini_set('post_max_size', '12M');
        }
        $encoded_data = 'cmd=_notify-validate';
        $arg_separator = ini_get('arg_separator.output');
        if ($post_data || strlen($post_data) > 0) {
            $encoded_data .= $arg_separator . $post_data;
        } else {
            // phpcs:ignore WordPress.Security.NonceVerification.Missing
            if (empty($_POST)) {
                return;
            } else {
                foreach ($_POST as $key => $value) {
                    $encoded_data .= $arg_separator . "$key=" . urlencode($value);
                }
            }
        }

        parse_str($encoded_data, $encoded_data_array);

        foreach ($encoded_data_array as $key => $value) {
            if (false !== strpos($key, 'amp;')) {
                $new_key = str_replace('&amp;', '&', $key);
                $new_key = str_replace('amp;', '&', $new_key);
                unset($encoded_data_array[$key]);
                $encoded_data_array[$new_key] = $value;
            }
        }
        $defaults = array(
            'txn_type' => '',
            'payment_status' => '',
            'custom' => ''
        );

        $encoded_data_array = wp_parse_args($encoded_data_array, $defaults);
        $customJson = ArrayHelper::get($encoded_data_array, 'custom');
        $customArray = json_decode($customJson, true);
        $paymentSettings = $this->getSettings();

        if (!is_array($encoded_data_array) && !empty($encoded_data_array)) {
            return;
        }

        $bookingModel = new Booking();

        if (!$bookingId) {
            if (!$customArray || empty($customArray['trm_id'])) {
                $bookingId = false;
            } else {
                $bookingId = intval($customArray['trm_id']);
            }
        }

        $booking = false;
        if ($bookingId) {
            $booking = $bookingModel->find($bookingId);
        }

        if (!$booking) {
            // PaymentHelper::log([
            //     'status'      => 'error',
            //     'title'       => __('Invalid PayPal IPN Notification Received. No booking Found', 'trmpro'),
            //     'description' => json_encode($encoded_data_array)
            // ]);
            return false;
        }

        
        $payment_id = 0;
        $transactions = new Transaction();

        if (!empty($encoded_data_array['parent_txn_id'])) {
            $payment_id = $transactions->getByPaymentId($encoded_data_array['parent_txn_id'],  'paypal');
        } elseif (!empty($encoded_data_array['txn_id'])) {
            $payment_id = $transactions->getByPaymentId($encoded_data_array['txn_id'], 'paypal');
        }

        if (empty($payment_id)) {
            $payment_id = !empty($encoded_data_array['custom']) ? absint($encoded_data_array['custom']) : 0;
        }

        $ipnVerified = false;
        if ($paymentSettings['disable_ipn_verification'] != 'yes') {

            $validate_ipn = wp_unslash($_POST); // WPCS: CSRF ok, input var ok.
            $validate_ipn['cmd'] = '_notify-validate';

            // Send back post vars to paypal.
            $params = array(
                'body'        => $validate_ipn,
                'timeout'     => 60,
                'httpversion' => '1.1',
                'compress'    => false,
                'decompress'  => false,
                'user-agent'  => 'trm/' . TRM_VERSION,
            );

            // Post back to get a response.
            $response = wp_safe_remote_post((!$this->isTestMode()) ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr', $params);
            if (!is_wp_error($response) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 && strstr($response['body'], 'VERIFIED')) {
                // PaymentHelper::log([
                //     'status' => 'success',
                //     'title'  => __('Received valid response from PayPal IPN', 'trmpro'),
                //     'description' => json_encode($encoded_data_array)
                // ], $booking);
                $ipnVerified = true;
            } else {
                // PaymentHelper::log([
                //     'status'      => 'error',
                //     'title'       => __('PayPal IPN verification Failed', 'trmpro'),
                //     'description' => json_encode($encoded_data_array)
                // ], $booking);
                return false;
            }
        }

        do_action('trm_paypal_action_web_accept', $encoded_data_array, $payment_id);
        exit;
    }

    /**
     * With the function it is now possible to replace
     * all utf8_encode calls with the new utf8_encode_custom function to avoid the deprecation notice
     * @return false|string
     */
    public function utf8_encode_custom($s)
    {
        $s .= $s;
        $len = \strlen($s);
        for ($i = $len >> 1, $j = 0; $i < $len; ++$i, ++$j) {
            switch (true) {
                case $s[$i] < "\x80": $s[$j] = $s[$i]; break;
                case $s[$i] < "\xC0": $s[$j] = "\xC2"; $s[++$j] = $s[$i]; break;
                default: $s[$j] = "\xC3"; $s[++$j] = \chr(\ord($s[$i]) - 64); break;
            }
        }
        return substr($s, 0, $j);
    }

    private function getSettings()
    {
        $settings = get_option('trm_payment_settings_paypal', []);
        return $settings;
    }

    private function isTestMode()
    {
        $settings = get_option('trm_payment_settings_paypal', []);
        if (isset($settings['payment_mode']) && $settings['payment_mode'] == 'live') {
            return false;
        }
        return true;
    }

}