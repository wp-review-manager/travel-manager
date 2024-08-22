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
        return $this->where('id', $booking_id)->get();
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

    //================================
    public function getBookingDetails($bookingId)
    {
        global $wpdb;
        $booking_table = $wpdb->prefix . 'tm_bookings';
        $transaction_table = $wpdb->prefix . 'tm_transactions'; // Assuming this is the correct table
        $order_items_table = $wpdb->prefix . 'tm_order_items';
    
        // Prepare the SQL statements
        $sqlBooking = $wpdb->prepare("SELECT * FROM $booking_table WHERE id = %d", $bookingId);
        $sqlTransactions = $wpdb->prepare("SELECT * FROM $transaction_table WHERE booking_id = %d", $bookingId);
        $sqlOrderItems = $wpdb->prepare("SELECT * FROM $order_items_table WHERE booking_id = %d", $bookingId);
    
        // Execute the queries and get the results
        $booking = $wpdb->get_row($sqlBooking, ARRAY_A);
        $transactions = $wpdb->get_results($sqlTransactions, ARRAY_A); // Fetch multiple transactions
        $orderItems = $wpdb->get_results($sqlOrderItems); // Assuming there might be multiple order items
    
        if (!$booking) {
            wp_send_json_error('Booking not found');
            return;
        }
    
        return array(
            'bookings' => $booking,
            'transactions' => $transactions,
            'orderItems' => $orderItems, // Now using consistent plural naming
        );
    }
    

}