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
            'get_booking_details' => 'getBookingDetails',
            'update_payment_status' => 'updatePaymentStatus'
          
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

        $discounts =( new Coupon())->getDiscounts($bookingId, 'booking_id');
      foreach($discounts as $discount){
       $totalDiscount =+ $discount->discount_amount;
      }

        wp_send_json_success(
            array(
                'transactions' => $transactionData,
                'bookings' => $bookingData,
                'orderItems' => $OrderData,
                'trip' => $tripData,
                'discounts' => $discounts,
                'totalDiscount' => $totalDiscount,
            )
        );
    }

    public function updatePaymentStatus(){
        $form_data = Arr::get($_REQUEST, 'data');
        $id = Arr::get($form_data, 'id', null);

        $paymentStatus =( new Transaction())->updateTransaction($id, $form_data);
    
        if ($paymentStatus) {
            wp_send_json_success('Payment Status updated successfully');
        } else {
            wp_send_json_error('Failed to updated Payment Status');
        }
    }

}