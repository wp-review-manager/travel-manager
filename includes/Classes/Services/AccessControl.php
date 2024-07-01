<?php
namespace WPTravelManager\Classes\Services;
use WPTravelManager\Classes\ArrayHelper as Arr;

class AccessControl {
    public static function hasTopLevelMenuPermission()
    {
        $menuPermissions = array(
            'manage_options',
            'tm_full_access'
        );

        $menuPermissions = apply_filters('travel_manager/top_label_menu_permissions', $menuPermissions);
        
        foreach ($menuPermissions as $menuPermission) {
            if (current_user_can($menuPermission)) {
                return true;
            }
        }

        return false;
    }

    public static function giveCustomAccess()
    {
        return [
            'has_access' => 'manage_options',
            'message' => 'You do not have permission to access this page'
        ];
    }
}