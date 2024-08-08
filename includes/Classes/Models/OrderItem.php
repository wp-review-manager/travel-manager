<?php
namespace WPTravelManager\Classes\Models;
use WPTravelManager\Classes\ArrayHelper as Arr;

class OrderItem extends Model
{
    protected $model = 'tm_checkout';

    public function getSessionData($booking_id) {
      
        global $wpdb;
        $request = $_REQUEST;
        $booking_id = sanitize_text_field($booking_id);

        $table_name = $wpdb->prefix . 'tm_sessions';

        $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $booking_id);
        $session_data = $wpdb->get_results($sql, ARRAY_A);
       
        // Process reviews
        $session_meta = static::processReviewData($session_data);

        // $data['session_meta'] = $session_meta;

        return [
            'session_meta' => $session_meta['packages_data'],
            'session_id' => $session_meta['session_id'],
        ];

    }

    public static function processReviewData($session_meta) {
        foreach ($session_meta as  $data) {
            $data['session_meta'] = maybe_unserialize($data['session_meta']);
           
            // Calculate average rating
            $packages_data = Arr::get($data, 'session_meta.packages', []);
            $session_id = Arr::get($data, 'id', '');
        }
        foreach($packages_data as $packages){
            $tm_travelers_number = Arr::get($packages, 'tm_travelers_number', '');
            $tm_package_price_total = Arr::get($packages, 'tm_package_price_total', '');
            $pricing_label = Arr::get($packages, 'pricing_label', '');
            $package_type = Arr::get($packages, 'package_type', '');
        }
        //dd($tm_travelers_number, $tm_package_price_total, $pricing_label, $package_type);
        return [
            'packages_data' => $packages_data,
            'session_id' => $session_id,
        ];
    }

    public function saveOrderItem($sanitize_order_item) {
  
        $id = Arr::get($sanitize_order_item, 'id', null);
        
        if ($id) {
            return TMDBModel('tm_order_items')->where('id', $id)->update($sanitize_order_item);
        } else {
            return TMDBModel('tm_order_items')->insert($sanitize_order_item);
        }
    }

}