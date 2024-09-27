@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <img src="{{ asset('storage/' . $user->profile_image) }}" class="img-fluid rounded-top-start-4"
                            style="height: 100px !important" alt="...">
                    </div>
                    <div class="col">
                        <h1 class="ls-tight">{{ $user->name }} -
                            {{ isset($user->departmentDetail) ? $user->departmentDetail->name : '-' }}
                            ( {{ $user->user_code }} )
                        </h1>
                    </div>
                    <div class="col text-end">
                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary">Edit</a>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs nav-tabs-flush gap-8 overflow-x border-0 mt-4" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="user-detail" data-bs-toggle="tab"
                        href="#user-detail-tab" role="tab" aria-controls="user-detail"
                        aria-selected="true">User Detail</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="attendance-list" data-bs-toggle="tab" href="#attendance-list-tab"
                        role="tab" aria-controls="attendance-list" aria-selected="false">Attendance Detail</a>
                </li>
            </ul>
            <div class="tab-content clearfix">
                <div id="attendance-list-tab"
                    class="tab-pane fade" role="tabpanel"
                    aria-labelledby="attendance-list-tab">
                    <div class="card row align-items-center g-3 mt-6">
                        <div id="attendance_calendar"></div>
                    </div>
                </div>
                <div id="user-detail-tab"
                    class="tab-pane fade  show active" role="tabpanel"
                    aria-labelledby="attendance-list-tab">
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $user->name }}
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Email</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $user->email }}
                        </div>
                    </div>
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Phone Number</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $user->phone_number }}
    
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Role</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ isset($user->roleDetail) ? $user->roleDetail->name : '-' }}
                        </div>
                    </div>
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Department</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ isset($user->departmentDetail) ? $user->departmentDetail->name : '-' }}
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Designation</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ isset($user->designationDetail) ? $user->designationDetail->name : '-' }}
                        </div>
                    </div>
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Shift Detail</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ isset($user->shiftDetail) ? $user->shiftDetail->shift_name . ' (' . Utility::convertHIAFormat($user->shiftDetail->shift_start_time) . ' - ' . Utility::convertHIAFormat($user->shiftDetail->shift_end_time) . ')' : '-' }}
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Aadhar Card Image</label></div>
                        <div class="col-md-4 col-xl-4">
                            @if ($user->aadhar_card)
                                <a href="{{ asset('storage/' . $user->aadhar_card) }}" target="_blank"
                                    class="btn btn-primary">View</a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Pan Card</label></div>
                        <div class="col-md-4 col-xl-4">
                            @if ($user->pan_card)
                                <a href="{{ asset('storage/' . $user->pan_card) }}" target="_blank"
                                    class="btn btn-primary">View</a>
                            @else
                                -
                            @endif
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Agreement</label></div>
                        <div class="col-md-4 col-xl-4">
                            @if ($user->user_agreement)
                                <a href="{{ asset('storage/' . $user->user_agreement) }}" target="_blank"
                                    class="btn btn-primary">View</a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Salary</label></div>
                        <div class="col-md-4">
                            &#x20B9; {{ number_format($user->employee_salary, 2) }}
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Joining Date</label></div>
                        <div class="col-md-4">
                            {{ isset($user->joining_date) ? date('d-m-Y', strtotime($user->joining_date)) : '-' }}
                        </div>
                    </div>
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Basics</label></div>
                        <div class="col-md-4 col-xl-4">
                            &#x20B9; {{ number_format($user->basic_amount ?? 0, 2) }}
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">HRA</label></div>
                        <div class="col-md-4 col-xl-4">
                            &#x20B9; {{ number_format($user->hra_amount ?? 0, 2) }}
                        </div>
                    </div>
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Allowances</label></div>
                        <div class="col-md-4 col-xl-4">
                            &#x20B9; {{ number_format($user->allowance_amount ?? 0, 2) }}
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Petrol</label></div>
                        <div class="col-md-4 col-xl-4">
                            &#x20B9; {{ number_format($user->petrol_amount ?? 0, 2) }}
                        </div>
                    </div>
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Created At</label></div>
                        <div class="col-md-4">
                            {{ Utility::convertDmyAMPMFormat($user->created_at) }}
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Created By</label></div>
                        <div class="col-md-4">
                            {{ isset($user->userDetail) ? $user->userDetail->name : '' }}
                        </div>
                    </div>
                    <div class="row align-items-center g-3 mt-6">
                        @foreach ($user->deductionDetail as $deduct)
                            <div class="col-md-2"><label class="form-label mb-0">{{ $deduct->deduction_type }}</label></div>
                            <div class="col-md-4 col-xl-4">
                                &#x20B9; {{ number_format($deduct->amount ?? 0, 2) }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title houmanity-color" id="staticBackdropLabelddd">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="container-fluid">
                    <div class="row gy-10 mt-1">
                        <div class="col-md-6 mt-0">
                            <div data-kt-href="true"
                                class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                <h4 class="ps-12 text-gray-900 fw-bold letter-spacing fs-4">Login At : </h4>
                            </div>
                        </div>
                        <div class="col-md-6 mt-0">
                            <div data-kt-href="true"
                                class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                <h4 class="ps-12 text-gray-900 fw-normal letter-spacing fs-4" id="login_at"></h4>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-10 mt-1">
                        <div class="col-md-6 mt-0">
                            <div data-kt-href="true"
                                class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                <h4 class="ps-12 text-gray-900 fw-bold letter-spacing fs-4">Logout At :</h4>
                            </div>
                        </div>
                        <div class="col-md-6 mt-0">
                            <div data-kt-href="true"
                                class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                <h4 class="ps-12 pt-9 mb-7 text-gray-900 fw-normal letter-spacing fs-4" id="logout_at">
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-10 mt-1">
                        <div class="col-md-6 mt-0">
                            <div data-kt-href="true"
                                class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                <h4 class="ps-12 text-gray-900 fw-bold letter-spacing fs-4">Total Work Hours</h4>
                            </div>
                        </div>
                        <div class="col-md-6 mt-0">
                            <div data-kt-href="true"
                                class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                <h4 class="ps-12 text-gray-900 fw-normal letter-spacing fs-4" id="work_hours"></h4>
                            </div>
                        </div>
                    </div>

                    <div class="row gy-10 mt-1">
                        <div class="col-md-6 mt-0">
                            <div data-kt-href="true"
                                class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                <h4 class="ps-12 text-gray-900 fw-bold letter-spacing fs-4">Break Time Count </h4>
                            </div>
                        </div>
                        <div class="col-md-6 mt-0">
                            <div data-kt-href="true"
                                class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                <h4 class="ps-12 text-gray-900 fw-normal letter-spacing fs-4" id="break_time_count"></h4>
                            </div>
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
                                <tbody class="break_logs">

                                </tbody>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="leaveReasonModel" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overflow-hidden">
                <div class="modal-header pb-0 border-0">
                    <h1 class="modal-title h4" id="depositLiquidityModalLabel">Leave Reson</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body undefined">
                    Reason:- <strong id="leave_reason"></strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var calendarUrl = "{{ route('calendar-detail', 'id') }}";
        var id = "{{ $user->id }}";
        var attendanceData = "{{ route('attendance_by_date') }}";
        var breakCount = "{{ route('break_time_count') }}"

        var calendar = $('#attendance_calendar').fullCalendar({
            editable: true,
            events: calendarUrl.replace('id', id),
            eventClick: function(event, element, view) {
                if (event.title == "Casual Leave" || event.title == "Sick Leave") {
                    $('#leave_reason').html(event.reason)
                    $('#leaveReasonModel').modal('show');
                }
                if (event.title == "Present" || event.title == "Half Day") {
                    text = "Half Day";
                    badgeClass = "warning";
                    if (event.title == "Present") {
                        text = "Present";
                        badgeClass = "success";
                    }
                    $('.modal-title').html(event.start.format('DD-MM-Y') + ' <span class="badge bg-' +
                        badgeClass + '">' + text +
                        '</span>');
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: attendanceData,
                        data: {
                            id: id,
                            date: event.start.format()
                        },
                        success: function(data) {
                            var loginTime = "";
                            if (data.login_time !== null) {
                                loginTime = moment(data.login_time, 'YYYY-MM-DD hh:mm:ss').format(
                                    'DD-MM-YYYY hh:mm A');
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
                                afterHourLogout = moment(data.after_hour_logout,
                                    'YYYY-MM-DD hh:mm:ss').format('DD-MM-YYYY hh:mm A');
                            }
                            $('#after_hour_logout').html(afterHourLogout);
                            var afterHourLogin = "";
                            if (data.after_hour_login !== null) {
                                afterHourLogin = moment(data.after_hour_login,
                                    'YYYY-MM-DD hh:mm:ss').format('DD-MM-YYYY hh:mm A');
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
                                minutes: Math.trunc((ms / 3600000 - Math.trunc(ms /
                                    3600000)) * 60) + ((ms / 3600000 - Math.trunc(ms /
                                    3600000)) * 60 % 1 != 0 ? 1 : 0)
                            })
                            var hours = msToTime(Math.abs(newDate - oldDate));
                            $('#work_hours').html(hours.hours + ' hours: ' + hours.minutes +
                                'minutes');

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
                                minutes: Math.trunc((ms / 3600000 - Math.trunc(ms /
                                    3600000)) * 60) + ((ms / 3600000 - Math.trunc(ms /
                                    3600000)) * 60 % 1 != 0 ? 1 : 0)
                            })
                            var hours = msAfterToTime(Math.abs(newAfterDate - oldAfterDate));
                            $('#total_time_after_work').html(hours.hours + ' hours: ' + hours
                                .minutes + 'minutes');
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
                                html =
                                    "<tr><td class='text-center' colspan='3'>No Data Available</td></tr>";
                            }

                            $('.break_logs').html('');
                            $('.break_logs').html(html);
                            $.ajax({
                                type: 'GET',
                                url: breakCount,
                                data: {
                                    'id': id,
                                    'date': event.start.format()
                                },
                                success: function(data) {
                                    $('#break_time_count').html(data);
                                }
                            });

                        }
                    });
                    $.ajax({
                        type: "GET",
                        url: "{{ route('get-log-detail') }}",
                        data: {
                            'id': id,
                            'date': event.start.format()
                        },
                        success: function(res) {
                            var html1 = "";
                            $.each(res, function(i, v) {
                                var number = i + 1;
                                html1 += `<tr>
                                <td>` + number + `</td>
                                <td>` + v.module + `</td>
                                <td>` + moment(v.created_at).format('DD-MM-YYYY hh:mm A') + `</td>
                            </tr>`;
                            })
                            $('.logs_detail').html('');
                            $('.logs_detail').html(html1);
                        }
                    })
                    $('#calendarModal').modal('show');
                }
            },
            eventRender: function(event, element, view) {
                if (event.allDay === 'true') {
                    event.allDay = true;
                } else {
                    event.allDay = false;
                }
            },
        });
    </script>
@endsection
