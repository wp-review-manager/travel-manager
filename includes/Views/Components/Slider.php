<?php
namespace WPTravelManager\Views\Components;

class Slider {
    public static function RenderSlider() {
        $slider = '
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
        </div>';

        return $slider;
    }
}