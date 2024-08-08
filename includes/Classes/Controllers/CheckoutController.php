<?php
namespace WPTravelManager\Classes\Controllers;
use WPTravelManager\Classes\Services\CheckoutServices;
use WPTravelManager\Classes\Models\Checkout;
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
        $sanitize_data = CheckoutServices::sanitize($form_data);
        $validation = CheckoutServices::validate($sanitize_data);
      
        if (!empty($validation)) {
            wp_send_json_error($validation);
        }
        $response = (new Checkout())->saveCheckout($sanitize_data);
// dd($response, 'response');
        if ($response) {
            wp_send_json_success('Inquiry updated successfully');
        } else {
            wp_send_json_error('Failed to updated Inquiry');
        }
    }

   
}