<?php
namespace WPTravelManager\Views\Checkout;
use WPTravelManager\Views\Checkout\SubmissionCheckout;
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

    <div class="tm_content" style="display: flex; align-items: center; gap: 20px">

        <div class="tm_checkout_form" style="width: 45%; padding: 20px">
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
                    <!-- <input type="phone" name="traveler_phone" placeholder="Please enter your number" required> -->
                    <input type="tel" id="phone" name="traveler_phone" placeholder="01X-XXXX-XXXX" pattern="01[3-9][0-9]{8}" required>
                </div>

                <div class="tm_filed">
                    <div class="tm_address_filed">
                    <div class="tm_filed">
                        <label>Address <span style="color: #ff8b3d;">* </span></label>
                        <input type="text" name="address" placeholder="Please enter your number" required>
                    </div>
                    <div class="tm_filed">
                        <label>City <span style="color: #ff8b3d;">* </span></label>
                        <input type="text" name="city" placeholder="Please enter your number" required>
                    </div>
                    <div class="tm_filed">
                        <label>State <span style="color: #ff8b3d;">* </span></label>
                        <input type="text" name="state" placeholder="Please enter your number" required>
                    </div>
                    <div class="tm_filed">
                        <label>Zip Code <span style="color: #ff8b3d;">* </span></label>
                        <input type="text" name="zip_code" placeholder="Please enter your number" required>
                    </div>
                    </div>
                    
                </div>

                <div class="tm_filed">
                    <label>Country <span style="color: #ff8b3d;">* </span></label>
                    <select name="traveler_country" required>
                        <option selected value="">Choose a country*</option>
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
                <?php do_action('trm/render_payment_options') ?>
                <!-- <div class="tm_filed_checkbox">
                    <input type="checkbox">
                    <label> Check the box to confirm you've read and agree to our Terms and Conditions and Privacy Policy.</label>
                </div> -->

                <div class="tm_submit">
                    <button  class="tm_checkout_button">Submit</button>
                </div>
            </form>
        </div>
        
        <div class="tm_book_summary" style="width: 50%; padding: 20px;">
            <?php do_action('trm/render_checkout_summary', $booking) ?>
        </div>
    </div>
</div>