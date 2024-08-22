<?php
namespace WPTravelManager\Classes\Controllers;

use ArrayObject;
use WPTravelManager\Classes\Services\BookingsServices;
use WPTravelManager\Classes\Models\Booking;
use WPTravelManager\Classes\ArrayHelper as Arr;
use WPTravelManager\Classes\Models\Order;
use WPTravelManager\Classes\Models\Transaction;

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
        
        $bookingModal = new Booking();
        $response = $bookingModal->getBooking($bookingId , new ArrayObject());
        $transactionModal =( new Transaction())->getTransaction($bookingId, 'booking_id');
        $OrderModal =( new Order())->getOrderItem($bookingId);
    
        wp_send_json_success(
            array(
                'transactions' => $transactionModal,
                'bookings' => $response,
                'orderItems' => $OrderModal,
            )
        );
    }

}