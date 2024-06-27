<?php
namespace WPTravelManager\Classes\Services;
use WPTravelManager\Classes\Helper;
use WPTravelManager\Classes\ArrayHelper as Arr;
class TripsServices {

    public function sanitizeTripMeta($trip_meta) {
        array_walk_recursive($trip_meta, function (&$value, $key) {
            if ('iframe_code' == $key) {
                $allow_html_tag_for_iframe = Helper::allowHtmlTagForIframe();
                 $value = wp_kses($value, $allow_html_tag_for_iframe);
            }
            else {
                $value = sanitize_text_field($value, $key);
            }
        });

        return $trip_meta;
    }

    public function validateTripMeta($trip_meta, $trip_id) {
        $errors = [];

        if (empty($trip_id)) {
            $errors[] = 'Trip ID is required';
        }
        
        $general = Arr::get($trip_meta, 'general', []);

        if (empty($general['trip_code'])) {
            $errors[] = 'Trip code is required';
        }

        if (empty($general['duration']['duration']) || +$general['duration']['duration'] < 0 || empty($general['duration']['type'])) {
            $errors[] = 'Trip duration is required and must be greater than 0';
        }

        if ($general['duration']['type'] == 'days') {
            if (empty($general['nights']['duration']) || empty($general['nights']['type'])) {
                $errors[] = 'Trip Nights is required';
            }
        }

        $packages = Arr::get($trip_meta, 'packages', []);
        
        foreach ($packages as $key => $package) {
            if (empty($package['title'])) {
                $errors[] = 'Package title is required';
            }
            $pricings = Arr::get($package, 'pricing', []);

            foreach ($pricings as $key => $pricing) {
                if (empty($pricing['price']) || +$pricing['price'] < 0 || gettype(+$pricing['price']) != 'integer') {
                    $errors[] = 'Package price is required and must be greater than 0';
                }
            }
            
        }
    

        if (!empty($errors)) {
            wp_send_json_error(array('messages' => $errors));
        }
     
        return maybe_serialize( $trip_meta );
    }
}