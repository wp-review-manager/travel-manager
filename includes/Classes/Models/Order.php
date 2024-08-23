<?php

namespace WPTravelManager\Classes\Models;

if (!defined('ABSPATH')) {
    exit;
}

use WPTravelManager\Classes\Models\Model;

class Order extends Model
{
    protected $table = 'tm_order_items';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function getOrderItem($booking_id)
    {
        $orderItems = $this->where('booking_id', $booking_id)->get();
        
        if(!$orderItems) {
            wp_send_json_error(array(
                'message' => 'Order Item Not Found'
            ), 400);
        }
        return  $orderItems;
    }
}