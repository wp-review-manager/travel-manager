<?php
namespace WPTravelManager\Classes\Routes;
use WPTravelManager\Classes\ArrayHelper as Arr;
use WPTravelManager\Classes\View;
use WPTravelManager\Classes\Models\Session;

class ShortcodeRegister {
    
    public function register()
    {
        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_style('travel_manager_public_css', TRM_URL.'assets/css/tm_public.css', [], TRM_VERSION);
            wp_enqueue_script( 'travel_manager_public_js', TRM_URL.'assets/js/tm_public.js',array('jquery'),TRM_VERSION, false );

            wp_localize_script('travel_manager_public_js', 'trm_public', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'tm_public_nonce' => wp_create_nonce('tm_public_nonce'),
            ]);
        });

        add_shortcode( 'tm_trip', array( $this, 'singleTripShortCode' ) );
        add_shortcode( 'travel_manager_checkout', array( $this, 'checkoutShortCode' ) );
        add_shortcode( 'travel_manager_cart', array( $this, 'cartShortCode' ) );
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
     
        View::render('Preview/Template1',[
            'id' => $id,
            'title' => $post->post_title,
            'trip' => $post_meta,
        ]);
    }

    public function checkoutShortCode( $atts )
    {

        global $wpdb; 

        $booking_id = Arr::get( $_REQUEST, 'booking_id', 0 );
       
        $table_name = $wpdb->prefix.'tm_sessions'; // Adjust table prefix if necessary
        $session_data = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE id = %d", // Use %d for integer placeholders
                $booking_id
            )
        );
      
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
        
}