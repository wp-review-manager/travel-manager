<?php
namespace WPTravelManager\Classes\Services;
use WPTravelManager\Classes\ArrayHelper as Arr;
use WPTravelManager\Classes\Helper;
class CouponServices {
    public static function sanitize($data)
    {
        $data['amount'] = sanitize_text_field(Arr::get($data, 'amount'));
        $data['coupon_type'] = sanitize_text_field(Arr::get($data, 'coupon_type'));
        $data['coupon_code'] = sanitize_text_field(Arr::get($data, 'coupon_code'));
        $data['created_by'] = "8-29-2024";
        $data['max_use'] = sanitize_text_field(Arr::get($data, 'max_use'));
        $data['min_amount'] = sanitize_text_field(Arr::get($data, 'min_amount'));
        $data['settings'] = "setting data";
        $data['allowed_trip_ids'] = sanitize_text_field(serialize(Arr::get($data, 'allowed_trip_ids')));
        $data['stackable'] = sanitize_text_field(Arr::get($data, 'stackable'));
        $data['end_date'] = sanitize_text_field(Arr::get($data, 'end_date'));
        $data['start_date'] = sanitize_text_field(Arr::get($data, 'start_date'));
        $data['coupon_status'] = sanitize_text_field(Arr::get($data, 'coupon_status'));
        $data['title'] = sanitize_text_field(Arr::get($data, 'title'));
        $data['meta_value'] = "meta value";

        $currentUser = Helper::getUserLoginInfo();
        if ($currentUser) {
            $data['user_ids'] = $currentUser->ID;
        }

        $id = Arr::get($data, 'id', null);
        if ($id !== null) {
            $data['id'] = absint($data['id']);
        }

        $data['created_at'] = $id ? $data['created_at'] : current_time('mysql');
        $data['updated_at'] = current_time('mysql');

     

        return $data;
    }


    public static function validate($data)
    {
        $errors = [];
        if (empty($data['title'])) {
            $errors['title'] = 'Coupon title  is required';
        }
        if (empty($data['coupon_code'])) {
            $errors['coupon_code'] = 'Coupon Code  is required';
        }
        if (empty($data['amount'])) {
            $errors['amount'] = 'Amount  is required';
        }

        return $errors;
    }
}