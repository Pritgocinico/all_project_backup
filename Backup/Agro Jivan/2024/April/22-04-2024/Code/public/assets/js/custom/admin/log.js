$(document).ready(function (e){
    logAjax(1);
});
function resetForm(){
    $('#search_data').val('')
    $('#search_date').val(date)
    logAjax(1);
}
function logAjax(page) {
    var search = $('#search_data').val();
    var date = $('#search_date').val();
    $.ajax({
        'method': 'get',
        'url': ajaxList,
        data: {
            page: page,
            search: search,
            search_date: date,
        },
        success: function (res) {
            $('#log_table_ajax').html('');
            $('#log_table_ajax').html(res);
        },
    });
}
$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    logAjax(page);
});
$(function() {
    $('.search_date').daterangepicker({
        autoUpdateInput: false,
        maxDate: moment(),
        startDate: moment().startOf('month'),
        endDate: moment().endOf('month'),
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,'month').endOf('month')]
        }
    }, function(start, end, label) {
        $('.search_date').val(start.format('Y-MM-DD') + '/' + end.format('Y-MM-DD'));
    });
});