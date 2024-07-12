<?php

namespace WPTravelManager\Views\Preview;

use WPTravelManager\Views\Components\Slider;
use WPTravelManager\Views\Components\Tab;
use WPTravelManager\Views\Components\CheckAvailability;
use WPTravelManager\Classes\ArrayHelper as Arr;
$durationType = Arr::get($trip, 'general.duration.type', 0);
$duration = Arr::get($trip, 'general.duration.duration', 0);
$transportation = Arr::get($trip, 'general.transportation', null);

?>
<div class="tm_trip_one_shortcode_preview_wrapper">
    <main class="tm_trip_main">
        <article>
            <div class="entry-header">
                <h1 class="entry-title"><?php echo esc_html($title); ?></h1>
                <span class="wte-title-duration">
                    <p class="duration"> <?php echo esc_html( $duration ) ?> </p>
                    <p class="days"> <?php echo esc_html( $durationType ) ?> </p>
                </span>
            </div>

            <div class="tm_gallery_wrapper">
                <?php echo Slider::RenderSlider(); ?>
            </div>

            <div class="tm_trip_entry_content">
                <div class="tm_secondary_trip_info">
                        <div class="tm_trip_price">
                            <div class="tm_info_label">
                                <span class="dashicons dashicons-airplane"></span>
                                <span class="tm_label">Transportation</span>
                            </div>
                            <span class="tm_price_value"><?php echo $transportation ?></span>
                        </div>

                        <div class="tm_trip_price">
                            <div class="tm_info_label">
                                <span class="dashicons dashicons-schedule"></span>
                                <span class="tm_label">Next</span>
                            </div>
                            <span class="tm_price_value">Next</span>
                        </div>
                </div>
            </div>

            <?php echo Tab::RenderTab(); ?>

        </article>
    </main>
    <div class="tm_trip_sidebar">
        <div class="tm_availability_wrapper">
            <div class="tm_trip_starting_price_list">
                <div class="tm_trip_starting_price">
                    <div class="tm_regular_price">
                        <span class="tm_label">From</span>
                        <span class="tm_price">$800</span>
                    </div>
                    <div class="tm_selling_price">
                        <span class="tm_price">$600</span>
                        <span class="tm_label"> / Child</span>
                    </div>

                </div>
                <div class="tm_trip_starting_price">
                    <div class="tm_regular_price">
                        <span class="tm_label">From</span>
                        <span class="tm_price">$1000</span>
                    </div>
                    <div class="tm_selling_price">
                        <span class="tm_price">$800</span>
                        <span class="tm_label"> / Adult</span>
                    </div>
                </div>
            </div>

            <div class="tm_check_availability">
                <button id="tm_openModal" class="tm_button tm_openModal">Check Availablity</button>
            </div>

            <div class="tm_booking_message">
                <p>Need help with booking? <a href="#">Contact Us</a></p>
            </div>
        </div>
        <?php echo CheckAvailability::RenderCheckAvailability(); ?>
        <div class="tm_trip_inquiry_form_wrapper">
            <h3 class="tm_inquiry_title">You can send your enquiry via the form below</h3>
            <form action="" method="post">
                <div class="tm_inquiry_form">
                    <div class="tm_form_group">
                        <p for="name">Name</p>
                        <input type="text" name="name" id="name" required>
                    </div>
                    <div class="tm_form_group">
                        <p for="email">Email</p>
                        <input type="email" name="email" id="email" required>
                    </div>
                    <div class="tm_form_group">
                        <p for="email">Country</p>
                        <select name="country" id="country" required>
                            <option value="Nepal">Nepal</option>
                            <option value="India">India</option>
                            <option value="China">China</option>
                            <option value="USA">USA</option>
                        </select>
                    </div>
                    <div class="tm_form_group">
                        <p for="phone">Phone</p>
                        <input type="text" name="phone" id="phone" required>
                    </div>
                    <div class="tm_form_group">
                        <p for="subject">Subject</p>
                        <input type="text" name="subject" id="subject" required>
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
                        <textarea name="message" id="message" required></textarea>
                    </div>
                    <div class="tm_form_group">
                        <button class="tm_button">Send Inquiry</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>