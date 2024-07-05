<?php
namespace WPTravelManager\Views\Preview;
use WPTravelManager\Views\Components\Slider;
use WPTravelManager\Views\Components\Tab;
?>
<div class="tm_trip_one_shortcode_preview_wrapper">
    <main class="tm_trip_main">
        <article>
            <div class="entry-header">
                <h1 class="entry-title"><?php echo esc_html($title); ?></h1>
                <span class="wte-title-duration">
                    <p class="duration"> 11 </p>
                    <p class="days"> Days </p>
                </span>
            </div>
            
            <div class="tm_gallery_wrapper">
                <?php echo Slider::RenderSlider(); ?>
            </div>

            <div class="tm_trip_entry_content">
                <div class="tm_secondary_trip_info">
                    <?php foreach (range(1,10) as $i) : ?>
                    <div class="tm_trip_price">
                        <div class="tm_info_label">
                            <span class="dashicons dashicons-schedule"></span>
                            <span class="tm_label">Accomodation</span>
                        </div>
                        <span class="tm_price_value">All meals during the trek</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php echo Tab::RenderTab(); ?>
        </article>
    </main>
    <div class="tm_trip_sidebar">
        sidebar
    </div>
</div>