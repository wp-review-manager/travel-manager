<?php
namespace WPTravelManager\Classes\Models;
use WPTravelManager\Classes\ArrayHelper as Arr;

class Checkout extends Model
{
    protected $model = 'tm_bookings';

    public function saveCheckout($data) {
 
        $id = Arr::get($data, 'id', null);
        
        if ($id) {
            return TMDBModel('tm_bookings')->where('id', $id)->update($data);
        } else {
            return TMDBModel('tm_bookings')->insert($data);
        }
    }

}