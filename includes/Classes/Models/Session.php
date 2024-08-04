<?php

namespace WPTravelManager\Classes\Models;

if (!defined('ABSPATH')) {
    exit;
}

use WPTravelManager\Classes\Models\Model;

class Session extends Model
{
    protected $table = 'tm_sessions';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function create($data)
    {
        $response = $this->insert($data);
        return $response;
    }
}