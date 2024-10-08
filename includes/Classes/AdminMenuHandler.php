<?php 
namespace WPTravelManager\Classes;
use WPTravelManager\Classes\Models\Settings;
use WPTravelManager\Classes\Services\GeneralSettings;


class AdminMenuHandler {
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
            // $submenu['travel-manager.php']['customers'] = array(
            //     'Customers',
            //     'manage_options',
            //     'admin.php?page=travel-manager.php#/customers',
            // );
            $submenu['travel-manager.php']['enquiries'] = array(
                'Enquiries',
                'manage_options',
                'admin.php?page=travel-manager.php#/enquiries',
            );
            $submenu['travel-manager.php']['coupons'] = array(
                'Coupons',
                'manage_options',
                'admin.php?page=travel-manager.php#/coupons',
            );
            $submenu['travel-manager.php']['settings'] = array(
                'Settings',
                'manage_options',
                'admin.php?page=travel-manager.php#/settings',
            );
        });
    }

    public function renderAdminPage()
    {
        wp_enqueue_script('TM-script-boot', TRM_URL . 'assets/admin/js/start.js', array('jquery'), TRM_VERSION, false);
        wp_enqueue_style('TM-global-styling', TRM_URL . 'assets/css/element.css', array(), TRM_VERSION);
        wp_enqueue_style('TM-admin-styling', TRM_URL . 'assets/css/element.css', array(), TRM_VERSION);

        if (function_exists('wp_enqueue_editor')) {
            wp_enqueue_editor();
        }
        if (function_exists('wp_enqueue_media')) {
            wp_enqueue_media();
        }

        $TM = apply_filters('TM/admin_app_vars', array(
            //'image_upload_url' => admin_url('admin-ajax.php?action=wpf_global_settings_handler&route=wpf_upload_image'),
            'assets_url' => TRM_URL . 'assets/',
            'ajaxurl' => admin_url('admin-ajax.php'),
            'tm_admin_nonce' => wp_create_nonce('tm_admin_nonce'),
            'tm_public_nonce' => wp_create_nonce('tm_admin_nonce'),
            'server_time' => current_time('timestamp'),
            'payment_routes' => apply_filters('trm/payment_methods_routes', []),
            'currencies' => GeneralSettings::getCurrencies(),
            'currency_settings' => (new Settings())->getSettings('trm_currency_settings'),
        ));

        wp_localize_script('TM-script-boot', 'wpTravelManager', $TM);
    // <router-link class="tm-menu-item" to="/">
    //     Dashboard
    // </router-link> 
    // <router-link class="tm-menu-item" to="/bookings" >
    //     Bookings
    // </router-link>
    // <router-link class="tm-menu-item" to="/customers" >
    //     Customers
    // </router-link>
    // <router-link class="tm-menu-item" to="/enquiries" >
    // Enquiries
    // </router-link>

        echo '<div class="TM-admin-page" id="TM_app">
            <div class="tm-main-menu">
                <div class="tm-menu-logo">
                    <h3>TRAVEL Manager</h3>
                </div>
                <div class="menu-item">
                    <router-link class="tm-menu-item" to="/trips" >
                        Trips
                    </router-link>
                     <router-link class="tm-menu-item" to="/bookings" >
                        Booking
                    </router-link>
                    <router-link class="tm-menu-item" to="/settings" >
                        Settings
                    </router-link>
                </div>
            </div>
            <hr/>
            <router-view></router-view>
        </div>';
    }
}