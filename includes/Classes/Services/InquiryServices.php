<?php
namespace WPTravelManager\Classes\Services;
use WPTravelManager\Classes\ArrayHelper as Arr;

class InquiryServices {
    public static function sanitize($data)
    {
        $data['trip_id'] = sanitize_text_field( Arr::get($data, 'trip_id') );
        $data['name'] = sanitize_text_field( Arr::get($data, 'name') );
        $data['email'] = sanitize_email( Arr::get($data, 'email') );
        $data['country'] = sanitize_text_field( Arr::get($data, 'country') );
        $data['phone'] = sanitize_text_field( Arr::get($data, 'phone') );
        $data['subject'] = sanitize_text_field( Arr::get($data, 'subject') );
        $data['number_of_adults'] = sanitize_text_field( Arr::get($data, 'number_of_adults') );
        $data['number_of_children'] = sanitize_text_field( Arr::get($data, 'number_of_children') );
        $data['message'] = sanitize_textarea_field( Arr::get($data, 'message') );

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

        if (empty($data['name'])) {
            $errors['name'] = 'Name is required';
        }
        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        }
        if (empty($data['phone'])) {
            $errors['phone'] = 'Phone is required';
        }
        if (empty($data['message'])) {
            $errors['message'] = 'Message is required';
        }

        return $errors;
    }
}