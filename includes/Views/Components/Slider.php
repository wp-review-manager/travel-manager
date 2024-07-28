<?php
namespace WPTravelManager\Views\Components;
use WPTravelManager\Classes\ArrayHelper as Arr;
class Slider {
    public static function RenderSlider($trip_gallery) {
        ob_start();
        $trip_gallery_image = Arr::get($trip_gallery, 'images', null);
        $trip_gallery_videos = Arr::get($trip_gallery, 'videos', null);
        $empty_image = TRM_URL . '/assets/images/360_F_473254957_bxG9yf4ly7OBO5I0O5KABlN930GwaMQz.jpg';
        $hasVideo = count($trip_gallery_videos) > 1;
        ?>
        <!-- Slider starts here -->
        <div class="tm_trip_slider">
      

        <div class="tm_trip_slider__container">
            <!-- there is some issue related with frontend , there always has a empty image path if actually has any image there length will be 2 -->
            <?php if (count($trip_gallery_image) < 2) : ?>
                <div class="tm_trip_slider__slide">
                    <img src="<?php echo $empty_image ?>" alt="image" class="tm_trip_gallery_item">
                </div>
            <?php else : ?>
            <?php foreach ($trip_gallery_image as $image) : ?>
                <?php
                    $image_url = Arr::get($image, 'url', null);
                    $image_id = Arr::get($image, 'id', null);
                    $image_name = Arr::get($image, 'name', null);
                ?>
                <?php if (!empty($image_url)) : ?>
                    <div class="tm_trip_slider__slide">
                        <img src="<?php echo esc_html($image_url); ?>" alt="<?php echo esc_html($image_name) . ' ' . esc_html($image_id) ; ?>">
                    </div>
                <?php endif; ?>

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
                <?php if($hasVideo) : ?>
                <button id="tm_video_gallery_btn">
                    <span class="dashicons dashicons-video-alt3"></span>
                    Video
                </button>
                <?php endif ?>
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
                    <img src="../.././../src/img/360_F_473254957_bxG9yf4ly7OBO5I0O5KABlN930GwaMQz.jpg" alt="image" class="tm_trip_gallery_item">
                </div>
            <?php else : ?>
                <?php foreach ($trip_gallery_image as $image) : ?>
                    <?php
                        $image_url = Arr::get($image, 'url', null);
                        $image_id = Arr::get($image, 'id', null);
                        $image_name = Arr::get($image, 'name', null);
                    ?>
                      <?php if (!empty($image_url)) : ?>
                            <img src="<?php echo esc_html($image_url); ?>" alt="<?php echo esc_html($image_name) . ' ' . esc_html($image_id) ; ?>" class="tm_trip_gallery_item">
                            <?php else : ?>
                                <img src="../.././../src/img/360_F_473254957_bxG9yf4ly7OBO5I0O5KABlN930GwaMQz.jpg" alt="image" >
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php if($hasVideo) : ?>
        <div class="tm_video_gallery_wrapper">
            <div id="tm_trip_video_gallery" class="tm_trip_video_gallery" style="display: none;">
                <?php foreach ($trip_gallery_videos as $index => $videos) : ?>
                    <?php
                        $video_url = Arr::get($videos, 'video_link', null);
                    ?>
                    <video class="tm_trip_video_gallery_item" data-index="<?php echo $index; ?>" data-src="<?php echo esc_html($video_url) ; ?>"></video>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        endif;
        $slider = ob_get_clean();

        return $slider;
    }
}