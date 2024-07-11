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
                                    <div class="tm_calendar">
                                        <div class="tm_calendar-header">
                                            <button id="prev">Prev</button>
                                            <h2 id="month-year"></h2>
                                            <button id="next">Next</button>
                                        </div>
                                        <div class="tm_calendar-body">
                                            <div class="tm_calendar_days-names">
                                                <div>Sun</div>
                                                <div>Mon</div>
                                                <div>Tue</div>
                                                <div>Wed</div>
                                                <div>Thu</div>
                                                <div>Fri</div>
                                                <div>Sat</div>
                                            </div>
                                            <div class="tm_calendar_days"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tm_tab_content tm_choose_package tm_package_type_content" id="package_type">
                                <div class="tm_section">
                                    <div class="tm_packages">
                                        <div class="tm_package_button active">Package 1</div>
                                        <div class="tm_package_button">Package 2</div>
                                    </div>
                                    <div class="tm_package_details">
                                        <?php for ($i = 0; $i < 4; $i++) : ?>
                                        <div class="tm_package_pricing">
                                            <p class="travelers">Adult</p>
                                            <div>
                                                <span class="tm_old_price">
                                                    <del>$150</del>
                                                </span>
                                                <span class="tm_price">$100</span>
                                                <span class="tm_per_person">/</span>
                                                <span class="tm_per_person">per person</span>
                                                <input type="number" class="tm_quantity" value="1">
                                            </div>
                                        </div>
                                        <?php endfor; ?>
                                    </div>
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