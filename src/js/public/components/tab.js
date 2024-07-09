const TMtab = ($) => {
    $('.tm_tab_menu .tab, .tm_availability_tab_menu .tab').on('click', function() {
        var tabId = $(this).data('tab');

        $(this).siblings().removeClass('active');
        $(this).parent().siblings().removeClass('active')

        $(this).addClass('active');
        $('#' + tabId).addClass('active');
    });
}

export { TMtab };