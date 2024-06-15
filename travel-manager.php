<?php

/**
 * Plugin Name: Travel Manager
 * Plugin URI: http://wpulse.com/
 * Description: A sample Wordpress plugin to implement Vue with tailwind.
 * Author: WPulse
 * Author URI: http://wpulse.com/
 * Version: 1.0.5
 */
define('TM_URL', plugin_dir_url(__FILE__));
define('TM_DIR', plugin_dir_path(__FILE__));

define('TM_VERSION', '1.0.5');

add_action('plugins_loaded', function () {
    include TM_DIR . 'includes/Classes/Bootstrap.php';
    $bootstrap = new \WPTravelManager\Classes\Bootstrap();
    $bootstrap->boot();
});



