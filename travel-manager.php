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

class WPTravelManager {
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
            $submenu['travel-manager.php']['dashboard'] = array(
                'Dashboard',
                'manage_options',
                'admin.php?page=travel-manager.php#/',
            );
            $submenu['travel-manager.php']['trips'] = array(
                'Trips',
                'manage_options',
                'admin.php?page=travel-manager.php#/trips',
            );
            $submenu['travel-manager.php']['bookings'] = array(
                'Bookings',
                'manage_options',
                'admin.php?page=travel-manager.php#/bookings',
            );
            $submenu['travel-manager.php']['customers'] = array(
                'Customers',
                'manage_options',
                'admin.php?page=travel-manager.php#/customers',
            );
        });
    }

    public function renderAdminPage()
    {
        wp_enqueue_script('TM-script-boot', TM_URL . 'assets/admin/js/start.js', array('jquery'), TM_VERSION, false);
        wp_enqueue_style('TM-global-styling', TM_URL . 'assets/css/element.css', array(), TM_VERSION);
        wp_enqueue_style('TM-admin-styling', TM_URL . 'assets/css/element.css', array(), TM_VERSION);

        $TM = apply_filters('TM/admin_app_vars', array(
            //'image_upload_url' => admin_url('admin-ajax.php?action=wpf_global_settings_handler&route=wpf_upload_image'),
            'assets_url' => TM_URL . 'assets/',
            'ajaxurl' => admin_url('admin-ajax.php')
        ));

        wp_localize_script('TM-script-boot', 'TMAdmin', $TM);

        echo '<div class="TM-admin-page" id="TM_app">
            <div class="tm-main-menu">
                <div class="tm-menu-logo">
                    <h3>TRAVEL Manager</h3>
                </div>
                <div class="menu-item">
                    <router-link class="tm-menu-item" to="/">
                        Dashboard
                    </router-link> 
                    <router-link class="tm-menu-item" to="/trips" >
                        Trips
                    </router-link>
                    <router-link class="tm-menu-item" to="/bookings" >
                        Bookings
                    </router-link>
                    <router-link class="tm-menu-item" to="/customers" >
                        Customers
                    </router-link>
                </div>
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
            $activator = new \WPTravelManager\Classes\Activator();
            $activator->migrateDatabases($newWorkWide);
        });
    }
}

(new WPTravelManager())->boot();



