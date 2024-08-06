<?php

namespace WPTravelManager\Classes\Modules\Payments;

if (!defined('ABSPATH')) {
    exit;
}


class PaymentMethods
{

    public static function getRoutes()
    {
        $default = array(
            [
                'path' => 'offline',
                'name' => 'offline',
                'meta'=> [
                    'title' => 'Direct Bank Transfer' 
                ]
            ],
            [
                'path' => 'paypal',
                'name' => 'paypal',
                'meta'=> [
                    'title' => 'PayPal' 
                ]
            ],
            [
                'path' => 'stripe',
                'name' => 'stripe',
                'meta'=> [
                    'title' => 'Stripe' 
                ]
            ],
            [
                'path' => 'sslcommerz',
                'name' => 'sslcommerz',
                'meta'=> [
                    'title' => 'SSLCommerz' 
                ]
            ],
        );
        
        return  $default;
    }

    public static function getMethods()
    {
        $default =  array(
            'offline' => array(
                'title' => 'Offline',
                'route_name' => 'offline',
                'available' => true,
                'svg' => TRM_URL .'assets/images/gateways/offline.svg',
            ),
            'stripe' => array(
                'title' => 'Stripe',
                'route_name' => 'stripe',
                'available' => true,
                'svg' => TRM_URL .'assets/images/gateways/stripe.svg',
            ),
            'paypal' => array(
                'title' => 'PayPal',
                'route_name' => 'paypal',
                'available' => false,
                'svg' => TRM_URL .'assets/images/gateways/paypal.svg',
                'route_query' => [],
            ),
            'sslcommerz' => array(
                'title' => 'SSLCommerz',
                'route_name' => 'sslcommerz',
                'available' => false,
                'svg' => TRM_URL .'assets/images/gateways/sslcommerz.svg',
                'route_query' => [],
            ),
        );
       
        return $default;
    }

    public static function getPaymentAddons()
    {
        $addons = array(
            'paypal' => array(
                'name' => 'paypal',
                'slug' => 'paypal-payment-for-travel-manager',
                'svg' => TRM_URL .'assets/images/gateways/paypal.svg',
                'src' => 'travel-manager',
                'url' => ''
            ),
        );

        return $addons;
    }

}