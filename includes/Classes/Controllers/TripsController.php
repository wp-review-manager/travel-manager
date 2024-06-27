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
            'get_trip_info' => 'getTripInfo',
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
    
            $sanitized_trip_meta = (new TripsServices())->sanitizeTripMeta(Arr::get($_REQUEST, 'trip_meta', []));
            $validate_and_serialized = (new TripsServices())->validateTripMeta($sanitized_trip_meta, $tripId, $trip_title, $trip_description);
            
            $response = $tripModal->updateTrip($tripId, $validate_and_serialized);
        } else {
            $tripData = array(
                'post_title' => sanitize_text_field(Arr::get($_REQUEST, 'trip_title', 'New Trip Title')),
                'post_content' => sanitize_text_field(Arr::get($_REQUEST, 'trip_description', '<p>New Trip Description</p>')),
                'post_status' => sanitize_text_field(Arr::get($_REQUEST, 'trip_status', 'publish')),
                'post_type' => 'tm_trip',
            );

            $response = $tripModal->createTrip($tripData);
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

    public function getTripInfo()
    {
        $tripId = sanitize_text_field(Arr::get($_REQUEST, 'trip_id'));

        if (!$tripId) {
            wp_send_json_error('Trip ID is required');
        }
        
        $tripModal = new Trips();
        $response = $tripModal->getTripInfo($tripId);

        wp_send_json_success($response);
    }
}