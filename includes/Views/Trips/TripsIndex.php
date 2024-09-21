<?php

namespace WPTravelManager\Views\Trips;

$demoImage = TRM_URL . 'assets/images/girl.jpeg';
$demoImage2 = TRM_URL . 'assets/images/sunflower.jpg';
// dd(isset($_GET['page']));
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
                        <h2><strong><?php echo $total ?></strong> Trips found</h2>
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
                 <div class="trm_category_trips_wrapper">
                    <?php echo (new TripsCard)->render($all_trip) ?>
                </div>
                <!-- =================================== -->
                <div class="trm_pagination">
                    <p data-trm_page_no="prev" class="trm_all_trips_pag trm_pag_prev trm_pag_disabled"><span class="dashicons dashicons-arrow-left-alt2"></span></p>
                    <?php for ($i = 1; $i <= $total_page; $i++) : ?>
                        <p class="trm_all_trips_pag <?php echo  $i == 1 ? 'trm_pag_active' : '' ?>" data-trm_page_no="<?php echo $i; ?>"><?php echo $i ?></p>
                    <?php endfor; ?>
                    <p data-trm_page_no="next" class="trm_all_trips_pag trm_pag_next"><span class="dashicons dashicons-arrow-right-alt2"></span></p>
                </div>

            </div>
            <!-- ======================================== -->
        </div>

    </div>
</div>


