$(document).ready(function (e) {
    holidayAjaxList(1);
});

function holidayAjaxList(page) {
    var search = $('#search_data').val();
    $.ajax({
        'method': 'get',
        'url': ajaxList,
        data: {
            page: page,
            search: search,
        },
        success: function (res) {
            $('#holiday_table_ajax').html('');
            $('#holiday_table_ajax').html(res);

        },
    });
}
$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    holidayAjaxList(page);
});
function exportCSV(){
    var format = $('#export_format').val();
    var cnt = 0;
    $('#export_format_error').html('')
    if(format == ""){
        $('#export_format_error').html('Please Select Export Format.');
        cnt = 1;
    }
    if(cnt == 1){
        return false;
    }
    var search = $('#search_data').val();
    window.open(exportFile + '?format=' + format + '&search=' + search, '_blank');
}
