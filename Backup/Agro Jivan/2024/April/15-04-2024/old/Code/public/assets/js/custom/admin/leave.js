$(document).ready(function (e) {
    leaveAjaxList(1);
});
function leaveAjaxList(page) {
    var search = $('#search_data').val();
    $.ajax({
        'method': 'get',
        'url': ajaxList,
        data: {
            page: page,
            search: search,
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


function updateLeaveStatus(status, id) {
    var statusId = status.charAt(0).toUpperCase() + status.slice(1);
    new swal({
        title: 'Are you sure change status ' + statusId + ' this Leave?',
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonColor: '#007f3e',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes ' + statusId + ' it!'
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
                    leaveAjaxList(parseInt(document.querySelector('.pagination .page-item.active .page-link').innerText));
                },
                error: function (error) {
                    toastr.error(error.responseJSON.message);
                }
            })
        }
    });
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
                    leaveAjaxList(parseInt(document.querySelector('.pagination .page-item.active .page-link').innerText));
                },
                error: function (error) {
                    toastr.error(error.responseJSON.message);
                }
            })
        }
    });
}