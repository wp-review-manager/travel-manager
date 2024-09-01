<?php

namespace WPTravelManager\Classes\Controllers;

use WPTravelManager\Classes\Services\CheckoutServices;
use WPTravelManager\Classes\Models\Checkout;
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
        $session_id =  Arr::get($form_data, 'session_id');;
        //=====================get session data==========================

        $session_data = (new OrderItem())->getSessionData($session_id);

        $sessionId = Arr::get($session_data, 'session_id', null);
        //========================================================
        if ($sessionId) {
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

                $transactionId = (new Transactions())->saveTransactions($validateData, $bookingId);
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

    public function submissionCouponCode()
    {
        $code = isset($_REQUEST['coupon_code']) ? sanitize_text_field($_REQUEST['coupon_code']) : '';

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
        // if ($coupon->min_amount && ($coupon->amount) < intval($coupon->min_amount * 100)) {
        //     wp_send_json([
        //         'message' => __('The provided coupon is not applicable with this amount', 'wp-payment-form-pro')
        //     ], 423);
        // }
        //=======================================
        $count_user_id = (new Coupon())->getTotalUser();
        $max_use = $coupon->max_use;
        //======================================


        return wp_send_json_success(array('message' => 'Coupon code used successfully'));
    }
}
