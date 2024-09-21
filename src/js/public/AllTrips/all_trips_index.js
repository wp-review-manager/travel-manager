(function ($) {
    'use strict';
    $(document).ready(function () {
        $('.trm_all_trips_pag').on('click', function () {
            if (!$(this).hasClass('trm_pag_disabled')) {
                const page = paginate($, $(this));
                getAllTripsAjax($, page);
            }
        });
    });
})(jQuery);

function paginate($, $this) {
    const active_page = $('.trm_pag_active');
    let page = $this.data('trm_page_no');

    if (page === 'prev') {
        page = active_page.data('trm_page_no') - 1;
        if (page < 1) return page; // Prevent going below page 1
    }

    if (page === 'next') {
        page = active_page.data('trm_page_no') + 1;
        const total_pages = $('.trm_all_trips_pag').length - 2; // Exclude prev/next
        if (page > total_pages) return page; // Prevent exceeding total pages
    }

    // Update active page class
    active_page.removeClass('trm_pag_active');
    $(`.trm_all_trips_pag[data-trm_page_no="${page}"]`).addClass('trm_pag_active');

    // Handle prev/next button disable states
    if (page === 1) {
        $('.trm_pag_prev').addClass('trm_pag_disabled');
    } else {
        $('.trm_pag_prev').removeClass('trm_pag_disabled');
    }

    const total_pages = $('.trm_all_trips_pag').length - 2;
    if (page === total_pages) {
        $('.trm_pag_next').addClass('trm_pag_disabled');
    } else {
        $('.trm_pag_next').removeClass('trm_pag_disabled');
    }
    return page;
}

function getAllTripsAjax($, page) {
    $.post(window.trm_public.ajax_url, {
        action: 'tm_trips',
        page: page,
        per_page: 2,
        route: 'get_trips',
        response_type: 'json',
        tm_admin_nonce: window.trm_public.tm_public_nonce
    })
    .done(response => {
        if (response.success) {
            console.log(response.data); // Handle the success case (display the trips)
            $('.trm_category_trips_wrapper').empty();
            $('.trm_category_trips_wrapper').html(response.data);
        } else {
            console.error('Error:', response.data.message);
        }
    })
    .fail(error => {
        console.error('Error:', error);
    });
}
