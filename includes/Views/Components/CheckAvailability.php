<?php
namespace WPTravelManager\Views\Components;

class CheckAvailability
{
    public static function RenderCheckAvailability()
    {
        ob_start();
        ?>
           <div id="tm_modal" class="tm_modal">
                <div class="tm_modal-content">
                    <div class="tm_trip_process_tabs">
                        <div class="tm_tab_container">
                            <ul class="tm_availability_tab_menu">
                                <li class="tab active" data-tab="check_availability">Check Availability</li>
                                <li class="tab" data-tab="package_type">Package Type</li>
                            </ul>

                            <div class="tm_tab_content tm_check_availability_content active" id="check_availability">
                                <div class="tm_section">
                                    Availability
                                </div>
                            </div>

                            <div class="tm_tab_content tm_package_type_content" id="package_type">
                                <div class="tm_section">
                                    Package Type
                                </div>
                            </div>

                        </div>
                    </div>
                    
                    <div class="tm_trip_process_sidebar">
                        <span id="tm_close" class="tm_close">&times;</span>
                        <div class="tm_trip_booking_summary">
                            <h3 class="tm_trip_booking_summary_title">Booking Summary</h3>
                        </div>
                    </div>
                    
                </div>
            </div>
        <?php
        return ob_get_clean();
    }
}