const sliderRender = ($) => {
    let currentSlide = 0;
    const slides = $('.tm_trip_slider__slide');
    const totalSlides = slides.length;

    function showSlide(index) {
        const newLeft = -index * 100 + '%';
        $('.tm_trip_slider__container').css('transform', 'translateX(' + newLeft + ')');
    }

    $('.tm_trip_slider__control--next').click(function () {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    });

    $('.tm_trip_slider__control--prev').click(function () {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        showSlide(currentSlide);
    });
}

export { sliderRender };