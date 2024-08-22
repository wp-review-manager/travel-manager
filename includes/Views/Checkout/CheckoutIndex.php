<?php
namespace WPTravelManager\Views\Checkout;
use WPTravelManager\Classes\ArrayHelper as Arr;

$booking_date = Arr::get($booking, 'booking_date_selected', null);
$trip_title = Arr::get($booking, 'trip_title', '[]');
$booking_packages = Arr::get($booking, 'packages', '[]');
$package_name = Arr::get($booking, 'packages.0.package_name', '[]');
$trip_id = Arr::get($booking, 'trip_id', null);
//dd($booking);
$subtotal = 0;
foreach ($booking_packages as $packages): 
    $tm_package_price_total = Arr::get($packages, 'tm_package_price_total', '');
    $subtotal += $tm_package_price_total;
endforeach;



?>






<div class="tm_checkout">
    <h1 class="tm_checkout_title">Checkout</h1>

    <div class="tm_content">

        <div class="tm_checkout_form">
            <h1 class="tm_title">Billing Details</h1>

            <form id="tm_checkout-form">

                <input type="hidden" name="session_id" value="<?php echo esc_html($booking_id)  ?>">

                <input type="hidden" name="trip_id" value="<?php echo esc_html($trip_id)  ?>">

                <input type="hidden" name="booking_total" value="<?php echo esc_html($subtotal)  ?>">

                <input type="hidden" name="booking_date" value="<?php echo esc_html($booking_date)  ?>">
              
                <div class="tm_filed">
                    <label>Name <span style="color: #ff8b3d;">* </span></label>
                    <input type="text" name="traveler_name" placeholder="Please enter your name" required>
                </div>

                <div class="tm_filed">
                    <label>Email <span style="color: #ff8b3d;">* </span></label>
                    <input type="email" name="traveler_email" placeholder="Please enter your email" required>
                </div>

                <div class="tm_filed">
                    <label>Phone <span style="color: #ff8b3d;">* </span></label>
                    <input type="number" name="traveler_phone" placeholder="Please enter your number" required>
                </div>

                <div class="tm_filed">
                    <label> Address <span style="color: #ff8b3d;">* </span></label>
                    <div class="tm_address_filed">
                    <div class="tm_filed">
                        <label>Address Line 1 <span style="color: #ff8b3d;">* </span></label>
                        <input type="text" name="traveler_address.address_1" placeholder="Please enter your number" required>
                    </div>
                    <div class="tm_filed">
                        <label>Address Line 2 <span style="color: #ff8b3d;">* </span></label>
                        <input type="text" name="traveler_address" placeholder="Please enter your number" required>
                    </div>
                    <div class="tm_filed">
                        <label>City <span style="color: #ff8b3d;">* </span></label>
                        <input type="text" name="traveler_phone" placeholder="Please enter your number" required>
                    </div>
                    <div class="tm_filed">
                        <label>State <span style="color: #ff8b3d;">* </span></label>
                        <input type="text" name="traveler_phone" placeholder="Please enter your number" required>
                    </div>
                    <div class="tm_filed">
                        <label>Zip Code <span style="color: #ff8b3d;">* </span></label>
                        <input type="text" name="traveler_phone" placeholder="Please enter your number" required>
                    </div>
                    </div>
                    
                </div>

                <div class="tm_filed">
                    <label>Country <span style="color: #ff8b3d;">* </span></label>
                    <select name="traveler_country" required>
                        <option selected="">Choose a country*</option>
                        <option>Bangladesh</option>
                        <option>India</option>
                        <option value="Japan">Japan</option>
                        <option value="Nepal">Nepal</option>
                        <option value="American Samoa">American Samoa</option>
                        <option value="Andorra">Andorra</option>
                        <option value="Angola">Angola</option>
                        <option value="Anguilla">Anguilla</option>
                        <option value="Antarctica">Antarctica</option>
                    </select>
                </div>

                <!-- <div class="tm_filed_checkbox">
                    <input type="checkbox">
                    <label> Check the box to confirm you've read and agree to our Terms and Conditions and Privacy Policy.</label>
                </div> -->

                <div class="tm_submit">
                    <button   class="tm_checkout_button">Submit</button>
                </div>
            </form>
        </div>

        <div class="tm_book_summary">
            <h1 class="tm_summary_title">Booking Summary</h1>

            <div class="tm_trip_details">
                <div class="tm_trip_name"><?php echo esc_html($trip_title)  ?></div>

                <span class="tm_trip_code">Trip Code: <span>WTE-84</span></span>
                <span class="tm_trip_date">
                    Booking Date: <?php echo $booking_date ?>
                </span>
            </div>

            <table class="tm_summary_table">
                <tbody>
               
                    <tr class="tm_package_name">
                        <td colspan="2">
                            <span class="label">Package:</span><span class="value"><?php echo esc_html($package_name)  ?></span>
                        </td>
                    </tr>
                    <?php  $subtotalPrice= 0;
                     foreach ($booking_packages as $packages): 

                        $pricing_label = Arr::get($packages, 'pricing_label', '');
                        $tm_travelers_number = Arr::get($packages, 'tm_travelers_number', '');
                        $tm_package_price_total = Arr::get($packages, 'tm_package_price_total', '');

                        $subtotalPrice += $tm_package_price_total;
                    
                    ?>
                    <tr class="tm_package_details">
                        <td><?php echo esc_html($tm_travelers_number)?>, <?php echo esc_html($pricing_label)  ?></td>
                        <td><span style="text-align: right !important;">$</span> <span class="wpte-price"><?php echo esc_html($tm_package_price_total)  ?></span>
                        </td>
                    </tr>
                    <!-- Extra Services -->
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align: right; padding-top:20px !important; font-size: 20px; color: #232323; font-weight: 500;">
                            <span style="padding-right: 10px;">Subtotal :</span>
                            <span class="tm_currency_code">$</span> <span class="wpte-price"><?php echo esc_html($subtotalPrice)?></span>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <div class="tm_total_price">
                <span style="color: rgba(35, 35, 35, 0.8); margin-right: 5px;letter-spacing: .5px;" >Total Payable : </span>
                <span style="  font-size: 24px; font-weight: 500; color: #232323;">
                     <span >$</span>
                      <span><?php echo esc_html($subtotalPrice)?></span>
                </span>

            </div>
        </div>

    </div>
</div>