<?php
namespace WPTravelManager\Classes\Controllers;

use ArrayObject;
use WPTravelManager\Classes\Services\BookingsServices;
use WPTravelManager\Classes\Models\Booking;
use WPTravelManager\Classes\ArrayHelper as Arr;
use WPTravelManager\Classes\Models\Order;
use WPTravelManager\Classes\Models\Trips;
use WPTravelManager\Classes\Models\Transaction;
use WPTravelManager\Classes\Models\Coupon;

class BookingsController {
    public function registerAjaxRoutes()
    {
        // tmValidateNonce('tm_admin_nonce');
        $route = sanitize_text_field($_REQUEST['route']);
        $routeMaps = array(
            'get_bookings' => 'getBookings',
            'delete_booking' => 'deleteBooking',
            'get_booking_details' => 'getBookingDetails'
          
        );
        if (isset($routeMaps[$route])) {
            $this->{$routeMaps[$route]}();
            die();
        }
    }

    public function getBookings() {
        $response = (new Booking())->getBookings();

        wp_send_json_success(
            array(
                'data' => $response,
                'message' => 'Booking fetched successfully'
            )
        );
    }

    public function deleteBooking() {
        $booking_id = Arr::get($_REQUEST, 'id', '');
        $id = sanitize_text_field($booking_id);
        
        if (!$id) {
            wp_send_json_error('Booking ID is required');
        }

        $response = Booking::deleteBooking($id);

        if ($response) {
            wp_send_json_success('Booking deleted successfully');
        } else {
            wp_send_json_error('Failed to delete Booking');
        }
    }

    public function getBookingDetails()
    {
        $bookingId = sanitize_text_field(Arr::get($_REQUEST, 'booking_id'));

        if (!$bookingId) {
            wp_send_json_error('Booking ID is required');
        }
        
        $bookingData = (new Booking())->getBooking($bookingId);
        $bookingData = Arr::get($bookingData, 0, new ArrayObject());
        
        if (!$bookingData) {
            wp_send_json_error('Booking not found');
        }

        $transactionData =( new Transaction())->getTransaction($bookingId, 'booking_id');
        $transactionData = Arr::get($transactionData, 0, new ArrayObject());

        if(!$transactionData) {
            wp_send_json_error('Transaction not found');
        }

        $bookingData->traveler_address = maybe_unserialize($bookingData->traveler_address);
        $transactionData->billing_address = maybe_unserialize($transactionData->billing_address);
        $transactionData->shipping_address = maybe_unserialize($transactionData->shipping_address);
        $tripData = (new Trips())->getTripInfo($bookingData->trip_id);

        $OrderData =( new Order())->getOrderItem($bookingId);

        $applyCoupon =( new Coupon())->getApplyCoupon($bookingId, 'booking_id');
        $applyCoupon = Arr::get($applyCoupon, 0, new ArrayObject());
        if(!$applyCoupon) {
            wp_send_json_error('Apply Coupon data not found');
        }

    
        wp_send_json_success(
            array(
                'transactions' => $transactionData,
                'bookings' => $bookingData,
                'orderItems' => $OrderData,
                'trip' => $tripData,
                'applyCoupon' => $applyCoupon,
            )
        );
    }

}