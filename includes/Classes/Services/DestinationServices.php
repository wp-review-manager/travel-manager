<?php
namespace WPTravelManager\Classes\Services;
use WPTravelManager\Classes\ArrayHelper as Arr;

class DestinationServices {
    public static function sanitize($data)
    {
        $data['place_name'] = sanitize_text_field( Arr::get($data, 'place_name') );
        $data['place_slug'] = sanitize_text_field( Arr::get($data, 'place_slug') );
        $data['place_desc'] = sanitize_text_field( Arr::get($data, 'place_desc') );

        $data['images']['id'] = sanitize_text_field( Arr::get($data,'images.id', '') );
        $data['images']['url'] = sanitize_url( Arr::get($data, 'images.url', '') );
        $data['images']['name'] = sanitize_text_field( Arr::get($data, 'images.name', '') );
        $data['images'] = maybe_serialize($data['images']);
        
        $id = Arr::get($data, 'id', null);
        if($id !== null) {
            $data['id'] = absint($data['id']);
        }
        
        $data['created_at'] = $id ? $data['created_at']  : current_time('mysql');
        $data['updated_at'] = current_time('mysql');
   
        return $data;
    }

    public static function validate($data)
    {
        $errors = [];
        if (empty($data['place_name'])) {
            $errors['place_name'] = 'Place name is required';
        }
        if (empty($data['place_slug'])) {
            $errors['place_slug'] = 'Place slug is required';
        }

        return $errors;
    }
}