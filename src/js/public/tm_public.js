import { sliderRender } from './components/slider.js';
import { TMtab } from './components/tab.js';
import { collapseRender } from './components/collapse.js';
(function ($) {
    $(document).ready(function () {
        sliderRender($)
        TMtab($)
        collapseRender($)
    });
})(jQuery);