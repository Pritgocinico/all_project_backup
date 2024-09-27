$(document).ready(function (e) {
    infoAjaxList(1);
});

function infoAjaxList(page) {
    var search = $('#search_data').val();
    var date = $('#order_date').val();
    $.ajax({
        'method': 'get',
        'url': ajaxList,
        data: {
            page: page,
            search: search,
            date: date,
        },
        success: function (res) {
            $('#info_sheet_ajax_list_table').html('');
            $('#info_sheet_ajax_list_table').html(res);

        },
    });
}
$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    infoAjaxList(page);
});
$(function () {
    $('.search_info_sheet_date').daterangepicker({
        autoUpdateInput: false,
        maxDate: moment(),
    }, function (start, end, label) {
        $('.search_info_sheet_date').val(start.format('Y-0M-0D') + '/' + end.format('Y-0M-0D'));
    });
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
    var date = $('#order_date').val();
    var search = $('#search_data').val();
    window.open(exportFile + '?format=' + format + '&search=' + search+'&date='+date , '_blank');
}

function deleteInfoSheet(id){
    var url = deleteUrl.replace('id', id)
    new swal({
        title: 'Are you sure delete this Info Sheet?',
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes Delete it!'
    }).then(function (isConfirm) {
        if (isConfirm.isConfirmed) {
            $.ajax({
                method: "DELETE",
                url: url,
                data: {
                    _token: token
                },
                success: function (res) {
                    toastr.success(res.message);
                    infoAjaxList(1);
                },
                error: function (error) {
                    toastr.error(error.responseJSON.message);
                }
            })
        }
    });   
}
function resetSearch(){
    $('#search_data').val('');
    $('#order_date').val('');
    infoAjaxList(1);
}