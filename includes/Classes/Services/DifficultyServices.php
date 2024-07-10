<?php
namespace WPTravelManager\Classes\Services;
use WPTravelManager\Classes\ArrayHelper as Arr;

class DifficultyServices {
    public static function sanitize($data)
    {
        $data['trip_defaulty_name'] = sanitize_text_field( Arr::get($data, 'trip_defaulty_name') );
        $data['trip_defaulty_slug'] = sanitize_text_field( Arr::get($data, 'trip_defaulty_slug') );
        $data['trip_defaulty_desc'] = sanitize_text_field( Arr::get($data, 'trip_defaulty_desc') );

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
        if (empty($data['trip_defaulty_name'])) {
            $errors['trip_defaulty_name'] = 'Difficulty name is required';
        }
        if (empty($data['trip_defaulty_slug'])) {
            $errors['trip_defaulty_slug'] = 'Difficulty slug is required';
        }

        return $errors;
    }
}