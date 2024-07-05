<?php

namespace WPTravelManager\Views\Components;

class Tab
{
    public static function RenderTab()
    {
        $slider = '
        <div class="tm_tab_container">
            <ul class="tm_tab_menu">
                <li class="tab active" data-tab="overview">OverView</li>
                <li class="tab" data-tab="itinerary">Itinerary</li>
                <li class="tab" data-tab="cost">cost</li>
                <li class="tab" data-tab="review">Review</li>
            </ul>
            <div class="tm_tab_content active" id="overview">
                <p>This is the content of overview</p>
            </div>
            <div class="tm_tab_content" id="itinerary">
                <p>This is the content of itinerary 2.</p>
            </div>
            <div class="tm_tab_content" id="cost">
                <p>This is the content of Tab 3.</p>
            </div>
            <div class="tm_tab_content" id="review">
                <p>This is the content of Tab review.</p>
            </div>
        </div';

        return $slider;
    }
}
