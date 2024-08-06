<?php

namespace WPTravelManager\Classes\Modules\Payments\PaymentMethods;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.  
}

abstract class BasePaymentMethod
{
    protected $key = '';

    protected $settingsKey = '';

    public function __construct($key)
    {
        $this->key = $key;
        $this->settingsKey = 'trm_payment_settings_'.$key;
        add_filter('trm/payment_methods_global_settings', function ($methods) {
            $fields = $this->getGlobalFields();
            if($fields) {
                $methods[$this->key] = $fields;
            }
            return $methods;
        });
        add_filter('trm/payment_settings_' . $this->key, array($this, 'getGlobalSettings'));
    }

    public function mapper($defaults, $settings = [], $get = true) 
    {
        foreach ($defaults as $key => $value) {
            if ($get) {
                if (isset($settings[$key])) {
                    $defaults[$key]['value'] = $settings[$key];
                }
            } else {
                if (isset($settings[$key])) {
                    $defaults[$key] = $settings[$key]['value'];
                }
            }
        }

        return $defaults;
    }

    abstract public function getGlobalFields();

    abstract public function getGlobalSettings();

}