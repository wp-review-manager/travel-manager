<?php

namespace WPTravelManager\Classes\Modules;

class CustomPageRegister
{
    public function registerPage()
    {
        $this->registerCustomCartPage();
        $this->registerTripSearchPage();
        $this->registerTripDetailsPage();
        $this->create_travel_manager_checkout_page();
    }

    public function registerCustomCartPage()
    {
        $page_title = 'Travel Manager Cart';
        $page_slug = 'travel-manager-cart';

        // Check if a page with the slug exists
        $existing_page = get_page_by_path($page_slug);

        if (!$existing_page) {
            // Create the page since it doesn't exist
            $page = array(
                'post_title'    => $page_title,
                'post_content'  => '[travel_manager_cart]',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'     => 'page',
                'post_name'     => $page_slug, // Ensure the slug is set
            );
            $new_page_id = wp_insert_post($page);
        }
    }

    public function registerTripSearchPage()
    {
        $page_title = 'Travel Manager Trips';
        $page_slug = 'travel-manager-trip-search';
        $page_settings = get_option('wp_travel_manager_trip_search_page', $page_slug);
        if($page_settings != $page_slug){
            return;
        }
        // Check if a page with the slug exists
        $existing_page = get_page_by_path($page_slug);
        
        if (!$existing_page) {
            // Create the page since it doesn't exist
            $page = array(
                'post_title'    => $page_title,
                'post_content'  => '[tm_trip_search]',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'     => 'page',
                'post_name'     => $page_slug, // Ensure the slug is set
            );
            $new_page_id = wp_insert_post($page);
            update_option( 'wp_travel_manager_trip_search_page', $page_slug );
        }
    }

    public function registerTripDetailsPage()
    {
        $page_title = 'Travel Manager Trip Details';
        $page_slug = 'travel-manager-trip-details';
        // Check if a page with the slug exists
        $page_settings = get_option('wp_travel_manager_trip_details_page', $page_slug);
        if($page_settings != $page_slug){
            return;
        }
        $existing_page = get_page_by_path($page_slug);
        if (!$existing_page) {
            // Create the page since it doesn't exist
            $page = array(
                'post_title'    => $page_title,
                'post_content'  => '[tm_trip]',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'     => 'page',
                'post_name'     => $page_slug, // Ensure the slug is set
            );
            $new_page_id = wp_insert_post($page);
            update_option( 'wp_travel_manager_trip_details_page', $page_slug );
        }
    }

    function create_travel_manager_checkout_page() {
        $page_title = 'Travel Manager Checkout';
        $page_slug = 'travel-manager-checkout'; // Using a unique slug
    
        // Check if a page with the slug exists
        $existing_page = get_page_by_path($page_slug);
        
        if (!$existing_page) {
            // Create the page since it doesn't exist
            $page = array(
                'post_title'    => $page_title,
                'post_content'  => '[travel_manager_checkout]',
                'post_status'   => 'publish',
                'post_author'   => 1, // Typically, the ID of the admin user
                'post_type'     => 'page',
                'post_name'     => $page_slug, // Ensure the slug is set
                'comment_status'=> 'closed', // Optional: close comments
            );
    
            // Insert the page into the database
            $new_page_id = wp_insert_post($page);
    
            if (is_wp_error($new_page_id)) {
                error_log('Failed to create the Travel Manager Checkout page: ' . $new_page_id->get_error_message());
            }
        }
    }    
}
