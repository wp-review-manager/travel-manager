const collapseRender = ($) => {
    $(".tm_collapse-btn").click(function() {
        // Toggle the content of the clicked tab
        $(this).next(".tm_collapse-content").slideToggle("fast");

        // Hide other tab contents
        $(".tm_collapse-content").not($(this).next()).slideUp("fast");
    });
}

export { collapseRender };