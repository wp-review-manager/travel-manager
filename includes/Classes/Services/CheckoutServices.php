<?php
namespace WPTravelManager\Classes\Services;
use WPTravelManager\Classes\ArrayHelper as Arr;
use WPTravelManager\Classes\Helper;

class CheckoutServices {
    public static function sanitize($data)
    {
        $data['booking_hash'] = $data['booking_hash'] = hash('sha256', Arr::get($data, 'booking_hash'));
        $data['trip_id'] = sanitize_text_field( Arr::get($data, 'trip_id') );
        $data['booking_total'] = sanitize_text_field( Arr::get($data, 'booking_total') );
        $data['traveler_name'] = sanitize_text_field( Arr::get($data, 'traveler_name') );
        $data['traveler_email'] = sanitize_email( Arr::get($data, 'traveler_email') );
        $data['traveler_country'] = sanitize_text_field( Arr::get($data, 'traveler_country') );
        $data['traveler_phone'] = sanitize_text_field( Arr::get($data, 'traveler_phone') );
        $data['traveler_address'] = sanitize_text_field( Arr::get($data, 'traveler_address') );
        $data['booking_date'] = sanitize_text_field( Arr::get($data, 'booking_date') );
        $data['booking_status'] = sanitize_text_field( 'padding' );
        $data['booking_note'] = sanitize_textarea_field( 'hello booking status' );
        $data['payment_method'] = sanitize_text_field( Arr::get($data, 'trm_payment_method') );

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

    public static function validate($data)
    {
        $errors = [];

        if (empty($data['traveler_name'])) {
            $errors['traveler_name'] = 'Name is required';
        }
        if (empty($data['traveler_email'])) {
            $errors['traveler_email'] = 'Email is required';
        }
        if (empty($data['traveler_phone'])) {
            $errors['traveler_phone'] = 'Phone is required';
        }
        if (empty($data['traveler_country'])) {
            $errors['traveler_country'] = 'Message is required';
        }
        if (empty($data['address'])) {
            $errors['traveler_address'] = 'Address is required';
        }
        if (empty($data['city'])) {
            $errors['city'] = 'Booking total is required';
        }
        if (empty($data['state'])) {
            $errors['state'] = 'Booking total is required';
        }
        if (empty($data['zip_code'])) {
            $errors['zip_code'] = 'Booking total is required';
        }
        if (empty($data['booking_date'])) {
            $errors['booking_date'] = 'Booking date is required';
        }

        if (empty($data['trm_payment_method'])) {
            $errors['payment_method'] = 'Payment method is required';
        }

        if (!empty($errors)) {
            wp_send_json_error($errors);
        }

        if (isset($data['session_id'])) {
            unset($data['session_id']);
        }

        $data['traveler_address'] = array(
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'zip_code' => $data['zip_code'],
            'country' => $data['traveler_country']
        );

        unset($data['address']);
        unset($data['city']);
        unset($data['state']);
        unset($data['zip_code']);

        $data['traveler_address'] = maybe_serialize($data['traveler_address']);
      
        return $data;
    }
}