<?php

namespace WPTravelManager\Classes\Modules\Payments\PaymentMethods\Sslcommerz;

use WPTravelManager\Classes\Modules\Payments\PaymentHelper;
use WPTravelManager\Classes\ArrayHelper as Arr;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
 /**
 * Automatically create global payment settings page
 * @param  String: key, title, routes_query, 'logo')
 */
class SslcommerzSettings
{
    /**
     * @function mapperSettings, To map key => value before store
     * @function validateSettings, To validate before save settings
     */
    public function init()
    {
        add_filter('trm_before_save_sslcommerz', array($this, 'mapperSettings'), 10, 1);
        add_action('trm_payment_method_settings_validation_sslcommerz', array($this, 'validateSettings'), 10, 1);
    }

    /**
     * @return Array of global fields
     */
    public static function globalFields(): array
    {
        return array (
            'is_active' => array(
                'value' => 'no',
                'label' => __('Enable/Disable', 'wp-payment-form'),
            ),
            'payment_mode' => array(
                'value' => 'test',
                'label' => __('Payment Mode', 'wp-payment-form'),
                'options' => array(
                    'test' => __('Test Mode', 'wp-payment-form'),
                    'live' => __('Live Mode', 'wp-payment-form')
                ),
                'type' => 'payment_mode'
            ),
            'checkout_type' => array(
                'value' => 'modal',
                'label' => __('Checkout Logo', 'wp-payment-form'),
                'options' => ['modal' => 'Modal checkout style', 'hosted' => 'Hosted checkout style'],
            ),
            'live_store_id' => array(
                'value' => 'live',
                'label' => __('Live Store Id', 'wp-payment-form'),
                'type' => 'live_pub_key',
                'placeholder' => __('Live Store Id', 'wp-payment-form')
            ),
            'test_store_id' => array(
                'value' => 'test',
                'label' => __('Test Store Id', 'wp-payment-form'),
                'type' => 'test_pub_key',
                'placeholder' => __('Test Store Id', 'wp-payment-form')
            ),
            'live_store_pass' => array(
                'value' => '',
                'label' => __('Live Secret Key', 'wp-payment-form'),
                'type' => 'live_secret_key',
                'placeholder' => __('Live Secret Key', 'wp-payment-form')
            ),
            'test_store_pass' => array(
                'value' => '',
                'label' => __('Test Secret Key', 'wp-payment-form'),
                'type' => 'test_secret_key',
                'placeholder' => __('Test Secret Key', 'wp-payment-form')
            ),
            'desc' => array(
                'value' => '<div> <p style="color: #d48916;">NB: Please add Email, Name and Address field on your form to get payment data correctly.
                .</p> <p>See our <a href="https://paymattic.com/docs/how-to-integrate-sslcommerz-with-paymattic-in-wordpress/" target="_blank" rel="noopener">documentation</a> to get more information about SSLCOMMERZ setup.</p> </div>',
                'label' => __('Payrexx IPN Settings', 'wp-payment-form'),
                'type' => 'html_attr'
            ),
            'is_pro_item' => array(
                'value' => 'yes',
                'label' => __('PayPal', 'wp-payment-form'),
            ),
        );
    }

     /**
     * @return Array of default fields
     */
    public static function settingsKeys() : array
    {
        return array(
            'is_active' => 'no',
            'payment_mode' => 'test',
            'test_store_id' => '',
            'test_store_pass' => '',
            'live_store_id' => '',
            'live_store_pass' => '',
        );
    }

    public function mapperSettings($settings)
    {
        $settings = PaymentHelper::mapper(
            self::settingsKeys(),
            $settings,
            false
        );

        return $settings;
    }

    public static function get($key = null)
    {
        $settings = get_option('trm_payment_settings_sslcommerz', array());

        $defaults = array(
            'is_active' => 'no',
            'payment_mode' => 'test',
            'test_store_id' => '',
            'test_store_pass' => '',
            'live_store_id' => '',
            'live_store_pass' => '',
        );

        $data = wp_parse_args($settings, $defaults);
        return $key && isset($data[$key]) ? $data[$key] : $data;
    }

    public function validateSettings($settings)
    {
        $errors = array();

        $mode = Arr::get($settings, 'payment_mode');

        if ($mode == 'test') {
            if (empty(Arr::get($settings, 'test_store_id')) || empty(Arr::get($settings, 'test_store_pass'))) {
                $errors['test_store_id'] = __('Please provide Test Store Id and Test Store Key', 'wp-payment-form-pro');
            }
        }

        if ($mode == 'live') {
            if (empty(Arr::get($settings, 'live_store_id')) || empty(Arr::get($settings, 'live_store_pass'))) {
                $errors['live_store_id'] = __('Please provide Live Store Id and Live Store Key', 'wp-payment-form-pro');
            }
        }

        if ($errors) {
            wp_send_json_error(
                array(
                    'message' => __('Please fill all required fields', 'wp-payment-form-pro'),
                    'errors' => $errors
                ),
                400
            );
        }

        return true;
    }

    // public static function isLive($formId = false)
    // {
    //     $settings = self::getSettings();
    //     $mode = Arr::get($settings, 'payment_mode');
    //     return $mode == 'live';
    // }

    // public static function getApiKeys($formId = false)
    // {
    //     $isLive = self::isLive($formId);
    //     $settings = self::getSettings();

    //     if ($isLive) {
    //         return array(
    //             'api_key' => Arr::get($settings, 'live_store_id'),
    //             'api_secret' => Arr::get($settings, 'live_store_pass'),
    //             'api_path' => 'https://securepay.sslcommerz.com'
    //         );
    //     }
    //     return array(
    //         'api_key' => Arr::get($settings, 'test_store_id'),
    //         'api_secret' => Arr::get($settings, 'test_store_pass'),
    //         'api_path' => 'https://sandbox.sslcommerz.com'
    //     );
    // }
}
