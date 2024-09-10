<?php
namespace WPTravelManager\Classes\Routes;
use WPTravelManager\Classes\ArrayHelper as Arr;
use WPTravelManager\Classes\View;
use WPTravelManager\Classes\Models\Session;
use WPTravelManager\Classes\Models\Settings;

class ShortcodeRegister {
    
    public function register()
    {
        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_style('travel_manager_public_css', TRM_URL.'assets/css/tm_public.css', [], TRM_VERSION);
            wp_enqueue_script( 'travel_manager_public_js', TRM_URL.'assets/js/tm_public.js',array('jquery'),TRM_VERSION, false );

            wp_localize_script('travel_manager_public_js', 'trm_public', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'tm_public_nonce' => wp_create_nonce('tm_public_nonce'),
                'currency_settings' => (new Settings())->getSettings('trm_currency_settings'),
            ]);
        });

        add_shortcode( 'tm_trip', array( $this, 'singleTripShortCode' ) );
        add_shortcode( 'travel_manager_checkout', array( $this, 'checkoutShortCode' ) );
        add_shortcode( 'travel_manager_cart', array( $this, 'cartShortCode' ) );
        add_shortcode( 'tm_trip_search', array( $this, 'tripSearchShortCode' ) );
    }
    public function singleTripShortCode( $atts )
    {
        $id = Arr::get( $atts, 'id', 0 );
        if( empty( $id ) ){
            return;
        }
        $this->preparedRenderData( $id );
    }

    public function preparedRenderData( $id )
    {
        $post = get_post( $id );
        if( empty( $post ) ){
            return;
        }
        $post_meta = get_post_meta( $id, 'trip_meta', true );
        $post_meta = maybe_unserialize( $post_meta );
    //  dd($post_meta);
        ob_start();
        View::render('Preview/Template1',[
            'id' => $id,
            'title' => $post->post_title,
            'trip' => $post_meta,
        ]);
        echo ob_get_clean();
    }

    public function checkoutShortCode( $atts )
    {
        $booking_id = Arr::get( $_REQUEST, 'booking_id', 0 );
        if( empty( $booking_id ) ){
            return;
        }

        global $wpdb; 
       
        $table_name = $wpdb->prefix.'tm_sessions'; // Adjust table prefix if necessary
        $session_data = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE id = %d", // Use %d for integer placeholders
                $booking_id
            )
        );

            if (empty($session_data)) {
                return '<h3 style="text-align: center">No booking found</h3>';
            }
      
            $session = $session_data[0];
          
            $session_meta = $session->session_meta; // Directly access the session_meta field
            $booking_meta = maybe_unserialize($session_meta);
       
        ob_start();
        View::render('Checkout/CheckoutIndex',[
            'booking_id' => $booking_id,
            'booking' => $booking_meta,
        ]);
        return ob_get_clean();
    }

    public function cartShortCode( $atts )
    {

        $device_id = Arr::get($_COOKIE, 'TRMdeviceId');

        if(!$device_id) {
            return 'There is no cart item';
        }

        $session_data = (new Session())->getSession($device_id);

        foreach ($session_data as $session) {
            $session->session_meta = maybe_unserialize($session->session_meta);
        }
        // dd($session_data);
        ob_start();
        View::render('CartItem/CartIndex',[
            'booking' => $session_data,
        ]);
        return ob_get_clean();
    }

    public function tripSearchShortCode( $atts )
    {
        ob_start();
        View::render('Search/SearchIndex');
        return ob_get_clean();
     
    }
        
}