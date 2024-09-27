@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        {{-- <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Daily Attendance List</h1>
                </div>
            </div>
            <div>
                <table class="table table-hover table-striped table-sm table-nowrap table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Login Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dailyAttendanceLists as $key=>$today_Attendance)
                            <tr>
                                <td>{{ $dailyAttendanceLists->firstItem() + $key }}</td>
                                <td>{{ $today_Attendance->employee->name }}</td>
                                <td> 
                                    @php
                                        $text = 'Present';
                                        $color = 'success';
                                        if ($today_Attendance->status == 0) {
                                            $color = 'danger';
                                            $text = 'Absent';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ $text }}</span> 
                                </td>
                                <td>{{Utility::convertDmyAMPMFormat($today_Attendance->login_time)}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No Data Available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end me-2 mt-2">
                    {{ $dailyAttendanceLists->links() }}
                </div>
            </div>
        </main> --}}
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5 justify-content-between">
                    <h1>Daily Attendance List</h1>
                    @if(Auth()->user()->role_id !== "2")
                        <div class="row g-3">
                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_export_users"
                                data-bs-toggle="modal"><i class="bi bi-plus-lg me-2"></i>
                                Export</a>
                        </div>
                    @endif
                </div>
            </div>
            <div>
                <table class="table table-hover table-striped table-sm table-nowrap table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Login Time</th>
                            <th>Logout Time</th>
                            <th>Total Hour</th>
                            <th>Total Break Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dailyAttendanceListsPaginated as $key => $today_Attendance)
                            <tr>
                                <td>{{ $dailyAttendanceListsPaginated->firstItem() + $key }}</td>
                                <td><a href="{{ route('user.show', $today_Attendance->employee->id) }}">{{ $today_Attendance->employee->name }}</a></td>
                                <td> 
                                    @php
                                        $text = 'Present';
                                        $color = 'success';
                                        if ($today_Attendance->status == 0) {
                                            $color = 'danger';
                                            $text = 'Absent';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ $text }}</span> 
                                </td>
                                <td>{{ $today_Attendance->login_time ? Utility::convertDmyAMPMFormat($today_Attendance->login_time) : '-' }}</td>
                                <td>{{ $today_Attendance->logout_time ? Utility::convertDmyAMPMFormat($today_Attendance->logout_time) : '-' }}</td>
                                <td>{{ $today_Attendance->total_work_hour }}</td>
                                <td>{{ $today_Attendance->break_time }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No Data Available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end me-2 mt-2">
                    {{ $dailyAttendanceListsPaginated->links() }}
                </div>
            </div>
        </main>
        
        
    </div>
    <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
                style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content overflow-hidden">
                        <div class="modal-header pb-0 border-0">
                            <h1 class="modal-title h4" id="depositLiquidityModalLabel">Export Customer</h1>
                            <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="" class="form" action="#">
                            <div class="modal-body undefined">
                                <div class="vstack gap-1">
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Export Format:</label></div>
                                        <div class="col-md-10 col-xl-10">
                                            <select name="format" class="form-control" id="export_format">
                                                <option value="">Select Format</option>
                                                <option value="excel">Excel</option>
                                                <option value="pdf">PDF</option>
                                                <option value="csv">CSV</option>
                                            </select>
                                            <span id="format_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="row align-items-center g-3 mt-6 d-none" id="status_div">
                                        <div class="col-md-2"><label class="form-label mb-0">Status</label></div>
                                        <div class="col-md-10 col-xl-10">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="status" id="status">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="reset" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="submitBtn" onclick="exportCSV()">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
@endsection
@section('script')
    <script>
        function exportCSV() {
            var exportFile = "{{ route('daily-attendance-export') }}";
            var format = $('#export_format').val();
            var search = $('#search_data').val();
            $('#format_error').html('');
            if(format.trim() == ""){
                $('#format_error').html('Please Select Export Format.');
                return false;
            }
            var allowValues = ['csv','excel','pdf'];
            if(!allowValues.includes(format)){
                $('#format_error').html('Please Select Valid Export Format.');
                return false;
            }
            var type = $('#ins_type').val();
            window.open(exportFile + '?format=' + format + '&search=' + search + "&type=" + type, '_blank');
        }
    </script>
@endsection
