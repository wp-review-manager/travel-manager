<?php
namespace WPTravelManager\Views\Components;

class Slider {
    public static function RenderSlider() {
        ob_start();
        ?>
        <!-- Slider starts here -->
        <div class="tm_trip_slider">
            <div class="tm_trip_slider__container">
                <div class="tm_trip_slider__slide">
                    <img src="https://st2.depositphotos.com/4695029/7141/i/450/depositphotos_71419053-stock-photo-beautiful-swimming-pool.jpg" alt="Slide 1">
                </div>
                <div class="tm_trip_slider__slide">
                    <img src="https://img.freepik.com/free-photo/abstract-autumn-beauty-multi-colored-leaf-vein-pattern-generated-by-ai_188544-9871.jpg" alt="Slide 2">
                </div>
                <div class="tm_trip_slider__slide">
                    <img src="https://img.freepik.com/free-photo/house-landscape-pool-relaxation-garden_1203-4900.jpg" alt="Slide 3">
                </div>
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
                <img src="https://img.freepik.com/free-photo/abstract-autumn-beauty-multi-colored-leaf-vein-pattern-generated-by-ai_188544-9871.jpg" class="tm_trip_gallery_item" data-index="0">
                <img src="https://img.freepik.com/free-photo/house-landscape-pool-relaxation-garden_1203-4900.jpg" class="tm_trip_gallery_item" data-index="1">
                <img src="image3.jpg" class="tm_trip_gallery_item" data-index="2">
            </div>
        </div>

        <div class="tm_video_gallery_wrapper">
            <div id="tm_trip_video_gallery" class="tm_trip_video_gallery" style="display: none;">
                <video class="tm_trip_video_gallery_item" data-index="0" data-src="https://www.youtube.com/embed/tgbNymZ7vqY"></video>
                <video class="tm_trip_video_gallery_item" data-index="1" data-src="https://youtu.be/PHftxFVicdQ?si=z0gC_eHWuuTqTHtO"></video>
                <video class="tm_trip_video_gallery_item" data-index="2" data-src="video3.mp4"></video>
            </div>
        </div>
        <?php
        $slider = ob_get_clean();

        return $slider;
    }
}