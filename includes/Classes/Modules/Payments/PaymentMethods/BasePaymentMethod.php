<?php

namespace WPTravelManager\Classes\Modules\Payments\PaymentMethods;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.  
}

use WPTravelManager\Classes\Modules\Payments\PaymentHelper;
use WPTravelManager\Classes\ArrayHelper as Arr;

abstract class BasePaymentMethod
{
    public $title = '';
    public $method = '';
    public $description = '';
    public $image = '';
   
    public static $methods = array();

    public static $routes = array();

    private static $index = 0;

    public $assetUrl = TRM_URL . 'assets/images/gateways/';

    public function __construct($title, $method, $description, $image)
    {
        $this->title = $title;
        $this->method = $method;
        $this->description = $description;
        $this->image = $this->assetUrl . $image;

        $this->registerHooks($method);

        add_action('trm/get_payment_settings_' . $this->method, array($this, 'getPaymentSettings'), 10, 1);

        add_filter('trm_before_save_' . $this->method, array($this, 'sanitize'), 15, 2);

        add_filter('trm/get_all_payment_methods', array($this, 'getAllMethods'), 10, 1);

        add_filter('trm/get_all_active_payment_methods', array($this, 'getAllActiveMethods'), 10, 1);

        add_filter('trm/payment_methods_routes', array($this, 'getPaymentRoutes'), 10, 1);

        add_action('wp_ajax_nopriv_trm_payment_confirmation_'. $this->method, array($this, 'paymentConfirmation'));
        add_action('wp_ajax_trm_payment_confirmation_' . $this->method, array($this, 'paymentConfirmation'));
    }

    public function getAllMethods()
    {
        static::$methods[$this->method] = array(
            'title' => $this->title,
            'route' => $this->method,
            'name' => $this->method,
            'description' => $this->description,
            'image' => $this->image,
            "status" => $this->isEnabled(),
        );
        return static::$methods;
    }

    public function getPaymentRoutes()
    {
        static::$routes[static::$index++] = array(
            'path' => $this->method,
            'name' => $this->method,
            'meta' => [
                'title' => $this->title
            ] 
        );

        return static::$routes;
    }

    public function getAllActiveMethods($methods)
    {
        if ($this->isEnabled()) {
            $methods[$this->method] = array(
                'title' => $this->title,
                'route' => $this->method,
                'name' => $this->method,
                'description' => $this->description,
                'image' => $this->image,
                "status" => $this->isEnabled(),
            );
        }
        return $methods;
    }

    public function registerHooks()
    {
        add_action('trm/render_payment_method_' . $this->method, array($this, 'render'), 10);
    }

    abstract public function isEnabled();

    public function getMode()
    {
        $paymentSettings = $this->getSettings();
        return ($paymentSettings['payment_mode'] == 'live') ? 'live' : 'test';
    }

    public function uniqueId($id = '')
    {
        return esc_attr(PaymentHelper::getCheckoutDynamicClass() . '_' . $id);
    }

    public function paymentConfirmation()
    {
        //return if no module extend
    }

    public function pushPaymentRoutes($routes)
    {

    }

    public function getPaymentMode($method)
    {
        $isLive = self::isLive($method);
        if ($isLive) {
            return 'live';
        }
        return 'test';

    }

    public static function isLive($method) {
        $settings = get_option('trm_payment_settings_' .  $method, array());
        $active = Arr::get($settings, 'is_active', 'no');
        $paymentMode = Arr::get($settings, 'payment_mode', 'test');

        if(!count($settings)) {
            wp_send_json_error(
                array(
                    'message' => __('Payment settings is not fulfill', 'travel-manager')
                ),
                400
                );
        }
        if($active == 'no') {
            wp_send_json_error(
                array(
                    'message' => __('This payment gateway is not active', 'travel-manager')
                ),
                400
                );
        }
       
        return $paymentMode == 'live';
    }

    abstract public function render();

    abstract public function getPaymentSettings();

    abstract public function getSettings();

    abstract public function sanitize($settings);
}
