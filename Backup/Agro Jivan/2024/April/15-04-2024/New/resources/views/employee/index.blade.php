@extends('layouts.main_layout')
@section('section')
    <!--begin::Main-->
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">

            <!--begin::Toolbar-->
            <div id="kt_app_toolbar" class="app-toolbar  pt-6 pb-2 ">

                <!--begin::Toolbar container-->
                <div id="kt_app_toolbar_container" class="app-container  container-fluid d-flex align-items-stretch ">
                    <!--begin::Toolbar wrapper-->
                    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">


                        <!--begin::Page title-->
                        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                            <div class="d-flex align-items-center gap-2 gap-lg-3 mt-3">
                                <a href="{{ route('employee-orders.create') }}"
                                    class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                                    <i class="fa-solid fa-plus"></i>&nbsp; Add New Order
                                </a>
                                <a href="{{ route('employee-lead.create') }}"
                                    class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                                    <i class="fa-solid fa-plus"></i>&nbsp; Add New Lead
                                </a>
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Page title-->
                        <!--begin::Actions-->
                        
                        <!--end::Actions-->
                    </div>
                    <!--end::Toolbar wrapper-->
                </div>
                <!--end::Toolbar container-->
            </div>
            <!--end::Toolbar-->

            <!--begin::Content-->
            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-fluid ">
                    <!--begin::Row-->
                    <div class="row gy-5 g-xl-10">
                        <!--begin::Col-->
                        <div class="col--6 col-xl-8 mb-xl-10">
                            <!--begin::Card widget 2-->
                            <div class="card bg-white py-4">
                                <div class="card-header flex-sm-row flex-column border-0">
                                    <div class="card-title">
                                        <div>
                                            <h3 class=" fw-bold">Order Summary </h3>
                                        </div>

                                    </div>
                                    <div class="card-title">
                                        <div class="d-flex justify-content-end flex-row-991 flex-column-1299">
                                            <input type="text" class="form-control search_date_picker"
                                                id="from_date_order" name="from_date_order"
                                                value="{{ date('Y-m-d') }}/{{ date('Y-m-d') }}">&nbsp;

                                            <input type="button" name="button" class="btn btn-primary" value="Search"
                                                onclick="getOrderDetail()">
                                        </div>
                                    </div>
                                </div>
                                <!--begin::Body-->
                                <div class="border border-gray-900 rounded min-w-80px py-3 px-4 mx-5 mb-3 d-flex justify-content-between align-items-start flex-column">

                                    <!--begin::Table container-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table table-row-dashed align-middle gs-0 gy-4 my-0 th">
                                            <!--begin::Table body-->
                                            <thead>
                                                <tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
                                                    <th class="p-0 min-w-150px border-th-0"></th>
                                                    <th class="p-0 min-w-150px border-th-0"></th>
                                                    <th class="p-0 min-w-150px border-th-0"></th>
                                                    <th class="p-0 min-w-150px border-th-0"></th>
                                                    <th class="p-0 min-w-150px border-th-0"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <a href="{{route('employee-pending-order')}}" class="text-gray-800 fw-bold d-block mb-1 fs-2"
                                                            id="pending_order_count"></a>
                                                        <span class="fw-semibold text-gray-500 d-block">Pending
                                                            Orders</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{route('employee-confirm-order')}}" class="text-gray-800 fw-bold d-block mb-1 fs-2"
                                                            id="confirm_order_count"></a>
                                                        <span class="fw-semibold text-gray-500 d-block">Confirmed
                                                            Orders</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{route('employee-cancel-order')}}" class="text-gray-800 fw-bold d-block mb-1 fs-2"
                                                            id="cancel_order_count"></a>
                                                        <span class="fw-semibold text-gray-500 d-block">Cancelled
                                                            Orders</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{route('employee-return-order')}}" class="text-gray-800 fw-bold d-block mb-1 fs-2"
                                                            id="return_order_count"></a>
                                                        <span class="fw-semibold text-gray-500 d-block">Returned
                                                            Orders</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{route('employee-completed-order')}}" class="text-gray-800 fw-bold d-block mb-1 fs-2"
                                                            id="deliver_order_count"></a>
                                                        <span class="text-gray-500 fw-semibold d-block fs-7">Delivered
                                                            Orders</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                    </div>
                                    <!--end::Table container-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card widget 2-->
                            <div class="card mt-5">
                                <div class="d-flex flex-column my-7 me-1">
                                    <div class="row align-items-center px-2">
                                        <div class="col-xl-10 col-md-8">
                                            <span class="fw-semibold fs-3x text-white lh-1 ls-n2"></span>
                                            <div class="ms-3">
                                                <h4 class="fw-semibold ">
                                                    Performance Chart</h4>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4">
                                            <select id="chart_year" name="chart_year" class="form-select"
                                                onchange="getChartData()">
                                                @foreach ($getYear as $year)
                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                    <canvas id="performance_chart" class="h-325px w-100 min-h-auto ps-4 pe-6">
                                    </canvas>
                                </div>
                            </div>
                        </div>
                        <!--end::Col-->
                        <div class="col-md-12 col-xl-4 mb-xl-10 col-xxl-4">
                            <!--begin::Card-->
                            <div class="card bg-light">

                                <!--begin::Card body-->
                                <div class="card-body d-flex flex-center flex-column pt-12 p-9">
                                    <div class="d-flex justify-content-start mb-7 w-100 flex-column-1299">
                                        <input type="text" class="form-control search_date_picker" id="leave_date_picker"
                                            name="leave_date_picker"
                                            value="{{ date('Y-m-01') }}/{{ date('Y-m-30') }}">&nbsp;

                                        <input type="button" name="button" class="btn btn-primary" value="Search"
                                            onclick="getLeavData()">
                                    </div>
                                    <!--begin::Name-->
                                    <a href="#" class="fs-4 text-gray-800 text-hover-primary fw-bold mb-0">
                                        {{ Auth()->user()->name }}</a>
                                    <!--end::Name-->

                                    <!--begin::Position-->
                                    <div class="fw-semibold text-gray-500 mb-6">{{ $attendance['role_name'] }}</div>
                                    <!--end::Position-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-center flex-wrap">
                                        <!--begin::Stats-->
                                        <div
                                            class="border border-gray-300 border-dashed rounded min-w-80px py-3 px-4 mx-2 mb-3">
                                            <div class="fs-6 fw-bold text-gray-700" id="present_count"></div>
                                            <div class="fw-semibold text-gray-500">Present Days</div>
                                        </div>
                                        <!--end::Stats-->

                                        <!--begin::Stats-->
                                        <div
                                            class="border border-gray-300 border-dashed rounded min-w-80px py-3 px-4 mx-2 mb-3">
                                            <div class="fs-6 fw-bold text-gray-700" id="absent_count"></div>
                                            <div class="fw-semibold text-gray-500">Absent Days</div>
                                        </div>
                                        <!--end::Stats-->

                                        <!--begin::Stats-->
                                        <div
                                            class="border border-gray-300 border-dashed rounded min-w-80px py-3 px-4 mx-2 mb-3">
                                            <div class="fs-6 fw-bold text-gray-700" id="leave_balance"></div>
                                            <div class="fw-semibold text-gray-500">Leave Balance</div>
                                        </div>
                                        <!--end::Stats-->
                                    </div>
                                    <!--end::Info-->

                                </div>
                                <!--end::Card body-->
                            </div>
                            <div class="card bg-light mt-5">
                                <!--begin::Card body-->
                                <h4 class="text-center mt-5">Order Competition</h4>
                                <div class="card-body d-flex flex-center flex-column pt-12 p-9">
                                    <div class="d-flex justify-content-start mb-7 w-100 flex-column-1299">
                                        <input type="text" class="form-control search_date_picker"
                                            id="winner_date_picker" name="winner_date_picker"
                                            value="{{ $attendance['order_date'] }}">&nbsp;

                                        <input type="button" name="button" class="btn btn-primary" value="Search"
                                            onclick="getwinnerCount()">
                                    </div>
                                    <!--begin::Avatar-->
                                    {{-- <div class="symbol symbol-100px symbol-circle mb-7">
                                        <img src="{{ url('/') }}/public/assets/images/certificate/1-m.png"
                                            width="150px" alt="">
                                    </div> --}}
                                    <!--end::Avatar-->
                                    <!--begin::Name-->
                                    <a href="#"
                                        class="fs-4 text-gray-800 text-hover-primary fw-bold mb-0">Winners</a>
                                    <!--end::Name-->

                                    <!--begin::Position-->
                                    <div class="fw-semibold text-gray-500 mb-6">{{ $attendance['role_name'] }}</div>
                                    <!--end::Position-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-center flex-wrap">
                                        <!--begin::Stats-->
                                        <div
                                            class="border d-flex align-items-center gap-3 border-gray-900 border-dotted rounded min-w-80px py-3 px-4 mb-3 w-100 text-center">
                                            <div class="symbol symbol-100px symbol-circle">
                                                <img src="{{ url('/') }}/public/assets/images/certificate/1-m.png"
                                                    width="150px" alt="">
                                            </div>
                                            <div>
                                            <div class="fs-4 fw-bold text-gray-900" id="first_winner_2">
                                            </div>
                                            <div class="fw-semibold text-gray-700">1<sup>st</sup> Winner</div>
                                        </div>
                                        </div>
                                        <div class="row gap-5 w-100">
                                            <div class="col-md-12 px-0">
                                                <div
                                                    class="border d-flex align-items-center gap-3 border-gray-900 border-dotted rounded min-w-80px py-3 px-1 text-center">
                                                    <div class="symbol symbol-100px symbol-circle">
                                                        <img src="{{ url('/') }}/public/assets/images/certificate/2-m.png"
                                                            width="150px" alt="">
                                                    </div>
                                                    <div>
                                                    <div class="fs-4 fw-bold text-gray-900" id='first_winner_1'>
                                                    </div>
                                                    <div class="fw-semibold text-gray-700">2<sup>nd</sup> Winner</div>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 px-0">
                                                <div
                                                    class="border d-flex align-items-center gap-3 border-gray-900 border-dotted rounded min-w-80px py-3 px-1 text-center">
                                                    <div class="symbol symbol-100px symbol-circle">
                                                        <img src="{{ url('/') }}/public/assets/images/certificate/3-m.png"
                                                            width="150px" alt="">
                                                    </div>
                                                    <div>
                                                    <div class="fs-4 fw-bold text-gray-900" id="first_winner_3">
                                                    </div>
                                                    <div class="fw-semibold text-gray-700">3<sup>rd</sup> Winner</div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!--end::Stats-->

                                        <!--begin::Stats-->

                                        <!--end::Stats-->

                                        <!--begin::Stats-->

                                        <!--end::Stats-->
                                    </div>
                                    <!--end::Info-->

                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <!--begin::Col-->
                        <div class="col-sm-6 col-xl-8 mb-xl-10">

                        </div>
                        <!--end::Col-->
                        <div class="col-sm-6 col-xl-4 mb-xl-10 col-xxl-4">
                            <!--begin::Card-->

                            <!--end::Card-->
                        </div>

                    </div>
                </div>
                <!--end::Content container-->
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
        $(document).ready(function(e) {
            getOrderDetail();
            getLeavData();
            getwinnerCount();
            getChartData();
        });
        $(function() {
            $('.search_date_picker').daterangepicker({
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
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf(
                        'month')]
                }

            }, function(start, end, label) {
                $('.search_date_picker').val(start.format('Y-0M-0D') + '/' + end.format('Y-0M-0D'));
            });
        });

        function getOrderDetail() {
            $.ajax({
                method: 'get',
                url: '{{ route("order-ajax-dashboard") }}',
                data: {
                    from: $('#from_date_order').val(),
                },
                success: function(res) {
                    $('#pending_order_count').html(res.data.pendingCount)
                    $('#cancel_order_count').html(res.data.cancelCount)
                    $('#confirm_order_count').html(res.data.confirmCount)
                    $('#deliver_order_count').html(res.data.deliverCount)
                    $('#return_order_count').html(res.data.returnCount)
                },
            })
        }

        function getLeavData() {
            $.ajax({
                method: 'get',
                url: '{{ route("leave-ajax-dashboard") }}',
                data: {
                    from: $('#leave_date_picker').val(),
                },
                success: function(res) {
                    $('#absent_count').html(res.data.absentCount)
                    $('#present_count').html(res.data.presentCount)
                    $('#leave_balance').html(Math.abs(res.data.leave_balance))
                },
            })
        }

        function getOrderCount() {
            $.ajax({
                method: 'get',
                url: '{{ route("leave-ajax-dashboard") }}',
                data: {
                    from: $('#order_date_picker').val(),
                },
                success: function(res) {
                    $('#absent_count').html(res.data.absentCount)
                    $('#present_count').html(res.data.presentCount)
                    $('#leave_balance').html(res.data.leave_balance)
                },
            })
        }

        function getwinnerCount() {
            $.ajax({
                method: 'get',
                url: '{{ route("winner-ajax-dashboard") }}',
                data: {
                    from: $('#winner_date_picker').val(),
                },
                success: function(res) {
                    console.log(res);
                    $.each(res.data, function(i, v) {
                        if (i <= 3) {
                            $('#first_winner_' + i).html(v['winner']+" - " + v['order'])
                        }
                    })
                },
            })
        }

        function getChartData() {
            $.ajax({
                url: "{{ route('chart-ajax-data') }}",
                method: 'GET',
                data: {
                    year: $('#chart_year').val(),
                },
                success: function(response) {
                    var labels = [];
                    var pending = [];
                    var confirm = [];
                    var return1 = [];
                    response.forEach(function(item) {
                        labels.push(item.month);
                        pending.push(item.pending);
                        confirm.push(item.confirm);
                        return1.push(item.return);
                    });

                    var ctx = document.getElementById('performance_chart').getContext('2d');
                    var chartInstance = Chart.getChart(ctx);
                    if (chartInstance) {
                        chartInstance.destroy();
                    }
                    var chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Pending Order',
                                backgroundColor: '#ffcc5b',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1,
                                data: pending
                            }, {
                                label: 'Confirm Order',
                                backgroundColor: '#007f3e',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1,
                                data: confirm
                            }, {
                                label: 'Return Order',
                                backgroundColor: '#17c653',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1,
                                data: return1
                            }]
                        },
                    });
                }
            });
        }
    </script>
@endsection
