<?php

namespace WPTravelManager\Classes\Modules\Payments\PaymentMethods\Sslcommerz;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
use WPTravelManager\Classes\ArrayHelper as Arr;


class API
{

    public function validation($keys, $payId, $mode = 'live')
    {
        $validationApi = 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php';

        if ($mode !== 'live') {
            // API Endpoint (Sandbox/Test Environment):
            $validationApi = 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php';
        }

        $args = [
            'val_id' => $payId,
        ];

        $keys['api_path'] = $validationApi;

        return $this->makeApiCall($keys, $args, 'GET');
    }

    public function makeApiCall($keys, $args, $method = 'GET')
    {
        $args['store_id'] = $keys['api_key'];
        $args['store_passwd'] = $keys['api_secret'];

                // dd($keys, $args, $method);

        if ($method == 'POST') {
            $response = wp_remote_post($keys['api_path'], [
                'body' => $args
            ]);
        } else {
            $response = wp_remote_get($keys['api_path'], [
                'body' => $args
            ]);
        }

        if (is_wp_error($response)) {
            return $response;
        }

        if (Arr::get($response, 'response.code') !== 200) {
            return new \WP_Error(423, Arr::get($response, 'response.code'));
        };

        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);
    }

    public function refundApi($keys, $args, $call = 'refund')
    {
        $args['store_id'] = $keys['api_key'];
        $args['store_passwd'] = $keys['api_secret'];

        $keys['api_path'] = 'https://securepay.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php';

        if ($keys['payment_mode'] !== 'live') {
            $keys['api_path'] = 'https://sandbox.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php';
        }
       
        if ($call == 'refund') {
            $response = wp_remote_post($keys['api_path'], [
                'body' => $args
            ]);
        } else {
            $response = wp_remote_get($keys['api_path'], [
                'body' => $args
            ]);
        }
     


        if (is_wp_error($response)) {
            return $response;
        }

        if (Arr::get($response, 'response.code') !== 200) {
            return new \WP_Error(423, Arr::get($response, 'response.code'));
        };

        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);

    }
}
