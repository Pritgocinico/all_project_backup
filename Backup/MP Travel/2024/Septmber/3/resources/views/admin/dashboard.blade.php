@extends('admin.partials.header', ['accesses' => $accesses, 'active' => 'dashboard'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center mb-5">
                    <div class="col">
                        <h1 class="ls-tight">Dashboard</h1>
                    </div>
                </div>
                <div class="vstack gap-3 gap-xl-6">
                    <div class="row row-cols-xl-4 g-3 g-xl-6 dashboard-cards">
                        <div class="col">
                            <a href="{{ route('user.index') }}">
                                <div class="card bg-style1">
                                    <div class="p-7 dashboard-inner-card">
                                        <div>
                                            <span class="d-block h3 d-inner-title">{{ $userCount }}</span>
                                            <i class="fas fa-user-friends d-inner-title"></i>
                                        </div>
                                        <h6 class="text-limit  mb-3">Total Employees</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('role.index') }}">
                                <div class="card bg-style2">
                                    <div class="p-7 dashboard-inner-card">
                                        <div>
                                            <span class="d-block h3 d-inner-title">{{ $roleCount }}</span>
                                            <i class="fas fa-business-time	d-inner-title"></i>
                                        </div>
                                        <h6 class="text-limit  mb-3">Total Roles</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('department.index') }}">
                                <div class="card bg-style3">
                                    <div class="p-7 dashboard-inner-card">
                                        <div>
                                            <span class="d-block h3 d-inner-title">{{ $departmentCount }}</span>
                                            <i class="fas fa-building d-inner-title"></i>
                                        </div>
                                        <h6 class="text-limit  mb-3">Total Departments</h6>

                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('designation.index') }}">
                                <div class="card bg-style4">
                                    <div class="p-7 dashboard-inner-card">
                                        <div>
                                            <span class="d-block h3 d-inner-title">{{ $designationCount }}</span>
                                            <i class="fa-solid fa-user-group d-inner-title"></i>
                                        </div>
                                        <h6 class="text-limit  mb-3">Total Designations</h6>

                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="row row-cols-xl-4 g-3 g-xl-6 dashboard-cards emplyee-dashboard">
                        <div class="inner-card">
                            @if (collect($accesses)->where('menu_id', '8')->first()->status !== 0)
                                <div class="col">
                                    <a href="{{ route('info_sheet.index') }}?type=assign">
                                        <div class="card bg-style1">
                                            <div class="p-7 dashboard-inner-card">
                                                <div>
                                                    <span class="d-block h3 d-inner-title">{{ $infoSheetCount }}</span>
                                                    <i class="fa-solid fa-circle-info d-inner-title"></i>
                                                </div>
                                                <h6 class="text-limit  mb-3">Total Info Sheets</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                            @if (collect($accesses)->where('menu_id', '10')->first()->status !== 0)
                                <div class="col">
                                    <a href="{{ route('holiday.index') }}">
                                        <div class="card bg-style2">
                                            <div class="p-7 dashboard-inner-card">
                                                <div>
                                                    <span class="d-block h3 d-inner-title">{{ $holidayCount }}</span>
                                                    <i class="fa-solid fa-wand-sparkles d-inner-title"></i>
                                                </div>
                                                <h6 class="text-limit  mb-3">Total Holidays</h6>

                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                            @if (collect($accesses)->where('menu_id', '9')->first()->status !== 0)
                                <div class="col">
                                    <a href="{{ route('leave.index') }}">
                                        <div class="card bg-style3">
                                            <div class="p-7 dashboard-inner-card">
                                                <div>
                                                    <span class="d-block h3 d-inner-title">{{ $leaveCount }}</span>
                                                    <i class="fa-solid fa-person-walking-arrow-right d-inner-title"></i>
                                                </div>
                                                <h6 class="text-limit  mb-3">Applied Leaves</h6>

                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                            @if (collect($accesses)->where('menu_id', '11')->first()->status !== 0)
                                <div class="col">
                                    <a href="{{ route('ticket.index') }}">
                                        <div class="card bg-style1">
                                            <div class="p-7 dashboard-inner-card">
                                                <div>
                                                    <span class="d-block h3 d-inner-title">{{ $ticketCount }}</span>
                                                    <i class="fa fa-ticket d-inner-title"></i>
                                                </div>

                                                <h6 class="text-limit  mb-3">Raised Tickets</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                            @if (collect($accesses)->where('menu_id', '14')->first()->status !== 0)
                                <div class="col">
                                    <a href="{{ route('certificate.index') }}">
                                        <div class="card bg-style2">
                                            <div class="p-7 dashboard-inner-card">
                                                <div>
                                                    <span class="d-block h3 d-inner-title">{{ $certificateCount }}</span>
                                                    <i class="fa fa-certificate d-inner-title"></i>
                                                </div>
                                                <h6 class="text-limit  mb-3">Certificates</h6>

                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                            @if (collect($accesses)->where('menu_id', '15')->first()->status !== 0)
                                <div class="col">
                                    <a href="{{ route('salary-slip.index') }}">
                                        <div class="card bg-style3">
                                            <div class="p-7 dashboard-inner-card">
                                                <div>
                                                    <span
                                                        class="d-block h3 d-inner-title">{{ $salarySlipMonth ? $salarySlipMonth->month : '' }}</span>
                                                    <i class="fa fa-receipt fs-2 d-inner-title"></i>
                                                </div>
                                                <h6 class="text-limit  mb-3">Salary Slips</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                            @if (collect($accesses)->where('menu_id', '7')->first()->status !== 0)
                                <div class="col">
                                    <a href="{{ route('customer.index') }}">
                                        <div class="card bg-style3">
                                            <div class="p-7 dashboard-inner-card">
                                                <div>
                                                    <span class="d-block h3 d-inner-title">{{ $customerCount }}</span>
                                                    <i class="fas fa-user d-inner-title"></i>
                                                </div>
                                                <h6 class="text-limit  mb-3">Total Customers</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                            <div class="col">
                                <a href="{{ route('daily_attendance') }}?status=1">
                                    <div class="card bg-style3">
                                        <div class="p-7 dashboard-inner-card">
                                            <div>
                                                <span class="d-block h3 d-inner-title">{{ $presentCount }}</span>
                                                <i class="fas fa-calendar-alt d-inner-title"></i>
                                            </div>
                                            <h6 class="text-limit  mb-3">Total Presents</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col">
                                <div class="card bg-style3">
                                    <div class="p-7 dashboard-inner-card">
                                        <div>
                                            <span class="d-block h3 d-inner-title">{{ $absentCount }}</span>
                                            <i class="fas fa-calendar-times d-inner-title"></i>
                                        </div>
                                        <h6 class="text-limit  mb-3">Total Absents</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card bg-style3">
                                    <div class="p-7 dashboard-inner-card">
                                        <div>
                                            <span class="d-block h3 d-inner-title">{{ $halfDayCount }}</span>
                                            <i class="far fa-clock d-inner-title"></i>
                                        </div>
                                        <h6 class="text-limit  mb-3">Total Half Days</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="inner-single-card">
                            <div class="card">
                                <div class="card-body d-flex flex-center flex-column  em-dashbord-counter">
                                    <div class="d-flex justify-content-start gap-1 mb-1 w-100 flex-column-1299">
                                        <h4>Daily Attendance Chart</h4>
                                    </div>
                                    <div class="mx-n4">
                                        <canvas id="attendance_chart" data-height="270"></canvas>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row g-3 g-xl-6 emplyee-dashboard">
                        <div class="col-md-6">
                            <div class="card bg-style2">
                                <div class="px-6 px-lg-7 pt-6 pb-5">
                                    <h4>All Leads</h4>
                                    <div id="lead_table_ajax" class="table-responsive custom-scrollbar">
                                        <table class="table table-hover table-sm table-nowraps mt-6 border"
                                            id="lead_table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Lead</th>
                                                    <th>Customer Name</th>
                                                    <th>Lead Amount</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($leads as $key=>$lead)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td><a
                                                                href="{{ route('leads.show', $lead->id) }}">{{ $lead->lead_id }}</a>
                                                        </td>
                                                        <td>{{ isset($lead->customerDetail) ? $lead->customerDetail->name : '-' }}
                                                        </td>
                                                        <td>&#x20B9; {{ number_format($lead->lead_amount ?? 0, 2) }}
                                                        </td>
                                                        <td>
                                                            @php
                                                                $status = 'warning';
                                                                $text = 'Pending Lead';
                                                                if ($lead->lead_status == 2) {
                                                                    $status = 'info';
                                                                    $text = 'Assigned Lead';
                                                                }
                                                                if ($lead->lead_status == 3) {
                                                                    $status = 'secondary';
                                                                    $text = 'Hold Lead';
                                                                }
                                                                if ($lead->lead_status == 4) {
                                                                    $status = 'success';
                                                                    $text = 'Complete Lead';
                                                                }
                                                                if ($lead->lead_status == 5) {
                                                                    $status = 'warning';
                                                                    $text = 'Extends Lead';
                                                                }
                                                                if ($lead->lead_status == 6) {
                                                                    $status = 'danger';
                                                                    $text = 'Cancel Lead';
                                                                }
                                                            @endphp
                                                            <span
                                                                class="badge bg-{{ $status }}">{{ $text }}</span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center">No Data Available.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body pb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5>Lead Chart</h5>
                                        </div>
                                    </div>
                                    <div class="mx-n4">
                                        <canvas id="leadStatusChart" data-height="270"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 g-xl-6 emplyee-dashboard">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body pb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5>Follow Up Chart</h5>
                                        </div>
                                    </div>
                                    <div class="mx-n4">
                                        <canvas id="FollowUpChart" data-height="270"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-style2">
                                <div class="pb-5 px-6 px-lg-7 pt-6">
                                    <h4>All Follow Ups</h4>
                                    <div id="follow_up_ajax" class="table-responsive custom-scrollbar">
                                        <table
                                            class="table table-hover table-sm table-nowraps mt-6 border dataTable no-footer"
                                            id="follow_up_table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Event Name</th>
                                                    <th>Status</th>
                                                    <th>Created By</th>
                                                    <th>Created At</th>
                                                    <th class="text-end">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($followUpList as $key=>$follow)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td><a
                                                                href="{{ route('follow-up.show', $follow->id) }}">{{ $follow->event_name }}</a>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $status = 'warning';
                                                                $text = 'Pending Lead';
                                                                if ($follow->event_status == 2) {
                                                                    $status = 'info';
                                                                    $text = 'Assigned Lead';
                                                                }
                                                                if ($follow->event_status == 3) {
                                                                    $status = 'secondary';
                                                                    $text = 'Hold Lead';
                                                                }
                                                                if ($follow->event_status == 4) {
                                                                    $status = 'success';
                                                                    $text = 'Complete Lead';
                                                                }
                                                                if ($follow->event_status == 5) {
                                                                    $status = 'warning';
                                                                    $text = 'Extends Lead';
                                                                }
                                                                if ($follow->event_status == 6) {
                                                                    $status = 'danger';
                                                                    $text = 'Cancel Lead';
                                                                }
                                                            @endphp
                                                            <span
                                                                class="badge bg-{{ $status }}">{{ $text }}</span>
                                                        </td>
                                                        <td>{{ isset($follow->userDetail) ? $follow->userDetail->name : '-' }}
                                                        </td>
                                                        <td>{{ Utility::convertDmyAMPMFormat($follow->created_at) }}</td>
                                                        <td class="text-end">
                                                            @if (collect($accesses)->where('menu_id', '19')->first()->status == 2)
                                                                <a href="{{ route('follow-up.edit', $follow->id) }}"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="Edit Follow Up"><i
                                                                        class="fa-solid fa-pen-to-square"></i></a>
                                                                <a href="javascript:void(0)"
                                                                    onclick="deleteFollow({{ $follow->id }})"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="Delete Follow Up"><i
                                                                        class="fa-solid fa-trash-can"></i></a>
                                                            @endif
                                                        </td>

                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center">No Data Available.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function(e) {
            var ctx = document.getElementById('attendance_chart').getContext('2d');
            var attendanceChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Employee Attendance',
                        data: @json($data),
                        backgroundColor: [
                            '#008000',
                            '#ff0000',
                            '#FFA500',
                        ],
                        borderColor: [
                            '#008000',
                            '#ff0000',
                            '#FFA500',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                }
            });
        })
    </script>
    <script>
        $(document).ready(function(e) {
            $("#lead_table").DataTable({
                initComplete: function() {
                    var $searchInput = $('#lead_table_filter input');
                    $searchInput.attr('id', 'lead_search');
                    $searchInput.attr('placeholder', 'Search Lead');
                },
                'pageLength': 4,
                lengthChange: false,
                "order": [
                    [0, 'asc']
                ],
                "columnDefs": [{
                    "orderable": false,
                    "targets": 0
                }]
            });
            $("#follow_up_table").DataTable({
                initComplete: function() {
                    var $searchInput = $('#lead_table_filter input');
                    $searchInput.attr('id', 'lead_search');
                    $searchInput.attr('placeholder', 'Search Lead');
                },
                'pageLength': 4,
                lengthChange: false,
                "order": [
                    [0, 'asc']
                ],
                "columnDefs": [{
                    "orderable": false,
                    "targets": 0
                }]
            });
        })
        var ctx = document.getElementById('leadStatusChart').getContext('2d');
        var leadStatusChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($lead_labels),
                datasets: [{
                    label: 'Total Lead',
                    data: @json($statusData),

                    backgroundColor: [
                        'rgb(255, 140, 0)',
                        'rgb(0, 212, 255)',
                        'rgb(137, 87, 255)',
                        'rgb(0, 204, 136)',
                        'rgb(15, 23, 42)',
                        'rgb(255, 51, 102)',
                    ],
                    borderColor: [
                        'rgb(255, 140, 0)',
                        'rgb(0, 212, 255)',
                        'rgb(137, 87, 255)',
                        'rgb(0, 204, 136)',
                        'rgb(15, 23, 42)',
                        'rgb(255, 51, 102)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        var ctx = document.getElementById('FollowUpChart').getContext('2d');
        var FollowUpChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($follow_labels),
                datasets: [{
                    label: 'Total Follow',
                    data: @json($followStatusArray),
                    backgroundColor: [
                        'rgb(255, 140, 0)',
                        'rgb(0, 212, 255)',
                        'rgb(137, 87, 255)',
                        'rgb(0, 204, 136)',
                        'rgb(15, 23, 42)',
                        'rgb(255, 51, 102)',
                    ],
                    borderColor: [
                        'rgb(255, 140, 0)',
                        'rgb(0, 212, 255)',
                        'rgb(137, 87, 255)',
                        'rgb(0, 204, 136)',
                        'rgb(15, 23, 42)',
                        'rgb(255, 51, 102)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        function deleteFollow(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('follow-up.destroy', '') }}" + "/" + id,
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {

                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message,
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'error!',
                                text: error.responseJSON.message,
                                icon: 'error',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            })
                        }
                    });

                }
            });
        }
    </script>
@endsection
