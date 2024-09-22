(function ($) {
    'use strict';
    let selected_page = 1;
    let sortData = {
        sortBy: 'post_modified',
        order: 'DESC'
    }
    $(document).ready(function () {
        $('.trm_all_trips_pag').on('click', handlePagination);
        
        $('#trip_sort_by').on('change', function () {
            const sortByFormData = $(this).val();
            if (sortByFormData === 'latest') return;

            const sortByMapping = {
                'price_desc': { sortBy: 'price', order: 'DESC' },
                'price_asc': { sortBy: 'price', order: 'ASC' },
                'name_asc': { sortBy: 'post_title', order: 'ASC' },
                'name_desc': { sortBy: 'post_title', order: 'DESC' },
                'departure_dates': { sortBy: 'duration', order: 'ASC' },
            };
            const { sortBy = 'post_modified', order = 'DESC' } = sortByMapping[sortByFormData] || {};
            sortData = { sortBy, order };
            getAllTripsAjax(selected_page, sortData);
        });
    });
    

    function handlePagination() {
        if ($(this).hasClass('trm_pag_disabled')) return;

        selected_page = paginate($(this));
        if (selected_page) getAllTripsAjax(selected_page, sortData);
    }

    function paginate($this) {
        const activePage = $('.trm_pag_active');
        let page = $this.data('trm_page_no');

        const totalPages = $('.trm_all_trips_pag').length - 2; // Exclude prev/next

        if (page === 'prev') {
            page = Math.max(1, activePage.data('trm_page_no') - 1); // Prevent going below 1
        } else if (page === 'next') {
            page = Math.min(totalPages, activePage.data('trm_page_no') + 1); // Prevent exceeding total pages
        }

        if (!page || page === activePage.data('trm_page_no')) return false; // Return early if no page change

        // Update active page class
        activePage.removeClass('trm_pag_active');
        $(`.trm_all_trips_pag[data-trm_page_no="${page}"]`).addClass('trm_pag_active');

        // Toggle prev/next button states
        $('.trm_pag_prev').toggleClass('trm_pag_disabled', page === 1);
        $('.trm_pag_next').toggleClass('trm_pag_disabled', page === totalPages);

        return page;
    }

    function getAllTripsAjax(page, sortData) {
        $.post(window.trm_public.ajax_url, {
            action: 'tm_trips',
            page: page,
            sortData: sortData,
            per_page: 2,
            route: 'get_trips_with_details',
            response_type: 'json',
            tm_admin_nonce: window.trm_public.tm_public_nonce
        })
        .done(response => {
            if (response.success) {
                $('.trm_category_trips_wrapper').empty().html(response.data);
            } else {
                console.error('Error:', response.data.message);
            }
        })
        .fail(error => console.error('Error:', error));
    }

})(jQuery);