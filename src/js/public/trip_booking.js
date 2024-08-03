const tripBooking = ($) => {
    let booking_data = {};

    $('.tm_calender_date').click(function() {
        booking_data.booking_date_selected = $(this).data('tm_date');
        if (booking_data.booking_date_selected) {
            $('[data-tab="package_type"]').removeClass('disabled');
        }
    });

    $('.tm_dec_btn, .tm_inc_btn').click(function() {
        let $parent = $(this).parent();
        let $this = $(this);
        let $quantity = $parent.find('.tm_quantity');
        let max = parseInt($parent.find('.tm_inc_btn').data('tm_max'));
        let min = parseInt($parent.find('.tm_dec_btn').data('tm_min'));
        let tm_travelers_number = parseInt($quantity.val());
        let package_price_total = parseInt($quantity.data('tm_package_price'));
        let package_id = $quantity.data('tm_travelers_number');
        let package_type = $quantity.data('tm_package_type');
        let package_name = $quantity.data('tm_package_name');
        let pricing_label = $quantity.data('tm_pricing_label');

        let isIncrement = $(this).hasClass('tm_inc_btn');

        if (isIncrement) {
            if (tm_travelers_number < max) {
                tm_travelers_number = Math.max(tm_travelers_number + 1, min);
            }
        } else {
            tm_travelers_number = Math.max(tm_travelers_number - 1, 0);
            tm_travelers_number = tm_travelers_number < min ? 0 : tm_travelers_number;
        }
        $quantity.val(tm_travelers_number);
        $parent.find('.tm_dec_btn').toggleClass('disabled', tm_travelers_number < min);
        $parent.find('.tm_inc_btn').toggleClass('disabled', tm_travelers_number >= max);

        if (package_type === 'person') {
            package_price_total *= tm_travelers_number;
        }
        booking_data.trip_title = $quantity.data('tm_trip_title');
        booking_data.packages = booking_data.packages || [];
        booking_data.packages[package_id] = booking_data.packages[package_id] || {};
        
        let packagesArray = Object.keys(booking_data.packages);
        
        packagesArray.forEach((package_id) => {
            if(booking_data.packages[package_id].package_name != package_name) {
                delete booking_data.packages[package_id];
            }
        });

        booking_data.packages[package_id] = {
            tm_travelers_number,
            tm_package_price_total: package_price_total,
            package_name,
            pricing_label,
            package_type
        };

        // console.log({booking_data});

        renderBookingData(booking_data, $this, $);
    });

    $(document).on('click', '#tm_trip_booking_btn', function() {
        makeApiCallForBooking(booking_data);
    });

    $('.tm_close').click(function() {
        booking_data = {};
        resetHtmlDom($);
    });
}

const renderBookingData = (booking_data = {}, $this, $) => {
    // console.log(booking_data);
    const { booking_date_selected = "", packages = [], trip_title = "" } = booking_data;
    let total_price = 0;
    let total_travelers = 0;
    let booking_data_html = '';

    booking_data_html +=
    `<div class="tm_trip_booking_summary_header">
        <h3 class="tm_booking_trip_title"> ${trip_title} </h3>
        <p class="tm_booking_trip_date"> Selected Date ${booking_date_selected} </p>
    </div>
    `
    booking_data_html += `
    <div class="tm_selected_package_lists">`
    for (let package_id in packages) {
        let package_data = packages[package_id];
        if (!package_data || package_data.tm_travelers_number == 0) {
            continue;
        }
        total_price += package_data.tm_package_price_total;
        total_travelers += package_data.tm_travelers_number;
        booking_data_html += `
        <ul>
            <li>
                <span class="bold">${package_data.tm_travelers_number} ${package_data.pricing_label} (${package_data.package_name})</span>
                <span> Per / ${package_data.package_type} </span>
                <span class="bold">${package_data.tm_package_price_total}</span>
            </li>
        </ul>`
    }
    booking_data_html += `</div>`;
    if(total_price) {
        booking_data_html +=
        `<div class="tm_selected_package_list_total">
            <ul>
                <li>
                    <span class="bold">${total_travelers}</span>
                    <span class="bold">Travelers</span>
                </li>
                <li>
                    <span class="bold">Total</span>
                    <span class="bold">${total_price}</span>
                </li>
        </div>`;

        booking_data_html += `<button id="tm_trip_booking_btn" class="tm_btn tm_btn_primary tm_btn_block">Book Now</button>`;
    }

    if(total_price == 0) {
        booking_data_html += `<p class="tm_no_package_selected">No package selected</p>`;
    }

    $($this).closest('.tm_modal-content').find('.tm_trip_booking_summary_body').html(booking_data_html);        
}

const resetHtmlDom = ($) => {
         $('.tm_trip_booking_summary_body').html('');
        $('.tm_trip_booking_summary_header').html('');
        $('.tm_trip_process_sidebar').find('.tm_trip_process_sidebar_item').removeClass('active');
        $('[data-tab="date"]').addClass('active');
        $('[data-tab="package_type"]').addClass('disabled');
        $('.tm_availability_tab_menu').find('.tab_item').removeClass('active');
        $('.tm_availability_tab_menu').find('.tab_item').first().addClass('active');
        $('.tm_tab_content').removeClass('active');
        $('.tm_check_availability_content').addClass('active');
}

const makeApiCallForBooking = (booking_data) => {
    let data = {
        action: 'trip_booking',
        booking_data
    };

    // $.ajax({
    //     url: tm_trip_booking.ajax_url,
    //     type: 'POST',
    //     data,
    //     success: function(response) {
    //         console.log(response);
    //     },
    //     error: function(error) {
    //         console.log(error);
    //     }
    // });
}

export { tripBooking };