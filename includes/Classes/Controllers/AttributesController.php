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

}
