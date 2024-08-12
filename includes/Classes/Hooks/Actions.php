<?php 
namespace WPTravelManager\Classes\Hooks;
use WPTravelManager\Classes\Modules\ProcessPreviewPage;

class Actions {
    public function __construct() {
        $this->register();
    }

    public function register() {
        // Handle Exterior Pages
        add_action('wp', array(new ProcessPreviewPage(), 'handleExteriorPages'));
        // Load Some Classes
        add_action('init', array($this, 'initClasses'));

    }
    public function initClasses() {
        // init payment methods
        require TRM_DIR . 'includes/Classes/Modules/Payments/PaymentMethods/PayPal/PayPal.php';
        // require TRM_DIR . 'includes/Classes/Modules/Payments/PaymentMethods/Stripe/Stripe.php';
        // require TRM_DIR . 'includes/Classes/Modules/Payments/PaymentMethods/Offline/Offline.php';
        require TRM_DIR . 'includes/Classes/Modules/Payments/PaymentMethods/SSLCommerz/SSLCommerz.php';

        new \WPTravelManager\Classes\Modules\Payments\PaymentMethods\PayPal\PayPal();
        // new \WPTravelManager\Classes\Modules\Payments\PaymentMethods\Stripe\Stripe();
        // new \WPTravelManager\Classes\Modules\Payments\PaymentMethods\Offline\Offline();
        new \WPTravelManager\Classes\Modules\Payments\PaymentMethods\SSLCommerz\SSLCommerz();

    }
}