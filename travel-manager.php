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

class WPPluginWithVueTailwind {
    public function boot()
    {
        $this->loadClasses();
        $this->registerShortCodes();
        $this->ActivatePlugin();
        $this->renderMenu();
    }

    public function loadClasses()
    {
        require TM_DIR . 'includes/autoload.php';
    }

    public function renderMenu()
    {
        add_action('admin_menu', function () {
            if (!current_user_can('manage_options')) {
                return;
            }
            global $submenu;
            add_menu_page(
                'TravelManager',
                'Travel Manager',
                'manage_options',
                'travel-manager.php',
                array($this, 'renderAdminPage'),
                'dashicons-editor-code',
                25
            );
            $submenu['wpp-plugin-with-vue-tailwind.php']['dashboard'] = array(
                'Dashboard',
                'manage_options',
                'admin.php?page=wpp-plugin-with-vue-tailwind.php#/',
            );
            $submenu['wpp-plugin-with-vue-tailwind.php']['contact'] = array(
                'Contact',
                'manage_options',
                'admin.php?page=wpp-plugin-with-vue-tailwind.php#/contact',
            );
        });
    }

    public function renderAdminPage()
    {
        wp_enqueue_script('WPWVT-script-boot', TM_URL . 'assets/admin/js/start.js', array('jquery'), TM_VERSION, false);
        wp_enqueue_style('WPWVT-global-styling', TM_URL . 'assets/css/element.css', array(), TM_VERSION);

        $WPWVT = apply_filters('WPWVT/admin_app_vars', array(
            //'image_upload_url' => admin_url('admin-ajax.php?action=wpf_global_settings_handler&route=wpf_upload_image'),
            'assets_url' => TM_URL . 'assets/',
            'ajaxurl' => admin_url('admin-ajax.php')
        ));

        wp_localize_script('WPWVT-script-boot', 'WPWVTAdmin', $WPWVT);

        echo '<div class="WPWVT-admin-page" id="WPWVT_app">
            <div class="main-menu text-white-200 bg-wheat-600 p-4">
                <router-link to="/">
                    Dashboard
                </router-link> |
                <router-link to="/trips" >
                    Trips
                </router-link>
                <router-link to="/bookings" >
                    Bookings
                </router-link>
                <router-link to="/customers" >
                    Bookings
                </router-link>
            </div>
            <hr/>
            <router-view></router-view>
        </div>';
    }

    public function registerShortCodes()
    {
        // your shortcode here
    }

    public function ActivatePlugin()
    {
        //activation deactivation hook
        register_activation_hook(__FILE__, function ($newWorkWide) {
            require_once(TM_DIR . 'includes/Classes/Activator.php');
            $activator = new \WPPluginWithVueTailwind\Classes\Activator();
            $activator->migrateDatabases($newWorkWide);
        });
    }
}

(new WPPluginWithVueTailwind())->boot();



