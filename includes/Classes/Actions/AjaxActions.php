<?php
namespace WPTravelManager\Classes\Actions;

class AjaxActions {
    public function register () {
        add_action('wp_ajax_tm_post_destinations', function () {
            dd("dhcdsjhc");
        });
    }
}