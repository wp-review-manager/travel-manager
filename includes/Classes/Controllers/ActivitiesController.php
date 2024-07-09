<?php
namespace WPTravelManager\Classes\Controllers;
use WPTravelManager\Classes\Services\ActivitiesServices;
use WPTravelManager\Classes\Models\Activities;
use WPTravelManager\Classes\ArrayHelper as Arr;

class ActivitiesController {
    public function registerAjaxRoutes()
    {
        tmValidateNonce('tm_admin_nonce');
        $route = sanitize_text_field($_REQUEST['route']);
        $routeMaps = array(
            'post_activities' => 'postActivities',
            'get_activities' => 'getActivities',
            'delete_activities' => 'deleteActivities'
        );
        if (isset($routeMaps[$route])) {
            $this->{$routeMaps[$route]}();
            die();
        }
    }

    public function postActivities() {
        $form_data = Arr::get($_REQUEST, 'data');
        $sanitize_data = ActivitiesServices::sanitize($form_data);
        $validation = ActivitiesServices::validate($sanitize_data);
       

        if (!empty($validation)) {
            wp_send_json_error($validation);
        }
        
        $response = (new Activities())->saveActivities($sanitize_data);
    
        if ($response) {
            wp_send_json_success('Activities updated successfully');
        } else {
            wp_send_json_error('Failed to updated activities');
        }
    }
    public function getActivities() {
        $response = (new Activities())->getActivities();

        wp_send_json_success(
            array(
                'data' => $response,
                'message' => 'Activities fetched successfully'
            )
        );
    }
    public function deleteActivities() {
        $activities_id = Arr::get($_REQUEST, 'id');
        if (!$activities_id) {
            wp_send_json_error('Activities ID is required');
        }
       
        $response = Activities::deleteActivities($activities_id);
       
        if ($response) {
            wp_send_json_success('Activities deleted successfully');
        } else {
            wp_send_json_error('Failed to delete activities');
        }
    }
}