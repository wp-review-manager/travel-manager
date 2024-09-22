<?php

namespace WPTravelManager\Classes\Models;

use WPTravelManager\Classes\ArrayHelper as Arr;
use WPTravelManager\Classes\Services\TripsServices;
use WPTravelManager\Views\Trips\TripsCard;

class Trips extends Model
{
    protected $model = 'posts';
    protected $metaModel = 'postmeta';

    public function updateTrip($tripId, $validate_and_serialized)
    {
        $trip_info = Arr::get($_REQUEST, 'trip_info', []);
        $trip_title = sanitize_text_field(Arr::get($trip_info, 'post_title', 'New Trip Title'));
        $trip_description = sanitize_text_field(Arr::get($trip_info, 'post_content', '<p>New Trip Description</p>'));

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

    public function getTrips($per_page = 0)
    {
        $page = sanitize_text_field(Arr::get($_REQUEST, 'page', 1));
        $limit = sanitize_text_field(Arr::get($_REQUEST, 'per_page', $per_page));
        $offset = ($page - 1) * $limit;
        $search = sanitize_text_field(Arr::get($_REQUEST, 'search', ''));
        $status = sanitize_text_field(Arr::get($_REQUEST, 'status', 'publish'));
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
            'total' => count($total),
            'all_trips' => $trips,
        );
    }

    public function getTripsWithDetails($per_page = 0)
    {
        $page = sanitize_text_field(Arr::get($_REQUEST, 'page', 1));
        $limit = sanitize_text_field(Arr::get($_REQUEST, 'per_page', $per_page));
        $offset = ($page - 1) * $limit;
        $search = sanitize_text_field(Arr::get($_REQUEST, 'search', ''));
        $status = sanitize_text_field(Arr::get($_REQUEST, 'status', 'publish'));
        $filter_date = Arr::get($_REQUEST, 'filter_date', []);
        $response_type = Arr::get($_REQUEST, 'response_type');
        $sortData = Arr::get($_REQUEST, 'sortData', []);
        $sortBy = Arr::get($sortData, 'sortBy', 'post_modified');
        $sortOrder = Arr::get($sortData, 'order', 'DESC');

        $filters = array(
            'destinations' => ['Kathmandu', 'Pokhara'],
            'price' => ['min' => 100, 'max' => 450],
            'duration' => ['min' => 2, 'max' => 10]
        );

        // Filter by date if start and end dates are provided
        if (!empty($filter_date) && count($filter_date) == 2) {
            $start_date = sanitize_text_field($filter_date[0]);
            $end_date = sanitize_text_field($filter_date[1]);
        }

        // Main query for trips
        $query = TMDBModel('posts')
            ->where('post_type', 'tm_trip')
            ->where('post_status', $status)
            ->orderBy($sortBy, $sortOrder);

        if (!empty($search)) {
            $query->where('post_title', 'LIKE', "%$search%");
        }

        $trips = $query->get();

        foreach ($trips as $key => $trip) {
            $post_meta = maybe_unserialize(get_post_meta($trip->ID, 'trip_meta', true));
            $trip->post_meta = $post_meta;
            $trip->shortcode = '[tm_trip id="' . $trip->ID . '"]';
            $trip->preview_url = site_url('?wp_tm_trip_preview=' . $trip->ID);
        }
        // Filter/sort trips based on meta like price, duration, etc.
        $filter_trips = $this->filterAndSortTripsByMeta($trips, $filters, $sortBy, $sortOrder, $limit, $offset);

        // Total trips count
        $total = TMDBModel('posts')->where('post_type', 'tm_trip')->getCount();

        if ($response_type == 'json') {
            ob_start();
            (new TripsCard())->render($filter_trips['trips']);
            return ob_get_clean();
        }

        return [
            'all_trips' => $filter_trips['trips'],
            'total' => $filter_trips['total'],
        ];
    }

    public function filterAndSortTripsByMeta($trips, $filters = [], $sortBy = 'price', $sortOrder = 'ASC', $limit = 0, $offset = 0)
    {
        // Filter trips by meta data
        $filteredTrips = array_filter($trips, function ($trip) use ($filters) {
            $tripMeta = $trip->post_meta;
            // // Check for category filter
            // if (!empty($filters['category']) && $filters['category'] !== $tripMeta['trip_category']) {
            //     return false;
            // }

            // Check for duration filter
            if (!empty($filters['duration'])) {
                $duration = Arr::get($tripMeta, 'general.duration.duration', 0);
                if (!empty($filters['duration']['min']) && $duration < $filters['duration']['min']) {
                    return false;
                }
                if (!empty($filters['duration']['max']) && $duration > $filters['duration']['max']) {
                    return false;
                }
            }

            // Check for price filter
            if (!empty($filters['price'])) {
                $price = Arr::get($tripMeta, 'packages.0.pricing.0.selling_price');
                if (!empty($filters['price']['min']) && $price < $filters['price']['min']) {
                    return false;
                }
                if (!empty($filters['price']['max']) && $price > $filters['price']['max']) {
                    return false;
                }
            }

            // Add other filter conditions as needed
            // Example: Filter by trip type, destination, etc.
            // if (!empty($filters['trip_type']) && $filters['trip_type'] !== $tripMeta['trip_type']) {
            //     return false;
            // }

            return true;
        });
        // Sort trips by specified meta data
        // usort($filteredTrips, function ($a, $b) use ($sortBy, $sortOrder) {
        //     $metaA = $a->post_meta['general'];
        //     $metaB = $b->post_meta['general'];

        //     // Example sorting by price
        //     if ($sortBy === 'price') {
        //         $priceA = $a->post_meta['packages'][0]['pricing'][0]['price'];
        //         $priceB = $b->post_meta['packages'][0]['pricing'][0]['price'];
        //         return ($sortOrder === 'ASC') ? $priceA - $priceB : $priceB - $priceA;
        //     }

        //     // Example sorting by duration
        //     if ($sortBy === 'duration') {
        //         $durationA = $metaA['duration']['duration'];
        //         $durationB = $metaB['duration']['duration'];
        //         return ($sortOrder === 'ASC') ? $durationA - $durationB : $durationB - $durationA;
        //     }

        //     // Add more sorting conditions if needed
        //     return 0;
        // });

        // Apply limit and offset
        $total = count($filteredTrips);
        $filteredTrips = array_slice($filteredTrips, $offset, $limit);

        return array(
            'trips' => $filteredTrips,
            'total' => $total,
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

    public function getTrip($tripId): Object
    {
        return get_post($tripId);
    }
}
