<?php
namespace WPTravelManager\Views\Components;

class Collapse {
    public static function RenderCollapse()
    {
        ob_start();
        ?>
         <div class="tm_collapse-container">
            <h1 class="tm_section_title">Itinerary</h1>
            <?php foreach (range(1, 5) as $i) : ?>
            <div class="tm_collapse">
                <div class="tm_collapse-btn">
                    <span class="dashicons dashicons-location"></span>
                    <p>Day : Arrive at Tribhuwan International Airport, Kathmandu</p>
                </div>
                <div class="tm_collapse-content">
                    <p>Content for Tab 1</p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}