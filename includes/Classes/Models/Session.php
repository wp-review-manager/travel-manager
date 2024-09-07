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
        unset($data['currency']);
        $response = $this->insert($data);
        return $response;
    }

    public function getSession($id)
    {
        $response = $this->where('device_id', $id)->get();
        return $response;
    }

    public function deleteSessionItem($id)
    {
        $id = sanitize_text_field($id);
        $response = $this->where('id', $id)->delete();
        return $response;
    }
}