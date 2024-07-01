<?php
namespace WPTravelManager\Classes\Modules;
use WPTravelManager\Classes\ArrayHelper as Arr;
use WPTravelManager\Classes\Services\AccessControl;
use WPTravelManager\Classes\View;

class ProcessPreviewPage {
    public function handleExteriorPages()
    {
    
        if (isset($_GET['wp_tm_trip_preview']) && $_GET['wp_tm_trip_preview']) {
            $hasDemoAccess = AccessControl::hasTopLevelMenuPermission();
            $hasDemoAccess = apply_filters('travel_manager/can_see_demo_trip_preview', $hasDemoAccess);

            if ($hasDemoAccess) {
                $template_id = intval($_GET['wp_tm_trip_preview']);
                wp_enqueue_style('dashicons');
                $this->renderPreview($template_id);
            }
        }
    }

    public function renderPreview($template_id)
    {
        $template = get_post($template_id);
      
        if (!$template || $template->post_type !== 'tm_trip') {
            return;
        }

        $template_meta = get_post_meta($template_id, 'trip_meta', true);
        
        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_style('travel_manager_public_css', TM_URL.'assets/css/tm_public.css', [], TM_VERSION);
            wp_enqueue_script( 'travel_manager_public_js', TM_URL.'assets/js/tm_public.js',array('jquery'),TM_VERSION, false );
        });

        wp_head();
        View::render('Preview.TripPreview', [
            'shortcode' => '[tm_trip id="' . $template_id . '"]',
            'edit_url' => admin_url('admin.php?page=travel-manager.php#/trip/'.$template_id . '/edit'),
            'title' => mb_strlen($template->post_title) > 30 ? mb_substr($template->post_title, 0, 30) . '...' : $template->post_title,
            'trip' => $template_meta,
        ]);
        wp_footer();
        exit();
    }
}