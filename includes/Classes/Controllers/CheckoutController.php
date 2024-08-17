<?php
namespace WPTravelManager\Classes\Controllers;
use WPTravelManager\Classes\Services\CheckoutServices;
use WPTravelManager\Classes\Models\Checkout;
use WPTravelManager\Classes\Models\OrderItem;
use WPTravelManager\Classes\Models\Transactions;
use WPTravelManager\Classes\ArrayHelper as Arr;

class CheckoutController {
    public function registerAjaxRoutes()
    {
        // tmValidateNonce('tm_admin_nonce');
        $route = sanitize_text_field($_REQUEST['route']);
        $routeMaps = array(
            'submission_checkout' => 'submissionCheckout',
          
        );
        if (isset($routeMaps[$route])) {
            $this->{$routeMaps[$route]}();
            die();
        }
    }

    public function submissionCheckout() {
        $form_data = Arr::get($_REQUEST, 'data');
        $session_id=  Arr::get($form_data, 'session_id'); ;
        //=====================get session data==========================

        $session_data = (new OrderItem())->getSessionData($session_id);

        $sessionId = Arr::get($session_data, 'session_id', null);
        //========================================================
        if($sessionId){          
            $sanitize_data = CheckoutServices::sanitize($form_data);
            $validateData = CheckoutServices::validate($sanitize_data);
            $totalPayable = Arr::get($validateData, 'booking_total', 0);
        
            $bookingId = (new Checkout())->saveCheckout($validateData);
                     
            if ($bookingId) {
                foreach ($session_data['session_meta'] as $order_item) {
                    $order_item['booking_id'] = $bookingId;
                    $order_item['trip_id'] = $validateData['trip_id'];
                    (new OrderItem())->saveOrderItem($order_item);
                }
            
                $transactionId = (new Transactions())->saveTransactions($validateData,$bookingId);
                $paymentMethod = Arr::get($validateData, 'payment_method', 'sslcommerz');
                do_action('trm_make_payment_' . $paymentMethod, $transactionId, $bookingId, $validateData, $totalPayable);
                wp_send_json_success('checkout Created successfully');
            } else {
                wp_send_json_error('Failed to updated CheckOut');
            }
            // if ($response_order_item) {
            //     wp_send_json_success('order Item Created successfully');
            // } else {
            //     wp_send_json_error('Failed to updated Order Item');
            // }
        }
        //========================================================
      
    }

   
}