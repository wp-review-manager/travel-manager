<?php
namespace WPTravelManager\Classes\Modules\Payments\PaymentMethods;

use WPTravelManager\Classes\PaymentSettings;

class PaymentSettingsController {
    public function registerAjaxRoutes() {
        tmValidateNonce('tm_admin_nonce');
        $route = sanitize_text_field($_REQUEST['route']);
        $routeMaps = array(
            'getPaymentSettings' => 'getPaymentSettings',
        );
        if (isset($routeMaps[$route])) {
            $this->{$routeMaps[$route]}();
            die();
        }
    }

    public function getPaymentSettings () {
        $gateway = sanitize_text_field($_REQUEST['gateway']);
        $payment_settings = apply_filters( 'trm/payment_settings_' . $gateway , [] );
        wp_send_json_success($payment_settings);
    }
}