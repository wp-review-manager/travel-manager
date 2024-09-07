import { sliderRender } from './components/slider.js';
import { TMtab } from './components/tab.js';
import { collapseRender } from './components/collapse.js';
import { galleryRender } from './components/gallery.js';
import { renderModal } from './components/modal.js';
import {calenderRender} from './components/calender.js';
import { submissionInquiry } from './submission_inquiry.js';
import { tripBooking } from './trip_booking.js';
import { submissionCheckout, applyCoupon, deleteCoupon } from './submission_checkout.js';



(function ($) {
    $(document).ready(function () {
        sliderRender($)
        TMtab($)
        collapseRender($)
        galleryRender($)
        renderModal($)
        calenderRender($)
        submissionInquiry($)
        tripBooking($)
        submissionCheckout($)
        applyCoupon($)
        deleteCoupon($)
    });
})(jQuery);