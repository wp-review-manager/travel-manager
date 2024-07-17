const TMtab = ($) => {
    $('.tm_tab_menu .tab, .tm_availability_tab_menu .tab, .tm_check_availability_continue_btn').on('click', function() {
        var tabId = $(this).data('tab');

        $(this).siblings().removeClass('active');
        $(this).parent().siblings().removeClass('active')

        $(this).addClass('active');
        $('#' + tabId).addClass('active');
    });

    $('.tm_package_button').on('click', function() {
        var tabId = $(this).attr('id');
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
        $('.tm_package_details').removeClass('active');
        $('.tm_package_details').each(function() {
            if ($(this).attr('id') === tabId) {
                $(this).addClass('active');
            }
        })
        
    });
}

export { TMtab };