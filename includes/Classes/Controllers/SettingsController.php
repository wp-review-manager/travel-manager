<?php

namespace WPTravelManager\Classes\Controllers;
use WPTravelManager\Classes\Models\Settings;
use WPTravelManager\Classes\ArrayHelper as Arr;


class SettingsController {
    public function registerAjaxRoutes()
    {
        tmValidateNonce('tm_admin_nonce');
        $route = sanitize_text_field($_REQUEST['route']);
        $routeMaps = array(
            'post_settings' => 'updateSettings',
            'get_settings' => 'getSettings',
        );
        if (isset($routeMaps[$route])) {
            $this->{$routeMaps[$route]}();
            die();
        }
    }

    public function updateSettings() {
        $settings = Arr::get($_REQUEST, 'currency_settings', array());
        if(empty($settings)) {
            wp_send_json_error(array('message' => __('Invalid data', 'travel-manager')));
        }
        $settings = array_map('sanitize_text_field', $settings);
        if (empty($settings)) {
            wp_send_json_error(array('message' => __('Invalid data', 'travel-manager')));
        }
        $option_key = Arr::get($_REQUEST, 'option_key');

        if (empty($option_key)) {
            wp_send_json_error(array('message' => __('Option Key Is Missing', 'travel-manager')));
        }

        $settingsModel = new Settings();
        $response = $settingsModel->updateSettings($settings, $option_key);

        if ($response) {
            wp_send_json_success(array('message' => __('Settings updated successfully', 'travel-manager')));
        } else {
            wp_send_json_error(array('message' => __('Failed to update settings', 'travel-manager')));
        }
    }

    public function getSettings() {
        $option_key = Arr::get($_REQUEST, 'option_key');
        if (empty($option_key)) {
            wp_send_json_error(array('message' => __('Option Key Is Missing', 'travel-manager')));
        }
        $settingsModel = new Settings();
        $settings = $settingsModel->getSettings($option_key);
        wp_send_json_success(array('settings' => $settings));
    }
}