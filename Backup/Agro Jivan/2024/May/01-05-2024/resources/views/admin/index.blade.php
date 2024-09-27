@extends('layouts.main_layout')
@section('section')
<div class="app-main flex-column flex-row-fluid " id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar  pt-6 pb-2 ">
            <div id="kt_app_toolbar_container" class="container-fluid d-flex align-items-stretch ">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="row">

                        <div class="col-xxl-8 col-xl-8 col-md-8 col-8 mb-2">
                            <input type="text" name="search_date" id="search_date" value="{{ date('Y-m-d') }}/{{ date('Y-m-d') }}" class="form-control search_date">
                        </div>
                        <div class="col-xl-4 col-4 mb-2">
                            <input type="button" name="search_order" id="search_order" value="search" onclick="ordersDetailAjax()" class="btn btn-primary">
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <a href="{{ route('employees.create') }}" class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold custom-button white">
                            <i class="fa-solid fa-plus"></i>&nbsp; Add Employee
                        </a>
                        <a href="{{ route('info-sheet.create') }}" class="btn btn-flex btn-primary h-40px fs-7 fw-bold custom-button black">
                            <i class="fa-solid fa-plus"></i>&nbsp; Add Info-sheet
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div id="kt_app_content" class="app-content  flex-column-fluid ">
            <div id="kt_app_content_container" class="app-container container-fluid ">
                <div class="row gy-5 g-xl-10">
                    <div class="col-md-12">
                        <div class="row gy-5 agro-parent-main-dashboard">
                            <div class="agro-main-dashboard-child">
                                <a href="{{ route('confirm-order-query') }}?status=1">
                                    <div class="card h-lg-100 bg-grow-early pending_order_card_dashboard">
                                        <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                            <div class="m-0">
                                                <i class="ki-outline ki-abstract-41 fs-2hx text-gray-600"></i>
                                            </div>
                                            <div class="d-flex flex-column my-7">
                                                <span class="fw-semibold fs-3x text-white lh-1 ls-n2" id="pending_order_count"></span>
                                                <div class="m-0">
                                                    <span class="fw-semibold fs-2x text-white">
                                                        Pending Orders </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="agro-main-dashboard-child">
                                <a href="{{ route('confirm-order-query') }}?status=2">
                                    <div class="card h-lg-100 bg-arielle-smile confirm_order_card_dashboard">
                                        <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                            <div class="m-0">
                                                <i class="ki-outline ki-notification-on fs-2hx text-gray-600"></i>
                                            </div>
                                            <div class="d-flex flex-column my-7">
                                                <span class="fw-semibold fs-3x lh-1 ls-n2 text-white" id="confirm_order_count"></span>
                                                <div class="m-0">
                                                    <span class="fw-semibold fs-2x text-white">
                                                        Confirmed Orders </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="agro-main-dashboard-child">
                                <a href="{{ route('confirm-order-query') }}?status=6">
                                    <div class="card h-lg-100 bg-midnight-bloom other_order_card_dashboard">
                                        <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                            <div class="m-0">
                                                <i class="ki-outline ki-notification-on fs-2hx text-gray-600"></i>
                                            </div>
                                            <div class="d-flex flex-column my-7">
                                                <span class="fw-semibold fs-3x lh-1 ls-n2 text-white" id="delivered_order_count"></span>
                                                <div class="m-0">
                                                    <span class="fw-semibold fs-2x text-white">
                                                        Delivered Orders </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="agro-main-dashboard-child ">
                                <a href="{{ route('confirm-order-query') }}?status=4">
                                    <div class="card h-lg-100 bg-grow-ice other_order_card_dashboard">
                                        <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                            <div class="m-0">
                                                <i class="ki-outline ki-cross fs-2hx text-gray-600"></i>
                                            </div>
                                            <div class="d-flex flex-column my-7">
                                                <span class="fw-semibold fs-3x lh-1 ls-n2 text-white" id="cancel_order_count"></span>
                                                <div class="m-0">
                                                    <span class="fw-semibold fs-2x text-white">
                                                        Cancelled Orders </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="agro-main-dashboard-child">
                                <a href="{{ route('confirm-order-query') }}?status=5">
                                    <div class="card h-lg-100 bg-grow-stone other_order_card_dashboard">
                                        <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                            <div class="m-0">
                                                <i class="ki-outline ki-educare fs-2hx text-gray-600"></i>
                                            </div>
                                            <div class="d-flex flex-column my-7">
                                                <span class="fw-semibold fs-3x lh-1 ls-n2 text-white" id="return_order_count"></span>
                                                <div class="m-0">
                                                    <span class="fw-semibold fs-2x text-white">
                                                        Returned Orders </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row gy-5 g-xl-10 mt-3 agro-parent-main-dashboard">
                    <div class="agro-main-dashboard-child">
                        <a href="{{ route('daily-attendance') }}?type=all&status=present">
                            <div class="card h-lg-100 bg-custom-purple">
                                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                    <div class="m-0">
                                        <i class="ki-outline ki-icon fs-3 text-gray-600"></i>
                                    </div>
                                    <div class="d-flex flex-column my-7">
                                        <span class="fw-semibold fs-3x lh-1 ls-n2 text-white">{{ $totalEmployeePresent }}</span>
                                        <div class="m-0">
                                            <div class="m-0">
                                                <span class="fw-semibold fs-2x text-white">
                                                    Total Present</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="agro-main-dashboard-child">
                        <a href="{{ route('daily-attendance') }}?type=all&status=absent">
                            <div class="card h-lg-100 bg-custom-red">
                                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                    <div class="m-0">
                                        <i class="ki-outline ki-icon fs-3 text-gray-600"></i>
                                    </div>
                                    <div class="d-flex flex-column my-7">
                                        <span class="fw-semibold fs-3x lh-1 ls-n2 text-white">{{ $totalEmployeeAbsent }}</span>
                                        <div class="m-0">
                                            <span class="fw-semibold fs-2x text-white">
                                                Total Absent</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="agro-main-dashboard-child">
                        <a href="{{ route('daily-attendance') }}?type=manager&status=present">
                            <div class="card h-lg-100 bg-custom-green">
                                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                    <div class="m-0">
                                        <i class="ki-outline ki-icon fs-3 text-gray-600"></i>
                                    </div>
                                    <div class="d-flex flex-column my-7">
                                        <span class="fw-semibold fs-3x lh-1 ls-n2 text-white">{{ $totalManagerPresent }}</span>
                                        <div class="m-0">
                                            <span class="fw-semibold fs-2x text-white">
                                                Present Manager</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="agro-main-dashboard-child">
                        <a href="{{ route('daily-attendance') }}?type=manager&status=absent">
                            <div class="card h-lg-100 bg-custom-purple">
                                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                    <div class="m-0">
                                        <i class="ki-outline ki-icon fs-3 text-gray-600"></i>
                                    </div>
                                    <div class="d-flex flex-column my-7">
                                        <span class="fw-semibold fs-3x lh-1 ls-n2 text-white">{{ $totalManagerAbsent }}</span>
                                        <div class="m-0">
                                            <span class="fw-semibold fs-2x text-white">
                                                Absent manager</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="agro-main-dashboard-child cursor-pointer" onclick="showDeliveryBatch(1)">
                        <a href="{{ route('admin-batch-list') }}?status=1">
                            <div class="card h-lg-100 bg-custom-red">
                                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                    <div class="m-0">
                                        <i class="ki-outline ki-icon fs-3 text-gray-600"></i>
                                    </div>
                                    <div class="d-flex flex-column my-7">
                                        <span class="fw-semibold fs-3x lh-1 ls-n2 text-white">{{ $totalBatchRoute }}</span>
                                        <div class="m-0">
                                            <span class="fw-semibold fs-2x text-white">
                                                On Route Drivers</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div id="kt_app_content_container" class="container-fluid mt-5">
                    <h5>On Route Delivery Detail</h5>
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                <input type="text" data-kt-user-table-filter="search" id="search_data" class="form-control w-250px ps-13" placeholder="Search" onkeyup="batchAjaxList(1)" />
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                <div class="parent-filter-menu">
                                    <button type="button" class="btn btn-light-primary me-3 order_filter_option" id="search_main_menu">
                                        <i class="ki-outline ki-filter fs-2"></i> Filter
                                    </button>
                                    <div class="menu filter-menu w-300px w-md-325px" data-kt-menu="true">
                                        <div class="px-7 py-5">
                                            <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                                        </div>
                                        <div class="separator border-gray-200"></div>
                                        <div class="px-7 py-5" data-kt-user-table-filter="form">
                                            <div class="mb-10">
                                                <label class="form-label fs-6 fw-semibold">Search
                                                    Driver:</label>
                                                <select name="diver_id" id="diver_id" class="form-control">
                                                    <option value="">Select Driver</option>
                                                    @foreach ($driverList as $driver)
                                                    <option value="{{ $driver->id }}">{{ $driver->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-10">
                                                <label class="form-label fs-6 fw-semibold">Date:</label>
                                                <input type="text" name="batch_date" id="batch_date" class="form-control form-select-solid fw-bold search_batch_date" max="{{ date('Y-m-d') }}" placeholder="Select Date">
                                            </div>

                                            <div class="d-flex justify-content-end">
                                                <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6 order_filter_option" data-kt-menu-dismiss="true" onclick="resetForm()" data-kt-user-table-filter="reset">Reset</button>
                                                <button type="button" class="btn btn-primary fw-semibold px-6 order_filter_option" data-kt-menu-dismiss="true" data-kt-user-table-filter="filter" onclick="batchAjaxList(1)">Apply</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-body py-4 table-responsive" id="batch_data_table"></div>
                </div>
                <div id="kt_app_content_container" class="container-fluid mt-5">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <h5>Team List</h5>
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                <div class="parent-filter-menu">
                                    <input type="text" name="team_search_date" id="team_search_date" value="{{ date('Y-m-d') }}/{{ date('Y-m-d') }}" class="form-control team_search_date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="team_list_ajax"></div>
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

</body>
@endsection
@section('page')
<script>
    var route = "{{route("team.show",'id')}}";
    $(document).ready(function(e) {
        $(function() {
            $('.search_date').daterangepicker({
                autoUpdateInput: false,
                maxDate: moment(),
                startDate: moment(),
                endDate: moment(),
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment()
                        .subtract(1,
                            'month').endOf(
                            'month')
                    ]
                }

            }, function(start, end, label) {
                $('.search_date').val(start.format('Y-MM-DD') + '/' + end.format('Y-MM-DD'));
            });
        });
        $(function() {
            $('.team_search_date').daterangepicker({
                autoUpdateInput: false,
                maxDate: moment(),
                startDate: moment(),
                endDate: moment(),
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment()
                        .subtract(1,
                            'month').endOf(
                            'month')
                    ]
                }

            }, function(start, end, label) {
                $('.team_search_date').val(start.format('Y-MM-DD') + '/' + end.format('Y-MM-DD'));
                teamAjaxList();
            });
        });
        $(function() {
            $('.search_batch_date').daterangepicker({
                autoUpdateInput: false,
                maxDate: moment(),
            }, function(start, end, label) {
                $('.search_batch_date').val(start.format('Y-MM-DD') + '/' + end.format(
                    'Y-MM-DD'));
            });
        });
        ordersDetailAjax();
        batchAjaxList(1);
        teamAjaxList();
    })

    function ordersDetailAjax() {
        var search = $('#search_date').val()
        $.ajax({
            method: 'get',
            url: '{{ route("admin-dashboard-order-ajax") }}',
            data: {
                search_date: search,
            },
            success: function(res) {
                $('#pending_order_count').html(res.pendingCount)
                $('#confirm_order_count').html(res.confirmCount)
                $('#delivered_order_count').html(res.completeCount)
                $('#cancel_order_count').html(res.cancelCount)
                $('#return_order_count').html(res.returnCount)
            },
        })
    }

    function resetForm() {
        $('#search_data').val('')
        $('#diver_id').val('')
        $('#batch_date').val('')
        batchAjaxList(1)
    }

    function batchAjaxList(page) {
        $.ajax({
            method: 'get',
            url: "{{ route('dashboard-batch-ajax') }}",
            data: {
                page: page,
                search: $('#search_data').val(),
                diver_id: $('#diver_id').val(),
                batch_date: $('#batch_date').val(),
            },
            success: function(res) {
                $('#batch_data_table').html('')
                $('#batch_data_table').html(res)
            },
        })
    }
    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        var page = $(this).attr('href').split('page=')[1];
        batchAjaxList(page);
    });
    function teamAjaxList(){
        $.ajax({
            method:"get",
            url:"{{route('dashboard-team-ajax')}}",
            data:{
                date:$('#team_search_date').val(),  
            }, success: function(res){
                $('#team_list_ajax').html('')
                $('#team_list_ajax').html(res)
            }
        })
    }
    function teamViewAll(id){
        var url  = route.replace('id',id);
        var date = $('#team_search_date').val();
        window.open(url+"?date="+date);
    }
</script>
@endsection