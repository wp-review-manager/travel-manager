<?php
namespace WPTravelManager\Classes\Models;
use WPTravelManager\Classes\ArrayHelper as Arr;
use WPTravelManager\Classes\Services\TripsServices;

class Trips extends Model {
    protected $model = 'posts';
    protected $metaModel = 'postmeta';

    public function updateTrip($tripId, $validate_and_serialized)
    {
        $trip_info = Arr::get($_REQUEST, 'trip_info', []);
        $trip_title = sanitize_text_field( Arr::get($trip_info, 'post_title', 'New Trip Title') );
        $trip_description = sanitize_text_field( Arr::get($trip_info, 'post_content', '<p>New Trip Description</p>') );
        
        $tripData = array(
            'ID' => $tripId,
            'post_title' => $trip_title,
            'post_content' => $trip_description,
            'post_status' => sanitize_text_field(Arr::get($trip_info, 'post_status', 'publish')),
            'post_type' => 'tm_trip',
        );

        wp_update_post($tripData);

        update_post_meta($tripId, 'trip_meta', $validate_and_serialized);

        return $tripId;
    }

    public function createTrip($tripData = [])
    {
        $tripId = wp_insert_post($tripData);

        if ($tripId) {
            wp_update_post([
                'ID' => $tripId,
                'post_title' => sanitize_text_field($tripData['post_title']) . ' (#' . $tripId . ')'
            ]);
        }
        return $tripId;
    }

    public function getTrips()
    {
        $page = sanitize_text_field( Arr::get($_REQUEST, 'page', 1) );
        $limit = sanitize_text_field( Arr::get($_REQUEST, 'per_page', 0) );
        $offset = ($page - 1) * $limit;
        $search = sanitize_text_field( Arr::get($_REQUEST, 'search', '') );
        $status = sanitize_text_field( Arr::get($_REQUEST, 'status', 'publish') );
        $filter_date = Arr::get($_REQUEST, 'filter_date', '');

        // Initialize date_query array
        $date_query = array();

        // If filter_date is provided and contains start and end dates
        if (!empty($filter_date) && count($filter_date) == 2) {
            $start_date = sanitize_text_field($filter_date[0]);
            $end_date = sanitize_text_field($filter_date[1]);

            $date_query[] = array(
                'after'     => $start_date,
                'before'    => $end_date,
                'inclusive' => true,
            );
        }

        $args = array(
            'post_type' => 'tm_trip',
            'post_status' => $status,
            'posts_per_page' => $limit,
            'offset' => $offset,
            's' => $search,
            'date_query'     => $date_query,
        );
     
        
       $trips = get_posts($args);

       foreach ($trips as $key => $trip) {
           $trips[$key]->shortcode = '[tm_trip id="' . $trip->ID . '"]';
           $trips[$key]->preview_url = site_url('?wp_tm_trip_preview=' . $trip->ID);
       }
     
       $total = get_posts(array(
            'post_type' => 'tm_trip',
            'post_status' => $status,
            'posts_per_page' => -1,
            's' => $search,
            'date_query'     => $date_query,
        ));
     

        return array(
            'trips' => $trips,
            'total' => count($total)
        );
        
    }

    
    public function getTripInfo($tripId)
    {
        $trip = get_post($tripId);
        if (!$trip) {
            wp_send_json_error('Trip not found');
        }

        $tripMeta = get_post_meta($tripId);
        $tripMeta_data = Arr::get($tripMeta, 'trip_meta', null);
        $trip->shortcode = '[tm_trip id="' . $trip->ID . '"]';
        $trip->preview_url = site_url('?wp_tm_trip_preview=' . $trip->ID);

        if ($tripMeta_data[0][0] == 's') { // means the data is serialized as string
             // Remove extra serialization string
            $pos = strpos($tripMeta_data[0], '"');
            $tripMetaData = substr($tripMeta_data[0], $pos + 1);
        }
      

        return array(
            'trip' => $trip,
            'trip_meta' => maybe_unserialize($tripMetaData)
        );
    }

    public function deleteTrip($tripId)
    {
        $trip = get_post($tripId);
        if (!$trip) {
            wp_send_json_error('Trip not found');
        }

        wp_delete_post($tripId);
        delete_post_meta($tripId, 'trip_meta');

        return $tripId;
    }

}