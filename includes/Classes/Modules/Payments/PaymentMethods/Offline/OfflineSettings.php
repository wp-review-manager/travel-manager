<?php

namespace WPTravelManager\Classes\Modules\Payments\PaymentMethods\Offline;

use WPTravelManager\Classes\Modules\Payments\PaymentHelper;
use WPTravelManager\Classes\ArrayHelper as Arr;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
 /**
 * Automatically create global payment settings page
 * @param  String: key, title, routes_query, 'logo')
 */
class OfflineSettings
{
    /**
     * @function mapperSettings, To map key => value before store
     * @function validateSettings, To validate before save settings
     */
    public function init()
    {
        add_filter('trm_before_save_offline', array($this, 'mapperSettings'), 10, 1);
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
            )
        );
    }

     /**
     * @return Array of default fields
     */
    public static function settingsKeys() : array
    {
        return array(
            'is_active' => 'no',
            'payment_mode' => 'test'
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
        $settings = get_option('trm_payment_settings_offline', array());

        $defaults = array(
            'is_active' => 'no',
            'payment_mode' => 'test',
        );

        $data = wp_parse_args($settings, $defaults);
        return $key && isset($data[$key]) ? $data[$key] : $data;
    }

}
