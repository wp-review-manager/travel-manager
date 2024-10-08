<?php

namespace WPTravelManager\Views\Preview;

use WPTravelManager\Views\Components\Slider;
use WPTravelManager\Views\Components\Tab;
use WPTravelManager\Views\Components\CheckAvailability;
use WPTravelManager\Classes\ArrayHelper as Arr;
$durationType = Arr::get($trip, 'general.duration.type', 0);
$duration = Arr::get($trip, 'general.duration.duration', 0);
$transportation = Arr::get($trip, 'general.transportation', null);

$max_traveler = Arr::get($trip, 'general.max_traveler', null);
$trip_type = Arr::get($trip, 'general.trip_type', null);
$trip_status = Arr::get($trip, 'general.trip_status', null);
$trip_destination = Arr::get($trip, 'general.trip_destination', null);
$accommodation = Arr::get($trip, 'general.accommodation', null);
$departure_location = Arr::get($trip, 'general.departure_location', null);
$max_age = Arr::get($trip, 'general.min_max_age.max_age', null);
$min_age = Arr::get($trip, 'general.min_max_age.min_age', null);

$trip_gallery = Arr::get($trip, 'trip_gallery', null);

$packages = Arr::get($trip, 'packages', []);

?>

<div class="tm_trip_one_shortcode_preview_wrapper">
    <main class="tm_trip_main">
        <article>
            <div class="entry-header">
                <h1 class="tm_trip_title"><?php echo esc_html($title); ?></h1>
                <span class="tm-title-duration">
                    <p class="duration"> <?php echo esc_html( $duration ) ?> </p>
                    <p class="days"> <?php echo esc_html( $durationType ) ?> </p>
                </span>
            </div>

            <div class="tm_gallery_wrapper">
                <?php echo Slider::RenderSlider($trip_gallery); ?>
            </div>

            <div class="tm_trip_entry_content">
                <div class="tm_secondary_trip_info">
                        <?php if ($transportation) : ?>
                        <div class="tm_trip_price">
                            <div class="tm_info_label"> 
                                <span class="dashicons dashicons-airplane"></span>
                                <span class="tm_label">Transportation</span>
                            </div>
                            <span class="tm_price_value"><?php echo $transportation ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if ($max_traveler) : ?>
                        <div class="tm_trip_price">
                            <div class="tm_info_label"> 
                            <span class="dashicons dashicons-groups"></span>
                                <span class="tm_label">Maximum Traveler</span>
                            </div>
                            <span class="tm_price_value"><?php echo $max_traveler ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if ($trip_type) : ?>
                        <div class="tm_trip_price">
                            <div class="tm_info_label"> 
                            <span class="dashicons dashicons-welcome-write-blog"></span>
                                <span class="tm_label">Tour Type</span>
                            </div>
                            <span class="tm_price_value"><?php echo $trip_type ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if ($trip_status) : ?>
                        <div class="tm_trip_price">
                            <div class="tm_info_label"> 
                            <span class="dashicons dashicons-admin-page"></span>
                                <span class="tm_label">Tour Status</span>
                            </div>
                            <span class="tm_price_value"><?php echo $trip_status ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if ($trip_destination) : ?>
                        <div class="tm_trip_price">
                            <div class="tm_info_label"> 
                            <span class="dashicons dashicons-location"></span>
                                <span class="tm_label">Destination</span>
                            </div>
                            <span class="tm_price_value"><?php echo $trip_destination ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if ($accommodation) : ?>
                        <div class="tm_trip_price">
                            <div class="tm_info_label"> 
                            <span class="dashicons dashicons-editor-insertmore"></span>
                                <span class="tm_label">Accommodation</span>
                            </div>
                            <span class="tm_price_value"><?php echo $accommodation ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if ($departure_location) : ?>
                        <div class="tm_trip_price">
                            <div class="tm_info_label"> 
                            <span class="dashicons dashicons-car"></span>
                                <span class="tm_label">Departure from</span>
                            </div>
                            <span class="tm_price_value"><?php echo $departure_location ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if ($max_age) : ?>
                        <div class="tm_trip_price">
                            <div class="tm_info_label"> 
                            <span class="dashicons dashicons-universal-access"></span>
                                <span class="tm_label">Maximum Age</span>
                            </div>
                            <span class="tm_price_value"><?php echo $max_age ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if ($min_age) : ?>
                        <div class="tm_trip_price">
                            <div class="tm_info_label"> 
                            <span class="dashicons dashicons-universal-access"></span>
                                <span class="tm_label">Minimum Age</span>
                            </div>
                            <span class="tm_price_value"><?php echo $min_age ?></span>
                        </div>
                        <?php endif; ?>

                </div>
            </div>

            <?php echo Tab::RenderTab($trip); ?>

        </article>
    </main>
    <div class="tm_trip_sidebar">
        <div class="tm_availability_wrapper">
            <div class="tm_trip_starting_price_list">
            <?php
            if (count($packages) > 0) :
                $packages_pricing= Arr::get($packages, '0.pricing', null);
              
                foreach ($packages_pricing as $pricing) :
                    $pricing_label= Arr::get($pricing, 'label', null);
                    $pricing_price= Arr::get($pricing, 'price', null);
                    $selling_price= Arr::get($pricing, 'selling_price', null);
                    
            ?>
                    <div class="tm_trip_starting_price">
                        <div class="tm_regular_price">
                            <span class="tm_label">From</span>
                            <span class="tm_price"><?php echo  esc_html($pricing_price) ;  ?></span>
                        </div>
                        <div class="tm_selling_price">
                            <span class="tm_price"><?php echo  esc_html($selling_price) ;  ?></span>
                            <span class="tm_label"><?php echo  esc_html($pricing_label) ;  ?></span>
                        </div>
                    </div>
                    
                <?php endforeach; ?>
            <?php endif; ?>
            </div>

            <div class="tm_check_availability">
                <?php
                    $cut_time = Arr::get($trip, 'general.cut_time');
                    $enable_cut_off = Arr::get($cut_time, 'enable');
                    $cut_of_start_date = Arr::get($cut_time, 'start_of_date');
                    $cut_of_end_date = Arr::get($cut_time, 'end_of_date');
                ?>
                <button style="cursor: pointer;"
                    id="tm_openModal"
                    class="tm_button tm_openModal"
                    data-enable_cut_off="<?php echo $enable_cut_off; ?>"
                    data-cut_of_start_date="<?php echo $cut_of_start_date; ?>"
                    data-cut_of_end_date="<?php echo $cut_of_end_date; ?>"
                >Check Availability</button>
            </div>

            <div class="tm_booking_message">
                <p>Need help with booking? <a href="#">Contact Us</a></p>
            </div>
        </div>
        <?php echo CheckAvailability::RenderCheckAvailability($packages, $title, $id); ?>
        <div class="tm_trip_inquiry_form_wrapper">
            <h3 class="tm_inquiry_title">You can send your enquiry via the form below</h3>
            <form id="tm_submission-inquiry-form">
                <div class="tm_inquiry_form">
                <div class="tm_form_group">
                        <input type="hidden" name="trip_id" id="tm_id" value="<?php echo  esc_html($id) ;  ?>" required>
                    </div>
                    <div class="tm_form_group">
                        <p for="name">Name</p>
                        <input type="text" name="name" id="tm_name" required>
                    </div>
                    <div class="tm_form_group">
                        <p for="email">Email</p>
                        <input type="email" name="email" id="tm_email" required>
                    </div>
                    <div class="tm_form_group">
                        <p for="email">Country</p>
                        <select name="country" id="tm_country" required>
                            <option value="Nepal">Nepal</option>
                            <option value="India">India</option>
                            <option value="China">China</option>
                            <option value="USA">USA</option>
                        </select>
                    </div>
                    <div class="tm_form_group">
                        <p for="phone">Phone</p>
                        <input type="text" name="phone" id="tm_phone" required>
                    </div>
                    <div class="tm_form_group">
                        <p for="subject">Subject</p>
                        <input type="text" name="subject" id="ym_subject" required>
                    </div>

                    <div class="tm_form_group">
                        <p for="subject">Number of Adults</p>
                        <input type="number" name="number_of_adults" id="number_of_adults" required>
                    </div>

                    <div class="tm_form_group">
                        <p for="subject">Number of Children</p>
                        <input type="number" name="number_of_children" id="number_of_children" required>
                    </div>

                    <div class="tm_form_group">
                        <p for="message">Message</p>
                        <textarea name="message" id="tm_message" required></textarea>
                    </div>
                    <div class="tm_form_group">
                        <ul class="tm_error_message"></ul>
                    </div>
                    <div class="tm_form_group">
                        <button style="cursor: pointer;" class="tm_inquiry_button">Send Inquiry</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


