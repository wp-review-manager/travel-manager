<?php

namespace WPTravelManager\Classes\Models;

if (!defined('ABSPATH')) {
    exit;
}

use WPTravelManager\Classes\Models\Model;

class Order extends Model
{
    protected $table = 'trm_order_items';

    public function __construct()
    {
        parent::__construct($this->table);
    }
}