<?php
namespace WPTravelManager\Views\Components;

class Collapse {
    public static function RenderCollapse($title = 'Itinerary')
    {
        ob_start();
        ?>
         <div class="tm_collapse-container">
            <h1 class="tm_section_title"><?php echo $title ?></h1>
            <?php foreach (range(1, 5) as $i) : ?>
            <div class="tm_collapse">
                <div class="tm_collapse-btn">
                    <span class="dashicons dashicons-location"></span>
                    <p>Day <?php echo $i++ ?>: Arrive at Tribhuwan International Airport, Kathmandu</p>
                </div>
                <div class="tm_collapse-content">
                    <p>Arrive at Tribhuwan International Airport, Kathmandu, you are welcomed by the team and then you will be transferred to your hotel. This trail goes through Ghorepani Poon Hill. Normally, the trek starts like Pokhara to Nayapul and ends like Phedi to Pokhara. While early travel tended to be slower, more dangerous, and more dominated by trade and migration, cultural and technological advances over many years have tended to mean that travel has become easier and more accessible. The evolution of technology in such diverse fields as horse tack and bullet trains has contributed to this trend.</p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}