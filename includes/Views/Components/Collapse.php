<?php
namespace WPTravelManager\Views\Components;
use WPTravelManager\Classes\ArrayHelper as Arr;

class Collapse {
    public static function RenderCollapse($itinerary, $title = 'Itinerary' )
    {
        ob_start();
        $itineraries = Arr::get($itinerary, 'options', []);
        ?>
         <div class="tm_collapse-container">
            <h1 class="tm_section_title"><?php echo htmlspecialchars ($itinerary['title']);  ?></h1>
            <?php foreach ($itineraries as $itinerary) : ?>
            <div class="tm_collapse" >
                <div class="tm_collapse-btn">
                    <span class="dashicons dashicons-location"></span>
                    <p> <?php echo htmlspecialchars ($itinerary['title']);  ?> </p>
                </div>
                <div class="tm_collapse-content">
                    <p> <?php echo htmlspecialchars ($itinerary['description']);  ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}