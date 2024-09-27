$('#To').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    timePicker: false,
    locale: {
        format: 'DD-MM-YYYY'
    }
});
$('#From').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    timePicker: false,
    locale: {
        format: 'DD-MM-YYYY'
    }
});
$(document).ready(function (e) {
    attendanceAjaxList(1);
});

function attendanceAjaxList(page) {
    var from = $('#From').val();
    var to = $('#To').val();
    $.ajax({
        'method': 'get',
        'url': ajaxAttendance,
        data: {
            page: page,
            from: from,
            to: to,
            manager:manager,
        },
        success: function (res) {
            $('#attendance_test_table_ajax').html('');
            $('#attendance_test_table_ajax').html(res);

        },
    });
}
$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    attendanceAjaxList(page);
});