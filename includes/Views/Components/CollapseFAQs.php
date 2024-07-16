<?php
namespace WPTravelManager\Views\Components;
use WPTravelManager\Classes\ArrayHelper as Arr;

class CollapseFAQs {
    public static function RenderFAQsCollapse($faqs, $title = 'Itinerary' )
    {
        ob_start();
        $faqs_option = Arr::get($faqs, 'options', []);
        $faqs_title = Arr::get($faqs, 'title', null);
        ?>
         <div class="tm_collapse-container">
            <h1 class="tm_section_title"><?php echo esc_html($faqs_title);  ?></h1>
            <?php foreach ($faqs_option as $faqs_option) : ?>
                <?php 
                $faqs_option_title=  Arr::get($faqs_option, 'title'); 
                $faqs_option_description=  Arr::get($faqs_option, 'description');
            ?>
            <div class="tm_collapse" >
                <div class="tm_collapse-btn">
                    <span class="dashicons dashicons-location"></span>
                    <p> <?php echo esc_html($faqs_option_title) ;  ?> </p>
                </div>
                <div class="tm_collapse-content">
                    <p> <?php echo  esc_html($faqs_option_description) ;  ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}