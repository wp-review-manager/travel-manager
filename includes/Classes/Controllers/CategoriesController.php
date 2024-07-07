<?php
namespace WPTravelManager\Classes\Controllers;
use WPTravelManager\Classes\Services\CategoriesServices;
use WPTravelManager\Classes\Models\Categories;
use WPTravelManager\Classes\ArrayHelper as Arr;

class CategoriesController {
    public function registerAjaxRoutes()
    {
        tmValidateNonce('tm_admin_nonce');
        $route = sanitize_text_field($_REQUEST['route']);
        $routeMaps = array(
            'post_categories' => 'postCategories',
            'get_categories' => 'getCategories',
            'delete_categories' => 'deleteCategories',

        );
        
        if (isset($routeMaps[$route])) {
            $this->{$routeMaps[$route]}();
            die();
        }
    }
    public function postCategories() {
        $form_data = Arr::get($_REQUEST, 'data');
        $sanitize_data = CategoriesServices::sanitize($form_data);
        $validation = CategoriesServices::validate($sanitize_data);

        if (!empty($validation)) {
            wp_send_json_error($validation);
        }
        
        $response = (new Categories())->saveCategories($sanitize_data);

        if ($response) {
            wp_send_json_success('Categories updated successfully');
        } else {
            wp_send_json_error('Failed to updated Categories');
        }
    }

    public function getCategories() {
        $response = (new Categories())->getCategories();

        wp_send_json_success(
            array(
                'data' => $response,
                'message' => 'Categories fetched successfully'
            )
        );
    }
    
    public function deleteCategories() {
        $categories_id = Arr::get($_REQUEST, 'id');
        if (!$categories_id) {
            wp_send_json_error('Categories ID is required');
        }

        $response = Categories::deleteCategories($categories_id);

        if ($response) {
            wp_send_json_success('Categories deleted successfully');
        } else {
            wp_send_json_error('Failed to delete Categories');
        }
    }


}