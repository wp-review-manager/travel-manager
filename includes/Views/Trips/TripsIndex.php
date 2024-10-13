<?php

namespace WPTravelManager\Views\Trips;
use WPTravelManager\Classes\ArrayHelper as Arr;

$demoImage = TRM_URL . 'assets/images/girl.jpeg';
$demoImage2 = TRM_URL . 'assets/images/sunflower.jpg';
?>
<div class="trm_container">
    <div class="trm_content">

        <div class="trm_page-header">
            <h1 class="trm_page-title">Trip Listing</h1>
        </div>

        <div class="trm_page_body">
            <!-- ======================================== -->
            <div class="trm_trip_details">
                <div class="trm_sidebar">
                    <div class="trm_search_header">
                        <h2>Criteria</h2>
                        <button class="trm_clear_search">Clear all</button>
                    </div>
                    <!-- ===========Destination================= -->
                     <?php if ($total_destinations > 0) : ?>
                        <div class="trm_search_type">
                            <div class="trm_filter_section_title">
                                <h3><?php echo __('Destination', 'travel-manager') ?></h3>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </div>
                            <div class="trm_filter_section_content">
                                <ul class="trm_search_terms_list">
                                    <?php foreach ($destinations as $destination) : ?>
                                        <li class="">
                                            <label class="container"><?php echo $destination->place_name ?>
                                                <input name="destinations" class="trm_trip_filter_input" type="checkbox" value="<?php echo $destination->place_slug ?>">
                                                <span class="checkmark"></span>
                                            </label>
                                            <!-- <span class="count"><?php echo $destination->count ?></span> -->
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                        </div>
                    <?php endif; ?>
                    <!-- ===========Price================= -->
                    <div class="trm_search_type">
                        <div class="trm_filter_section_title">
                            <h3>Price</h3>
                            <span class="dashicons dashicons-arrow-down-alt2"></span>
                        </div>
                        <div class="trm_price_filter">
                            <div class="trm_cost_slider_range" id="trm_price-slider"></div>
                            <div class="trm_cost_slider_value">
                                <span class="trm_min_cost">$<span id="trm_price-min"><?php echo $min_price ?></span></span>
                                <span class="trm_max_cost">$<span id="trm_price-max"><?php echo $max_price ?></span></span>
                            </div>
                        </div>
                    </div>
                    <!-- ===========Duration=================== -->
                    <div class="trm_search_type">
                        <div class="trm_filter_section_title">
                            <h3>Duration</h3>
                            <span class="dashicons dashicons-arrow-down-alt2"></span>
                        </div>
                        <div class="trm_price_filter">
                            <div class="trm_cost_slider_range" id="trm_duration-slider"></div>
                            <div class="trm_cost_slider_value">
                                <span class="trm_min_cost"><span id="trm_duration-min"><?php echo $min_duration ?></span> Days</span>
                                <span class="trm_max_cost"><span id="trm_duration-max"><?php echo $max_duration ?></span> Days</span>
                            </div>
                        </div>
                    </div>
                    <!-- ===========Activities================= -->
                     <?php if ($total_activities > 0) : ?>
                        <div class="trm_search_type">
                            <div class="trm_filter_section_title">
                                <h3><?php echo __('Activities', 'travel-manager') ?></h3>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </div>
                            <div class="trm_filter_section_content">
                                <ul class="trm_search_terms_list">
                                    <?php foreach ($activities as $activity) : ?>
                                        <li class="">
                                            <label class="container"><?php echo $activity->trip_activity_name ?>
                                                <input name="activities" class="trm_trip_filter_input" type="checkbox" value="<?php echo $activity->trip_activity_slug ?>">
                                                <span class="checkmark"></span>
                                            </label>
                                            <!-- <span class="count"><?php echo $activity->count ?></span> -->
                                        </li>
                                    <?php endforeach; ?>
                                    <li>
                                        <button class="trm_show_less">Show less <span class="trm_icon dashicons dashicons-arrow-up-alt2"></span></button>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    <?php endif; ?>
                    <!-- ===========Trip Types================= -->
                     <?php if ($total_trip_types > 0) : ?>
                        <div class="trm_search_type">
                            <div class="trm_filter_section_title">
                                <h3><?php echo __('Trip Types', 'travel-manger') ?></h3>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </div>
                            <div class="trm_filter_section_content">
                                <ul class="trm_search_terms_list">
                                    <?php foreach ($trip_types as $trip_type) : ?>
                                        <li class="">
                                            <label class="container"><?php echo $trip_type->trip_category_name ?>
                                                <input name="categories" class="trm_trip_filter_input" type="checkbox" value="<?php echo $trip_type->trip_category_slug ?>">
                                                <span class="checkmark"></span>
                                            </label>
                                            <!-- <span class="count">5</span> -->
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                        </div>
                    <?php endif; ?>
                    <!-- ======================================= -->
                </div>
            </div>
            <!-- ======================================= -->
            <div class="trm_trip_card">
                <div class="trm_travel_toolbar">
                    <div class="trm_filter_foundposts">
                        <h2><strong><?php echo $total ?></strong> Trips found</h2>
                    </div>
                    <div class="trm_dropdown">
                        <form>
                            <label><?php echo __('Sort By', 'travel-manager') ?> :</label>
                            <select id="trip_sort_by">
                                <option value="latest" selected><?php echo __('Latest', 'travel-manager') ?></option>
                                <?php /*
                                <option value="departure_dates"><?php echo __('Departure Dates', 'travel-manager') ?></option>
                                <option value="price_asc"><?php echo __('Price Low to High', 'travel-manager') ?></option>
                                <option value="price_desc"><?php echo __('Price High to Low', 'travel-manager') ?></option>
                                */ ?>
                                <option value="name_asc"><?php echo __('A to Z', 'travel-manager') ?></option>
                                <option value="name_desc"><?php echo __('Z to A', 'travel-manager') ?></option>
                            </select>
                        </form>
                    </div>
                    <div class="trm_view_modes">
                        <a href="#"> <span class="menu dashicons  dashicons-menu-alt"></"></span></a>
                        <!-- <a href="#"><span class="menu dashicons  dashicons-screenoptions"></span></a> -->
                    </div>
                </div>
                <!-- ================================== -->
                 <div class="trm_category_trips_wrapper">
                    <?php echo (new TripsCard)->render($all_trip) ?>
                </div>
                <!-- =================================== -->
                <div class="trm_pagination trm_trips_page_pagination">
                    <?php echo (new Pagination)->render($total_page) ?>
                </div>
            </div>
            <!-- ======================================== -->
        </div>

    </div>
</div>


