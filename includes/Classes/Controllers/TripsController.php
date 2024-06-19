<?php
namespace WPTravelManager\Classes\Controllers;
use WPTravelManager\Classes\Services\TripsServices;
use WPTravelManager\Classes\Models\Trips;
use WPTravelManager\Classes\ArrayHelper as Arr;

class TripsController {
    public function registerAjaxRoutes()
    {
        tmValidateNonce('tm_admin_nonce');
        $route = sanitize_text_field($_REQUEST['route']);
        $routeMaps = array(
            'create_or_update_trip' => 'createOrUpdateTrip',
            'get_trips' => 'getTrips',
            'delete_destinations' => 'deleteDestinations',
        );
        if (isset($routeMaps[$route])) {
            $this->{$routeMaps[$route]}();
            die();
        }
    }

    public function createOrUpdateTrip()
    {
        $tripId = sanitize_text_field(Arr::get($_REQUEST, 'trip_id'));
        $tripModal = new Trips();
        if ($tripId) {
            $response = $tripModal->updateTrip($tripId);
        } else {
            $response = $tripModal->createTrip();
        }

        wp_send_json(array(
            'message' => 'Trip has been saved successfully',
            'data' => $response
        ));
        
    }

    public function getTrips()
    {
        $tripModal = new Trips();
        $response = $tripModal->getTrips();

        wp_send_json_success($response);
    }
}