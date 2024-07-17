<?php
namespace WPTravelManager\Views\Components;
use WPTravelManager\Classes\ArrayHelper as Arr;

class CheckAvailability
{
    public static function RenderCheckAvailability($packages)
    {
        ob_start();
        ?>
           <div id="tm_modal" class="tm_modal">
                <div class="tm_modal-content">
                    <div class="tm_trip_process_tabs">
                        <div class="tm_tab_container">
                            <ul class="tm_availability_tab_menu">
                                <li class="tab active" data-tab="check_availability">Check Availability</li>
                                <li class="tab disabled" data-tab="package_type">Package Type</li>
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
                                    <!-- <div class="tm_footer_section">
                                        <button data-tab="package_type" class="tm_check_availability_continue_btn disabled">Continue</button>
                                    </div> -->
                                </div>
                            </div>

                            <div class="tm_tab_content tm_choose_package tm_package_type_content" id="package_type">
                                <div class="tm_section">
                                    <div class="tm_packages">
                                        <?php foreach ($packages as $key=>$package) :
                                            $enable_pack = Arr::get($package, 'enable');
                                            if($enable_pack == "yes"):
                                        ?>
                                            <div id="<?php echo 'tm_package_' . $key ?>" class="tm_package_button <?php echo $key == 0 ? 'active' : '' ?>"><?php echo Arr::get($package, 'title') ?></div>
                                        <?php endif; endforeach; ?>
                                    </div>
                                    <?php foreach ($packages as $key=>$package) :
                                        $pricing = Arr::get($package, 'pricing', []);
                                        $enable_available_booking_date = Arr::get($package, 'available_booking_date.enable', false);
                                        $start_booking_date = Arr::get($package, 'available_booking_date.start_date', '');
                                        $end_booking_date = Arr::get($package, 'available_booking_date.end_date', '');
                                        $enable_pack = Arr::get($package, 'enable');
                                    ?>
                                    <div id="<?php echo 'tm_package_' . $key ?>" class="tm_package_details <?php echo $key == 0 ? 'active' : '' ?>">
                                        <?php foreach($pricing as $pricing_data) :
                                            $max_pax = $pricing_data['max_pax'] < 1 ? 999999 : $pricing_data['max_pax'];
                                        ?>
                                        <div class="tm_package_pricing">
                                            <p class="travelers"><?php echo $pricing_data['label'] ?></p>
                                            <div style="display: flex; align-items: center; gap: 8px">
                                                <span class="tm_old_price">
                                                    <del><?php echo tmFormatPrice($pricing_data['price']) ?></del>
                                                </span>
                                                <span class="tm_price"><?php echo tmFormatPrice($pricing_data['selling_price']) ?></span>
                                                <span class="tm_per_person">/</span>
                                                <span class="tm_per_person">per <?php echo $pricing_data['pricing_type'] ?></span>
                                                <input type="number" max="<?php echo $max_pax ?>" min="<?php echo $pricing_data['min_pax'] ?>" class="tm_quantity" value="<?php echo $pricing_data['min_pax'] ?>">
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php endforeach; ?>
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