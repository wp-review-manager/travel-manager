<?php
namespace WPTravelManager\Classes\Controllers;
use WPTravelManager\Classes\Services\SessionServices;
use WPTravelManager\Classes\Models\Session;
use WPTravelManager\Classes\ArrayHelper as Arr;

class SessionsController {
    public function registerAjaxRoutes()
    {
        tmValidateNonce('tm_admin_nonce');
        $route = sanitize_text_field($_REQUEST['route']);
        $routeMaps = array(
            'create_session' => 'createSession',
            'get_sessions' => 'getSessions',
            'get_session_info' => 'getSessionInfo',
            'delete_session' => 'deleteSession',
        );
        if (isset($routeMaps[$route])) {
            $this->{$routeMaps[$route]}();
            die();
        }
    }

    public function createSession()
    {
        $data = Arr::get($_REQUEST, 'data', []);
        $sanitized_data = (new SessionServices())->sanitize($data);
        $validate_data = (new SessionServices())->validate($sanitized_data);
        $sessionModal = new Session();
        $response = $sessionModal->create($validate_data);

        if($response) {
            wp_send_json_success(array(
                'message' => 'Trip has been saved successfully',
                'data' => array(
                    'redirect_url' => home_url('/travel-manager-checkout/?booking_id=' . $response)
                )
            ));
        } else {
            wp_send_json_error(array(
                'message' => 'Failed to create session',
                'status' => 500
            ));
        }

    }
}