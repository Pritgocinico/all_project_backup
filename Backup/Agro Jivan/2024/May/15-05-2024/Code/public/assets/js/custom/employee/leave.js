$(document).ready(function (e) {
    leaveAjax(1);
});
function resetSearch(){
    $('#search_data').val('');
    $('#order_date').val('');
    $('#leave_feature').val('');
    $('#leave_status').val('');
    leaveAjax(1)
}
function leaveAjax(page) {
    var search = $('#search_data').val();
    var date = $('#order_date').val();
    var feature = $('#leave_feature').val();
    var status = $('#leave_status').val();
    $.ajax({
        method: 'get',
        url: ajax,
        data: {
            search: search,
            page: page,
            date: date,
            feature: feature,
            status: status,
        }, success: function (res) {
            $('#leave_data_table').html('');
            $('#leave_data_table').html(res);
            $('[data-bs-toggle="tooltip"]').tooltip()
        }
    })
}
$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    leaveAjax(page);
});
function minDateTo() {
    var date = $('#leave_from').val();
    $('#leave_to').val(date);
    document.getElementById("leave_to").setAttribute("min", date);
    $('#leave_feature_div').removeClass('d-none')
    if(date == new moment().format('YYYY-MM-DD')){
        $('#leave_feature_div').addClass('d-none')
    }
}

function addLeave() {
    var type = $('#leave_type').val();
    var from = $('#leave_from').val();
    var to = $('#leave_to').val();
    var reason = $('#reason').val();
    var feature = $('#leave_feature').val();
    var cnt = 0;

    $('#leave_type_error').html('');
    $('#leave_from_error').html('');
    $('#leave_to_error').html('');
    $('#reason_error').html('');
    $('#leave_feature_error').html('');

    if (type == "") {
        $('#leave_type_error').html('Please Select Leave Type.');
        cnt = 1;
    }
    if (from == "") {
        $('#leave_from_error').html('Please Select Leave From.');
        cnt = 1;
    }
    if (to == "") {
        $('#leave_to_error').html('Please Select Leave To.');
        cnt = 1;
    }
    if (reason.trim() == "") {
        $('#reason_error').html('Please Enter Leave Reason.');
        cnt = 1;
    }

    if (cnt == 1) {
        return false;
    }

    $.ajax({
        'method': 'post',
        'url': storeURL,
        data: {
            leave_type: type,
            leave_from: from,
            leave_to: to,
            reason: reason,
            leave_feature: feature,
            _token: token,
        }, success: function (res) {
            toastr.success(res.message);
            $("#add_leave_modal").modal('hide');
            $('#leave_type').val("");
            $('#leave_from').val("");
            $('#leave_to').val("");
            $('#reason').val("");
            $('#leave_feature').val("");
            leaveAjax(1)
        }, error: function (error) {
            toastr.error(error.responseJSON.message)
        }
    });
}

function editLeave(id){
    var url = edit.replace('id', id);
    $.ajax({
        method: 'get',
        url: url,
        success: function (res) {
            $('#id').val(res.data.id);
            $('#edit_leave_type').val(res.data.leave_type);
            $('#edit_leave_from').val(res.data.leave_from);
            $('#edit_leave_to').val(res.data.leave_to);
            $('#edit_reason').val(res.data.reason);
            $('#edit_leave_feature').val(res.data.leave_feature);
            $("#status").removeAttr("checked");
            if (res.data.status == 1) {
                $('#status').attr('checked', 'true')
            }
        $('#edit_leave_modal').modal('show');

        }
    }) 
}

function updateLeave(){
    var type = $('#edit_leave_type').val();
    var from = $('#edit_leave_from').val();
    var to = $('#edit_leave_to').val();
    var reason = $('#edit_reason').val();
    var feature = $('#edit_leave_feature').val();
    var id = $('#id').val();
    var cnt = 0;

    $('#edit_leave_type_error').html('');
    $('#edit_leave_from_error').html('');
    $('#edit_leave_to_error').html('');
    $('#edit_reason_error').html('');
    $('#edit_leave_feature_error').html('');

    if (type == "") {
        $('#edit_leave_type_error').html('Please Select Leave Type.');
        cnt = 1;
    }
    if (from == "") {
        $('#edit_leave_from_error').html('Please Select Leave From.');
        cnt = 1;
    }
    if (to == "") {
        $('#edit_leave_to_error').html('Please Select Leave To.');
        cnt = 1;
    }
    if (reason.trim() == "") {
        $('#edit_reason_error').html('Please Enter Leave Reason.');
        cnt = 1;
    }
    if (feature == "") {
        $('#edit_leave_feature_error').html('Please Select Leave Feature.');
        cnt = 1;
    }

    if (cnt == 1) {
        return false;
    }
    var status = "on";
    if (!$('#status').is(':checked')) {
        status = "off";
    }
    $.ajax({
        'method': 'PUT',
        'url': update.replace('id', id),
        data: {
            leave_type: type,
            leave_from: from,
            leave_to: to,
            reason: reason,
            leave_feature: feature,
            status: status,
            _token: token,
        }, success: function (res) {
            toastr.success(res.message);
            $('#edit_leave_modal').modal('hide');
            leaveAjax(1);
        }, error: function (error) {
            toastr.error(error.responseJSON.message)
        }
    });
}

function deleteLeave(id) {
    new swal({
        title: 'Are you sure delete this Leave?',
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes Delete it!'
    }).then(function (isConfirm) {
        if (isConfirm.isConfirmed) {
            $.ajax({
                method: "DELETE",
                url: deleteURL.replace('id', id),
                data: {
                    _token: token
                },
                success: function (res) {
                    toastr.success(res.message);
                    leaveAjax(1);
                },
                error: function (error) {
                    toastr.error(error.responseJSON.message);
                }
            })
        }
    });
}
$(function () {
    $('.search_leave_date').daterangepicker({
        autoUpdateInput: false,
        
    }, function (start, end, label) {
        $('.search_leave_date').val(start.format('Y-MM-DD') + '/' + end.format('Y-MM-DD'));
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
    var search = $('#search_data').val();
    var date = $('#order_date').val();
    var feature = $('#leave_feature').val();
    var status = $('#leave_status').val();
    window.open(exportFile + '?format=' + format + '&search=' + search+'&date='+date + '&feature=' +feature+ '&status='+status, '_blank');
}