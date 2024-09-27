$(document).ready(function (e) {    
    employeeOrderAjaxList(1);
});

function employeeOrderAjaxList(page) {
    var search = $('#search_data').val();
    var district = $('#order_district').val();
    var date = $('#order_date').val();
    var date = $('#order_date').val();
    var order_sub_district = $('#order_sub_district').val();
    $.ajax({
        'method': 'get',
        'url': pendingOrderList,
        data: {
            page: page,
            search: search,
            district: district,
            order_sub_district: order_sub_district,
            date: date,
        },
        success: function (res) {
            $('#pending_order_table_ajax').html('');
            $('#pending_order_table_ajax').html(res);
            $('[data-bs-toggle="tooltip"]').tooltip()
        },
    });
}
function resetSearch(){
    $('#search_data').val('');
    $('#order_district').val('');
    $('#order_date').val('');
    $('#order_sub_district').val('');
    employeeOrderAjaxList(1);
}
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
    var format = $('#export_format').val();
    var search = $('#search_data').val();
    var district = $('#order_district').val();
    var date = $('#order_date').val();
    window.open(exportFile + '?format=' + format + '&search=' + search +'&district=' + district + '&date=' + date+'&type=pending', '_blank');
}
$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    employeeOrderAjaxList(page);
});
function dateValueSet(){
    $('#order_date').val(date);
}