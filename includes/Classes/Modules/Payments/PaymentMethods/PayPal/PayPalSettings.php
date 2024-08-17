<?php

namespace WPTravelManager\Classes\Modules\Payments\PaymentMethods\PayPal;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use WPTravelManager\Classes\ArrayHelper;
use WPTravelManager\Classes\Modules\Payments\PaymentHelper;

class PayPalSettings
{

    public function init()
    {
        add_filter('trm_before_save_paypal', array($this, 'mapperSettings'), 10, 1);
        add_action('trm_payment_method_settings_validation_paypal', array($this, 'validateSettings'), 10, 1);
    }

    public static function globalFields() : array
    {
        return array(
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
            'payment_type' => array(
                'value' => 'standard',
                'label' => __('Payment Type', 'wp-payment-form'),
                'options' => array(
                    'standard' => __('Standard', 'wp-payment-form'),
                    'express' => __('Express Checkout', 'wp-payment-form')
                ),
                'type' => 'payment_type'
            ),
            'test_public_key' => array(
                'value' => '',
                'label' => __('Test Public Key', 'wp-payment-form'),
                'type' => 'test_pub_key',
                'placeholder' => __('Test Public Key', 'wp-payment-form')
            ),
            'test_secret_key' => array(
                'value' => '',
                'label' => __('Test Secret Key', 'wp-payment-form'),
                'type' => 'test_secret_key',
                'placeholder' => __('Test Secret Key', 'wp-payment-form')
            ),
            'live_public_key' => array(
                'value' => '',
                'label' => __('Live Public Key', 'wp-payment-form'),
                'type' => 'live_pub_key',
                'placeholder' => __('Live Public Key', 'wp-payment-form')
            ),
            'live_secret_key' => array(
                'value' => '',
                'label' => __('Live Secret Key', 'wp-payment-form'),
                'type' => 'live_secret_key',
                'placeholder' => __('Live Secret Key', 'wp-payment-form')
            ),
            'paypal_email' => array(
                'value' => '',
                'label' => __('PayPal Email Address', 'wp-payment-form'),
                'type' => 'email',
                'placeholder' => __('PayPal Email Address', 'wp-payment-form')
            ),
            'disable_ipn_verification' => array(
                'value' => 'no',
                'label' => __('Disable IPN Verification', 'wp-payment-form'),
                'type' => 'checkbox',
            ),
        );
    }

    public static function settingKeys()
    {
        return array(
            'is_active' => 'no',
            'payment_mode' => 'test',
            'payment_type' => 'standard',
            'test_public_key' => '',
            'test_secret_key' => '',
            'live_public_key' => '',
            'live_secret_key' => '',
            'paypal_email' => '',
            'disable_ipn_verification' => 'no',
        );
    }

    public function mapperSettings($settings)
    {
        $settings = PaymentHelper::mapper(
            self::settingKeys(),
            $settings,
            false
        );

        return $settings;
    }

    public static function get($key = null)
    {
        $settings = get_option('trm_payment_settings_paypal', array());

        $defaults = array(
            'is_active' => 'no',
            'payment_mode' => 'test',
            'payment_type' => 'standard',
            'test_public_key' => '',
            'test_secret_key' => '',
            'live_public_key' => '',
            'live_secret_key' => '',
            'paypal_email' => '',
            'disable_ipn_verification' => 'no',
        );

        $data = wp_parse_args($settings, $defaults);
        return $key && isset($data[$key]) ? $data[$key] : $data;
    }

    public static function getKeys($key = null)
    {
        $settings = self::get();

        if ($settings['payment_mode'] == 'test') {
            $data = array(
                'secret' => $settings['test_secret_key'],
                'public' => $settings['test_public_key']
            );
        } else {
            $data = array(
                'secret' => $settings['live_secret_key'],
                'public' =>$settings['live_public_key']
            );
        }

        return $key && isset($data[$key]) ? $data[$key] : $data;

    }

    public function validateSettings($settings)
    {
        $errors = array();
        if (ArrayHelper::get($settings, 'is_active') == 'no') {
            return [];
        }

        if (!ArrayHelper::get($settings, 'paypal_email')) {
            $errors['paypal_email'] = __('PayPal Email Address is required', 'travel-manager');
        }

        if (!ArrayHelper::get($settings, 'payment_mode')) {
            $errors['payment_mode'] = __('Please select Payment Mode', 'travel-manager');
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
    }
}