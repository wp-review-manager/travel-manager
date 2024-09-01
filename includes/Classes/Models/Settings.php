<?php

namespace WPTravelManager\Classes\Models;

if (!defined('ABSPATH')) {
    exit;
}

use WPTravelManager\Classes\Models\Model;

class Settings extends Model
{
    protected $table = 'options';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function getSettings($option_key)
    {
        // currency settings key = 'trm_currency_settings'
        return get_option( $option_key );
    }

    public function updateSettings($data, $option_key)
    {
        // currency settings key = 'trm_currency_settings'
        return update_option( $option_key , $data );
    }
}
