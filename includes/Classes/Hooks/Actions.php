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

        // Example Hooks
        // add_action('wp_travel_manager_before_booking_form', [$this, 'beforeBookingForm']);
        // add_action('wp_travel_manager_after_booking_form', [$this, 'afterBookingForm']);
    }
}