<?php
namespace WPTravelManager\Classes\Routes;
use WPTravelManager\Classes\Controllers\DestinationController;
use WPTravelManager\Classes\Controllers\AttributesController;
use WPTravelManager\Classes\Controllers\TripsController;
use WPTravelManager\Classes\Controllers\CategoriesController;
use WPTravelManager\Classes\Controllers\ActivitiesController;
use WPTravelManager\Classes\Controllers\DifficultyController;
use WPTravelManager\Classes\Controllers\PricingCategoriesController;
use WPTravelManager\Classes\Controllers\SettingsController;
use WPTravelManager\Classes\Controllers\InquiryController;
use WPTravelManager\Classes\Controllers\BookingsController;
use WPTravelManager\Classes\Controllers\SessionsController;
use WPTravelManager\Classes\Controllers\CheckoutController;
use WPTravelManager\Classes\Controllers\CouponController;
use WPTravelManager\Classes\Modules\Payments\PaymentMethods\PaymentSettingsController;

class AjaxActions {
    public function register () {
        add_action('wp_ajax_tm_trips', function () {
            tmValidateNonce('tm_admin_nonce');
            (new TripsController())->registerAjaxRoutes();
        });

        add_action('wp_ajax_nopriv_tm_trips', function () {
            tmValidateNonce('tm_admin_nonce');
            (new TripsController())->registerAjaxRoutes();
        });

        add_action('wp_ajax_tm_destinations', function () {
            (new DestinationController())->registerAjaxRoutes();
        });
        add_action('wp_ajax_tm_categories', function () {
            (new CategoriesController())->registerAjaxRoutes();
        });
        add_action('wp_ajax_tm_attributes', function () {
            (new AttributesController())->registerAjaxRoutes();
        });
        add_action('wp_ajax_tm_activities', function () {
            (new ActivitiesController())->registerAjaxRoutes();
        });
        add_action('wp_ajax_tm_difficulty', function () {
            (new DifficultyController())->registerAjaxRoutes();
        });
        add_action('wp_ajax_tm_pricing_categories', function () {
            (new PricingCategoriesController())->registerAjaxRoutes();
        });

        add_action( 'wp_ajax_trm_payment_settings', function () {
            (new PaymentSettingsController())->registerAjaxRoutes();
        });

        add_action('wp_ajax_tm_settings', function () {
            (new SettingsController())->registerAjaxRoutes();
        });

        // Public Ajax Actions

        add_action('wp_ajax_tm_inquiry', function () {
            (new InquiryController())->registerAjaxRoutes();
        });

        add_action('wp_ajax_tm_trip_booking', function() {
            // (new BookingsController())->registerAjaxRoutes();
        });
        add_action('wp_ajax_nopriv_tm_trip_booking', function() {
            dd($_REQUEST);
        });

        add_action('wp_ajax_tm_trip_session', function() {
            (new SessionsController())->registerAjaxRoutes();
        });
        add_action('wp_ajax_nopriv_tm_trip_session', function() {
            (new SessionsController())->registerAjaxRoutes();
        });
        add_action('wp_ajax_tm_checkout', function() {
            (new CheckoutController())->registerAjaxRoutes();
        });

        add_action('wp_ajax_tm_bookings', function () {
            (new BookingsController())->registerAjaxRoutes();
        });

        add_action('wp_ajax_tm_coupon', function () {
            (new CouponController())->registerAjaxRoutes();
        });


    }
}