<?php
namespace WPTravelManager\Classes\Modules\Payments;

if (!defined('ABSPATH')) {
    exit;
}

use WPTravelManager\Classes\ArrayHelper as Arr;
use WPTravelManager\Classes\Models\Transaction;
use WPTravelManager\Classes\Models\Booking;

class PaymentHandler {

    public function init() {
        // require TRM_DIR . 'includes/Classes/Modules/Payments/PaymentMethods/PayPal/PayPal.php';
        // require TRM_DIR . 'includes/Classes/Modules/Payments/PaymentMethods/SSLCommerz/SSLCommerz.php';

        new \WPTravelManager\Classes\Modules\Payments\PaymentMethods\PayPal\PayPal();
        new \WPTravelManager\Classes\Modules\Payments\PaymentMethods\SSLCommerz\SSLCommerz();

        if (isset($_GET['trm_payment']) && isset($_GET['payment_method'])) {
            $data = $_GET;
            $this->validateFrameLessPage($data);
            add_action('wp', function () use ($data){
                $paymentMethod = sanitize_text_field($_GET['payment_method']);
                if (isset($_GET['payment_status'])) {
                   if ($_GET['payment_status'] == 'failed') {
                       do_action('trm_payment_failed_' . $paymentMethod, $data);
                   } else if ($_GET['payment_status'] == 'success') {
                       do_action('trm_payment_success_' . $paymentMethod, $data);
                   } else if ($_GET['payment_status'] == 'cancelled') {
                       do_action('trm_payment_cancelled_' . $paymentMethod, $data);
                   }
                }
                // do_action('trm_payment_success_' . $paymentMethod);
            });
        }

        if (isset($_REQUEST['trm_ipn_listener']) && isset($_REQUEST['payment_method']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
            add_action('wp', function () {
                $paymentMethod = sanitize_text_field($_REQUEST['payment_method']);
                do_action('trm_ipn_endpoint_' . $paymentMethod);
            });
        }

    }

    public function validateFrameLessPage($data) {
       // We should verify the transaction hash from the URL
       $paymentMethod = sanitize_text_field(Arr::get($data, 'payment_method'));
       
       $bookingId = intval(Arr::get($data, 'trm_payment'));

       if (!$bookingId || !$paymentMethod) {
           die('Validation Failed');
       }

       if ($bookingId) {
            $transactionModel = new Transaction();

           $transaction = $transactionModel->where('booking_id', $bookingId)
               ->where('payment_method', $paymentMethod)
               ->first();
           if (!$transaction) {
               die('Booking or payment method not matched!');
           }
       }

       $hash = sanitize_text_field(Arr::get($data, 'booking_hash'));
      
       if (!$hash) {
           die('Validation Failed');
       } else {
            $bookingModel = new Booking();

           $booking = $bookingModel->where('booking_hash', $hash)->first();
           if (!$booking) {
               die('booking Hash Invalid');
           }
       }
       return true;
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