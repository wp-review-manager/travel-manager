<?php
namespace WPTravelManager\Classes\Controllers;
use WPTravelManager\Classes\Services\DestinationServices;
use WPTravelManager\Classes\Models\Destination;
use WPTravelManager\Classes\ArrayHelper as Arr;

class DestinationController {
    public function registerAjaxRoutes()
    {
        tmValidateNonce('tm_admin_nonce');
        $route = sanitize_text_field($_REQUEST['route']);
        $routeMaps = array(
            'post_destinations' => 'postDestinations',
            'get_destinations' => 'getDestinations',
            'delete_destinations' => 'deleteDestinations',
        );
        if (isset($routeMaps[$route])) {
            $this->{$routeMaps[$route]}();
            die();
        }
    }

    public function deleteDestinations() {
        $destination_id = Arr::get($_REQUEST, 'id');
        if (!$destination_id) {
            wp_send_json_error('Destination ID is required');
        }

        $response = Destination::deleteDestination($destination_id);

        if ($response) {
            wp_send_json_success('Destination deleted successfully');
        } else {
            wp_send_json_error('Failed to delete destination');
        }
    }

    public function postDestinations() {
        $form_data = Arr::get($_REQUEST, 'data');
        $sanitize_data = DestinationServices::sanitize($form_data);
        $validation = DestinationServices::validate($sanitize_data);

        if (!empty($validation)) {
            wp_send_json_error($validation);
        }
        
        $response = (new Destination())->saveDestination($sanitize_data);

        if ($response) {
            wp_send_json_success('Destination updated successfully');
        } else {
            wp_send_json_error('Failed to updated destination');
        }
    }

    public function getDestinations() {
        $response = (new Destination())->getDestination();

        wp_send_json_success(
            array(
                'data' => $response,
                'message' => 'Destinations fetched successfully'
            )
        );
    }
}