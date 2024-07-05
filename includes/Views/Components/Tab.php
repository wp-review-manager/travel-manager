<?php

namespace WPTravelManager\Views\Components;
use WPTravelManager\Views\Components\Collapse;

class Tab
{
    public static function RenderTab()
    {
        ob_start();
        ?>
        <div class="tm_tab_container">
            <ul class="tm_tab_menu">
                <li class="tab active" data-tab="overview">OverView</li>
                <li class="tab" data-tab="itinerary">Itinerary</li>
                <li class="tab" data-tab="cost">cost</li>
                <li class="tab" data-tab="review">Review</li>
            </ul>
            <div class="tm_tab_content tm_overview_content active" id="overview">
                <div class="tm_section_title">
                    <h2>Overview</h2>
                </div>
                <div class="tm_overview_content">
                    <p>Travel is the movement of people between relatively distant geographical locations, and can involve travel by foot, bicycle, automobile, train, boat, bus, airplane, or other means, with or without luggage, and can be one way or round trip. Travel can also include relatively short stays between successive movements. The origin of the word "travel" is most likely lost to history. The term "travel" may originate from the Old French word travail, which means 'work'. According to the Merriam Webster dictionary, the first known use of the word travel was in the 14th century..</p>
                </div>
                <div class="tm_section_title">
                    <h2>Highlights</h2>
                </div>
                <div class="tm_overview_content">
                    <div class="tm_overview_highlights">
                        <span class="dashicons dashicons-saved"></span>
                        Travel is the movement
                    </div>

                    <div class="tm_overview_highlights">
                        <span class="dashicons dashicons-saved"></span>
                        Travel is the movement
                    </div>

                    <div class="tm_overview_highlights">
                        <span class="dashicons dashicons-saved"></span>
                        Travel is the movement
                    </div>

                    <div class="tm_overview_highlights">
                        <span class="dashicons dashicons-saved"></span>
                        Travel is the movement
                    </div>

                    <div class="tm_overview_highlights">
                        <span class="dashicons dashicons-saved"></span>
                        Travel is the movement
                    </div>
                </div>
            </div>
            <div class="tm_tab_content" id="itinerary">
                <?php echo Collapse::RenderCollapse(); ?>
            </div>
            <div class="tm_tab_content" id="cost">
                <p>This is the content of Tab 3.</p>
            </div>
            <div class="tm_tab_content" id="review">
                <p>This is the content of Tab review.</p>
            </div>
        </div';
        <?php

        $tab = ob_get_clean();

        return $tab;
    }
}
