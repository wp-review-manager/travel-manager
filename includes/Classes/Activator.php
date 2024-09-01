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
        $this->migrateOrderItemsTable();
        $this->migrateTransactionsTable();
        $this->migrateBookingsTable();
        $this->migrateBookingMeta();
        $this->migrateInquiryTable();
        $this->migrateSessionsTable();
        $this->migrateCouponTable();
    }

    public function migrateSessionsTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'tm_sessions';
        $sql = "CREATE TABLE $table_name (
            id int(10) NOT NULL AUTO_INCREMENT,
            session_hash VARCHAR(255) NULL,
            device_id VARCHAR(255) NOT NULL,
            user_id VARCHAR(255) NULL,
            trip_id VARCHAR(255) NULL,
            session_meta LONGTEXT  NULL,
            currency VARCHAR(22) DEFAULT 'USD',
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY (id)
            ) $charset_collate;";

        $this->runSQL($sql, $table_name);
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

    public function migrateOrderItemsTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'tm_order_items';
        $sql = "CREATE TABLE $table_name (
            id int(10) NOT NULL AUTO_INCREMENT,
            trip_id int(10) NOT NULL,
            booking_id int(10) NOT NULL,
            package_type VARCHAR(255) NOT NULL,
            item_name VARCHAR(255) NOT NULL,
            item_qty int(11),
            item_price int(11),
            line_total int(11),
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY (id)
           ) $charset_collate;";

        $this->runSQL($sql, $table_name);
    }

    public function migrateInquiryTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'tm_inquiry';
        $sql = "CREATE TABLE $table_name (
            id int(10) NOT NULL AUTO_INCREMENT,
            trip_id INTEGER ,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            country VARCHAR(255) NOT NULL,
            phone INTEGER NOT NULL,
            subject VARCHAR(255) NOT NULL,
            number_of_adults VARCHAR(255) NOT NULL,
            number_of_children VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY (id)
            ) $charset_collate;";

        $this->runSQL($sql, $table_name);
    }

    public function migrateTransactionsTable($force = false)
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'tm_transactions';
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            transaction_hash varchar(255) NULL,
            payer_name varchar(255) NULL,
            payer_email varchar(255) NULL,
            billing_address varchar(255) NULL,
            shipping_address varchar(255) NULL,
            trip_id int(11) NOT NULL,
            booking_id int(11) NULL,
            user_id int(11) DEFAULT NULL,
            transaction_type varchar(255) DEFAULT 'onetime',
            payment_method varchar(255),
            card_last_4 int(4),
            card_brand varchar(255),
            charge_id varchar(255),
            payment_total int(11) DEFAULT 1,
            payment_status varchar(255),
            currency varchar(255),
            payment_mode varchar(255),
            payment_note longtext,
            created_at timestamp NULL,
            updated_at timestamp NULL,
            PRIMARY  KEY  (id)
        ) $charset_collate;";

        if ($force) {
            $this->runForceSQL($sql, $table_name);
        } else {
            $this->runSQL($sql, $table_name);
        }
    }

    public function migrateBookingsTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'tm_bookings';
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            booking_hash varchar(255) NULL,
            trip_id int(11) NOT NULL,
            user_id int(11) DEFAULT NULL,
            booking_total int(11) DEFAULT 0,
            payment_method varchar(255) NULL,
            payment_status varchar(255) DEFAULT 'pending',
            payment_mode varchar(255) NULL,
            currency varchar(22) DEFAULT 'USD',
            traveler_name varchar(255) NULL,
            traveler_email varchar(255) NULL,
            traveler_phone varchar(255) NULL,
            traveler_country varchar(255) NULL,
            traveler_address varchar(255) NULL,
            booking_date date DEFAULT NULL,
            booking_status varchar(255) DEFAULT 'pending',
            booking_note longtext,
            created_at timestamp NULL,
            updated_at timestamp NULL,
            PRIMARY  KEY  (id)
        ) $charset_collate;";
        $this->runSQL($sql, $table_name);
    }

    public function migrateBookingMeta()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'tm_booking_meta';
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            booking_id int(11) NOT NULL,
            meta_key varchar(255) NULL,
            meta_value longtext,
            PRIMARY  KEY  (id)
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

    protected function runForceSQL($sql, $tableName)
    {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    protected function migrateCouponTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'tm_coupon';
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            amount int(11) NOT NULL,
            coupon_type varchar(255) NOT NULL,
            coupon_code varchar(255) NOT NULL,  /* Fixed the syntax error here */
            created_by varchar(255) NULL,
            max_use int(11) NULL,
            min_amount int(11)  NULL,
            settings varchar(255) NULL,
            allowed_trip_ids text  NULL,
            user_ids int(11) NOT NULL,
            stackable varchar(255) NULL,
            start_date varchar(255) NULL,
            end_date varchar(255) NULL,
            coupon_status varchar(255) NULL,
            title varchar(255) NOT NULL,
            meta_value longtext,
            created_at timestamp NULL,
            updated_at timestamp NULL,
            PRIMARY  KEY  (id)
        ) $charset_collate;";
        $this->runSQL($sql, $table_name);
    }
    
}
