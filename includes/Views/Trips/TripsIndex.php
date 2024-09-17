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
                    <div class="trm_search_type">
                        <div class="trm_filter_section_title">
                            <h3>Destination</h3>
                            <span class="dashicons dashicons-arrow-down-alt2"></span>
                        </div>
                        <div class="trm_filter_section_content">
                            <ul class="trm_search_terms_list">
                                <li class="">
                                    <label class="container">Bhutan
                                        <input type="checkbox" value="bhutan">
                                        <span class="checkmark"></span>
                                    </label>
                                    <span class="count">2</span>
                                </li>
                                <li class="">
                                    <label class="container">Bhutan
                                        <input type="checkbox" value="bhutan">
                                        <span class="checkmark"></span>
                                    </label>
                                    <span class="count">2</span>
                                </li>
                                <li>
                                    <button class="trm_show_less">Show less <span class="trm_icon dashicons dashicons-arrow-up-alt2"></span></button>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <!-- ===========Price================= -->
                    <div class="trm_search_type">
                        <div class="trm_filter_section_title">
                            <h3>Price</h3>
                            <span class="dashicons dashicons-arrow-down-alt2"></span>
                        </div>
                        <div class="trm_price_filter">
                            <input type="hidden">
                            <input type="hidden">
                            <div class="trm_cost_slider_range">
                                <div class="trm_ui_slider_range"></div>
                                <span tabindex="0" class="trm_ui_slider_handle"></span>
                                <span tabindex="0" class="trm_ui_slider_handle"></span>
                            </div>
                            <div class="trm_cost_slider_value">
                                <span class="trm_min_cost">$3000</span>
                                <span class="trm_max_cost">$3000</span>
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
                            <input type="hidden">
                            <input type="hidden">
                            <div class="trm_cost_slider_range">
                                <div class="trm_ui_slider_range" style="left: 0%; width: 100%;"></div>
                                <span tabindex="0" class="trm_ui_slider_handle" style="left: 0px;" data-value="0 Days"></span>
                                <span tabindex="0" class="trm_ui_slider_handle" style="left: 100%; " data-value="12 Days"></span>
                            </div>
                            <div class="trm_cost_slider_value">
                                <span class="trm_min_cost">0 Days</span>
                                <span class="trm_max_cost">12 Days</span>
                            </div>

                        </div>
                    </div>
                    <!-- ===========Activities================= -->
                    <div class="trm_search_type">
                        <div class="trm_filter_section_title">
                            <h3>Activities</h3>
                            <span class="dashicons dashicons-arrow-down-alt2"></span>
                        </div>
                        <div class="trm_filter_section_content">
                            <ul class="trm_search_terms_list">
                                <li class="">
                                    <label class="container">Bhutan
                                        <input type="checkbox" value="bhutan">
                                        <span class="checkmark"></span>
                                    </label>
                                    <span class="count">2</span>
                                </li>
                                <li class="">
                                    <label class="container">Bhutan
                                        <input type="checkbox" value="bhutan">
                                        <span class="checkmark"></span>
                                    </label>
                                    <span class="count">2</span>
                                </li>
                                <li>
                                    <button class="trm_show_less">Show less <span class="trm_icon dashicons dashicons-arrow-up-alt2"></span></button>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <!-- ===========Trip Types================= -->
                    <div class="trm_search_type">
                        <div class="trm_filter_section_title">
                            <h3>Trip Types</h3>
                            <span class="dashicons dashicons-arrow-down-alt2"></span>
                        </div>
                        <div class="trm_filter_section_content">
                            <ul class="trm_search_terms_list">
                                <li class="">
                                    <label class="container">Bhutan
                                        <input type="checkbox" value="bhutan">
                                        <span class="checkmark"></span>
                                    </label>
                                    <span class="count">2</span>
                                </li>
                                <li class="">
                                    <label class="container">Bhutan
                                        <input type="checkbox" value="bhutan">
                                        <span class="checkmark"></span>
                                    </label>
                                    <span class="count">2</span>
                                </li>
                                <li>
                                    <button class="trm_show_less">Show less <span class="trm_icon dashicons dashicons-arrow-up-alt2"></span></button>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <!-- ======================================= -->
                </div>
            </div>
            <!-- ======================================= -->
            <div class="trm_trip_card">
                <div class="trm_travel_toolbar">
                    <div class="trm_filter_foundposts">
                        <h2><strong>2</strong> Trips found</h2>
                    </div>
                    <div class="trm_dropdown">
                        <form>
                            <label>Sort :</label>
                            <select>
                                <option value="latest" selected>Latest</option>
                                <option>Most Reviewed</option>
                                <option>Departure Dates</option>
                                <option>Price</option>
                                <option>Low to High</option>
                                <option>High to Low</option>
                                <option>A to Z</option>
                                <option>Z to A</option>
                            </select>
                        </form>
                    </div>
                    <div class="trm_view_modes">
                        <a href="#"> <span class="menu dashicons  dashicons-menu-alt"></"></span></a>
                        <a href="#"><span class="menu dashicons  dashicons-screenoptions"></span></a>
                    </div>
                </div>
                <!-- ================================== -->
                <?php

                foreach ($all_trip as $trip) :
                    $id = ($trip->ID);

                    $post_meta = get_post_meta($id, 'trip_meta', true);
                    $trip->post_meta = maybe_unserialize($post_meta);

                    $trip_title = $trip->post_title;
                    $trip_details = ($trip->post_meta);

                    $trips_destination = Arr::get($trip_details, 'general.trip_destination', null);
                    $duration = Arr::get($trip_details, 'general.duration.duration', 0);
                    $description = Arr::get($trip_details, 'general.description.description', 0);
                    $packages = Arr::get($trip_details, 'packages', []);
                    $packages_price = Arr::get($packages, '0.pricing.0.price', null);
                    $packages_selling_price = Arr::get($packages, '0.pricing.0.selling_price', null);

                    $image = Arr::get($trip_details, 'trip_gallery.images.1.url', null);
                    $empty_image = TRM_URL . 'assets/images/images.jpg';

                ?>

                    <div class="trm_category_trips">
                        <div class="trm_trips_details">
                            <figure class="trm_trips_image">
                                <?php if (!$image) : ?>
                                    <a href="#">
                                        <img src="<?php echo $empty_image ?>">
                                    </a>
                                  
                                <?php else : ?>
                                    <a href="#">
                                        <img src="<?php echo $image ?>">
                                    </a>
                                <?php endif; ?>
                               
                            </figure>
                            <div class="trm_category_trip_content">
                                <div class="trm_category_title">
                                    <h2>
                                        <a itemprop="url" href="#"><?php echo esc_html($trip_title) ?></a>
                                    </h2>
                                </div>
                                <div class="trm_category_trip_detail">
                                    <div class="trm_trip_category_price">
                                        <div class="trm_trip_desti">
                                            <div class="trm_trip_loc">
                                                <span class="icon dashicons dashicons-location"></span>
                                                <span class="trm_name"><?php echo esc_html($trips_destination) ?></span>
                                            </div>
                                            <div class="trm_trip_dur">
                                                <span class="icon dashicons dashicons-clock"></span>
                                                <span class="trm_name"><?php echo esc_html($duration) ?> Day</span>
                                            </div>
                                        </div>
                                        <div class="trm_trip_budget">
                                            <div class="trm_trip_discount">
                                                <span>14% Off</span>
                                            </div>
                                            <div class="trm_price_holder">
                                                <span class="trm_actual_price">$<?php echo esc_html($packages_selling_price) ?></span>
                                                <span class="trm_striked_price">$<?php echo esc_html($packages_price) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="trm_trip_desc">
                                        <?php echo esc_html($description) ?>
                                    </div>
                                </div>

                            </div>
                            <div class="trm_trip_aval_time">
                                <div class="trm_category_trip_inner">
                                    <span class="category-available-trip-text">Available through out the year:</span>
                                    <ul class="category-available-months">
                                        <li>Jan</li>
                                        <li>Feb</li>
                                        <li>Mar</li>
                                        <li>Apr</li>
                                        <li>May</li>
                                        <li>Jun</li>
                                        <li>Jul</li>
                                        <li>Aug</li>
                                        <li>Sep</li>
                                        <li>Oct</li>
                                        <li>Nov</li>
                                        <li>Dec</li>
                                    </ul>
                                </div>
                                <a href="#" class="trm_view_btn">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <!-- =================================== -->
                <div class="trm_category_trips">
                    <div class="trm_trips_details">
                        <div class="trm_trips_image">
                            <a href="#">
                                <img src="<?php echo $demoImage2 ?>">
                            </a>
                        </div>
                        <div class="trm_category_trip_content">
                            <div class="trm_category_title">
                                <h2>
                                    <a href="#">Motel 6 Conyers GA</a>
                                </h2>
                            </div>
                            <div class="trm_category_trip_detail">
                                <div class="trm_trip_category_price">
                                    <div class="trm_trip_desti">
                                        <div class="trm_trip_loc">
                                            <span class="icon dashicons dashicons-location"></span>
                                            <span class="trm_name">Nepal</span>
                                        </div>
                                        <div class="trm_trip_dur">
                                            <span class="icon dashicons dashicons-clock"></span>
                                            <span class="trm_name">7Day</span>
                                        </div>
                                    </div>
                                    <div class="trm_trip_budget">
                                        <div class="trm_trip_discount">
                                            <span>14% Off</span>
                                        </div>
                                        <div class="trm_price_holder">
                                            <span class="trm_actual_price">$3000</span>
                                            <span class="trm_striked_price">$3500</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="trm_trip_desc">
                                    The Tiananmen, a gate in the wall of the Imperial City, was built in 1415 during the Ming dynasty. In the 17th century, fighting between... </div>
                            </div>

                        </div>
                        <div class="trm_trip_aval_time">
                            <div class="trm_category_trip_inner">
                                <span class="category-available-trip-text">Available through out the year:</span>
                                <ul class="category-available-months">
                                    <li>Jan</li>
                                    <li>Feb</li>
                                    <li>Mar</li>
                                    <li>Apr</li>
                                    <li>May</li>
                                    <li>Jun</li>
                                    <li>Jul</li>
                                    <li>Aug</li>
                                    <li>Sep</li>
                                    <li>Oct</li>
                                    <li>Nov</li>
                                    <li>Dec</li>
                                </ul>
                            </div>
                            <a href="#" class="trm_view_btn">View Details</a>
                        </div>
                    </div>
                </div>

            </div>
            <!-- ======================================== -->


        </div>
    </div>
</div>