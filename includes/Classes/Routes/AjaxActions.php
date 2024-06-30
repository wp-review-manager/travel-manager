<?php
namespace WPTravelManager\Classes\Routes;
use WPTravelManager\Classes\Controllers\DestinationController;
use WPTravelManager\Classes\Controllers\AttributesController;
use WPTravelManager\Classes\Controllers\TripsController;

class AjaxActions {
    public function register () {
        add_action('wp_ajax_tm_destinations', function () {
            (new DestinationController())->registerAjaxRoutes();
        });
        add_action('wp_ajax_tm_trips', function () {
            (new TripsController())->registerAjaxRoutes();
        });
        add_action('wp_ajax_tm_attributes', function () {
            (new AttributesController())->registerAjaxRoutes();
        });
    }
}