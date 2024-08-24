<?php

namespace WPTravelManager\Views\Checkout;

use WPTravelManager\Views\Checkout\SubmissionCheckout;
use WPTravelManager\Classes\ArrayHelper as Arr;

$data = $booking;


?>


<div class="tm_checkout">
    <h1 class="tm_checkout_title">Cart Item</h1>

    <div class="tm_content" style="    gap: 30px;">
        <?php
        foreach ($data as $booking):
            $session_meta = isset($booking->session_meta) ? $booking->session_meta : '[]';
            $booking_date = Arr::get($session_meta, 'booking_date_selected', null);
            $trip_title = Arr::get($session_meta, 'trip_title', '');
            $package_name = Arr::get($session_meta, 'packages.0.package_name', '');

            $booking_packages = Arr::get($session_meta, 'packages', '[]');

        ?>
            <div class="tm_book_summary" style="width: 31.3%; 
   
">
                <?php echo SubmissionCheckout::BookingSummery($package_name, $trip_title, $booking_date, $booking_packages); ?>
                <!-- <button class="tm_remove_cart_item" data-id="<?php echo $booking->id; ?>">Remove</button> -->
                <button class="tm_cart_checkout_button">
                    <a href="<?php echo site_url('/travel-manager-checkout/?booking_id=' . $booking->id); ?>">Checkout</a>
                </button>
            </div>
        <?php
        endforeach;
        ?>
    </div>
</div>