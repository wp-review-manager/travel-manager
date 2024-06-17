<?php
namespace WPTravelManager\Classes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ajax Handler Class
 * @since 1.0.0
 */
class Activator
{
    public function migrateDatabases($network_wide = false)
    {
        global $wpdb;
        if ($network_wide) {
            // Retrieve all site IDs from this network (WordPress >= 4.6 provides easy to use functions for that).
            if (function_exists('get_sites') && function_exists('get_current_network_id')) {
                $site_ids = get_sites(array('fields' => 'ids', 'network_id' => get_current_network_id()));
            } else {
                $site_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs WHERE site_id = $wpdb->siteid;");
            }
            // Install the plugin for all these sites.
            foreach ($site_ids as $site_id) {
                switch_to_blog($site_id);
                $this->migrate();
                restore_current_blog();
            }
        } else {
            $this->migrate();
        }
    }

    private function migrate()
    {
        /*
        * database creation commented out,
        * If you need any database just active this function bellow
        * and write your own query at createUserFavorite function
        */

        $this->migrateDestinationTable();
        $this->migrateAttributesTable();
        $this->migrateTripCategoriesTable();
        $this->migrateActivityTable();
        $this->migrateDefaultyTable();
        $this->migratePricingCategoriesTable();
    }

    public function migrateDestinationTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'tm_destinations';
        $sql = "CREATE TABLE $table_name (
            id int(10) NOT NULL AUTO_INCREMENT,
            place_name VARCHAR(255) NOT NULL,
            place_slug VARCHAR(255) NOT NULL,
            place_desc LONGTEXT  NULL,
            images LONGTEXT null,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY (id)
            ) $charset_collate;";

        $this->runSQL($sql, $table_name);
    }

    public function migrateAttributesTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'tm_attributes';
        $sql = "CREATE TABLE $table_name (
            id int(10) NOT NULL AUTO_INCREMENT,
            attr_title VARCHAR(255) NOT NULL,
            attr_slug VARCHAR(255) NOT NULL,
            attr_desc LONGTEXT  NULL,
            images LONGTEXT null,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY (id)
            ) $charset_collate;";

        $this->runSQL($sql, $table_name);
    }

    public function migrateTripCategoriesTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'tm_trip_categories';
        $sql = "CREATE TABLE $table_name (
            id int(10) NOT NULL AUTO_INCREMENT,
            trip_category_name VARCHAR(255) NOT NULL,
            trip_category_slug VARCHAR(255) NOT NULL,
            trip_category_desc LONGTEXT  NULL,
            images LONGTEXT null,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY (id)
            ) $charset_collate;";

        $this->runSQL($sql, $table_name);
    }

    public function migrateActivityTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'tm_trip_activity';
        $sql = "CREATE TABLE $table_name (
            id int(10) NOT NULL AUTO_INCREMENT,
            trip_activity_name VARCHAR(255) NOT NULL,
            trip_activity_slug VARCHAR(255) NOT NULL,
            trip_activity_desc LONGTEXT  NULL,
            images LONGTEXT null,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY (id)
            ) $charset_collate;";

        $this->runSQL($sql, $table_name);
    }

    public function migrateDefaultyTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'tm_trip_defaulty';
        $sql = "CREATE TABLE $table_name (
            id int(10) NOT NULL AUTO_INCREMENT,
            trip_defaulty_name VARCHAR(255) NOT NULL,
            trip_defaulty_slug VARCHAR(255) NOT NULL,
            trip_defaulty_desc LONGTEXT  NULL,
            images LONGTEXT null,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY (id)
            ) $charset_collate;";

        $this->runSQL($sql, $table_name);
    }

    public function migratePricingCategoriesTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'tm_pricing_categories';
        $sql = "CREATE TABLE $table_name (
            id int(10) NOT NULL AUTO_INCREMENT,
            pricing_categories_name VARCHAR(255) NOT NULL,
            pricing_categories_slug VARCHAR(255) NOT NULL,
            pricing_categories_desc LONGTEXT  NULL,
            images LONGTEXT null,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY (id)
            ) $charset_collate;";

        $this->runSQL($sql, $table_name);
    }

    private function runSQL($sql, $tableName)
    {
        global $wpdb;
        if ($wpdb->get_var("SHOW TABLES LIKE '$tableName'") != $tableName) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }
}
