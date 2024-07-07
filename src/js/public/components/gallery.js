const galleryRender = ($) => {
    let currentIndex = 0;
    let currentSlide = 'image';
    const images = $(".tm_trip_gallery_item");
    const videos = $(".tm_trip_video_gallery_item");
    console.log({images}, {videos});

    $("#tm_trip_gallery_button").on("click", function() {
        $("#tm_trip_gallery").show();
        openLightbox(0, 'image');
    });

    $("#tm_video_gallery_btn").on("click", function() {
        currentSlide = 'video';
        openLightbox(0, 'video');
    });

    function openLightbox(index, type) {
        currentIndex = index;
        if (type === 'image') {
            const imageUrl = images.eq(index).attr("src");
            $("#tm_trip_lightbox_image").attr("src", imageUrl).show();
            $("#tm_trip_lightbox_video").hide();
        } else if (type === 'video') {
            const videoUrl = videos.eq(index).data("src");
            $("#tm_trip_lightbox_video").attr("src", videoUrl).show();
            $("#tm_trip_lightbox_image").hide();
        }
        $("#tm_trip_lightbox").show();
    }

    $(".tm_trip_close").on("click", function() {
        $("#tm_trip_lightbox").hide();
        $("#tm_trip_lightbox_video").attr("src", "");
        currentSlide = 'image';
    });

    $("#tm_trip_prev").on("click", function() {
        currentIndex = currentSlide === 'image' ? (currentIndex > 0 ? currentIndex - 1 : images.length - 1) : (currentIndex > 0 ? currentIndex - 1 : videos.length - 1);
        openLightbox(currentIndex, currentSlide);
    });

    $("#tm_trip_next").on("click", function() {
        currentIndex = currentSlide === 'image' ? (currentIndex < images.length - 1 ? currentIndex + 1 : 0) : (currentIndex < videos.length - 1 ? currentIndex + 1 : 0);
        openLightbox(currentIndex, currentSlide);
    });
}

export { galleryRender };