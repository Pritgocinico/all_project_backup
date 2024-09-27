$(document).ready(function(e){
    leaveAjaxList(1);
});
function leaveAjaxList(page) {
    var search = $('#search_data').val();
    var userId = $('#leave_dropdown_filter').val();
    var dateFilter = $('#leave_date').val();    

    $.ajax({
        'method': 'get',
        'url': ajaxList,
        data: {
            page: page,
            search: search,
            userId: userId,
            dateFilter: dateFilter,
        },
        success: function (res) {
            $('#leave_data_table').html('');
            $('#leave_data_table').html(res);
            $('[data-bs-toggle="tooltip"]').tooltip()
        },
    });
}
$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    leaveAjaxList(page);
});


function  updateLeaveStatus(status, id){
    var statusId = status.charAt(0).toUpperCase() + status.slice(1);
    new swal({
        title: 'Are you sure change status '+statusId+' this Leave?',
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes '+statusId+' it!'
    }).then(function (isConfirm) {
        if (isConfirm.isConfirmed) {
            $.ajax({
                method: "get",
                url: updateStatus,
                data: {
                    status: status,
                    id: id,
                },
                success: function (res) {
                    toastr.success(res.message);
                    leaveAjaxList(1);
                },
                error: function (error) {
                    toastr.error(error.responseJSON.message);
                }
            })
        }
    });
}
// search_leave_date
$(function () {
    $('.search_leave_date').daterangepicker({
        autoUpdateInput: false,
    }, function (start, end, label) {
        $('.search_leave_date').val(start.format('Y-0M-0D') + '/' + end.format('Y-0M-0D'));
        leaveAjaxList(1)
    });
});


function exportCSVLeave() {
    var format = $('#export_format').val();
    var search = $('#search_data').val();
    var userId = $('#leave_dropdown_filter').val();
    var dateFilter = $('#leave_date').val(); 
    window.open(exportFileLeave + '?format=' + format + '&userId=' + userId + '&dateFilter=' + dateFilter + '&search=' + search, '_blank');
}

function rejectLeaveStatus(status, id) {
    var statusId = status.charAt(0).toUpperCase() + status.slice(1);
    new swal({
        title: 'Are you sure change status ' + statusId + ' this Leave?',
        text: "Enter Reason for Rejection",
        showCancelButton: true,
        input: 'text',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes ' + statusId + ' it!',
        cancelButtonText: 'Close',
        customClass: {
            validationMessage: 'my-validation-message',
        },
        preConfirm: (value) => {
            if (!value) {
                Swal.showValidationMessage('Reason for cancellation is required')
            }
        },
    }).then(function (isConfirm) {
        if (isConfirm.isConfirmed) {
            $.ajax({
                method: "get",
                url: updateStatus,
                data: {
                    status: status,
                    id: id,
                    reject_reason: isConfirm.value
                },
                success: function (res) {
                    toastr.success(res.message);
                    leaveAjaxList(1);
                },
                error: function (error) {
                    toastr.error(error.responseJSON.message);
                }
            })
        }
    });
}