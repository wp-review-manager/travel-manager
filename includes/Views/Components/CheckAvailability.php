<?php
namespace WPTravelManager\Views\Components;
use WPTravelManager\Classes\ArrayHelper as Arr;

class CheckAvailability
{
    public static function RenderCheckAvailability($packages, $title = "")
    {
        ob_start();
        ?>
           <div id="tm_modal" class="tm_modal">
                <div class="tm_modal-content">
                    <div class="tm_trip_process_tabs">
                        <div class="tm_tab_container">
                            <ul class="tm_availability_tab_menu">
                                <li class="tab_item active" data-tab="check_availability">Check Availability</li>
                                <li class="tab_item disabled" data-tab="package_type">Package Type</li>
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
                                    <div class="tm_footer_section">
                                        <button data-tab="package_type" class="tm_check_availability_continue_btn disabled">Continue</button>
                                    </div>
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
                                        $enable_pack = Arr::get($package, 'enable');
                                    ?>
                                    <div id="<?php echo 'tm_package_' . $key ?>" class="tm_package_details <?php echo $key == 0 ? 'active' : '' ?>">
                                        <?php foreach($pricing as $pricing_key=>$pricing_data) :
                                            $pricing_id = $package['title'] . '_' . $pricing_key;
                                            $max_pax = $pricing_data['max_pax'] < 1 ? 999999 : $pricing_data['max_pax'];
                                        ?>
                                        <div class="tm_package_pricing">
                                            <div style="display: flex; align-items: center; gap: 20px">
                                                <input
                                                    data-tm_package_price=" <?php echo $pricing_data['selling_price'] ?>"
                                                    data-tm_pricing_id="<?php echo $pricing_id ?>"
                                                    data-tm_package_type="<?php echo $pricing_data['pricing_type'] ?>"
                                                    data-tm_package_name="<?php echo $package['title'] ?>"
                                                    data-tm_pricing_label="<?php echo $pricing_data['label'] ?>"
                                                    type="checkbox"
                                                    class="tm_pricing_checkbox"
                                                />
                                                <p class="travelers"><?php echo $pricing_data['label'] ?></p>
                                            </div>
                                            <div style="display: flex; align-items: center; gap: 8px">
                                                <span class="tm_old_price">
                                                    <del><?php echo tmFormatPrice($pricing_data['price']) ?></del>
                                                </span>
                                                <span class="tm_price"><?php echo tmFormatPrice($pricing_data['selling_price']) ?></span>
                                                <span class="tm_per_person">/</span>
                                                <span class="tm_per_person">per <?php echo $pricing_data['pricing_type'] ?></span>
                                                <span data-tm_min="<?php echo $pricing_data['min_pax'] ?>" class="tm_dec_btn"> - </span>
                                                <input
                                                    data-tm_travelers_number="<?php echo $pricing_id ?>"
                                                    readonly
                                                    class="tm_quantity" value="<?php echo $pricing_data['min_pax'] ?>"
                                                    data-tm_package_price=" <?php echo $pricing_data['selling_price'] ?>"
                                                    data-tm_package_type="<?php echo $pricing_data['pricing_type'] ?>"
                                                    data-tm_package_name="<?php echo $package['title'] ?>"
                                                    data-tm_pricing_label="<?php echo $pricing_data['label'] ?>"
                                                    data-tm_trip_title="<?php echo $title ?>"
                                                >
                                                <span data-tm_max="<?php echo $max_pax ?>" class="tm_inc_btn"> + </span>
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
                            <div class="tm_trip_booking_summary_body">
                                
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        <?php
        return ob_get_clean();
    }
}