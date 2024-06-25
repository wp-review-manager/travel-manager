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
}