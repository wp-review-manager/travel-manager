<?php

namespace WPTravelManager\Classes\Models;

use SebastianBergmann\CodeCoverage\Report\PHP;
use WPTravelManager\Classes\ArrayHelper as Arr;
use WPTravelManager\Classes\Services\TripsServices;
use WPTravelManager\Classes\Models\Destination;
use WPTravelManager\Classes\Models\Activities;
use WPTravelManager\Classes\Models\Categories;
use WPTravelManager\Views\Trips\TripsCard;
use WPTravelManager\Views\Trips\Pagination;

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

        // Filter data
        $filterData = Arr::get($_REQUEST, 'filterData', []);
        // dd($filterData, Arr::get($filterData, 'destinations', []));
        $filters = array(
            'destinations' => Arr::get($filterData, 'destinations', []),
            'price' => Arr::get($filterData, 'price', []),
            'duration' => Arr::get($filterData, 'duration', []),
            'activities' => Arr::get($filterData, 'activities', []),
            'trip_types' => Arr::get($filterData, 'trip_types', []),
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

        $min_price = PHP_INT_MAX;
        $max_price = 0;
        $min_duration = PHP_INT_MAX; 
        $max_duration = 0;
        $trips = $query->get();
        foreach ($trips as $key => $trip) {
            $post_meta = maybe_unserialize(get_post_meta($trip->ID, 'trip_meta', true));
            $trip->post_meta = $post_meta;
            $trip->shortcode = '[tm_trip id="' . $trip->ID . '"]';
            $trip->preview_url = site_url('?wp_tm_trip_preview=' . $trip->ID);

            // Assume price and duration are stored as 'price' and 'duration' keys in post_meta
            if (isset($post_meta['packages'][0]['pricing'][0]['selling_price'])) {
                $trip_price = (float) $post_meta['packages'][0]['pricing'][0]['selling_price']; // Cast to float in case it's stored as string

                // Update min and max prices
                $min_price = min($min_price, $trip_price);
                $max_price = max($max_price, $trip_price);
            }

            if (isset($post_meta['general']['duration'])) {
                $trip_duration = (int) Arr::get($post_meta, 'general.duration.duration', 0);

                // Update min and max durations
                $min_duration = min($min_duration, $trip_duration);
                $max_duration = max($max_duration, $trip_duration);
            }
        }

        if ($min_price === PHP_INT_MAX) $min_price = 0; // Fallback to 0 if no price was found
        if ($min_duration === PHP_INT_MAX) $min_duration = 0; // Fallback to 0 if no duration was found
        // Now you have $min_price, $max_price, $min_duration, and $max_duration
        // Filter/sort trips based on meta like price, duration, etc.
        $filter_trips = $this->filterAndSortTripsByMeta($trips, $filters, $sortBy, $sortOrder, $limit, $offset);

        if ($response_type == 'json') {
            ob_start();
            (new TripsCard())->render($filter_trips['trips']);
            $tripsHtml = ob_get_clean();

            // ob_start();
            // (new Pagination())->render(ceil(Arr::get($filter_trips, 'total', 0) / 2));
            // $paginationHtml = ob_get_clean();

            wp_send_json_success(array(
                'tripsHtml' => $tripsHtml,
                // 'paginationHtml' => $paginationHtml,
            ));
        }

        $destinations = (new Destination())->getDestination(['place_name', 'id', 'place_slug']);
        $activities = (new Activities())->getActivities(['trip_activity_name', 'id', 'trip_activity_slug']);
        $trpTypes = (new Categories())->getCategories();

        return [
            'all_trips' => $filter_trips['trips'],
            'total' => Arr::get($filter_trips, 'total', 0),
            'activities' => $activities,
            'destinations' => $destinations,
            'min_price' => $min_price,
            'max_price' => $max_price,
            'min_duration' => $min_duration,
            'max_duration' => $max_duration,
            'trip_types' => $trpTypes
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
                if (!empty($filters['duration'][0]) && $duration < $filters['duration'][0]) {
                    return false;
                }
                if (!empty($filters['duration'][1]) && $duration > $filters['duration'][1]) {
                    return false;
                }
            }

            // Check for price filter
            if (!empty($filters['price'])) {
                $price = Arr::get($tripMeta, 'packages.0.pricing.0.selling_price');
                if (!empty($filters['price'][0]) && $price < $filters['price'][0]) {
                    return false;
                }
                if (!empty($filters['price'][1]) && $price > $filters['price'][1]) {
                    return false;
                }
            }

            // Check for activities filter
            // if (!empty($filters['activities'])) {
            //     $activities = Arr::get($tripMeta, 'general.activities', []);
            //     foreach ($filters['activities'] as $activity) {
            //         if (!in_array($activity, $activities)) {
            //             return false;
            //         }
            //     }
            // }

            // Check for destinations filter
            if (!empty($filters['destinations'])) {
                $destination = Arr::get($tripMeta, 'general.trip_destination', "");
                if (!in_array($destination, $filters['destinations'])) {
                    return false;
                }
            }

            // Check for trip types filter
            if (!empty($filters['trip_types'])) {
                $trip_type = Arr::get($tripMeta, 'general.trip_type', "");
                if (!in_array($trip_type, $filters['trip_types'])) {
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
