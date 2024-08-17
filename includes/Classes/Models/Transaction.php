<?php

namespace WPTravelManager\Classes\Models;

if (!defined('ABSPATH')) {
    exit;
}

use WPTravelManager\Classes\Models\Model;

class Transaction extends Model
{
    protected $table = 'tm_transactions';

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

    public function getTransaction($id) {
        if(!$id) {
            wp_send_json_error(array(
                'message' => 'Transaction Id Not Found'
            ), 400);
        }

        $transaction = TMDBModel($this->table)->where('id', $id)->get();
        
        if(!$transaction) {
            wp_send_json_error(array(
                'message' => 'Transaction Not Found'
            ), 400);
        }

        dd($transaction);

        return $transaction;

    }

    public function updateTransaction($id, $data) {
        $update = TMDBModel($this->table)->where('id', $id)->update($data);
        return $update;
    }
}