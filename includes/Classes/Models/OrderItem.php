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
            'currency' => Arr::get($session_data, '0.currency', []),
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

    public function saveOrderItem($order_item) {
        $prepared_data = [];
        $prepared_data['created_at'] = current_time('mysql');
        $prepared_data['updated_at'] = current_time('mysql');
        $prepared_data['trip_id'] = Arr::get($order_item, 'trip_id', '');
        $prepared_data['booking_id'] = Arr::get($order_item, 'booking_id', '');
        $prepared_data['item_qty'] = Arr::get($order_item, 'tm_travelers_number', '');
        $prepared_data['package_type'] = Arr::get($order_item, 'package_type', '');
        $prepared_data['item_name'] = Arr::get($order_item, 'pricing_label', '');
        $prepared_data['line_total'] = Arr::get($order_item, 'tm_package_price_total', '');
        $prepared_data['item_price'] = $prepared_data['package_type'] == 'person' ? Arr::get($order_item, 'tm_package_price_total', '') / $prepared_data['item_qty'] : Arr::get($order_item, 'tm_package_price_total', '');

        $id = Arr::get($order_item, 'id', null);
        
        if ($id) {
            return TMDBModel('tm_order_items')->where('id', $id)->update($prepared_data);
        } else {
            return TMDBModel('tm_order_items')->insert($prepared_data);
        }
    }

}