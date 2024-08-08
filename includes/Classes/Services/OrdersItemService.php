<?php
namespace WPTravelManager\Classes\Services;
use WPTravelManager\Classes\ArrayHelper as Arr;
use WPTravelManager\Classes\Helper;

class OrdersItemService {
    public static function sanitize($data, $session_meta)
    {
        $data['booking_id'] = sanitize_text_field( Arr::get($data, 'booking_id') );
        $data['trip_id'] = sanitize_text_field( Arr::get($data, 'trip_id') );
        
      foreach($session_meta as $session_data){
        $data['item_name'] = sanitize_text_field( Arr::get($session_data, 'pricing_label') );
        $data['package_type'] = sanitize_text_field( Arr::get($session_data, 'package_type') );
        $data['item_qty'] = sanitize_text_field( Arr::get($session_data, 'tm_travelers_number') );
        $data['item_price'] = sanitize_text_field( Arr::get($session_data, 'tm_package_price_total') );
        $data['line_total'] = sanitize_text_field( $data['item_qty'] * $data['item_price'] );
      }
        

        $id = Arr::get($data, 'id', null);
        if($id !== null) {
            $data['id'] = absint($data['id']);
        }
        
        $data['created_at'] = $id ? $data['created_at']  : current_time('mysql');
        $data['updated_at'] = current_time('mysql');
        
        $currentUser = Helper::getUserLoginInfo();
        if($currentUser) {
            $data['user_id'] = $currentUser->ID;
        }
 
        return $data;
    }

   
}