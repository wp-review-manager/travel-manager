<?php

namespace WPTravelManager\Classes\Models;

if (!defined('ABSPATH')) {
    exit;
}

use WPTravelManager\Classes\Models\Model;

class Transaction extends Model
{
    protected $table = 'trm_transactions';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function getByPaymentId($chargeId, $method = 'paypal')
    {
        $payment = $this->where('charge_id', $chargeId)
            ->where('payment_method', $method)
            ->first();

        if ($payment) {
            return $payment->id;
        }
        return false;
    }
}