<?php
namespace WPTravelManager\Classes;

class Helper {
    public static function allowHtmlTagForIframe() {
       return array(
            'a' => array(
                'href' => [],
                'title' => array()
            ),
            'iframe' => array(
                'src' => array(),
                'width' => array(),
                'height' => array(),
                'frameborder' => array(),
                'scrolling' => array(),
                'marginheight' => array(),
                'marginwidth' => array()
            ),
            'div' => array(
                'style' => array()
            ),
            // Add more tags and attributes as needed
        );
    }

    public static function allowHtmlTag() {
        return array(
            '*' => array(
                '*' => []
            )
        );
    }

    public static function getUserLoginInfo() {
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            return $current_user;

        } else {
            return false;
        }
    }
}