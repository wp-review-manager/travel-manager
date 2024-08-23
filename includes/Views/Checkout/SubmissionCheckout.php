<?php
namespace WPTravelManager\Views\Checkout;
use WPTravelManager\Classes\ArrayHelper as Arr;



class SubmissionCheckout {

    public static function BookingSummery($package_name,$trip_title,$booking_date,$booking_packages)
    {
        ob_start();
        ?>
     
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


        <?php
        return ob_get_clean();  
    }
        
}
