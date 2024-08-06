<?php
namespace WPTravelManager\Views\Checkout;
use WPTravelManager\Classes\ArrayHelper as Arr;

$booking_date = Arr::get($booking, 'booking_date_selected', null);

dd($booking);
?>

<div class="tm_checkout">
    <h1 class="tm_checkout_title">Checkout</h1>

    <div class="tm_content">

        <div class="tm_checkout_form">
            <h1 class="tm_title">Billing Details</h1>

            <form>
                <div class="tm_filed">
                    <label>First Name <span style="color: #ff8b3d;">* </span></label>
                    <input type="text" placeholder="Please enter your first name">
                </div>

                <div class="tm_filed">
                    <label>Last Name <span style="color: #ff8b3d;">* </span></label>
                    <input type="text" placeholder="Please enter your last name">
                </div>

                <div class="tm_filed">
                    <label>Email <span style="color: #ff8b3d;">* </span></label>
                    <input type="email" placeholder="Please enter your email">
                </div>

                <div class="tm_filed">
                    <label> Address <span style="color: #ff8b3d;">* </span></label>
                    <input type="text" placeholder="Please enter your address">
                </div>

                <div class="tm_filed">
                    <label>City <span style="color: #ff8b3d;">* </span></label>
                    <input type="text" placeholder="Please enter your city">
                </div>

                <div class="tm_filed">
                    <label>Country <span style="color: #ff8b3d;">* </span></label>
                    <select>
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

                <div class="tm_filed_checkbox">
                    <input type="checkbox">
                    <label> Check the box to confirm you've read and agree to our Terms and Conditions and Privacy Policy.</label>
                </div>

                <div class="tm_submit">
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>

        <div class="tm_book_summary">
            <h1 class="tm_summary_title">Booking Summary</h1>

            <div class="tm_trip_details">
                <div class="tm_trip_name">Motel 6 Conyers GA</div>

                <span class="tm_trip_code">Trip Code: <span>WTE-84</span></span>
                <span class="tm_trip_date">
                    Starting Date: <?php echo $booking_date ?>
                </span>
            </div>

            <table class="tm_summary_table">
                <tbody>
                    <tr class="tm_package_name">
                        <td colspan="2">
                            <span class="label">Package:</span><span class="value">Budget Friendly</span>
                        </td>
                    </tr>
                    <tr class="tm_package_details">
                        <td>Adult 1</td>
                        <td><span style="text-align: right !important;">$</span> <span class="wpte-price">3,000</span>
                        </td>
                    </tr>

                    <!-- Extra Services -->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align: right; padding-top:20px !important; font-size: 20px; color: #232323; font-weight: 500;">
                            <span style="padding-right: 10px;">Subtotal :</span>
                            <span class="tm_currency_code">$</span> <span class="wpte-price">3,000</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <div class="tm_total_price">
                <span style="color: rgba(35, 35, 35, 0.8); margin-right: 5px;letter-spacing: .5px;" >Total Payable : </span>
                <span style="  font-size: 24px; font-weight: 500; color: #232323;">
                     <span >$</span>
                      <span>3,000</span>
                </span>

            </div>
        </div>

    </div>
</div>