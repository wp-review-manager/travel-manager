<?php
namespace WPTravelManager\Views\Components;
use WPTravelManager\Classes\ArrayHelper as Arr;

class Collapse {
    public static function RenderCollapse($itinerary, $title = 'Itinerary' )
    {
        ob_start();
        $itineraries = Arr::get($itinerary, 'options', []);
        $itineraries_title = Arr::get($itinerary, 'title', null);
        ?>
         <div class="tm_collapse-container">
            <h1 class="tm_section_title"><?php echo esc_html($itineraries_title);    ?></h1>
           
            <?php foreach ($itineraries as $itinerary) : ?>
            <?php 
                $itinerary_title=  Arr::get($itinerary, 'title'); 
                $itinerary_description=  Arr::get($itinerary, 'description');
            ?>
            <div class="tm_collapse" >
                <div class="tm_collapse-btn">
                    <span class="dashicons dashicons-location"></span>
                    <p> <?php echo  esc_html($itinerary_title);  ?> </p>
                </div>
                <div class="tm_collapse-content">
                    <p> <?php echo  esc_html($itinerary_description);  ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}