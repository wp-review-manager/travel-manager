<?php
namespace WPTravelManager\Classes\Controllers;

use WPTravelManager\Classes\Services\AttributesServices;
use WPTravelManager\Classes\Models\Attributes;
use WPTravelManager\Classes\ArrayHelper as Arr;

class AttributesController {
    public function registerAjaxRoutes()
    {
        tmValidateNonce('tm_admin_nonce');
        $route = sanitize_text_field($_REQUEST['route']);
        $routeMaps = array(
            'post_attributes' => 'postAttributes',
            'get_attributes' => 'getAttributes',
            'delete_attributes' => 'deleteAttributes',
        );
        if (isset($routeMaps[$route])) {
            $this->{$routeMaps[$route]}();
            die();
        }
    }

   

    public function postAttributes() {
        $form_data = Arr::get($_REQUEST, 'data');
        $sanitize_data = AttributesServices::sanitize($form_data);
        $validation = AttributesServices::validate($sanitize_data);

        if (!empty($validation)) {
            wp_send_json_error($validation);
        }
        
        $response = (new Attributes())->saveAttributes($sanitize_data);

        if ($response) {
            wp_send_json_success('Attributes updated successfully');
        } else {
            wp_send_json_error('Failed to updated Attributes');
        }
    }

    public function getAttributes() {
        $response = (new Attributes())->getAttributes();

        wp_send_json_success(
            array(
                'data' => $response,
                'message' => 'Destinations fetched successfully'
            )
        );
    }

    public function deleteAttributes() {
        $attributes_id = Arr::get($_REQUEST, 'id');
        if (!$attributes_id) {
            wp_send_json_error('Attributes ID is required');
        }

        $response = Attributes::deleteAttributes($attributes_id);

        if ($response) {
            wp_send_json_success('Attributes deleted successfully');
        } else {
            wp_send_json_error('Failed to delete Attributes');
        }
    }

}
