<?php
namespace WPTravelManager\Classes\Controllers;

use WPTravelManager\Classes\Services\CheckoutServices;
use WPTravelManager\Classes\Models\Checkout;
use WPTravelManager\Classes\Models\Session;
use WPTravelManager\Classes\Models\OrderItem;
use WPTravelManager\Classes\Models\Transactions;
use WPTravelManager\Classes\Models\Coupon;
use WPTravelManager\Classes\ArrayHelper as Arr;

class CheckoutController
{
    public function registerAjaxRoutes()
    {
        // tmValidateNonce('tm_admin_nonce');
        $route = sanitize_text_field($_REQUEST['route']);
        $routeMaps = array(
            'submission_checkout' => 'submissionCheckout',
            'submission_coupon_code' => 'submissionCouponCode'

        );
        if (isset($routeMaps[$route])) {
            $this->{$routeMaps[$route]}();
            die();
        }
    }

    public function submissionCheckout()
    {
        $form_data = Arr::get($_REQUEST, 'data');
        $session_id =  Arr::get($form_data, 'session_id');
        if (!$session_id) {
            wp_send_json_error('Session id not found');
        }
        $couponCode = Arr::get($_REQUEST, 'couponCode');
        //=====================get session data==========================

        $session_data = (new OrderItem())->getSessionData($session_id);
        $sessionId = Arr::get($session_data, 'session_id', null);
        //If session data not found then return error
        if ($sessionId) {
            $sanitize_data = CheckoutServices::sanitize($form_data);
            $validateData = CheckoutServices::validate($sanitize_data);
            if ($couponCode) {
                $couponData = $this->validateCouponCode($couponCode, $validateData['booking_total'], $sanitize_data['traveler_email']);
            }
            $validateData['currency'] = Arr::get($session_data, 'currency', 'USD');
            $totalPayable = Arr::get($validateData, 'booking_total', 0);

            // Booking create
            $bookingId = (new Checkout())->saveCheckout($validateData);
            if ($bookingId) {
                // Apply Coupon
                if ($couponData['discount'] > 0) {
                    $validateData['booking_total'] = $validateData['booking_total'] - $couponData['discount'];
                    Coupon::applyCouponSubmit($sanitize_data, $couponData, $bookingId);
                }

               // Create Order Item
                foreach ($session_data['session_meta'] as $order_item) {
                    $order_item['booking_id'] = $bookingId;
                    $order_item['trip_id'] = $validateData['trip_id'];
                    (new OrderItem())->saveOrderItem($order_item);
                }

                // Create Transaction
                $transactionId = (new Transactions())->saveTransactions($validateData,$bookingId);
                if(!$transactionId){
                    wp_send_json_error('Failed to create Transaction');
                }
                // Make Payment
                $paymentMethod = Arr::get($validateData, 'trm_payment_method', 'sslcommerz');
                do_action('trm_make_payment_' . $paymentMethod, $transactionId, $bookingId, $validateData, $totalPayable);
                // Delete Session item
               (new Session())->deleteSessionItem($sessionId);
                wp_send_json_success('checkout Created successfully');
            } else {
                wp_send_json_error('Failed to updated CheckOut');
            }
        }
    }

    public function submissionCouponCode()
    {

        $code = isset($_REQUEST['coupon_code']) ? sanitize_text_field($_REQUEST['coupon_code']) : '';
        $customer_email = isset($_REQUEST['customer_email']) ? sanitize_text_field($_REQUEST['customer_email']) : '';
        $subtotal = isset($_REQUEST['subtotal']) ? sanitize_text_field($_REQUEST['subtotal']) : '';
     
        $couponData = $this->validateCouponCode($code, $subtotal, $customer_email);
      
        return wp_send_json_success(array(
            'message' => 'Coupon code used successfully',
            'discount' => $couponData['discount'],
            'coupon_code' =>  $couponData['coupon_code'],
        ));
 
    }
   

    public function validateCouponCode ($code, $subtotal = '', $customer_email = '')
    {
      
        if (!$code) {
            return wp_send_json_error(array('message' => 'Please enter a coupon code'));
        }
       
        $coupon = (new Coupon())->getCoupon('coupon_code', $code);
        
        //==================================
        if (!$coupon) {
            return wp_send_json_error(array('message' => 'Invalid coupon code.'));
        }
        //====================================
        if ($coupon->coupon_status !== 'Active') {
            wp_send_json([
                'message' => __('The provided coupon is not valid.')
            ], 423);
        }
        //==============================
        $startDate = strtotime($coupon->start_date);
        $endDate = strtotime($coupon->end_date);

        $dateTime = current_datetime();
        $localtime = $dateTime->getTimestamp() + $dateTime->getOffset();

        if ($startDate && $localtime <= $startDate) {
            wp_send_json([
                'message' => __('The provided coupon is not live yet')
            ], 423);
        }

        if ($endDate && $localtime > $endDate) {
            wp_send_json([
                'message' => __('The provided coupon is outdated')
            ], 423);
        }
   
        //======================================
       
        // if ($coupon->min_amount && ($subtotal) < intval($coupon->min_amount * 100)) {
        //     wp_send_json([
        //         'message' => __('The provided coupon is not applicable with this amount', 'wp-payment-form-pro')
        //     ], 423);
        // }

        //Check if the coupon has usage limit
 
        if ($coupon->max_use > 0) {

            if(!$customer_email){
                wp_send_json_error(array('message' => __('The coupon has usage limit, please provide an email for validation.', 'travel-manager')));
            }

            if($this->getCouponUsageCount($customer_email, $code) >= $coupon->max_use){
                wp_send_json([
                    'message' => __('You have crossed this coupon use limit.', 'wp-payment-form-pro')
                ], 423);
            }
        }
        
        if (!$coupon->coupon_type == "Fixed") {
           
            $discount = ($coupon->amount / $subtotal) * 100;
        } else {
            $discount = $coupon->amount;
        }
     
        return array (
            'discount' => $discount,
            'coupon_code' =>  $code,
            'coupon' => $coupon
        );
    }

    public function getCouponUsageCount($customer_email, $coupon_code)
    {
        return (new Coupon())->getCouponUsageCount($customer_email, $coupon_code);
    }
}
