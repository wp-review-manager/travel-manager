<?php

namespace WPTravelManager\Views\Components;
use WPTravelManager\Views\Components\Collapse;
use WPTravelManager\Classes\ArrayHelper as Arr;

class Tab
{
    public static function RenderTab($trip)
    {
        ob_start();
        $overview_title = Arr::get($trip, 'general.description.section_title', null);
        $overview_description = Arr::get($trip, 'general.description.description', null);
        $trip_highlights_title = Arr::get($trip, 'general.description.trip_highlights.title', null);
        $trip_highlights = Arr::get($trip, 'general.description.trip_highlights.options', null);
       
        $itinerary = Arr::get($trip, 'itinerary', []);

        $section_title = Arr::get($trip, 'inc_exc.section_title', []);
        $inc_title = Arr::get($trip, 'inc_exc.includes.title', []);
        $inc_option = Arr::get($trip, 'inc_exc.includes.services', []);
        $exc_title = Arr::get($trip, 'inc_exc.excludes.title', []);
        $exc_option = Arr::get($trip, 'inc_exc.excludes.services', []);
        
        $faqs = Arr::get($trip, 'faqs', []);

        $map_title = Arr::get($trip, 'map.title', []);
        // dd($map);
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
                    <h2><?php echo esc_html( $overview_title ) ?></h2>
                </div>
                <div class="tm_overview_content">
                    <p><?php echo esc_html( $overview_description ) ?></p>
                </div>
                <div class="tm_section_title">
                    <h2><?php echo esc_html( $trip_highlights_title ) ?></h2>
                </div>
                <div class="tm_overview_content">
                <?php foreach ($trip_highlights as $highlight) : ?>
                    <div class="tm_overview_highlights">
                        <span class="dashicons dashicons-saved"></span>
                        <?php echo htmlspecialchars ($highlight['label']); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="tm_tab_content" id="itinerary">
                <?php echo Collapse::RenderCollapse($itinerary); ?>
            </div>

            <div class="tm_tab_content" id="cost">
                <div class="tm_trip_cost_section">
                    <h1 class="tm_section_title"><?php echo esc_html( $section_title ) ?></h1>
                    <div class="tm_trip_included_section">
                        <h2 class="tm_sub_section_title"><?php echo esc_html( $inc_title ) ?></h2>
                        <div class="tm_trip_included_content">
                            <ul>
                                <?php foreach ($inc_option as $inc_option) : ?>
                                    <li> <span class="dashicons dashicons-saved"></span><?php echo esc_html( $inc_option ) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <h2 style="margin-top: 20px" class="tm_sub_section_title"><?php echo esc_html( $exc_title ) ?></h2>
                        <div class="tm_trip_exclude_content">
                            <ul>
                                <?php foreach ($exc_option as $exc_option) : ?>
                                    <li> <span class="dashicons dashicons-no-alt"></span><?php echo esc_html( $exc_option ) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>

            <div class="tm_tab_content" id="faqs">
                <?php echo CollapseFAQs::RenderFAQsCollapse($faqs); ?>
            </div>

            <div class="tm_tab_content" id="map">
            <h1 class="tm_section_title"><?php echo esc_html( $map_title ) ?></h1>
            <div style="width: 100%"><iframe width="100%" height="600" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=1%20Grafton%20Street,%20Dublin,%20Ireland+(My%20Business%20Name)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"><a href="https://www.gps.ie/">gps tracker sport</a></iframe></div>
            </div>
        </div>
        <?php

        $tab = ob_get_clean();

        return $tab;
    }
}
