import { sliderRender } from './components/slider.js';
import { TMtab } from './components/tab.js';
import { collapseRender } from './components/collapse.js';
import { galleryRender } from './components/gallery.js';
(function ($) {
    $(document).ready(function () {
        sliderRender($)
        TMtab($)
        collapseRender($)
        galleryRender($)
    });
})(jQuery);