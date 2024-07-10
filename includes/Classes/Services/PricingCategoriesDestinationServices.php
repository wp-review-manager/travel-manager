<?php
namespace WPTravelManager\Classes\Services;
use WPTravelManager\Classes\ArrayHelper as Arr;

class PricingCategoriesDestinationServices {
    public static function sanitize($data)
    {
        $data['pricing_categories_name'] = sanitize_text_field( Arr::get($data, 'pricing_categories_name') );
        $data['pricing_categories_slug'] = sanitize_text_field( Arr::get($data, 'pricing_categories_slug') );
        $data['pricing_categories_desc'] = sanitize_text_field( Arr::get($data, 'pricing_categories_desc') );

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
        if (empty($data['pricing_categories_name'])) {
            $errors['pricing_categories_name'] = 'Pricing categories name is required';
        }
        if (empty($data['pricing_categories_slug'])) {
            $errors['pricing_categories_slug'] = 'Pricing categories slug is required';
        }

        return $errors;
    }
}