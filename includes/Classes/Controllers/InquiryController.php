<?php
namespace WPTravelManager\Classes\Controllers;
use WPTravelManager\Classes\Services\InquiryServices;
use WPTravelManager\Classes\Models\Inquiry;
use WPTravelManager\Classes\ArrayHelper as Arr;

class InquiryController {
    public function registerAjaxRoutes()
    {
        // tmValidateNonce('tm_admin_nonce');
        $route = sanitize_text_field($_REQUEST['route']);
        $routeMaps = array(
            'submission_inquiry' => 'submissionInquiry',
            'get_enquiries' => 'getEnquiries',
            'delete_enquiries' => 'deleteEnquiries'
        );
        if (isset($routeMaps[$route])) {
            $this->{$routeMaps[$route]}();
            die();
        }
    }

    public function submissionInquiry() {
        
        $form_data = Arr::get($_REQUEST, 'data');
        $sanitize_data = InquiryServices::sanitize($form_data);
        $validation = InquiryServices::validate($sanitize_data);
      
        if (!empty($validation)) {
            wp_send_json_error($validation);
        }
        
        $response = (new Inquiry())->saveInquiry($sanitize_data);

        if ($response) {
            wp_send_json_success('Inquiry updated successfully');
        } else {
            wp_send_json_error('Failed to updated Inquiry');
        }
    }

    public function getEnquiries() {
        $response = (new Inquiry())->getEnquiries();

        wp_send_json_success(
            array(
                'data' => $response,
                'message' => 'Enquiries fetched successfully'
            )
        );
    }

    public function deleteEnquiries() {
        $enquiries_id = Arr::get($_REQUEST, 'id');
        if (!$enquiries_id) {
            wp_send_json_error('Enquiries ID is required');
        }

        $response = Inquiry::deleteEnquiries($enquiries_id);

        if ($response) {
            wp_send_json_success('Enquiries deleted successfully');
        } else {
            wp_send_json_error('Failed to delete Enquiries');
        }
    }

}