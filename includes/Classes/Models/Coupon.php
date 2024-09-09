<?php
namespace WPTravelManager\Classes\Models;
use WPTravelManager\Classes\ArrayHelper as Arr;

class Coupon extends Model
{
    protected $model = 'tm_coupon';

    public function getTotalUser() {
        return TMDBModel('tm_coupon')->getCount('user_ids');
    }


    public function getCoupon($key= 'id', $val){
        if(!$key){
          return;
        }
        return TMDBModel('tm_coupon')->where($key, $val)->first();
    }

    public function saveCoupon($data) {
        $id = Arr::get($data, 'id', null);
        if ($id) {
            return TMDBModel('tm_coupon')->where('id', $id)->update($data);
        } else {
            return TMDBModel('tm_coupon')->insert($data);
        }
    }

    public function getCoupons() {
        $per_page = sanitize_text_field(Arr::get($_REQUEST, 'per_page', 0));
        $page = sanitize_text_field(Arr::get($_REQUEST, 'page', 1));
        $search = sanitize_text_field(Arr::get($_REQUEST, 'search', ''));
        $orderby = sanitize_text_field(Arr::get($_REQUEST, 'orderby', 'id'));
        $order = sanitize_text_field(Arr::get($_REQUEST, 'order', 'DESC'));
        $offset = ($page - 1) * $per_page;

        $query = $this->table($this->model)->select('*')->where('title', 'LIKE', '%'.$search.'%');
        $total = $query->getCount();
        $response = $query->orderBy($orderby, $order)->limit($per_page)->offset($offset)->get();
      
        // foreach ($response as  $data) {
        //     $data['allowed_trip_ids'] = maybe_unserialize($data['allowed_trip_ids']);
        // }
        // dd($response);
        $data['total'] = $total;
        $data['coupons'] = $response;

        return $data;
    }
   
    public static function deleteCoupon($coupon_id) {
        return TMDBModel('tm_coupon')->where('id', $coupon_id)->delete();
    }

    public function getCouponUsageCount($customer_email, $coupon_code) {
        return TMDBModel('tm_apply_coupon')->where('customer_email', $customer_email)->where('coupon_code', $coupon_code)->getCount();
    }

    public static function applyCouponSubmit($form_data, $couponData, $bookingId) {
        $coupon = $couponData['coupon'];
        $data = array(
            'booking_id' => $bookingId,
            'coupon_id' => $coupon->id,
            'customer_email' => $form_data['traveler_email'],
            'coupon_code' => $couponData['coupon_code'],
            'discount_amount' => $couponData['discount'],
            'trip_id' => $form_data['trip_id'],
            'coupon_type' => $coupon->coupon_type,
            'stackable' => $coupon->stackable,
            'title' => $coupon->title,
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql'),
        );

        try {
            TMDBModel('tm_apply_coupon')->insert($data);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getApplyCoupon($id, $filed = 'id' ) {
        if(!$id) {
            wp_send_json_error(array(
                'message' => 'Apply Coupon Id Not Found'
            ), 400);
        }
        $applyCoupon = TMDBModel('tm_apply_coupon')->where($filed, $id)->get();
       
        if(!$applyCoupon) {
            return;
            // wp_send_json_error(array(
            //     'message' => 'Apply Coupon Not Found'
            // ), 400);
        }
       
        return $applyCoupon;
          

    }
}