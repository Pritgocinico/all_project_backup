$(document).ready(function (e) {    
    employeeOrderAjaxList(1);
});

function employeeOrderAjaxList(page) {
    var search = $('#search_data').val();
    var order_district = $('#order_district').val();
    var order_sub_district = $('#order_sub_district').val();
    var date = $('#order_date').val();
    $.ajax({
        'method': 'get',
        'url': completedOrderList,
        data: {
            page: page,
            search: search,
            order_district: order_district,
            order_sub_district: order_sub_district,
            date: date,
        },
        success: function (res) {
            $('#completed_order_table_ajax').html('');
            $('#completed_order_table_ajax').html(res);
            $('[data-bs-toggle="tooltip"]').tooltip()
        },
    });
}
function resetSearch(){
    $('#search_data').val('');
    $('#order_district').val('');
    $('#order_date').val('');
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
    window.open(exportFile + '?format=' + format + '&search=' + search +'&district=' + district + '&date=' + date+'&type=complete', '_blank');
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