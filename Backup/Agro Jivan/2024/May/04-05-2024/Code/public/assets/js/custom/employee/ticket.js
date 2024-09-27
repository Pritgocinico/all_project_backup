$(document).ready(function (e) {
    ticketAjaxList(1);
});
function resetSearch(){
    $('#search_data').val('');
    $('#order_date').val('');
    ticketAjaxList(1);
}
function ticketAjaxList(page) {
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
            $('#ticket_data_table').html('');
            $('#ticket_data_table').html(res);
            $('[data-bs-toggle="tooltip"]').tooltip()

        },
    });
}
$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    ticketAjaxList(page);
});

function addTicket() {
    var type = $('#type').val();
    var subject = $('#subject').val();
    var description = $('#description').val();
    var cnt = 0;
    var emp_id = $('#emp_ids').val();

    $('#type_error').html('');
    $('#subject_error').html('');
    $('#description_error').html('');

    if (type == "") {
        $('#type_error').html('Please Select Ticket Type.');
        cnt = 1;
    }
    if (subject.trim() == "") {
        $('#subject_error').html('Please Enter Subject.');
        cnt = 1;
    }
    if (description.trim() == "") {
        $('#description_error').html('Please Enter Description.');
        cnt = 1;
    }

    if (cnt == 1) {
        return false;
    }

    $.ajax({
        'method': 'post',
        'url': storeURL,
        data: {
            type: type,
            subject: subject,
            description: description,
            emp_id: emp_id,
            _token: token,
        }, success: function (res) {
            toastr.success(res.message);
            $("#add_ticket_modal").modal('hide');
            $('#subject').val("");
            $('#description').val("");
            ticketAjaxList(1)
        }, error: function (error) {
            toastr.error(error.responseJSON.message)
        }
    });
}
function editTicket(id) {
    var url = edit.replace('id', id);
    $.ajax({
        method: 'get',
        url: url,
        success: function (res) {
            $('#id').val(res.data.id);
            $('#edit_type').val(res.data.ticket_type);
            $('#edit_subject').val(res.data.subject);
            $('#edit_description').val(res.data.description);
            $("#status").removeAttr("checked");
            if (res.data.status == 1) {
                $('#status').attr('checked', 'true')
            }
            $('#edit_ticket_modal').modal('show');

        }
    })
}
function updateTicket() {
    var id = $('#id').val();
    var type = $('#edit_type').val();
    var subject = $('#edit_subject').val();
    var description = $('#edit_description').val();
    var cnt = 0;

    $('#edit_type_error').html('');
    $('#edit_subject_error').html('');
    $('#edit_description_error').html('');

    if (type == "") {
        $('#type_error').html('Please Select Ticket Type.');
        cnt = 1;
    }
    if (subject.trim() == "") {
        $('#subject_error').html('Please Enter Subject.');
        cnt = 1;
    }
    if (description.trim() == "") {
        $('#description_error').html('Please Enter Description.');
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
            type: type,
            subject: subject,
            description: description,
            status:status,
            _token: token,
        }, success: function (res) {
            toastr.success(res.message);
            $('#edit_ticket_modal').modal('hide');
            ticketAjaxList(1);

        }, error: function (error) {
            toastr.error(error.responseJSON.message)
        }
    });
}

function deleteTicket(id) {
    new swal({
        title: 'Are you sure delete this Ticket?',
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
                    ticketAjaxList(1);
                },
                error: function (error) {
                    toastr.error(error.responseJSON.message);
                }
            })
        }
    });
}

$(function () {
    $('.search_ticket_date').daterangepicker({
        autoUpdateInput: false,
        maxDate: moment(),
    }, function (start, end, label) {
        $('.search_ticket_date').val(start.format('Y-MM-DD') + '/' + end.format('Y-MM-DD'));
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
    window.open(exportFile + '?format=' + format + '&search=' + search+'&date='+date, '_blank');
}

function showEmployeeSystemCode(){
    var type = $('#type').val();
    $('#show_system_code_div').addClass('d-none')
    if(type == "System Repair"){
        $('#show_system_code_div').removeClass('d-none')
    }
}