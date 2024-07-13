<?php

namespace WPTravelManager\Views\Components;
use WPTravelManager\Views\Components\Collapse;
use WPTravelManager\Classes\ArrayHelper as Arr;

class Tab
{
    public static function RenderTab($trip)
    {
        ob_start();
        $itenary = Arr::get($trip, 'itinerary.options.0.title', "nitesh");
   
        ?>
        <div class="tm_tab_container">
            <ul class="tm_tab_menu">
                <li class="tab active" data-tab="overview">OverView</li>
                <li class="tab" data-tab="itinerary">Itinerary</li>
                <li class="tab" data-tab="cost">Cost</li>
                <li class="tab" data-tab="faqs">FAQs</li>
                <li class="tab" data-tab="map">Map</li>
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
                <div class="tm_trip_cost_section">
                    <h1 class="tm_section_title">Cost</h1>
                    <div class="tm_trip_included_section">
                        <h2 class="tm_sub_section_title">The Cost Includes</h2>
                        <div class="tm_trip_included_content">
                            <ul>
                                <?php foreach (range(1,10) as $i) : ?>
                                    <li> <span class="dashicons dashicons-saved"></span>Pick-up or Drop-off service from and to Airport(in our own vehicle)</li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <h2 style="margin-top: 20px" class="tm_sub_section_title">The Cost Exclude</h2>
                        <div class="tm_trip_exclude_content">
                            <ul>
                                <?php foreach (range(1,10) as $i) : ?>
                                    <li> <span class="dashicons dashicons-no-alt"></span>Pick-up or Drop-off service from and to Airport(in our own vehicle)</li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>

            <div class="tm_tab_content" id="faqs">
                <?php echo Collapse::RenderCollapse('FAQs'); ?>
            </div>

            <div class="tm_tab_content" id="map">
            <div style="width: 100%"><iframe width="100%" height="600" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=1%20Grafton%20Street,%20Dublin,%20Ireland+(My%20Business%20Name)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"><a href="https://www.gps.ie/">gps tracker sport</a></iframe></div>
            </div>
        </div>
        <?php

        $tab = ob_get_clean();

        return $tab;
    }
}
