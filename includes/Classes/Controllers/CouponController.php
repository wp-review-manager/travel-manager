<?php

namespace WPTravelManager\Classes\Controllers;

use WPPayFormPro\Classes\Coupons\Coupon as CouponsCoupon;
use WPTravelManager\Classes\Services\CouponServices;
use WPTravelManager\Classes\Models\Coupon;
use WPTravelManager\Classes\ArrayHelper as Arr;

class CouponController
{
    public function registerAjaxRoutes()
    {
        tmValidateNonce('tm_admin_nonce');
        $route = sanitize_text_field($_REQUEST['route']);
        $routeMaps = array(
            'post_coupon' => 'postCoupon',
            'get_coupons' => 'getCoupons',
            'delete_coupon' => 'deleteCoupon',
        );
        if (isset($routeMaps[$route])) {
            $this->{$routeMaps[$route]}();
            die();
        }
    }

    public function postCoupon() {
        $form_data = $_REQUEST['data'];
        $id = Arr::get($form_data, 'id');
  
        $coupon_code = $form_data['coupon_code'];
    
        $get_coupon_code = (new Coupon())->getCoupon('coupon_code', $coupon_code);
        
        if (!empty($get_coupon_code) && $get_coupon_code->id != $id) {
           return wp_send_json_error('Please enter a unique coupon code');
        } else {
        
            $sanitize_data = CouponServices::sanitize($form_data);
           
            $validation = CouponServices::validate($sanitize_data);
    
            if (!empty($validation)) {
                wp_send_json_error($validation);
            }
            
            $response = (new Coupon())->saveCoupon($sanitize_data);
    
            if ($response) {
                wp_send_json_success('Coupon updated successfully');
            } else {
                wp_send_json_error('Coupon failed to update');
            }
        }
    }

    public function getCoupons()
    {
        $response = (new Coupon())->getCoupons();

        wp_send_json_success(
            array(
                'data' => $response,
                'message' => 'Coupons fetched successfully'
            )
        );
    }

    public function deleteCoupon()
    {
        $coupon_id = Arr::get($_REQUEST, 'id');

        if (!$coupon_id) {
            wp_send_json_error('Activities ID is required');
        }

        $response = Coupon::deleteCoupon($coupon_id);

        if ($response) {
            wp_send_json_success('Coupon deleted successfully');
        } else {
            wp_send_json_error('Failed to delete Coupon');
        }
    }
}
