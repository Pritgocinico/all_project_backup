@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                <div id="kt_app_content_container" class="app-container container-fluid ">
                    <div class="row gy-5 g-xl-10">
                        <div class="col-md-12">
                            <div class="row gy-5 agro-parent-main-dashboard">
                                <div class="agro-main-dashboard-child">
                                    <a href="{{ route('delivered-order-list') }}">
                                        <div class="card h-lg-100 bg-grow-early">
                                            <div
                                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                                <div class="d-flex flex-column my-7">
                                                    <span
                                                        class="fw-semibold fs-3x text-white lh-1 ls-n2">{{$orderCount}}</span>
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
                                    <a href="{{ route('sale-order-feedback-list') }}">
                                        <div class="card h-lg-100 bg-arielle-smile">
                                            <div
                                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                                <div class="d-flex flex-column my-7">
                                                    <span
                                                        class="fw-semibold fs-3x text-white lh-1 ls-n2">{{$feedbackCount}}</span>
                                                    <div class="m-0">
                                                        <span class="fw-semibold fs-2x text-white">
                                                            Orders Feedback </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
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