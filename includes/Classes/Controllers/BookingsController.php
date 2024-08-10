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

}