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

        return  $orderItems;
    }
}