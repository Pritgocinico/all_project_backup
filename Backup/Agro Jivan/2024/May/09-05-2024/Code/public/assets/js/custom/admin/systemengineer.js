$(document).ready(function (e) {
    employeeAjaxList(1);
});
function systemengineerValidate() {
    var name = $('#name').val();
    var number = $('#phone_number').val();
    var password = $('#password').val();
    var role = $('#role').val();
   

    var cnt = 0;

    $('#name_error').html('');
    $('#phone_number_error').html('');
    $('#password_error').html('');
    $('#role_error').html('');

    if (name.trim() == "") {
        $('#name_error').html('Please Enter Name.');
        $('#name').focus();
        cnt = 1;
    }
    if (number.trim() == "") {
        $('#phone_number_error').html('Please Enter Phone Number.');
        cnt = 1;
    }
    var pattern = new RegExp(
        "^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[-+_!@#$%^&*.,?]).+$"
    );
    if (password.trim() == "") {
        $('#password_error').html('Please Enter Password.');
        cnt = 1;
    } else if (!pattern.test(password)) {
        $('#password_error').html('Password allowed one uppercase, one lowercase , one number and one special character.');
        cnt = 1;
    }
    
    if (role == "") {
        $('#role_error').html('Please Select Role.');
        cnt = 1;
    }

    if (cnt == 1) {
        return false;
    }
    return true;
}

function exportCSV() {
    var format = $('#export_format').val();
    var role = $('#role_dropdown_export').val();
    var search = $('#search_data').val();
    window.open(exportFile + '?format=' + format + '&role=' + role + '&search=' + search, '_blank');
}

function systemengineerAjaxList(page) {
    var status = $('#employee_status').val();
    var role = $('#role_dropdown_filter').val();
    var search = $('#search_data').val();
    $.ajax({
        'method': 'get',
        'url': ajaxList,
        data: {
            page: page,
            status: status,
            role: role,
            search: search,
        },
        success: function (res) {
            $('#employee_table_ajax').html('');
            $('#employee_table_ajax').html(res);

        },
    });
}
$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    systemengineerAjaxList(page);
});
function updateEmployeeValidate() {
    var name = $('#name').val();
    var number = $('#phone_number').val();
    var password = $('#password').val();
    var role = $('#role').val();
    var code = $('#system_code').val();

    var cnt = 0;

    $('#name_error').html('');
    $('#phone_number_error').html('');
    $('#password_error').html('');
    $('#aadhar_card_error').html('');
    $('#pan_card_error').html('');
    $('#qualification_error').html('');
    $('#role_error').html('');

    if (name.trim() == "") {
        $('#name_error').html('Please Enter Name.');
        $('#name').focus();
        cnt = 1;
    }
    if (number.trim() == "") {
        $('#phone_number_error').html('Please Enter Phone Number.');
        cnt = 1;
    }
    var pattern = new RegExp(
        "^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[-+_!@#$%^&*.,?]).+$"
    );
    if (password.trim() !== "") {
        if (!pattern.test(password)) {
            $('#password_error').html('Password allowed one uppercase, one lowercase , one number and one special character.');
            cnt = 1;
        }
    }
    if (role == "") {
        $('#role_error').html('Please Select Role.');
        cnt = 1;
    }
    if (code.trim() == "") {
        $('#system_code_error').html('Please Enter System Code.');
        cnt = 1;
    }
    if (cnt == 1) {
        return false;
    }
    return true;
}

function deleteEmployee(id) {
    var url = deleteURL.replace('id',id)
    new swal({
        title: 'Are you sure delete this employee?',
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes Delete it!'
    }).then(function(isConfirm){
        if(isConfirm.isConfirmed){
            $.ajax({
                method:"DELETE",
                url: url,
                data:{
                    _token: token
                },
                success: function(res){
                    toastr.success(res.message);
                    employeeAjaxList(1);
                },
                error: function(error){
                    toastr.error(error.responseJSON.message);
                }
            })
        }
    });
}