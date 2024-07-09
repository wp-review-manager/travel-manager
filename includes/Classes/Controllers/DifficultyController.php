<?php
namespace WPTravelManager\Classes\Controllers;
use WPTravelManager\Classes\Services\DifficultyServices;
use WPTravelManager\Classes\Models\Difficulty;
use WPTravelManager\Classes\ArrayHelper as Arr;

class DifficultyController {
    public function registerAjaxRoutes()
    {
        tmValidateNonce('tm_admin_nonce');
        $route = sanitize_text_field($_REQUEST['route']);
      
        $routeMaps = array(
            'post_difficulty' => 'postDifficulty',
            'get_difficulty' => 'getDifficulty',
            'delete_difficulty' => 'deleteDifficulty',
        );
        
        if (isset($routeMaps[$route])) {
            $this->{$routeMaps[$route]}();
            die();
        }
    }
    public function getDifficulty() {
        $response = (new Difficulty())->getDifficulty();

        wp_send_json_success(
            array(
                'data' => $response,
                'message' => 'Difficulty fetched successfully'
            )
        );
    }

    public function postDifficulty() {
        $form_data = Arr::get($_REQUEST, 'data');
        $sanitize_data = DifficultyServices::sanitize($form_data);
        $validation = DifficultyServices::validate($sanitize_data);
      
        if (!empty($validation)) {
            wp_send_json_error($validation);
        }
        
        $response = (new Difficulty())->saveDifficulty($sanitize_data);

        if ($response) {
            wp_send_json_success('Activities updated successfully');
        } else {
            wp_send_json_error('Failed to updated Activities');
        }
    }

    public function deleteDifficulty() {
        $difficulty_id = Arr::get($_REQUEST, 'id');
        if (!$difficulty_id) {
            wp_send_json_error('Difficulty ID is required');
        }

        $response = Difficulty::deleteDifficulty($difficulty_id);

        if ($response) {
            wp_send_json_success('Difficulty deleted successfully');
        } else {
            wp_send_json_error('Failed to delete Difficulty');
        }
    }



}