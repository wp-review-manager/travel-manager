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

    public function getTransaction($id, $filed = 'id' ) {
        if(!$id) {
            wp_send_json_error(array(
                'message' => 'Transaction Id Not Found'
            ), 400);
        }

        $transaction = TMDBModel($this->table)->where($filed, $id)->get();

       
        if(!$transaction) {
            wp_send_json_error(array(
                'message' => 'Transaction Not Found'
            ), 400);
        }
       
        return $transaction;
          

    }

    public function getTransactionBYBookingId($bookingId) {
        $transaction = TMDBModel($this->table)->where('booking_id', $bookingId)->get();
        return $transaction;
    }

    public function updateTransaction($id, $data) {
        
        $update = TMDBModel('tm_transactions')->where('id', $id)->update($data);
      
        return $update;
    }
}