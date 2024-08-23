<?php

namespace WPTravelManager\Classes\Models;
use WPTravelManager\Classes\ArrayHelper as Arr;

if (!defined('ABSPATH')) {
    exit;
}

use WPTravelManager\Classes\Models\Model;

class Booking extends Model
{
    protected $table = 'tm_bookings';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function getBookings() {
      
        $per_page = sanitize_text_field(Arr::get($_REQUEST, 'per_page', 0));
        $page = sanitize_text_field(Arr::get($_REQUEST, 'page', 1));
        $search = sanitize_text_field(Arr::get($_REQUEST, 'search', ''));
        $orderby = sanitize_text_field(Arr::get($_REQUEST, 'orderby', 'id'));
        $order = sanitize_text_field(Arr::get($_REQUEST, 'order', 'DESC'));
        $offset = ($page - 1) * $per_page;
       
        $query = $this->table('tm_bookings')->select('*')->where('traveler_name', 'LIKE', '%'.$search.'%');
        $total = $query->getCount();
        $response = $query->orderBy($orderby, $order)->limit($per_page)->offset($offset)->get();
        $data['total'] = $total;
        $data['bookings'] = $response;

        return $data;

    }

    public static function deleteBooking($booking_id) {
        return TMDBModel('tm_bookings')->where('id', $booking_id)->delete();
    }

    public function getBooking($booking_id)
    {
        $booking = $this->where('id', $booking_id)->get();

        if(!$booking) {
            wp_send_json_error(array(
                'message' => 'Booking is Not Found'
            ), 400);
        }

        return $booking;
    }

    // booking meta
    public static function getBookingMeta($booking_id, $meta_key = null)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'tm_booking_meta';

        $sql = "SELECT * FROM $table_name WHERE booking_id = %d";
        $wpdb->prepare($sql, $booking_id);
        return $wpdb->get_results($sql, ARRAY_A);
    }

    

}