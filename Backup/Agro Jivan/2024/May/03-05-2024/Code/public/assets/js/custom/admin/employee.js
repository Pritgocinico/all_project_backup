$(document).ready(function (e) {
    // department
    $('#department').select2();
    employeeAjaxList(1);

    var ctx = document.getElementById('present_absent_chart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: label,
            datasets: [{
                data: jsonData,
                backgroundColor: [
                    'red',
                    'green',
                    'gray'
                ],
                borderColor: [
                    'red',
                    'green',
                    'gray'
                ],
                height: '100px',
                borderWidth: 1
            }]
        },
    });
});
function employeeValidate() {
    var name = $('#name').val();
    var number = $('#phone_number').val();
    var password = $('#password').val();
    var aadharNumber = $('#aadhar_card').val();
    var panNumber = $('#pan_card').val();
    var qualification = $('#qualification').val();
    var role = $('#role').val();
    var code = $('#system_code').val();
    var department = $('#department').val();
    var shift = $('#shift_type').val();

    var cnt = 0;

    $('#name_error').html('');
    $('#phone_number_error').html('');
    $('#password_error').html('');
    $('#aadhar_card_error').html('');
    $('#pan_card_error').html('');
    $('#qualification_error').html('');
    $('#role_error').html('');
    $('#department_error').html('');
    $('#shift_type_error').html('');

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
    if (aadharNumber == "") {
        $('#aadhar_card_error').html('Please Select Aadhar Card.');
        cnt = 1;
    }
    if (panNumber == "") {
        $('#pan_card_error').html('Please Select Pan Card.');
        cnt = 1;
    }
    if (qualification == "") {
        $('#qualification_error').html('Please Select Qualification.');
        cnt = 1;
    }
    if (role == "") {
        $('#role_error').html('Please Select Role.');
        cnt = 1;
    } else {
        if (role == 2) {
            if (department == "") {
                $('#department_error').html('Please Select Department.');
                cnt = 1;
            }
        }
    }
    if (code.trim() == "") {
        $('#system_code_error').html('Please Enter System Code.');
        cnt = 1;
    }
    if (shift == "") {
        $('#shift_type_error').html('Please Select Shift Type.');
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
function resetFormValue(){
    $('#employee_status').val("");
    $('#role_dropdown_filter').val("");
    $('#search_data').val("");
    employeeAjaxList(1)
}
function employeeAjaxList(page) {
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
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
    });
}
$(document).on('click', '.pagination a', function (event) {
    var attendance =$('#attedance_date').val();
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    if(attendance == undefined){
        employeeAjaxList(page);
    } else {
        loadDateData(page)
    }
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
    if (code !== undefined) {
        if (code.trim() == "") {
            $('#system_code_error').html('Please Enter System Code.');
            cnt = 1;
        }
    }
    if (cnt == 1) {
        return false;
    }
    return true;
}

function deleteEmployee(id) {
    var url = deleteURL.replace('id', id)
    new swal({
        title: 'Are you sure delete this employee?',
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
                    var textNumber = document.querySelector('.pagination .page-item.active .page-link'); 
                    if(textNumber !== null){
                        employeeAjaxList(parseInt(textNumber.innerText));
                    } else {
                        employeeAjaxList(1);
                    }
                },
                error: function (error) {
                    toastr.error(error.responseJSON.message);
                }
            })
        }
    });
}
function passwordHideShow() {
    const passwordField = document.getElementById("password");
    const togglePassword = document.getElementById("togglePasswordEmployee");
    if (passwordField.type === "password") {
        passwordField.type = "text";
        togglePassword.classList.remove("fa-eye");
        togglePassword.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        togglePassword.classList.remove("fa-eye-slash");
        togglePassword.classList.add("fa-eye");
    }
}

function roleDropdown() {
    var role = $('#role').val();
    $('#department_check_div').css('display', "none");
    if (role == "2") {
        $('#is_manager').val('');
        $('#department_check_div').css('display', "");
    } else{
        $('#is_manager').val('yes');
    }
}

