<?php
namespace WPTravelManager\Classes\Services;
use WPTravelManager\Classes\ArrayHelper as Arr;

class CategoriesServices {
    public static function sanitize($data)
    {
        $data['trip_category_name'] = sanitize_text_field( Arr::get($data, 'trip_category_name') );
        $data['trip_category_slug'] = sanitize_text_field( Arr::get($data, 'trip_category_slug') );
        $data['trip_category_desc'] = sanitize_text_field( Arr::get($data, 'trip_category_desc') );

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
        if (empty($data['trip_category_name'])) {
            $errors['trip_category_name'] = 'Place name is required';
        }
        if (empty($data['trip_category_slug'])) {
            $errors['trip_category_slug'] = 'Place slug is required';
        }

        return $errors;
    }
}