<?php
namespace WPTravelManager\Views\Components;

class CheckAvailability
{
    public static function RenderCheckAvailability()
    {
        ob_start();
        ?>
           <button id="tm_openModal">Open Modal</button>
           <div id="tm_modal" class="tm_modal">
                <div class="tm_modal-content">
                    <span id="tm_close" class="tm_close">&times;</span>
                    <p>This is a modal!</p>
                </div>
            </div>
        <?php
        return ob_get_clean();
    }
}