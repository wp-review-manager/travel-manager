<?php
namespace WPTravelManager\Classes\Models;
use WPTravelManager\Classes\ArrayHelper as Arr;

class Transactions extends Model
{
    protected $model = 'tm_checkout';

    public function saveTransactions($data, $booking_id) {
        $preparedData = [];
        $preparedData['transaction_hash'] = Arr::get($data, 'booking_hash', '');
        $preparedData['payer_name'] = Arr::get($data, 'traveler_name', '');
        $preparedData['payer_email'] = Arr::get($data, 'traveler_email', '');
        $preparedData['billing_address'] = Arr::get($data, 'traveler_address', '');
        $preparedData['shipping_address'] = Arr::get($data, 'traveler_address', '');
        $preparedData['trip_id'] = Arr::get($data, 'trip_id', '');
        $preparedData['booking_id'] = sanitize_text_field( $booking_id );
        $preparedData['user_id'] = Arr::get($data, 'user_id', '');
        $preparedData['transaction_type'] =  sanitize_text_field( 'padding' );
        $preparedData['payment_method'] =  Arr::get($data, 'trm_payment_method', '');
        $preparedData['card_last_4'] =  sanitize_text_field( '1234' );
        $preparedData['card_brand'] =  sanitize_text_field( '' );
        $preparedData['charge_id'] =  sanitize_text_field( '' );
        $preparedData['payment_total'] = Arr::get($data, 'booking_total', '');
        $preparedData['status'] =  sanitize_text_field( 'padding' );
        $preparedData['currency'] =  Arr::get($data, 'currency', '');
        $preparedData['payment_mode'] = sanitize_text_field( 'test' );
        $preparedData['payment_note'] =  sanitize_text_field( '' );

        $preparedData['created_at'] = current_time('mysql');
        $preparedData['updated_at'] = current_time('mysql');

        $id = Arr::get($data, 'id', null);
        
        if ($id) {
            $response = TMDBModel('tm_transactions')->where('id', $id)->update($preparedData);
        } else {
            $response = TMDBModel('tm_transactions')->insert($preparedData);
        }
        return $response;
    }

}