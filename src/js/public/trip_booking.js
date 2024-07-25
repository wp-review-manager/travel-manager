const tripBooking = ($) => {
    let booking_data = {};

    $('.tm_calender_date').click(function() {
        booking_data.booking_date_selected = $(this).data('tm_date');
        if (booking_data.booking_date_selected) {
            $('[data-tab="package_type"]').removeClass('disabled');
        }
  
        $('.tm_dec_btn, .tm_inc_btn').click(function() {
            $(this).parent().find('.tm_dec_btn').removeClass('disabled');
            $(this).parent().find('.tm_inc_btn').removeClass('disabled');

            let max =                   parseInt($(this).parent().find('.tm_inc_btn').data('tm_max'));
            let min =                   parseInt($(this).parent().find('.tm_dec_btn').data('tm_min'));
            let tm_travelers_number =   parseInt($(this).parent().find('.tm_quantity').val());
            let package_price_total =   parseInt($(this).parent().find('.tm_quantity').data('tm_package_price'));
            let package_id =            $(this).parent().find('.tm_quantity').data('tm_travelers_number');
            let package_type =          $(this).parent().find('.tm_quantity').data('tm_package_type');
            let package_name =          $(this).parent().find('.tm_quantity').data('tm_package_name');
            let pricing_label =         $(this).parent().find('.tm_quantity').data('tm_pricing_label');

            let checkIncOrDec = $(this).hasClass('tm_inc_btn') ? 'inc' : 'dec';
            if (checkIncOrDec == 'inc') {
                if (tm_travelers_number < max) {
                    if (tm_travelers_number < min) {
                        $(this).parent().find('.tm_quantity').val(min);
                        tm_travelers_number = min;
                    } else {
                        $(this).parent().find('.tm_quantity').val(tm_travelers_number + 1);
                        tm_travelers_number = tm_travelers_number + 1;
                    }
                } else {
                    $(this).parent().find('.tm_inc_btn').addClass('disabled');
                }
            } else {
                if (tm_travelers_number - 1 < min || tm_travelers_number < 1) {
                    $(this).parent().find('.tm_quantity').val(0)
                    $(this).parent().find('.tm_dec_btn').addClass('disabled');
                    tm_travelers_number = 0;
                } else  {
                    $(this).parent().find('.tm_quantity').val(tm_travelers_number - 1);
                    tm_travelers_number = tm_travelers_number - 1;
                }
            }

            if (package_type == 'person') {
                package_price_total = package_price_total * tm_travelers_number;
            }

            booking_data.packages = booking_data.packages ? booking_data.packages : [];
            // let package_index = booking_data.packages.findIndex((package_data) => package_data.tm_package_name == package_name);
            // if (package_index > -1) {
                if (booking_data.packages[package_id] === undefined) {
                    // Initialize the package at package_id if it doesn't exist
                    booking_data.packages[package_id] = {};
                }
                booking_data.packages[package_id].tm_travelers_number = tm_travelers_number;
                booking_data.packages[package_id].tm_package_price_total = package_price_total;
                booking_data.packages[package_id].package_name = package_name;
                booking_data.packages[package_id].pricing_label = pricing_label;
            // }

            console.log({booking_data})
            // domRenderBookingSummary(packages, $, booking_date_selected)
        })
    });
}

// const calculateBookingSummary = (scope, $, packages = []) => {
//     let package_id = $(scope).data('tm_pricing_id');
//     package_id = package_id ? package_id : $(scope).data('tm_travelers_number');

//     const package_checked = $('input[data-tm_pricing_id="' + package_id + '"]:checked')?.length;

//     if (package_checked) {
//         let tm_travelers_number = $(`input[data-tm_travelers_number=${package_id}]`).val();
//         let tm_package_price = $(scope).data('tm_package_price');
//         let tm_package_type = $(scope).data('tm_package_type');
//         let tm_package_name = $(scope).data('tm_package_name');
//         let tm_pricing_label = $(scope).data('tm_pricing_label');
//         let tm_package_price_total = $(scope).data('tm_package_price');
//         if (tm_package_type == 'person') {
//             tm_package_price_total = tm_package_price * tm_travelers_number;
//         }

//         packages.push(
//             {
//                 tm_travelers_number,
//                 tm_package_price,
//                 tm_package_type,
//                 tm_package_name,
//                 tm_pricing_label,
//                 tm_package_price_total
//             }
//         )
//     }

//     return packages;
// }

// const domRenderBookingSummary = (packages = [], $, booking_date_selected) => {
//     let trip_title = $('.entry-title').text();
//     $('.tm_trip_booking_summary_body').empty();
//     $('.tm_trip_booking_summary_body').append(
//         `<h2 class="tm-booking-trip-title">${trip_title}</h2>
//         <p class="tm-booking-starting-date"><strong>Starting Date:</strong>${booking_date_selected}</p>
//         <p class="tm-booking-details-title">Travellers</p>
//         `
//     )
//     let $html = ""
//     let total_amount = 0;
//     packages?.map((package_data) => {
//         console.log(package_data)
//         total_amount += package_data.tm_package_price_total;
//         $html = $html + `<li>
//         <label><strong>${package_data.tm_travelers_number} ${package_data.tm_pricing_label}</strong> <span class="qty">(<span class="wpte-currency-code currency">$</span><strong class="wpte-price amount">${package_data.tm_package_price}</strong>/group) - <strong>Package ${package_data.tm_package_name}</strong></strong></strong></span></label>
//         <div class="amount-figure"><strong><span class="wpte-currency-code currency">$</span><strong class="wpte-price amount">${package_data.tm_package_price_total}</strong></strong></div>
//     </li>`
//     })

//     $('.tm_trip_booking_summary_body').append(
//         `<div class="tm_trip_booking_details"><ul>${$html}</ul></div>`
//     )
//     $('.tm_trip_booking_summary_body').append(
//         `<p class="tm_trip_booking_total">Total: ${total_amount}</li>`
//     )
// } 
export { tripBooking };