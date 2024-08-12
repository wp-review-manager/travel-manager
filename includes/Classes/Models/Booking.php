<?php

namespace WPTravelManager\Classes\Models;

if (!defined('ABSPATH')) {
    exit;
}

use WPTravelManager\Classes\Models\Model;

class Booking extends Model
{
    protected $table = 'trm_bookings';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function getBooking($booking_id)
    {
        return $this->get($booking_id);
    }

    // booking meta
    public static function getBookingMeta($booking_id, $meta_key = null)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'trm_booking_meta';

        $sql = "SELECT * FROM $table_name WHERE booking_id = %d";
        $wpdb->prepare($sql, $booking_id);
        return $wpdb->get_results($sql, ARRAY_A);
    }

}