$('#phone_number').keypress(function (event) {
    if (event.which != 8 && isNaN(String.fromCharCode(event.which))) {
        event.preventDefault();
    }
});
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var calendar = $('#attendance_calendar').fullCalendar({
    editable: true,
    events: calendarUrl.replace('id', id),
    eventClick: function (event, element, view) {
        if (event.title == "Present" || event.title == "Half Day") {
            $('.modal-title').html(event.start.format('DD-MM-Y') + ' ( ' + event.title + ' )');
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: attendanceData,
                data: {
                    id: id,
                    date: event.start.format()
                },
                success: function (data) {
                    var loginTime = "";
                    if(data.login_time !== null){
                        loginTime = moment(data.login_time, 'YYYY-MM-DD hh:mm:ss').format('DD-MM-YYYY hh:mm A');
                    }
                    $('#login_at').html(loginTime);
                    var logoutTime = "";
                    if(data.logout_time !== null){
                        logoutTime = moment(data.logout_time, 'YYYY-MM-DD hh:mm:ss').format('DD-MM-YYYY hh:mm A');
                    }
                    $('#logout_at').html(logoutTime);

                    var afterHourLogout = "";
                    if(data.after_hour_logout !== null){
                        afterHourLogout = moment(data.after_hour_logout, 'YYYY-MM-DD hh:mm:ss').format('DD-MM-YYYY hh:mm A');
                    }
                    $('#after_hour_logout').html(afterHourLogout);
                    var afterHourLogin = "";
                    if(data.after_hour_login !== null){
                        afterHourLogin = moment(data.after_hour_login, 'YYYY-MM-DD hh:mm:ss').format('DD-MM-YYYY hh:mm A');
                    }
                    $('#after_hour_login').html(afterHourLogin);

                    var logout = data.logout_time;
                    if(data.login_time == '' || data.logout_time == null){
                        const date = new Date()
                        const timeZone = 'Asia/Kolkata';
                        var fmt = new Intl.DateTimeFormat('sv', { timeStyle: 'medium', dateStyle: 'short', timeZone });
                        var logout = fmt.format(date);
                    }
                    var login = data.login_time;
                    if(data.login_time == '' || data.logout_time == null){
                        const date1 = new Date()
                        const timeZone = 'Asia/Kolkata';
                        var fmt1 = new Intl.DateTimeFormat('sv', { timeStyle: 'medium', dateStyle: 'short', timeZone });
                        var login = fmt1.format(date1);
                    }
                    const oldDate = new Date(login)
                    const newDate = new Date(logout)
                    const msToTime = (ms) => ({
                        hours: Math.trunc(ms/3600000),
                        minutes: Math.trunc((ms/3600000 - Math.trunc(ms/3600000))*60) + ((ms/3600000 - Math.trunc(ms/3600000))*60 % 1 != 0 ? 1 : 0)
                    })
                    var hours = msToTime(Math.abs(newDate-oldDate));
                    $('#work_hours').html(hours.hours+' hours: '+hours.minutes+'minutes');

                    // After Hour Count
                    var afterLogout = data.after_hour_logout;
                    if(data.after_hour_login == '' || data.after_hour_logout == null){
                        const date = new Date()
                        const timeZone = 'Asia/Kolkata';
                        var fmt = new Intl.DateTimeFormat('sv', { timeStyle: 'medium', dateStyle: 'short', timeZone });
                        var afterLogout = fmt.format(date);
                    }
                    var afterLogin = data.after_hour_login;
                    if(data.after_hour_login == '' || data.after_hour_logout == null){
                        const date1 = new Date()
                        const timeZone = 'Asia/Kolkata';
                        var fmt1 = new Intl.DateTimeFormat('sv', { timeStyle: 'medium', dateStyle: 'short', timeZone });
                        var afterLogin = fmt1.format(date1);
                    }
                    const oldAfterDate = new Date(afterLogin)
                    const newAfterDate = new Date(afterLogout)
                    const msAfterToTime = (ms) => ({
                        hours: Math.trunc(ms/3600000),
                        minutes: Math.trunc((ms/3600000 - Math.trunc(ms/3600000))*60) + ((ms/3600000 - Math.trunc(ms/3600000))*60 % 1 != 0 ? 1 : 0)
                    })
                    var hours = msAfterToTime(Math.abs(newAfterDate-oldAfterDate));
                    $('#total_time_after_work').html(hours.hours+' hours: '+hours.minutes+'minutes');
                    var html = ""; 
                    $.each(data.break_log_detail,function(i,v){
                        i++;
                        html += `<tr>
                            <td>`+i+`</td>
                            <td>`+moment(v.break_start, 'YYYY-MM-DD hh:mm:ss').format('DD-MM-YYYY hh:mm A')+`</td>
                            <td>`+moment(v.break_over, 'YYYY-MM-DD hh:mm:ss').format('DD-MM-YYYY hh:mm A')+`</td>
                        </tr>`;
                    })
                    if(data.break_log_detail.length == 0){
                        html = "<tr><td class='text-center' colspan='3'>No Data Available</td></tr>";
                    }

                    $('.break_logs').html('');
                    $('.break_logs').html(html);
                    $.ajax({
                        type: 'GET',
                        url: breakCount,
                        data: {'id': id,'date': event.start.format()},
                        success: function (data) {
                            $('#break_time_count').html(data);
                        }
                    });

                }
            });
            $('#calendarModal').modal('show');
        }
    }, 
    eventRender: function (event, element, view) {
        if (event.allDay === 'true') {
            event.allDay = true;
        } else {
            event.allDay = false;
        }
    },
});
function openOfferModel(id){
    CKEDITOR.replace('offer_text',{
        toolbar: [ 'bold', 'italic', 'link', 'bulletedList', 'numberedList' ],
    });
    $('#id').val(id);
    $('#edit_department_modal').modal('show');
}
function closeOfferModel(){
    $('#edit_department_modal').modal('hide');
}

function checkFileType(){
    const fileInput = document.getElementById('join_agreement');
    const file = fileInput.files[0];
    
    $('#join_agreement_error').html('');
    if (file && file.type !== 'application/pdf') {
        $('#join_agreement_error').html('Please upload only for pdf.');
    }
}