if (role_id == 1) {
    window.setInterval(function () {
        var date = new Date();
        $.ajax({
            type: 'GET',
            url: CheckAttendanceUrl,
            success: function (data) { },
            error: function (data) { }
        });
    }, 60 * 1000);
}

// initilize jquery tootip
$(function () {
    $('[data-bs-toggle="tooltip"]').tooltip()
})
$(function () {
    $('.search_order_date').daterangepicker({
        autoUpdateInput: false,
        maxDate: moment(),
    }, function (start, end, label) {
        $('.search_order_date').val(start.format('Y-MM-DD') + '/' + end.format('Y-MM-DD'));
    });
});
$(document).on('click', '.take_break', function () {
    $.ajax({
        type: 'GET',
        url: breakTime,
        success: function (data) {
            $('#staticBackdrop').modal('show');
        },
        error: function (data) {
        }
    });
});



// ------------filter div --------------

        jQuery('.filter-menu').hide()
            jQuery('.order_filter_option').on('click',function(){
            jQuery('.filter-menu').toggle();
        })

        $(document).mouseup(function(e) {
            var container = $('.filter-menu .daterangepicker');
            if (!container.is(e.target) && container.has(e.target).length === 0)
            {
            container.hide();
            }
        });



$('.close-btn-filter').on('click', function () {
    $('.custom-close').hide();
});
        