<?php

namespace WPTravelManager\Views\Trips;

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
                <p>Apache/2.4.58 (Win64) OpenSSL/3.1.3 PHP/8.2.12
                    Database client version: libmysql - mysqlnd 8.2.12
                    PHP extension: mysqli Documentation curl Documentation mbstring Documentation
                    PHP version: 8.2.12</p>
            </div>
            <!-- ======================================= -->
            <div class="trm_trip_card">
                <div class="trm_travel_toolbar">
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
                <div class="trm_category_trips">
                    <div class="trm_trips_details">
                        <div class="trm_trips_image">
                            <a href="#">
                                <img src="<?php echo $demoImage ?>">
                            </a>
                        </div>
                        <div class="trm_category_trip_content">
                            <div class="trm_category_title">
                                <h2>
                                    <a itemprop="url" href="#">Motel 6 Conyers GA</a>
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
                                    <a  href="#">Motel 6 Conyers GA</a>
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