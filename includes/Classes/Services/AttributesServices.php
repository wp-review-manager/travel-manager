<?php
namespace WPTravelManager\Classes\Services;
use WPTravelManager\Classes\ArrayHelper as Arr;

class AttributesServices {
    public static function sanitize($data)
    {
        $data['attr_title'] = sanitize_text_field( Arr::get($data, 'attr_title') );
        $data['attr_slug'] = sanitize_text_field( Arr::get($data, 'attr_slug') );
        $data['attr_desc'] = sanitize_text_field( Arr::get($data, 'attr_desc') );

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
        if (empty($data['attr_title'])) {
            $errors['attr_title'] = 'Attributes Title is required';
        }
        if (empty($data['attr_slug'])) {
            $errors['attr_slug'] = 'Attributes slug is required';
        }

        return $errors;
    }
}