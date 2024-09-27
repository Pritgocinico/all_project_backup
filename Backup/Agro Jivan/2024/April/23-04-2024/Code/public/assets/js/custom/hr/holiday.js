$(document).ready(function(e){
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
            $('#hr_holiday_ajax').html('');
            $('#hr_holiday_ajax').html(res);
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
function addHoliday() {
    var date = $('#holiday_date').val();
    var name = $('#holiday_name').val();

    var cnt = 0;

    $('#holiday_date_error').html('');
    $('#holiday_name_error').html('');

    if (date.trim() == "") {
        $('#holiday_date_error').html('Please Select Holiday Date.');
        cnt = 1;
    }
    if (name.trim() == "") {
        $('#holiday_name_error').html('Please Enter Holiday Name.');
        cnt = 1;
    }

    if (cnt == 1) {
        return false;
    }
    $.ajax({
        'method': 'post',
        'url': create,
        data: {
            holiday_date: date,
            holiday_name: name,
            _token: token,
        },
        success: function(res) {
            toastr.success(res.message);
            $("#add_holiday_modal").modal('hide');
            $('#holiday_date').val("{{ date('Y-m-d') }}");
            $('#holiday_name').val('');
            holidayAjaxList(1)
        },
        error: function(error) {
            toastr.error(error.responseJSON.message)
        }
    });
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
    var search = $('#search_data').val();
    window.open(exportFile + '?format=' + format + '&search=' + search, '_blank');
    $('#kt_modal_export_users').modal("hide");
}