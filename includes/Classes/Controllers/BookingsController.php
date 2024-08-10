<?php
namespace WPTravelManager\Classes\Controllers;

use WPTravelManager\Classes\Services\BookingsServices;
use WPTravelManager\Classes\Models\Booking;
use WPTravelManager\Classes\ArrayHelper as Arr;

class BookingsController {
    public function registerAjaxRoutes()
    {
        // tmValidateNonce('tm_admin_nonce');
        $route = sanitize_text_field($_REQUEST['route']);
        $routeMaps = array(
            'get_bookings' => 'getBookings',
            'delete_booking' => 'deleteBooking'
          
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
        $booking_id = Arr::get($_REQUEST, 'id');
        
        if (!$booking_id) {
            wp_send_json_error('Booking ID is required');
        }

        $response = Booking::deleteBooking($booking_id);

        if ($response) {
            wp_send_json_success('Booking deleted successfully');
        } else {
            wp_send_json_error('Failed to delete Booking');
        }
    }

}