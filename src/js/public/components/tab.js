const TMtab = ($) => {
    $('.tm_tab_menu .tab').on('click', function() {
        var tabId = $(this).data('tab');

        $('.tm_tab_menu .tab').removeClass('active');
        $('.tm_tab_content').removeClass('active');

        $(this).addClass('active');
        $('#' + tabId).addClass('active');
    });
}

export { TMtab };