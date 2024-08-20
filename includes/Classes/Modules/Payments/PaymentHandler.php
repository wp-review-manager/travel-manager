<?php
namespace WPTravelManager\Classes\Modules\Payments;

class PaymentHandler {

    public function init() {
        // require TRM_DIR . 'includes/Classes/Modules/Payments/PaymentMethods/PayPal/PayPal.php';
        // require TRM_DIR . 'includes/Classes/Modules/Payments/PaymentMethods/SSLCommerz/SSLCommerz.php';

        new \WPTravelManager\Classes\Modules\Payments\PaymentMethods\PayPal\PayPal();
        new \WPTravelManager\Classes\Modules\Payments\PaymentMethods\SSLCommerz\SSLCommerz();

        if (isset($_REQUEST['trm_payment_api_notify'])) {
            add_action('wp', function () {
                $paymentMethod = sanitize_text_field($_REQUEST['payment_method']);
                do_action('trm_ipn_endpoint_' . $paymentMethod);
            });
        }

        if (isset($_REQUEST['trm_payment_success_url'])) {
            add_action('wp', function () {
                $paymentMethod = sanitize_text_field($_REQUEST['payment_method']);
                do_action('trm_payment_success_' . $paymentMethod);
            });
        }

    }

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