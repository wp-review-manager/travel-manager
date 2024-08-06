<?php
namespace WPTravelManager\Classes\Modules\Payments\PaymentMethods;

use WPTravelManager\Classes\PaymentSettings;

class PaymentSettingsController {
    public function registerAjaxRoutes() {
        tmValidateNonce('tm_admin_nonce');
        $route = sanitize_text_field($_REQUEST['route']);
        $routeMaps = array(
            'getPaymentSettings' => 'getPaymentSettings',
            'savePaymentSettings' => 'savePaymentSettings',
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

    public function savePaymentSettings () {
        $gateway = sanitize_text_field($_REQUEST['gateway']);
        $settings = $_REQUEST['settings'];
        $settings = $this->sanitizePaymentSettingsUpdateData($settings);
        $payment_settings = apply_filters( 'trm/payment_settings_' . $gateway , [] );
        $payment_settings = array_merge($payment_settings, $settings);
        do_action( 'trm/save_payment_settings_' . $gateway , $payment_settings );
        wp_send_json_success(array(
            'message' => __('Settings saved successfully', 'travel-manager')
        ));
    }

    public function sanitizePaymentSettingsUpdateData($data) {
        array_walk_recursive($data, function (&$value, $key) {
            $value = sanitize_text_field($value);
        });

        return $data;
    }
}