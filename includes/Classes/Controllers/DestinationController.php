<?php
namespace WPTravelManager\Classes\Controllers;
use WPTravelManager\Classes\Services\DestinationServices;
use WPTravelManager\Classes\ArrayHelper as Arr;

class DestinationController {
    public function registerAjaxRoutes()
    {
        tmValidateNonce('tm_admin_nonce');
        $route = sanitize_text_field($_REQUEST['route']);
        $routeMaps = array(
            'post_destinations' => 'postDestinations',
        );
        if (isset($routeMaps[$route])) {
            $this->{$routeMaps[$route]}();
            die();
        }
    }

    public function postDestinations() {
        $form_data = Arr::get($_REQUEST, 'data');
        $sanitize_data = DestinationServices::sanitize($form_data);
        $validation = DestinationServices::validate($sanitize_data);

        if (!empty($validation)) {
            wp_send_json_error($validation);
        }

        $response = TMDBModel('tm_destinations')->insert($sanitize_data);
        
        if ($response) {
            wp_send_json_success('Destination added successfully');
        } else {
            wp_send_json_error('Failed to add destination');
        }
    }
}