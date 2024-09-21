<?php

namespace WPTravelManager\Views\Trips;

class TripAvailability
{
    public function render($trip_availability)
    {
        $start_of_date = new \DateTime($trip_availability['start_of_date']);
        $end_of_date = new \DateTime($trip_availability['end_of_date']);
        $enable = $trip_availability['enable'];
        if ('yes' != $enable) {
            return;
        }
        ob_start();
    ?>
        <div class="trm_category_trip_inner">
            <span class="category-available-trip-text">Available trip booking form</span>
            <ul class="trm_category-available-months">
                <p>Start Date: <?php echo $start_of_date->format('Y-m-d'); ?>  </p>
                <p>End Date: <?php echo $end_of_date->format('Y-m-d'); ?></p>
            </ul>
        </div>
<?php
        return ob_get_clean();
    }
}
