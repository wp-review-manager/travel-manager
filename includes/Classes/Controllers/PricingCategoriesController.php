<?php
namespace WPTravelManager\Classes\Controllers;
use WPTravelManager\Classes\Services\PricingCategoriesDestinationServices;
use WPTravelManager\Classes\Models\PricingCategories;
use WPTravelManager\Classes\ArrayHelper as Arr;

class PricingCategoriesController {
    public function registerAjaxRoutes()
    {
        tmValidateNonce('tm_admin_nonce');
        $route = sanitize_text_field($_REQUEST['route']);
        $routeMaps = array(
            'post_pricing_categories' => 'postPricingCategories',
            'get_pricing_categories' => 'getPricingCategories',
            'delete_pricing_categories' => 'deletePricingCategories'
        );
        if (isset($routeMaps[$route])) {
            $this->{$routeMaps[$route]}();
            die();
        }
    }

    public function postPricingCategories() {
        $form_data = Arr::get($_REQUEST, 'data');
        $sanitize_data = PricingCategoriesDestinationServices::sanitize($form_data);
        $validation = PricingCategoriesDestinationServices::validate($sanitize_data);

        if (!empty($validation)) {
            wp_send_json_error($validation);
        }
        
        $response = (new PricingCategories())->savePricingCategories($sanitize_data);

        if ($response) {
            wp_send_json_success('Pricing Categories Updated Successfully');
        } else {
            wp_send_json_error('Failed To Updated Pricing Categories');
        }
    }
    
    public function getPricingCategories() {
        $response = (new PricingCategories())->getPricingCategories();

        wp_send_json_success(
            array(
                'data' => $response,
                'message' => 'Pricing Categories fetched successfully'
            )
        );
    }

    public function deletePricingCategories() {
        $pricing_categories_id = Arr::get($_REQUEST, 'id');
        if (!$pricing_categories_id) {
            wp_send_json_error('Pricing Categories ID is required');
        }

        $response = PricingCategories::deletePricingCategories($pricing_categories_id);

        if ($response) {
            wp_send_json_success('Pricing Categories deleted successfully');
        } else {
            wp_send_json_error('Failed to delete Pricing Categories');
        }
    }

  
}