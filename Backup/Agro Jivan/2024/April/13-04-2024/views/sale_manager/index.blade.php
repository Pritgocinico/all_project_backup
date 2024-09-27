@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                <div id="kt_app_content_container" class="app-container container-fluid ">
                    <div class="row gy-5 g-xl-10">
                        <div class="col-md-12">
                            <div class="row pt-5 gy-5 mt-3  agro-parent-main-dashboard">
                                <div class="agro-main-dashboard-child">
                                    <a href="{{ route('sale-orders.index') }}">
                                        <div class="card h-lg-100 bg-grow-early">
                                            <div
                                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                                <div class="d-flex flex-column my-7">
                                                    <span
                                                        class="fw-semibold fs-3x text-white lh-1 ls-n2">{{ $pendingCount }}</span>
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
                                    <a href="{{ route('sale-confirm-order') }}">
                                        <div class="card h-lg-100 bg-arielle-smile">
                                            <div
                                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                                <div class="d-flex flex-column my-7">
                                                    <span
                                                        class="fw-semibold fs-3x lh-1 ls-n2 text-white">{{ $confirmCount }}</span>
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
                                    <a href="{{ route('sale-deliver-order') }}">
                                        <div class="card h-lg-100 bg-midnight-bloom">
                                            <div
                                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                                <div class="d-flex flex-column my-7">
                                                    <span
                                                        class="fw-semibold fs-3x lh-1 ls-n2 text-white">{{ $deliverCount }}</span>
                                                    <div class="m-0">
                                                        <span class="fw-semibold fs-2x text-white">
                                                            Delivered Orders </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="agro-main-dashboard-child">
                                    <a href="{{ route('sales-stock-list') }}">
                                        <div class="card h-lg-100 bg-grow-ice">
                                            <div
                                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                                <div class="d-flex flex-column my-7">
                                                    <span class="fw-semibold fs-3x lh-1 ls-n2 text-white">
                                                        {{ $inOutStockCount->total_in_stock + $inOutStockCount->total_out_stock }}
                                                    </span>
                                                    <div class="m-0">
                                                        <span class="fw-semibold fs-2x text-white">
                                                            Stock</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="agro-main-dashboard-child ">
                                    <a href="{{ route('cancel-order') }}">
                                        <div class="card h-lg-100 bg-grow-stone">
                                            <div
                                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                                <div class="d-flex flex-column my-7">
                                                    <span
                                                        class="fw-semibold fs-3x lh-1 ls-n2 text-white">{{ $cancelCount }}</span>
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
                                    <a href="{{ route('return-order') }}">
                                        <div class="card h-lg-100 bg-grow-early">
                                            <div
                                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                                <div class="d-flex flex-column my-7">
                                                    <span
                                                        class="fw-semibold fs-3x lh-1 ls-n2 text-white">{{ $returnCount }}</span>
                                                    <div class="m-0">
                                                        <span class="fw-semibold fs-2x text-white">
                                                            Returned Orders </span>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="agro-main-dashboard-child">
                                    <a href="{{ route('sale-employee') }}">
                                        <div class="card h-lg-100 bg-arielle-smile">
                                            <div
                                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                                <div class="d-flex flex-column my-7">
                                                    <span
                                                        class="fw-semibold fs-3x lh-1 ls-n2 text-white">{{ $totalEmployee }}</span>
                                                    <div class="m-0">
                                                        <span class="fw-semibold fs-2x text-white">
                                                            Total Employee</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-lg-12">
                            <div class="card bg-light">
                                <h4 class="text-center mt-5 pt-5">Order Competition</h4>
                                <div class="card-body d-flex flex-center flex-column pt-12 p-9">
                                    <div class="d-flex justify-content-start mb-7 flex-column-1299 w-100">
                                        <input type="text" class="form-control search_date_picker"
                                            id="winner_date_picker" name="winner_date_picker" value="">&nbsp;

                                        <input type="button" name="button" class="btn btn-primary" value="Search"
                                            onclick="getwinnerCount()">
                                    </div>
                                    <div class="symbol symbol-100px symbol-circle mb-7">
                                        <img src="{{ url('/') }}/public/assets/media/dashboard/winner.png"
                                            width="150px" alt="">
                                    </div>
                                    <a href="#"
                                        class="fs-4 text-gray-800 text-hover-primary fw-bold mb-0">Winners</a>
                                    <div class="fw-semibold text-gray-500 mb-6"></div>
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

    </body>
@endsection
@section('page')
    <script>
        $(document).ready(function(e) {
            $('.search_date_picker').val(moment().subtract(6, 'days').format('Y-0M-0D') + '/' + moment().format(
                'Y-0M-0D'));
            getwinnerCount();
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
        function getwinnerCount() {
            $.ajax({
                method: 'get',
                url: '{{ route('sale-winner-ajax-dashboard') }}',
                data: {
                    from: $('#winner_date_picker').val(),
                },
                success: function(res) {
                    $.each(res.data, function(i, v) {
                        if (i <= 3) {
                            $('#first_winner_' + i).html(v['winner'])
                        }
                    })
                },
            })
        }
    </script>
@endsection