<?php
namespace WPTravelManager\Views\Trips;
$demoImage = TRM_URL . 'assets/images/girl.jpeg';
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
                    <p>icon</p>
                </div>
            </div>
            <div class="trm_category_trips">
                <div class="trm_trips_details">
                    <div class="trm_trips_image">
                        <a href="#">
                            <img src="<?php echo $demoImage ?>">
                        </a>
                    </div>
                </div>
            </div>
           
        </div>

    </div>
   </div>
</div>