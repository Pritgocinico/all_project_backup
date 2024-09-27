$(document).ready(function (e) {
    departmentAjax(1);
});
function addDepartment() {
    var department = $('#department_name').val();
    var cnt = 0;

    $('#department_name_error').html('');

    if (department.trim() == "") {
        $('#department_name_error').html('Please enter department name.');
        cnt = 1;
    }

    if (cnt == 1) {
        return false;
    }

    $.ajax({
        'method': 'post',
        'url': storeURL,
        data: {
            department_name: department,
            _token: token,
        }, success: function (res) {
            toastr.success(res.message);
            $("#add_department_modal").modal('hide');
            $('#department_name').val('');
            departmentAjax(1)
        }, error: function (error) {
            toastr.error(error.responseJSON.message)
        }
    });
}

function departmentAjax(page) {
    var search = $('#search_data').val();
    $.ajax({
        method: 'get',
        url: ajax,
        data: {
            search: search,
            page: page,
        }, success: function (res) {
            $('#department_table_ajax').html('');
            $('#department_table_ajax').html(res);
            $('[data-bs-toggle="tooltip"]').tooltip()
        }
    })
}

function editDepartment(id) {
    var url = edit.replace('id', id);
    $.ajax({
        method: 'get',
        url: url,
        success: function (res) {
            $('#id').val(res.data.id);
            $('#edit_department_name').val(res.data.department_name);
            $("#status").removeAttr("checked");
            if (res.data.status == 1) {
                $('#status').attr('checked', 'true')
            }
            $('#edit_department_modal').modal('show');

        }
    })
}

function updateDepartment() {
    var department = $('#edit_department_name').val();
    var cnt = 0;
    var id = $('#id').val();
    $('#edit_department_name_error').html('');

    if (department.trim() == "") {
        $('#edit_department_name_error').html('Please enter department name.');
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
            department_name: department,
            status: status,
            _token: token,
        }, success: function (res) {
            toastr.success(res.message);
            $('#edit_department_modal').modal('hide');
            var textNumber = document.querySelector('.pagination .page-item.active .page-link'); 
            if(textNumber !== null){
                departmentAjax(parseInt(textNumber.innerText));
            } else {
                departmentAjax(1);
            }

        }, error: function (error) {
            toastr.error(error.responseJSON.message)
        }
    });
}
$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    departmentAjax(page);
});

function deleteDepartment(id) {
    new swal({
        title: 'Are you sure delete this Department?',
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
                    var textNumber = document.querySelector('.pagination .page-item.active .page-link'); 
                    if(textNumber !== null){
                        departmentAjax(parseInt(textNumber.innerText));
                    } else {
                        departmentAjax(1);
                    }
                },
                error: function (error) {
                    toastr.error(error.responseJSON.message);
                }
            })
        }
    });
}