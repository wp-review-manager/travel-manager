<?php
namespace WPTravelManager\Classes\Modules\Payments;

class PaymentHandler {
    public function init() {
        // Register PayPal Payment Gateway
        $payrexx = new \WPTravelManager\Classes\Modules\Payments\PaymentMethods\Sslcommerz\SslcommerzProcessor();
        $payrexx->init();
    }
}