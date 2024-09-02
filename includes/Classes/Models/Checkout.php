<?php
namespace WPTravelManager\Classes\Models;
use WPTravelManager\Classes\ArrayHelper as Arr;

class Checkout extends Model
{
    protected $model = 'tm_bookings';

    public function saveCheckout($data) {
 
        $id = Arr::get($data, 'id', null);
        $data['payment_method'] = Arr::get($data, 'trm_payment_method', '');
        unset($data['trm_payment_method']);
    
        if ($id) {
            return TMDBModel('tm_bookings')->where('id', $id)->update($data);
        } else {
            return TMDBModel('tm_bookings')->insert($data);
        }
    }

}