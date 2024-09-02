<?php
namespace WPTravelManager\Classes\Services;
use WPTravelManager\Classes\ArrayHelper as Arr;
use WPTravelManager\Classes\Models\Trips;
use WPTravelManager\Classes\Helper;


class SessionServices {
    public static function sanitize($data)
    {
        array_walk_recursive($data, function (&$value, $key) {
            $value = sanitize_text_field($value);
        });

        $data['created_at'] = current_time('mysql');
        $data['updated_at'] = current_time('mysql');

        $currentUser = Helper::getUserLoginInfo();
        if($currentUser) {
            $data['user_id'] = $currentUser->ID;
        }

        return $data;
    }

    public static function validate($data)
    {
        $booking_data = Arr::get($data, 'booking_data', []);
        $packages = Arr::get($booking_data, 'packages', []);
        $trip_id = Arr::get($booking_data, 'trip_id', 0);
        self::validatePackageData($packages, $trip_id);

        $prepare_data = array(
            'user_id' => Arr::get($data, 'user_id', 0),
            'device_id' => Arr::get($data, 'deviceId', ''),
            'trip_id' => Arr::get($booking_data, 'trip_id', 0),
            'session_meta' => maybe_serialize( $booking_data ),
            'currency' => Arr::get($data, 'currency', 'USD'),
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql'),
        );

        return $prepare_data;

    }

    public static function validatePackageData($packages, $trip_id) {
        $tripModal = new Trips();
        $trip = $tripModal->getTripInfo($trip_id);
        $package_name = Arr::get($packages, '0.package_name', '');
        if($trip) {
            $trip_packages = Arr::get($trip, 'trip_meta.packages', []);
            $package = Arr::first($trip_packages, function($value, $key) use ($package_name) {
                return $value['title'] == $package_name;
            });

            if(!$package) {
                wp_send_json_error( array(
                    'message' => 'Package not found',
                    'status' => 404
                ));
            }

            $package_quantity = Arr::get($package, 'quantity', 0);

            // validate all selected package price and quantity

            foreach($packages as $package_item) {
                $trip_package_total = Arr::get($package_item, 'tm_package_price_total', 0);
                $trip_package_total = floatval($trip_package_total);
                $trip_total_travelers = Arr::get($package_item, 'tm_travelers_number', 0);

                if($trip_package_total <= 0 || $trip_total_travelers <= 0) {
                    wp_send_json_error( array(
                        'message' => 'Invalid package price or quantity',
                        'status' => 400
                    ));
                }
                $package_price = Arr::get($package, 'pricing', 0);
                $pricing_id = Arr::get($package_item, 'pricing_id', null);
                $selected_package_price = Arr::get($package_price, $pricing_id, 0);
                $min_pax = Arr::get($selected_package_price, 'min_pax', 0);
                $max_pax = Arr::get($selected_package_price, 'max_pax', 0);
                $max_pax = $max_pax < 1 ? 999999 : $max_pax;
                
                if(!$selected_package_price) {
                    wp_send_json_error( array(
                        'message' => 'Invalid package price',
                        'status' => 400
                    ));
                }

                if($package_item['tm_travelers_number'] < $min_pax || $package_item['tm_travelers_number'] > $max_pax) {
                    wp_send_json_error( array(
                        'message' => 'Invalid package quantity',
                        'status' => 400
                    ));
                }
                
                $selected_pricing = Arr::get($package_item, 'package_type') == 'group' ? floatval($selected_package_price['selling_price']) : floatval($selected_package_price['selling_price'] * $trip_total_travelers);
                $isEquals = $trip_package_total == $selected_pricing;

                // if(!$isEquals) {
                //     wp_send_json_error( array(
                //         'message' => 'Invalid package total price',
                //         'status' => 400
                //     ));
                // }

                return true;
            }
            
        } else {
            wp_send_json_error( array(
                'message' => 'Trip not found',
                'status' => 404
            ));
        }
    }
}