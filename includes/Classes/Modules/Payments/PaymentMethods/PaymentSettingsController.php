<?php
namespace WPTravelManager\Classes\Modules\Payments\PaymentMethods;

use WPTravelManager\Classes\PaymentSettings;
use WPTravelManager\Classes\Modules\Payments\PaymentHandler;

class PaymentSettingsController {
    public function registerAjaxRoutes() {
        tmValidateNonce('tm_admin_nonce');
        $route = sanitize_text_field($_REQUEST['route']);
        $routeMaps = array(
            'gateways' => 'getAllMethods',
            'getPaymentSettings' => 'getPaymentSettings',
            'savePaymentSettings' => 'savePaymentSettings',
        );
        if (isset($routeMaps[$route])) {
            $this->{$routeMaps[$route]}();
            die();
        }
    }

    public function getAllMethods()
    {
        $methods = PaymentHandler::getAllMethods();
        wp_send_json_success($methods, 200);
    }

    public function getPaymentSettings () {
        $gateway = sanitize_text_field($_REQUEST['gateway']);
        do_action('trm/get_payment_settings_' . $gateway);
    }

    public function savePaymentSettings () {
        $gateway = sanitize_text_field($_REQUEST['gateway']);
        $settings = $_REQUEST['settings'];
        $settings = $this->sanitizePaymentSettingsUpdateData($settings);
      
        $payment_settings = apply_filters( 'trm/payment_settings_' . $gateway , [] );
        $payment_settings = array_merge($payment_settings, $settings);
        
        (new PaymentHandler())->saveSettings($gateway, $payment_settings);

    }

    public function sanitizePaymentSettingsUpdateData($data) {
        array_walk_recursive($data, function (&$value, $key) {
            $value = sanitize_text_field($value);
        });

        return $data;
    }
}