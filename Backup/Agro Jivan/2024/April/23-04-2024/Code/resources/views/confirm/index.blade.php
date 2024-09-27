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
                        <div class="row">
                            <div class="col-xxl-8 col-xl-8 col-md-8 col-8 mb-2">
                                <input type="text" name="search_order_picker" id="search_order_picker"
                                    value="{{ date('Y-m-d') }}/{{ date('Y-m-d') }}" class="form-control search_order_picker">
                            </div>
                            <div class="col-xl-4 col-4 mb-2">
                                <input type="button" name="search_order" id="search_order" value="search"
                                    onclick="ordersDetailAjax()" class="btn btn-primary">
                            </div>
                        </div> 
                        <div class="d-flex align-items-center gap-2 gap-lg-3">
                            <a href="{{ route('confirm-orders.create') }}"
                                class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                                <i class="fa-solid fa-plus"></i>&nbsp; New Orders
                            </a>
                            <a href="{{ route('confirm-manual-order') }}"
                                class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                                <i class="fa-solid fa-eye"></i>&nbsp; Pending Orders
                            </a>
                        </div>
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
                        <div class="col-md-12">
                            <div class="row pt-0 gy-5 g-xl-10 agro-parent-main-dashboard">
                                <!--begin::Col-->
                                <div class="agro-main-dashboard-child">
                                    <a href="{{ route('confirmation-order-list') }}?status=1">
                                        <!--begin::Card widget 2-->
                                        <div class="card h-lg-100 bg-grow-early">
                                            <!--begin::Body-->
                                            <div
                                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                                <!--begin::Icon-->
                                                <div class="m-0">
                                                    <i class="ki-outline ki-notification-on fs-2hx text-gray-600"></i>

                                                </div>
                                                <!--end::Icon-->

                                                <!--begin::Section-->
                                                <div class="d-flex flex-column my-7">
                                                    <!--begin::Number-->
                                                    <span
                                                        class="fw-semibold fs-3x text-white lh-1 ls-n2" id="total_order_count"></span>
                                                    <!--end::Number-->

                                                    <!--begin::Follower-->
                                                    <div class="m-0">
                                                        <span class="fw-semibold fs-2x text-white">
                                                            Total Orders </span>

                                                    </div>
                                                    <!--end::Follower-->
                                                </div>
                                                <!--end::Section-->
                                            </div>
                                            <!--end::Body-->
                                        </div>
                                    </a>
                                    <!--end::Card widget 2-->


                                </div>
                                <!--end::Col-->
                                <div class="agro-main-dashboard-child">
                                    <a href="{{ route('confirmation-order-list') }}?status=1">
                                        <!--begin::Card widget 2-->
                                        <div class="card h-lg-100 bg-arielle-smile">
                                            <!--begin::Body-->
                                            <div
                                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                                <!--begin::Icon-->
                                                <div class="m-0">
                                                    <i class="ki-outline ki-abstract-41 fs-2hx text-gray-600"></i>

                                                </div>
                                                <!--end::Icon-->

                                                <!--begin::Section-->
                                                <div class="d-flex flex-column my-7">
                                                    <!--begin::Number-->
                                                    <span
                                                        class="fw-semibold fs-3x text-white lh-1 ls-n2" id="pending_order_count"></span>
                                                    <!--end::Number-->

                                                    <!--begin::Follower-->
                                                    <div class="m-0">
                                                        <span class="fw-semibold fs-2x text-white">
                                                            Pending Orders </span>

                                                    </div>
                                                    <!--end::Follower-->
                                                </div>
                                                <!--end::Section-->
                                            </div>
                                            <!--end::Body-->
                                        </div>
                                    </a>
                                    <!--end::Card widget 2-->


                                </div>
                                <!--end::Col-->

                                <!--begin::Col-->
                                <div class="agro-main-dashboard-child">
                                    <a href="{{ route('confirmation-order-list') }}?status=2">
                                        <!--begin::Card widget 2-->
                                        <div class="card h-lg-100 bg-midnight-bloom">
                                            <!--begin::Body-->
                                            <div
                                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                                <!--begin::Icon-->
                                                <div class="m-0">
                                                    <i class="ki-outline ki-notification-on fs-2hx text-gray-600"></i>

                                                </div>
                                                <!--end::Icon-->

                                                <!--begin::Section-->
                                                <div class="d-flex flex-column my-7">
                                                    <!--begin::Number-->
                                                    <span
                                                        class="fw-semibold fs-3x text-white lh-1 ls-n2" id="confirmed_order_count"></span>
                                                    <!--end::Number-->

                                                    <!--begin::Follower-->
                                                    <div class="m-0">
                                                        <span class="fw-semibold fs-2x text-white">
                                                            Confirmed Orders </span>

                                                    </div>
                                                    <!--end::Follower-->
                                                </div>
                                                <!--end::Section-->
                                            </div>
                                            <!--end::Body-->
                                        </div>
                                    </a>
                                    <!--end::Card widget 2-->


                                </div>
                                <!--end::Col-->

                                <!--begin::Col-->
                                <div class="agro-main-dashboard-child">
                                    <a href="{{ route('confirmation-order-list') }}?status=6">
                                        <!--begin::Card widget 2-->
                                        <div class="card h-lg-100 bg-grow-ice">
                                            <!--begin::Body-->
                                            <div
                                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                                <!--begin::Icon-->
                                                <div class="m-0">
                                                    <i class="ki-outline ki-notification-on fs-2hx text-gray-600"></i>

                                                </div>
                                                <!--end::Icon-->

                                                <!--begin::Section-->
                                                <div class="d-flex flex-column my-7">
                                                    <!--begin::Number-->
                                                    <span
                                                        class="fw-semibold fs-3x text-white lh-1 ls-n2" id="delivered_order_count"></span>
                                                    <!--end::Number-->

                                                    <!--begin::Follower-->
                                                    <div class="m-0">
                                                        <span class="fw-semibold fs-2x text-white">
                                                            Delivered Orders </span>

                                                    </div>
                                                    <!--end::Follower-->
                                                </div>
                                                <!--end::Section-->
                                            </div>
                                            <!--end::Body-->
                                        </div>
                                    </a>
                                    <!--end::Card widget 2-->


                                </div>
                                <!--end::Col-->

                                <!--begin::Col-->
                                <div class="agro-main-dashboard-child ">
                                    <a href="{{ route('confirmation-order-list') }}?status=4">
                                        <!--begin::Card widget 2-->
                                        <div class="card h-lg-100 bg-grow-stone">
                                            <!--begin::Body-->
                                            <div
                                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                                <!--begin::Icon-->
                                                <div class="m-0">
                                                    <i class="ki-outline ki-cross fs-2hx text-gray-600"></i>

                                                </div>
                                                <!--end::Icon-->

                                                <!--begin::Section-->
                                                <div class="d-flex flex-column my-7">
                                                    <!--begin::Number-->
                                                    <span
                                                        class="fw-semibold fs-3x text-white lh-1 ls-n2" id="cancel_order_count"></span>
                                                    <!--end::Number-->

                                                    <!--begin::Follower-->
                                                    <div class="m-0">
                                                        <span class="fw-semibold fs-2x text-white">
                                                            Cancelled Orders </span>

                                                    </div>
                                                    <!--end::Follower-->
                                                </div>
                                                <!--end::Section-->
                                            </div>
                                            <!--end::Body-->
                                        </div>
                                    </a>
                                    <!--end::Card widget 2-->


                                </div>
                                <div class="agro-main-dashboard-child">
                                    <a href="{{ route('confirmation-order-list') }}?status=5">
                                        <!--begin::Card widget 2-->
                                        <div class="card h-lg-100 bg-grow-early">
                                            <!--begin::Body-->
                                            <div
                                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                                <!--begin::Icon-->
                                                <div class="m-0">
                                                    <i class="ki-outline ki-educare fs-2hx text-gray-600"></i>

                                                </div>
                                                <!--end::Icon-->

                                                <!--begin::Section-->
                                                <div class="d-flex flex-column my-7">
                                                    <!--begin::Number-->
                                                    <span
                                                        class="fw-semibold fs-3x text-white lh-1 ls-n2" id="return_order_count"></span>
                                                    <!--end::Number-->

                                                    <!--begin::Follower-->
                                                    <div class="m-0">
                                                        <span class="fw-semibold fs-2x text-white">
                                                            Returned Orders </span>

                                                    </div>
                                                    <!--end::Follower-->
                                                </div>
                                                <!--end::Section-->
                                            </div>
                                            <!--end::Body-->
                                        </div>
                                    </a>
                                    <!--end::Card widget 2-->


                                </div>
                                <!--end::Col-->

                            </div>
                        </div>
                        <div class="col-xxl-8 col-md-12">
                        <div class="card mt-5">
                                <div class="d-flex flex-column my-7 me-1">
                                    <div class="row mx-5 align-items-center">
                                        <div class="col-xl-8 col-md-8">
                                            <span class="fw-semibold fs-3x text-white lh-1 ls-n2"></span>
                                            <div class="ms-3">
                                                <h4 class="fw-semibold ">
                                                    <b>Orders Overview</b></h4>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4">
                                            <input type="text" class="form-control search_date" id="search_date"
                                                name="search_date" value="">
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="card-body d-flex justify-content-between align-items-start flex-column w-50 m-auto">
                                    <canvas id="orders_chart" class="ps-4 pe-6"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-12 mb-xl-10">
                            <!--begin::Card-->
                            <div class="card bg-light">
                                <!--begin::Card body-->
                                <h4 class="text-center mt-5">Order Competition</h4>
                                <div class="card-body d-flex flex-center flex-column pt-12 p-9">
                                    <div class="d-flex justify-content-start mb-7 w-100 flex-column-1299">
                                        <input type="text" class="form-control search_date_picker"
                                            id="winner_date_picker" name="winner_date_picker" value="">&nbsp;

                                        <input type="button" name="button" class="btn btn-primary" value="Search"
                                            onclick="getwinnerCount()">
                                    </div>
                                    <!--begin::Avatar-->
                                    <div class="symbol symbol-100px symbol-circle mb-7">
                                        <img src="{{ url('/') }}/public/assets/media/dashboard/winner.png"
                                            width="150px" alt="">
                                    </div>
                                    <!--end::Avatar-->
                                    <!--begin::Name-->
                                    <a href="#"
                                        class="fs-4 text-gray-800 text-hover-primary fw-bold mb-0">Winners</a>
                                    <!--end::Name-->

                                    <!--begin::Position-->
                                    <div class="fw-semibold text-gray-500 mb-6"></div>
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
                    </div>

                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->

        </div>
        <!--end::Content wrapper-->




        <!--end::Menu-->
    </div>
    <!--end::Footer container-->
    </div>
    <!--end::Footer-->
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
            $('.search_date_picker').val(moment().subtract(6, 'days').format('Y-0M-0D') + '/' + moment().format(
                'Y-0M-0D'));
            $('.search_date').val(moment().format('Y-MM-DD') + '/' + moment().format(
                'Y-MM-DD'));
            ordersDetailAjax();
            getwinnerCount();
            chartDetail()
        })
        $(function() {
            $('.search_date_picker').daterangepicker({
                autoUpdateInput: false,
                maxDate: moment(),
                startDate: moment().subtract(6, 'days'),
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
        $(function() {
            $('.search_order_picker').daterangepicker({
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
                $('.search_order_picker').val(start.format('Y-MM-DD') + '/' + end.format('Y-MM-DD'));
            });
        });

        

        function getwinnerCount() {
            $.ajax({
                method: 'get',
                url: '{{ route("confirm-winner-ajax-dashboard") }}',
                data: {
                    from: $('#winner_date_picker').val(),
                },
                success: function(res) {
                    $.each(res.data, function(i, v) {
                        if (i <= 3) {
                            $('#first_winner_' + i).html(v['winner'] + " - "+ v.order)
                        }
                    })
                },
            })
        }
        $(function() {
            $('.search_date').daterangepicker({
                autoUpdateInput: false,
                maxDate: moment(),
            }, function(start, end, label) {
                $('.search_date').val(start.format('Y-MM-DD') + '/' + end.format('Y-MM-DD'));
                chartDetail();
            });
        });

        function chartDetail() {
            var date = $('#search_date').val();
            $.ajax({
                method: 'get',
                url: '{{ route("chart-dashboard-ajax") }}',
                data: {
                    date: date
                },
                success: function(res) {
                    var ctx = document.getElementById('orders_chart').getContext('2d');
                    var chartInstance = Chart.getChart(ctx);
                    if (chartInstance) {
                        chartInstance.destroy();
                    }
                    var chart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: res.labels,
                            datasets: [{
                                data: res.data,
                                backgroundColor: [
                                    '#3498db',
                                    '#9b59b6',
                                    '#2ecc71',
                                    '#e74c3c',
                                    '#f39c12',
                                    '#ed6362',
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(153, 102, 255, 1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                align:'start'
                            }
                        }
                    });
                },
            })
        }
        function ordersDetailAjax(){
            $.ajax({
                method: 'get',
                url: '{{ route("order-dashboard-ajax") }}',
                data: {
                    date: $('#search_order_picker').val(),
                },
                success: function(res) {
                    $('#total_order_count').html(res.totalCount);
                    $('#pending_order_count').html(res.pendingCount);
                    $('#confirmed_order_count').html(res.confirmCount);
                    $('#delivered_order_count').html(res.completeCount);
                    $('#cancel_order_count').html(res.cancelCount);
                    $('#return_order_count').html(res.returnCount);
                },
            })
        }
    </script>
@endsection
