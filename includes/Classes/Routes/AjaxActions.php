<?php
namespace WPTravelManager\Classes\Routes;

class AjaxActions {
    public function register () {
        add_action('wp_ajax_tm_post_destinations', function () {
        });
    }
}