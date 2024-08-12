<?php
namespace WPTravelManager\Classes\Modules\Payments;

class PaymentHandler {
    public static function getAllMethods()
    {
        $methods = apply_filters('trm/get_all_payment_methods', []);
        return $methods;
    }

    public function getPaymentRoutes()
    {
        $routes = apply_filters('trm_get_payment_routes', []);
        return $routes;
    }

    public function saveSettings($method, $settings)
    {
        $settings = apply_filters('trm_before_save_' . $method, $settings);

        do_action('trm_payment_method_settings_validation_' . $method, $settings);

        update_option('trm_payment_settings_' . $method, $settings, false);
       
        do_action('trm_after_save_' . $method, $settings);

        wp_send_json_success(array(
            'message' => "Settings $method successfully updated"
        ), 200);

    }
}