<?php
namespace WPTravelManager\Views\Trips;
use WPTravelManager\Classes\ArrayHelper as Arr;

class TripsCard
{
    public function render($all_trip)
    {
?>
        <?php
        $trip_search_page = get_option('wp_travel_manager_trip_search_page');
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
            $trip_details_page = get_option('wp_travel_manager_trip_details_page');
            $discount = round(($packages_price - $packages_selling_price) / $packages_price * 100);
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
                                    <?php if ($discount > 0) : ?>
                                        <div class="trm_trip_discount">
                                            <span><?php echo $discount ?>% Off</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="trm_price_holder">
                                        <span class="trm_actual_price">$<?php echo esc_html($packages_selling_price) ?></span>
                                        <span class="trm_striked_price">$<?php echo esc_html($packages_price) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="trm_trip_desc">
                                <?php echo esc_html(wp_trim_words($description, 100, '...')); ?>
                            </div>
                        </div>

                    </div>
                    <div class="trm_trip_aval_time">
                        <?php
                        $trip_availability = Arr::get($trip_details, 'general.cut_time', []);
                        echo (new TripAvailability())->render($trip_availability);
                        ?>
                        <a href="<?php echo site_url($trip_details_page); ?>?id=<?php echo $id ?>" class="trm_view_btn">Trip Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
<?php
    }
}
