<?php
namespace WPTravelManager\Views\Components;
use WPTravelManager\Classes\ArrayHelper as Arr;

class CollapseFAQs {
    public static function RenderFAQsCollapse($faqs, $title = 'Itinerary' )
    {
        ob_start();
        $faqs_option = Arr::get($faqs, 'options', []);
        ?>
         <div class="tm_collapse-container">
            <h1 class="tm_section_title"><?php echo htmlspecialchars ($faqs['title']);  ?></h1>
            <?php foreach ($faqs_option as $faqs_option) : ?>
            <div class="tm_collapse" >
                <div class="tm_collapse-btn">
                    <span class="dashicons dashicons-location"></span>
                    <p> <?php echo htmlspecialchars ($faqs_option['title']);  ?> </p>
                </div>
                <div class="tm_collapse-content">
                    <p> <?php echo htmlspecialchars ($faqs_option['description']);  ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}