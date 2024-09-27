@extends('admin.partials.header', ['accesses' => $accesses, 'active' => 'dashboard'])
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center mb-5">
                    <div class="col">
                        <h1 class="ls-tight">{{ Common::getUserRoleName(Auth()->user()->role_id) }} Dashboard</h1>

                    </div>

                </div>
                <div class="row row-cols-xl-4 g-3 g-xl-6 dashboard-cards emplyee-dashboard">
                    <div class="inner-card ">
                        @if (collect($accesses)->where('menu_id', '8')->first()->status !== 0)
                            <div class="col">
                                <a href="{{ route('info_sheet.index') }}?type=assign">
                                    <div class="card bg-style1">
                                        <div class="p-7 dashboard-inner-card">
                                            <div>
                                                <span class="d-block h3">{{ $infoSheetCount }}</span>
                                                <i class="fa-solid fa-circle-info"></i>
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
                                                <span class="d-block h3">{{ $holidayCount }}</span>
                                                <i class="fa-solid fa-wand-sparkles"></i>
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
                                                <span class="d-block h3">{{ $leaveCount }}</span>
                                                <i class="fa-solid fa-person-walking-arrow-right"></i>
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
                                                <span class="d-block h3">{{ $ticketCount }}</span>
                                                <i class="fa fa-ticket"></i>
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
                                                <span class="d-block h3">{{ $certificateCount }}</span>
                                                <i class="fa fa-certificate"></i>
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
                                                    class="d-block h3">{{ $salarySlipMonth ? $salarySlipMonth->month : '' }}</span>
                                                <i class="fa fa-receipt fs-2"></i>
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
                                                <span class="d-block h3">{{ $customerCount }}</span>
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <h6 class="text-limit  mb-3">Total Customers</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (collect($accesses)->where('menu_id', '5')->first()->status !== 0)
                            <div class="col">
                                <a href="{{ route('salary-slip.index') }}">
                                    <div class="card bg-style3">
                                        <div class="p-7 dashboard-inner-card">
                                            <div>
                                                <span class="d-block h3">{{ $totalEmployee }}</span>
                                                <i class="fas fa-user-friends"></i>
                                            </div>
                                            <h6 class="text-limit  mb-3">Total Employees</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        <div class="col">
                            <div class="card bg-style3">
                                <div class="p-7 dashboard-inner-card">
                                    <div>
                                        <span class="d-block h3">{{ $presentCount }}</span>
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <h6 class="text-limit  mb-3">Total Presents</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card bg-style3">
                                <div class="p-7 dashboard-inner-card">
                                    <div>
                                        <span class="d-block h3">{{ $absentCount }}</span>
                                        <i class="fas fa-calendar-times"></i>
                                    </div>
                                    <h6 class="text-limit  mb-3">Total Absents</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card bg-style3">
                                <div class="p-7 dashboard-inner-card">
                                    <div>
                                        <span class="d-block h3">{{ $halfDayCount }}</span>
                                        <i class="far fa-clock"></i>
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

        // attendance_chart
    </script>
@endsection
