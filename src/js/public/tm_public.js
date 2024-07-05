import { sliderRender } from './components/slider.js';
import { TMtab } from './components/tab.js';
(function ($) {
    $(document).ready(function () {
        sliderRender($)
        TMtab($)
    });
})(jQuery);