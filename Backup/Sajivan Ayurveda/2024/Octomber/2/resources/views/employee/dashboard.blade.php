@extends('admin.partials.header', ['accesses' => $accesses, 'active' => 'dashboard'])
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body  main-table rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center mb-5">
                    <div class="col">
                        <h1 class="ls-tight">{{ Common::getUserRoleName(Auth()->user()->role_id) }} Dashboard</h1>

                    </div>
                    {{-- <div class="col text-end">
                        @if (collect($accesses)->where('menu_id', '17')->first()->status == 2)
                            <a href="{{ route('follow-up.index') }}" class="btn btn-dark btn-sm">Add Follow Up
                            </a>
                        @endif
                        @if (collect($accesses)->where('menu_id', '16')->first()->status == 2)
                            <a href="{{ route('leads.create') }}" class="btn btn-dark btn-sm">Add Lead</a>
                        @endif
                    </div> --}}
                </div>
                <div class="row row-cols-xl-4 g-3 g-xl-6 dashboard-cards emplyee-dashboard">
                    <div class="inner-card p-0">
                        {{-- <div class="col">
                            <a href="{{ route('leads.index') }}?type=assign">
                                <div class="card bg-style1">
                                    <div class=" dashboard-inner-card">
                                        <div>
                                            <span class="d-block h3 d-inner-title">{{ $assignLead }}</span>
                                            <i class="fa fa-exchange d-inner-title" aria-hidden="true"></i>
                                        </div>
                                        <h6 class="text-limit  mb-3">Assigned Leads</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('leads.index') }}?type=create">
                                <div class="card bg-style2">
                                    <div class=" dashboard-inner-card">
                                        <div>
                                            <span class="d-block h3 d-inner-title d-inner-title">{{ $createdLead }}</span>
                                            <i class="fa fa-tasks d-inner-title" aria-hidden="true"></i>
                                        </div>
                                        <h6 class="text-limit  mb-3">My Created Leads</h6>

                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('follow-up.index') }}">
                                <div class="card bg-style3">
                                    <div class=" dashboard-inner-card">
                                        <div>
                                            <span class="d-block h3 d-inner-title">{{ $followUpCount }}</span>
                                            <i class="fa fa-bullhorn d-inner-title" aria-hidden="true"></i>
                                        </div>
                                        <h6 class="text-limit  mb-3">My Follow-Ups</h6>

                                    </div>
                                </div>
                            </a>
                        </div> --}}
                        <div class="col">
                            <a href="{{ route('customer.index') }}">
                                <div class="card bg-style1">
                                    <div class=" dashboard-inner-card">
                                        <div>
                                            <span class="d-block h3 d-inner-title">{{ $customerCount }}</span>
                                            <i class="fa-solid fa-user d-inner-title"></i>
                                        </div>

                                        <h6 class="text-limit  mb-3">Total Customers</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('certificate.index') }}">
                                <div class="card bg-style2">
                                    <div class=" dashboard-inner-card">
                                        <div>
                                            <span class="d-block h3 d-inner-title">{{ $certificateCount }}</span>
                                            <i class="fa fa-certificate d-inner-title" aria-hidden="true"></i>
                                        </div>
                                        <h6 class="text-limit  mb-3">My Certificates</h6>

                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('salary-slip.index') }}">
                                <div class="card bg-style3">
                                    <div class=" dashboard-inner-card">
                                        <div>
                                            <span
                                                class="d-block h3 d-inner-title">{{ $salarySlipMonth ? $salarySlipMonth->month : 0 }}</span>
                                            <i class="fa-solid fa-receipt d-inner-title"></i>
                                        </div>
                                        <h6 class="text-limit  mb-3">Salary Slips</h6>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>
                    <div class="inner-single-card">
                        <div class="card">
                            <div class="card-body d-flex flex-center flex-column  em-dashbord-counter">
                                <div class="d-flex justify-content-start gap-1 mb-1 w-100 flex-column-1299">
                                    <select name="month_name" id="month_name" class="form-control">
                                        @foreach ($monthList as $key => $month)
                                            <option value="{{ $month }}"
                                                @if (Utility::getCurrentMonthName() == $month) {{ 'selected' }} @endif>
                                                {{ $month }}</option>
                                        @endforeach
                                    </select>&nbsp;
                                    <input type="button" name="button" class="btn btn-dark" value="Search"
                                        onclick="getLeaveData()">
                                </div>
                                <a href="#"
                                    class="d-flex fs-4 fw-bold mt-3 mb-3 justify-content-center mb-0 text-center text-gray-800 text-hover-primary">
                                    {{ ucfirst(Auth()->user()->name) }} - <div class="month_name">
                                        {{ Utility::getCurrentMonthName() }}</div></a>

                                <div class="d-flex flex-center  employe-counter">
                                    <div
                                        class="border border-gray-300 employe-counter-card border-dashed employe-counter-inner rounded mr-0 min-w-80px  emp-dash-attadance">
                                        <div class="fs-6 fw-bold text-gray-700 employe-count" id="working_day"
                                            style="font-size: 36px;"></div>
                                        <div class="fw-semibold text-gray-500 employe-title">Total Working Days</div>
                                    </div>
                                    <div
                                        class="border border-gray-300  employe-counter-card border-dashed employe-counter-inner rounded mr-0 min-w-80px  emp-dash-attadance">
                                        <div class="fs-6 fw-bold text-gray-700 employe-count" id="present_count"
                                            style="font-size: 36px;"></div>
                                        <div class="fw-semibold text-gray-500 employe-title">Present Days</div>
                                    </div>

                                    <div
                                        class="border border-gray-300 employe-counter-card border-dashed employe-counter-inner rounded mr-0 min-w-80px  emp-dash-attadance">
                                        <div class="fs-6 fw-bold text-gray-700 employe-count" id="absent_count"
                                            style="font-size: 36px;"></div>
                                        <div class="fw-semibold text-gray-500 employe-title">Absent Days</div>
                                    </div>

                                    <div
                                        class="border border-gray-300 employe-counter-card border-dashed employe-counter-inner rounded mr-0 min-w-80px  emp-dash-attadance">
                                        <div class="fs-6 fw-bold text-gray-700 employe-count" id="leave_balance"
                                            style="font-size: 36px;"></div>
                                        <div class="fw-semibold text-gray-500 employe-title">Leave Balance</div>
                                    </div>
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
                                    <table class="table table-hover table-sm  table-scrolling table-nowraps mt-6 border dataTable no-footer"
                                        id="lead_table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Lead</th>
                                                <th>Customer Name</th>
                                                <th>Lead Amount</th>
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
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No Data Available.</td>
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
                                <div id="follow_up_ajax" class=" custom-scrollbar">
                                    <table class="table table-hover table-sm  table-scrolling table-nowraps mt-6 border dataTable no-footer"
                                        id="follow_up_table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Event Name</th>
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
                                                    <td>{{ isset($follow->userDetail) ? $follow->userDetail->name : '-' }}
                                                    </td>
                                                    <td>{{ Utility::convertDmyAMPMFormat($follow->created_at) }}</td>
                                                    <td class="text-end">
                                                    <div class="icon-td">
                                                        @if (collect($accesses)->where('menu_id', '17')->first()->status == 2)
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
                                                        </div>
                                                    </td>

                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No Data Available.</td>
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
        </main>
    </div>
@endsection
@section('script')
    <script script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function(e) {
            getLeaveData();
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

        function getLeaveData() {
            var month_name = $('#month_name').val();
            $.ajax({
                type: "GET",
                url: "{{ route('emp-leave-detail') }}",
                data: {
                    month_name: month_name
                },
                success: function(res) {
                    $('.month_name').html(month_name);
                    $('#present_count').text(res.presentDays);
                    $('#absent_count').text(res.absentDays);
                    $('#leave_balance').text(res.leaveCount);
                    $('#working_day').text(res.working_days);
                }
            });
        }
        var ctx = document.getElementById('leadStatusChart').getContext('2d');
        var leadStatusChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ,
                datasets: [{
                    label: 'Leads by Status',
                    data: ,
                    backgroundColor: '#9b70ff',
                    borderColor: 'rgba(54, 162, 235, 1)',
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
                    label: 'Follow Up by Status',
                    data: @json($followStatusArray),
                    backgroundColor: '#9b70ff',
                    borderColor: 'rgba(54, 162, 235, 1)',
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
    </script>
@endsection
