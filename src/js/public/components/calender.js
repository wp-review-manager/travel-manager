const calenderRender = ($) => {
    const date = new Date();
    let month = date.getMonth();
    let year = date.getFullYear();

    const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    function renderCalendar(month, year) {
        console.log('renderCalendar', month, year);
        const firstDay = (new Date(year, month)).getDay();
        const daysInMonth = 32 - new Date(year, month, 32).getDate();

        $('.tm_calendar_days').empty();
        $('#month-year').text(`${monthNames[month]} ${year}`);

        for (let i = 0; i < firstDay; i++) {
            $('.tm_calendar_days').append('<div></div>');
        }

        for (let i = 1; i <= daysInMonth; i++) {
            let date = `${year}-${month + 1}-${i}`;
            $('.tm_calendar_days').append(`<div data-tm_date=${date}>${i}</div>`);
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

}

export { calenderRender };