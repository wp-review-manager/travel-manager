<?php
namespace WPTravelManager\Classes\Modules\Payments\PaymentMethods\Sslcommerz;
use WPPayForm\Framework\Support\Arr;

class SslcommerzProcessor {
    public function init() {
        (new SslcommerzSettings())->init();
    }
}

