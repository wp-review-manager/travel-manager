<?php
namespace WPTravelManager\Classes\Routes;
use WPTravelManager\Classes\Controllers\DestinationController;

class AjaxActions {
    public function register () {
        add_action('wp_ajax_tm_destinations', function () {
            (new DestinationController())->registerAjaxRoutes();
        });
    }
}