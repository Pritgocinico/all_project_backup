@extends('layouts.main_layout')
@section('section')
<div class="app-main flex-column flex-row-fluid " id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar  pt-6 pb-2 ">
            <div id="kt_app_toolbar_container" class="container-fluid d-flex align-items-stretch ">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                            {{ $page }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <div id="kt_app_content" class="app-content  flex-column-fluid pt-0">


            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container  container-fluid ">
                <div id="kt_app_content" class="flex-column-fluid ">
                    <div id="kt_app_content_container" class="container-fluid ">
                        <div class="card-body py-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card-body py-4 table-responsive" id="attendance_table_scroll">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header justify-content-start">
                                                <h5 class="modal-title houmanity-color" id="log_detail_date">
                                                    {{ date('d-m-Y') }}
                                                </h5>
                                            </div>
                                            <div class="row gy-10 mt-1">
                                                <div class="col-md-6 mt-0">
                                                    <div data-kt-href="true" class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                                        <h3 class="ps-12 text-gray-900 fw-bold letter-spacing fs-4">
                                                            Login At : </h3>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-0">
                                                    <div data-kt-href="true" class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                                        <h3 class="ps-12 text-gray-900 fw-normal letter-spacing fs-4" id="login_at"></h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gy-10 mt-1">
                                                <div class="col-md-6 mt-0">
                                                    <div data-kt-href="true" class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                                        <h3 class="ps-12 text-gray-900 fw-bold letter-spacing fs-4">
                                                            Logout At :</h3>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-0">
                                                    <div data-kt-href="true" class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                                        <h3 class="ps-12 pt-9 mb-7 text-gray-900 fw-normal letter-spacing fs-4" id="logout_at"></h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gy-10 mt-1">
                                                <div class="col-md-6 mt-0">
                                                    <div data-kt-href="true" class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                                        <h3 class="ps-12 text-gray-900 fw-bold letter-spacing fs-4">
                                                            Total Work Hours</h3>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-0">
                                                    <div data-kt-href="true" class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                                        <h3 class="ps-12 text-gray-900 fw-normal letter-spacing fs-4" id="work_hours"></h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gy-10 mt-1">
                                                <div class="col-md-6 mt-0">
                                                    <div data-kt-href="true" class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                                        <h3 class="ps-12 text-gray-900 fw-bold letter-spacing fs-4">
                                                            Break Time Count </h3>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-0">
                                                    <div data-kt-href="true" class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                                        <h3 class="ps-12 text-gray-900 fw-normal letter-spacing fs-4" id="break_time_count"></h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gy-10 mt-1">
                                                <div class="col-md-6 mt-0">
                                                    <div data-kt-href="true" class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                                        <h3 class="ps-12 text-gray-900 fw-bold letter-spacing fs-4">After Hour Login</h3>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-0">
                                                    <div data-kt-href="true" class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                                        <h3 class="ps-12 text-gray-900 fw-normal letter-spacing fs-4" id="after_hour_login"></h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gy-10 mt-1">
                                                <div class="col-md-6 mt-0">
                                                    <div data-kt-href="true" class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                                        <h3 class="ps-12 text-gray-900 fw-bold letter-spacing fs-4">After Hour Logout</h3>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-0">
                                                    <div data-kt-href="true" class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                                        <h3 class="ps-12 text-gray-900 fw-normal letter-spacing fs-4" id="after_hour_logout"></h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gy-10 mt-1">
                                                <div class="col-md-6 mt-0">
                                                    <div data-kt-href="true" class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                                        <h3 class="ps-12 text-gray-900 fw-bold letter-spacing fs-4">Total Time after Work Hour</h3>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-0">
                                                    <div data-kt-href="true" class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                                        <h3 class="ps-12 text-gray-900 fw-normal letter-spacing fs-4" id="total_time_after_work"></h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <hr>
                                                <h6 class="houmanity-color">Break Logs:</h6>
                                                <div class="">
                                                    <div class="table-responsive" tabindex="1">
                                                        <table id="" class="table table-custom" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Break Start</th>
                                                                    <th>Break Complete</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="break_logs"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <h6 class="houmanity-color">Logs Detail:</h6>
                                                <div class="">
                                                    <div class="table-responsive" tabindex="1">
                                                        <table id="" class="table table-custom" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Logs</th>
                                                                    <th>Created At</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="logs_detail"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<input type="hidden" id="user_id" name="user_id" value="{{ Auth()->user() !== null ? Auth()->user()->id : '' }}">
<input type="hidden" id="attedance_date" name="attedance_date" value="{{ date('Y-m-d') }}">

</div>
</div>
</div>
</div>

</body>
@endsection
@section('page')
<script>
    var id = $('#user_id').val()
    var attendanceData = "{{ route('employee-attendance_by_date') }}";
    var breakCount = "{{ route('break_time_count') }}";
    $(document).ready(function(e) {
        loadLogDetail();
        loadDateData();
    })

    function loadDateData(page) {
        $.ajax({
            method: 'get',
            url: "{{ route('attendance-date-ajax') }}",
            data: {
                page: page
            },
            success: function(res) {
                $('#attendance_table_scroll').html('')
                $('#attendance_table_scroll').html(res)
            }
        })
    }
    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        var page = $(this).attr('href').split('page=')[1];
        loadDateData(page);
    });

    function showAttedanceDetail(id) {
        var date = $('#date-' + id).val();
        $('#attedance_date').val(moment(date, "DD-MM-YYYY").format("YYYY-MM-DD"));
        loadLogDetail();
    }

    function loadLogDetail() {
        var date = $('#attedance_date').val();
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: attendanceData,
            data: {
                id: id,
                date: date
            },
            success: function(data) {
                text = "Half Day";
                badgeClass = "warning";
                if (data.status == 1) {
                    text = "Present";
                    badgeClass = "success";
                }
                $('#present_absent_badge').html('');
                $('.modal-title').html(moment(date).format('DD-MM-YYYY') + ' <span class="badge bg-' +
                    badgeClass + '">' + text +
                    '</span>');
                var loginTime = "";
                if (data.login_time !== null) {
                    loginTime = moment(data.login_time, 'YYYY-MM-DD hh:mm:ss').format('DD-MM-YYYY hh:mm A');
                }
                $('#login_at').html(loginTime);
                var logoutTime = "";
                if (data.logout_time !== null) {
                    logoutTime = moment(data.logout_time, 'YYYY-MM-DD hh:mm:ss').format(
                        'DD-MM-YYYY hh:mm A');
                }
                $('#logout_at').html(logoutTime);

                var afterHourLogout = "";
                if (data.after_hour_logout !== null) {
                    afterHourLogout = moment(data.after_hour_logout, 'YYYY-MM-DD hh:mm:ss').format('DD-MM-YYYY hh:mm A');
                }
                $('#after_hour_logout').html(afterHourLogout);
                var afterHourLogin = "";
                if (data.after_hour_login !== null) {
                    afterHourLogin = moment(data.after_hour_login, 'YYYY-MM-DD hh:mm:ss').format('DD-MM-YYYY hh:mm A');
                }
                $('#after_hour_login').html(afterHourLogin);

                var logout = data.logout_time;
                if (data.login_time == '' || data.logout_time == null) {
                    const date = new Date()
                    const timeZone = 'Asia/Kolkata';
                    var fmt = new Intl.DateTimeFormat('sv', {
                        timeStyle: 'medium',
                        dateStyle: 'short',
                        timeZone
                    });
                    var logout = fmt.format(date);
                }
                var login = data.login_time;
                if (data.login_time == '' || data.logout_time == null) {
                    const date1 = new Date()
                    const timeZone = 'Asia/Kolkata';
                    var fmt1 = new Intl.DateTimeFormat('sv', {
                        timeStyle: 'medium',
                        dateStyle: 'short',
                        timeZone
                    });
                    var login = fmt1.format(date1);
                }
                const oldDate = new Date(login)
                const newDate = new Date(logout)
                const msToTime = (ms) => ({
                    hours: Math.trunc(ms / 3600000),
                    minutes: Math.trunc((ms / 3600000 - Math.trunc(ms / 3600000)) * 60) + ((ms /
                        3600000 - Math.trunc(ms / 3600000)) * 60 % 1 != 0 ? 1 : 0)
                })
                var hours = msToTime(Math.abs(newDate - oldDate));
                $('#work_hours').html(hours.hours + ' hours: ' + hours.minutes + 'minutes');

                // After Hour Count
                var afterLogout = data.after_hour_logout;
                if (data.after_hour_login == '' || data.after_hour_logout == null) {
                    const date = new Date()
                    const timeZone = 'Asia/Kolkata';
                    var fmt = new Intl.DateTimeFormat('sv', {
                        timeStyle: 'medium',
                        dateStyle: 'short',
                        timeZone
                    });
                    var afterLogout = fmt.format(date);
                }
                var afterLogin = data.after_hour_login;
                if (data.after_hour_login == '' || data.after_hour_logout == null) {
                    const date1 = new Date()
                    const timeZone = 'Asia/Kolkata';
                    var fmt1 = new Intl.DateTimeFormat('sv', {
                        timeStyle: 'medium',
                        dateStyle: 'short',
                        timeZone
                    });
                    var afterLogin = fmt1.format(date1);
                }
                const oldAfterDate = new Date(afterLogin)
                const newAfterDate = new Date(afterLogout)
                const msAfterToTime = (ms) => ({
                    hours: Math.trunc(ms / 3600000),
                    minutes: Math.trunc((ms / 3600000 - Math.trunc(ms / 3600000)) * 60) + ((ms / 3600000 - Math.trunc(ms / 3600000)) * 60 % 1 != 0 ? 1 : 0)
                })
                var hours = msAfterToTime(Math.abs(newAfterDate - oldAfterDate));
                $('#total_time_after_work').html(hours.hours + ' hours: ' + hours.minutes + 'minutes');
                var html = "";
                $.each(data.break_log_detail, function(i, v) {
                    i++;
                    html += `<tr>
                            <td>` + i + `</td>
                            <td>` + moment(v.break_start, 'YYYY-MM-DD hh:mm:ss').format('DD-MM-YYYY hh:mm A') + `</td>
                            <td>` + moment(v.break_over, 'YYYY-MM-DD hh:mm:ss').format('DD-MM-YYYY hh:mm A') + `</td>
                        </tr>`;
                })
                if (data.break_log_detail.length == 0) {
                    html = "<tr><td class='text-center' colspan='3'>No Data Available</td></tr>";
                }

                $('.break_logs').html('');
                $('.break_logs').html(html);
                $.ajax({
                    type: 'GET',
                    url: breakCount,
                    data: {
                        'id': id,
                        'date': date
                    },
                    success: function(data) {
                        $('#break_time_count').html(data);
                    }
                });
                $.ajax({
                    type: "GET",
                    url: "{{ route('employee-get-log-detail') }}",
                    data: {
                        'id': id,
                        'date': date
                    },
                    success: function(res) {
                        console.log(res);
                        var html1 = "";
                        $.each(res, function(i, v) {
                            html1 += `<tr>
                                <td>` + i + `</td>
                                <td>` + v.log + `</td>
                                <td>` + moment(v.created_at, 'YYYY-MM-DD hh:mm:ss').format('DD-MM-YYYY hh:mm A') + `</td>
                            </tr>`;
                        })
                        $('.logs_detail').html('');
                        $('.logs_detail').html(html1);
                    }
                })

            },
            error: function() {
                $('.modal-title').html(date + ' (<span class="badge bg-danger">Absent</span>)');
                $('#login_at').html('-')
                $('#logout_at').html('-')
                $('#repeate_login').html('-')
                $('#work_hours').html('-')
                $('#break_time_count').html('-')
                html = "<tr><td class='text-center' colspan='3'>No Data Available</td></tr>";
                $('.break_logs').html('');
                $('.break_logs').html(html);
            }
        });
    }
</script>
@endsection