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
}