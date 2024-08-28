<?php
namespace WPTravelManager\Classes\Controllers;

use WPPayFormPro\Classes\Coupons\Coupon as CouponsCoupon;
use WPTravelManager\Classes\Services\CouponServices;
use WPTravelManager\Classes\Models\Coupon;
use WPTravelManager\Classes\ArrayHelper as Arr;

class CouponController {
    public function registerAjaxRoutes()
    {
        tmValidateNonce('tm_admin_nonce');
        $route = sanitize_text_field($_REQUEST['route']);
        $routeMaps = array(
            'post_coupon' => 'postCoupon',
            'get_coupons' => 'getCoupons'
        );
        if (isset($routeMaps[$route])) {
            $this->{$routeMaps[$route]}();
            die();
        }
    }

   

    public function postCoupon() {
        $form_data = Arr::get($_REQUEST, 'data');
        $sanitize_data = CouponServices::sanitize($form_data);
      
        $validation = CouponServices::validate($sanitize_data);

        if (!empty($validation)) {
            wp_send_json_error($validation);
        }
        
        $response = (new Coupon())->saveCoupon($sanitize_data);

        if ($response) {
            wp_send_json_success('Coupon updated successfully');
        } else {
            wp_send_json_error('Coupon to updated destination');
        }
    }

    public function getCoupons() {
        $response = (new Coupon())->getCoupons();

        wp_send_json_success(
            array(
                'data' => $response,
                'message' => 'Coupons fetched successfully'
            )
        );
    }


}