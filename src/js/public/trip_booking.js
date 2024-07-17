const tripBooking = ($) => {
    $('.tm_calender_date').click(function() {;
        let booking_date_selected = $(this).data('tm_date');
        if (booking_date_selected) {
            $('[data-tab="package_type"]').removeClass('disabled');
        }
        console.log('booking_date_selected', booking_date_selected);
    });
}
export { tripBooking };