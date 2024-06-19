<?php
namespace WPTravelManager\Classes\Models;
use WPTravelManager\Classes\ArrayHelper as Arr;
use WPTravelManager\Classes\Services\TripsServices;

class Trips extends Model {
    protected $model = 'posts';
    protected $metaModel = 'postmeta';

    public function updateTrip($tripId)
    {
        // $tripData = array(
        //     'post_title' => sanitize_text_field(Arr::get($_REQUEST, 'trip_title')),
        //     'post_content' => sanitize_text_field(Arr::get($_REQUEST, 'trip_description')),
        //     'post_status' => sanitize_text_field(Arr::get($_REQUEST, 'trip_status')),
        //     'post_type' => 'trip',
        // );
        // $tripId = wp_update_post(array_merge(array('ID' => $tripId), $tripData));
        // $this->saveTripMeta($tripId);
        // return $tripId;
    }

    public function createTrip()
    {
        $tripData = array(
            'post_title' => sanitize_text_field(Arr::get($_REQUEST, 'trip_title', 'New Trip Title')),
            'post_content' => sanitize_text_field(Arr::get($_REQUEST, 'trip_description', '<p>New Trip Description</p>')),
            'post_status' => sanitize_text_field(Arr::get($_REQUEST, 'trip_status', 'publish')),
            'post_type' => 'tm_trip',
        );
        $tripId = wp_insert_post($tripData);

        if ($tripId) {
            wp_update_post([
                'ID' => $tripId,
                'post_title' => sanitize_text_field($tripData['post_title']) . ' (#' . $tripId . ')'
            ]);
        }
        return $tripId;
    }

    public function saveTripMeta($tripId)
    {
        $tripMeta = array(
            'trip_price' => sanitize_text_field(Arr::get($_REQUEST, 'trip_price', 0)),
            'trip_duration' => sanitize_text_field(Arr::get($_REQUEST, 'trip_duration', 0)),
            'trip_start_date' => sanitize_text_field(Arr::get($_REQUEST, 'trip_start_date', date('Y-m-d'))),
            'trip_end_date' => sanitize_text_field(Arr::get($_REQUEST, 'trip_end_date', date('Y-m-d'))),
            'trip_max_people' => sanitize_text_field(Arr::get($_REQUEST, 'trip_max_people', 0)),
            'trip_min_people' => sanitize_text_field(Arr::get($_REQUEST, 'trip_min_people', 0)),
            'trip_location' => sanitize_text_field(Arr::get($_REQUEST, 'trip_location', '')),
            'trip_destination' => sanitize_text_field(Arr::get($_REQUEST, 'trip_destination', '')),
        );
        foreach ($tripMeta as $key => $value) {
            update_post_meta($tripId, $key, $value);
        }
    }

    public function getTrips()
    {
        $page = sanitize_text_field( Arr::get($_REQUEST, 'page', 1) );
        $limit = sanitize_text_field( Arr::get($_REQUEST, 'per_page', 0) );
        $offset = ($page - 1) * $limit;
        $search = sanitize_text_field( Arr::get($_REQUEST, 'search', '') );
        $status = sanitize_text_field( Arr::get($_REQUEST, 'status', 'publish') );

        $args = array(
            'post_type' => 'tm_trip',
            'post_status' => $status,
            'posts_per_page' => $limit,
            'offset' => $offset,
            's' => $search,
        );
        
       $trips = get_posts($args);

       foreach ($trips as $key => $trip) {
           $trips[$key]->shortcode = '[tm_trip id="' . $trip->ID . '"]';
       }

       $total = get_posts(array(
            'post_type' => 'tm_trip',
            'post_status' => $status,
            'posts_per_page' => -1,
            's' => $search,
        ));

        return array(
            'trips' => $trips,
            'total' => count($total)
        );
    }

}