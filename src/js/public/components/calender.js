const calenderRender = ($) => {
    const date = new Date();
    let month = date.getMonth();
    let year = date.getFullYear();

    let today = date.getDate();
    let currentMonth = date.getMonth();
    let currentYear = date.getFullYear();
    let startDate = `${currentYear}-${currentMonth + 1}-${today}`;
    let endDate = '2070-7-28';

    let enable_cut_off = $('#tm_openModal').data("enable_cut_off");
    let cut_of_start_date = $('#tm_openModal').data("cut_of_start_date");
    let cut_of_end_date = $('#tm_openModal').data("cut_of_end_date");

    if(enable_cut_off == 'yes') {
        console.log('enable_cut_off', );
        startDate = new Date(cut_of_start_date).getTime() > new Date(startDate).getTime() ? cut_of_start_date : startDate;
        endDate = cut_of_end_date ? cut_of_end_date : endDate;
    }

    const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    function renderCalendar(month, year) {

        const firstDay = (new Date(year, month)).getDay();
        const daysInMonth = 32 - new Date(year, month, 32).getDate();

        $('.tm_calendar_days').empty();
        $('#month-year').text(`${monthNames[month]} ${year}`);

        for (let i = 0; i < firstDay; i++) {
            $('.tm_calendar_days').append('<div></div>');
        }

        for (let i = 1; i <= daysInMonth; i++) {
            let date = `${year}-${month + 1}-${i}`;
            let dateInMini = new Date(date); dateInMini = dateInMini.getTime(); 
            let startDateMini = new Date(startDate); startDateMini = startDateMini.getTime();
            let endDateMini = new Date(endDate); endDateMini = endDateMini.getTime();
            if(startDateMini < dateInMini && dateInMini < endDateMini) {
                $('.tm_calendar_days').append(`<div class="tm_calender_date" data-tm_date=${date}>${i}</div>`);
            } else {
                $('.tm_calendar_days').append(`<div class="disabled">${i}</div>`);
            }
        }

        $('.tm_calendar_days div').click(function() {
            if ($(this).data('tm_date')) {
                $('.tm_calendar_days div').removeClass('active');
                $(this).addClass('active');
            }
        });
        
    }

    $('#prev').click(function () {
        month--;
        if (month < 0) {
            month = 11;
            year--;
        }
        renderCalendar(month, year);
    });

    $('#next').click(function () {
        month++;
        if (month > 11) {
            month = 0;
            year++;
        }
        renderCalendar(month, year);
    });

    renderCalendar(month, year);

    $('.tm_check_availability_continue_btn').click(function () {
        $('.tm_check_availability_content').removeClass('active');
        $('.tm_choose_package').addClass('active');
        $('[data-tab="check_availability"]').removeClass('active');
        $('[data-tab="package_type"]').addClass('active')
    })

}

export { calenderRender };