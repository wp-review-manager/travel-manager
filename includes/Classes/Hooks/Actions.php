<?php 
namespace WPTravelManager\Classes\Hooks;
use WPTravelManager\Classes\Modules\ProcessPreviewPage;
use WPTravelManager\Classes\Modules\Payments\PaymentHandler;
use WPTravelManager\Views\Checkout\SubmissionCheckout;

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
        (new PaymentHandler())->init();
        (new SubmissionCheckout())->init();

    }
}