<?php
namespace WPTravelManager\Classes\Routes;
use WPTravelManager\Classes\ArrayHelper as Arr;
use WPTravelManager\Classes\View;

class ShortcodeRegister {
    
    public function register()
    {

        add_shortcode( 'tm_trip', array( $this, 'singleTripShortCode' ) );
        add_shortcode( 'travel_manager_checkout', array( $this, 'checkoutShortCode' ) );
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
        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_style('travel_manager_public_css', TRM_URL.'assets/css/tm_public.css', [], TRM_VERSION);
            wp_enqueue_script( 'travel_manager_public_js', TRM_URL.'assets/js/tm_public.js',array('jquery'),TRM_VERSION, false );

            wp_localize_script('travel_manager_public_js', 'tm_public', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'tm_public_nonce' => wp_create_nonce('tm_public_nonce'),
            ]);
        });

        $booking_id = Arr::get( $_REQUEST, 'booking_id', 0 );
        ob_start();
        View::render('Checkout/CheckoutIndex',[
            'booking_id' => $booking_id,
        ]);
        return ob_get_clean();
    }
        
}