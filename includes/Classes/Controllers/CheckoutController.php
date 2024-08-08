<?php
namespace WPTravelManager\Classes\Controllers;
use WPTravelManager\Classes\Services\CheckoutServices;
use WPTravelManager\Classes\Services\OrdersItemService;
use WPTravelManager\Classes\Models\Checkout;
use WPTravelManager\Classes\Models\OrderItem;
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
        $booking_id=  Arr::get($form_data, 'booking_id') ;
        //=====================get session data==========================

        $session_data = (new OrderItem())->getSessionData($booking_id);
       
        $session_meta = $session_data['session_meta'];
        $session_id = $session_data['session_id'];
       
        //========================================================
        if($booking_id === $session_id){
            $sanitize_order_item = OrdersItemService::sanitize($form_data, $session_meta);
          
            $sanitize_data = CheckoutServices::sanitize($form_data);
           
            $validation = CheckoutServices::validate($sanitize_data);
          
            if (!empty($validation)) {
                wp_send_json_error($validation);
            }
            $response = (new Checkout())->saveCheckout($sanitize_data);
          
            $response_order_item = (new OrderItem())->saveOrderItem($sanitize_order_item);
         
            if ($response) {
                wp_send_json_success('checkout Created successfully');
            } else {
                wp_send_json_error('Failed to updated CheckOut');
            }
            if ($response_order_item) {
                wp_send_json_success('order Item Created successfully');
            } else {
                wp_send_json_error('Failed to updated Order Item');
            }
        }
        //========================================================
      
    }

   
}