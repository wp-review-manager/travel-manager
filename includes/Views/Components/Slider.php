<?php
namespace WPTravelManager\Views\Components;
use WPTravelManager\Classes\ArrayHelper as Arr;
class Slider {
    public static function RenderSlider($trip_gallery) {
        ob_start();
        $trip_gallery_image = Arr::get($trip_gallery, 'images', null);
        $trip_gallery_videos = Arr::get($trip_gallery, 'videos', null);
        ?>
        <!-- Slider starts here -->
        <div class="tm_trip_slider">
      

        <div class="tm_trip_slider__container">
            <?php if (!$trip_gallery_image) : ?>
                <div class="tm_trip_slider__slide">
                    <img src="https://t4.ftcdn.net/jpg/04/73/25/49/360_F_473254957_bxG9yf4ly7OBO5I0O5KABlN930GwaMQz.jpg" alt="image">
                </div>
            <?php else : ?>
            <?php foreach ($trip_gallery_image as $image) : ?>
                <div class="tm_trip_slider__slide">
                    <img src="<?php echo htmlspecialchars($image['url']); ?>" alt="<?php echo htmlspecialchars($image['name']) . ' ' . htmlspecialchars($image['id']); ?>">
                </div>
            <?php endforeach; ?>
             <?php endif; ?>
         </div>


            <div class="tm_trip_slider__controls">
                <div class="tm_trip_slider__control tm_trip_slider__control--prev" aria-label="Previous slide">
                    <span class="dashicons dashicons-arrow-left-alt2"></span>
                </div>
                <div class="tm_trip_slider__control tm_trip_slider__control--next" aria-label="Next slide">
                    <span class="dashicons dashicons-arrow-right-alt2"></span>
                </div>
            </div>

            <div>
                <button id="tm_trip_gallery_button">
                    <span class="dashicons dashicons-format-gallery"></span>
                    Gallery
                </button>
                <button id="tm_video_gallery_btn">
                    <span class="dashicons dashicons-video-alt3"></span>
                    Video
                </button>
            </div>

        </div>

        <!-- Gallery starts here -->
        <div class="tm_gallery_wrapper">
            <div id="tm_trip_lightbox" class="tm_trip_lightbox">
                <span class="tm_trip_close">&times;</span>
                <div class="tm_trip_lightbox_content">
                    <img class="tm_trip_lightbox_image" id="tm_trip_lightbox_image" src="">
                    <div class="tm_trip_video_gallery">
                        <iframe class="tm_trip_lightbox_video" id="tm_trip_lightbox_video" width="100%" height="720" src="" allowfullscreen></iframe>
                    </div>
                    <a class="tm_trip_prev" id="tm_trip_prev">&#10094;</a>
                    <a class="tm_trip_next" id="tm_trip_next">&#10095;</a>
                </div>
            </div>

            <div style="display: none" id="tm_trip_gallery" class="tm_trip_gallery">
            <?php if (!$trip_gallery_image) : ?>
                <div class="tm_trip_slider__slide">
                    <img src="https://t4.ftcdn.net/jpg/04/73/25/49/360_F_473254957_bxG9yf4ly7OBO5I0O5KABlN930GwaMQz.jpg" alt="image" class="tm_trip_gallery_item">
                </div>
            <?php else : ?>
                <?php foreach ($trip_gallery_image as $image) : ?>
                        <img src="<?php echo htmlspecialchars($image['url']); ?>" alt="<?php echo htmlspecialchars($image['name']), htmlspecialchars($image['id']); ?>" class="tm_trip_gallery_item">
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="tm_video_gallery_wrapper">
            <div id="tm_trip_video_gallery" class="tm_trip_video_gallery" style="display: none;">
                <?php foreach ($trip_gallery_videos as $index => $videos) : ?>
                    <video class="tm_trip_video_gallery_item" data-index="<?php echo $index; ?>" data-src="<?php echo htmlspecialchars($videos['video_link']); ?>"></video>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        $slider = ob_get_clean();

        return $slider;
    }
}