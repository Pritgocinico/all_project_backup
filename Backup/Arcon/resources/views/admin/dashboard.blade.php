@extends('admin.layouts.app')
@section('style')
<script type="text/javascript" src="{{url('/')}}/assets/admin/js/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);
      google.charts.setOnLoadCallback(drawChart1);
      function drawChart() {
        var data = google.visualization.arrayToDataTable({{ Js::from($result) }});
        var options = {
            chart: {
                title: '',
                subtitle: '',
            },
            hAxis: {
                textStyle: {
                    fontSize: 9
                },
            },
            colors: ["#a6ce39"],
        };
        var chart = new google.charts.Bar(document.getElementById('barchart_material'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
      function drawChart1() {
        var data1 = google.visualization.arrayToDataTable({{ Js::from($revenue) }});
        var options1 = {
            hAxis: {
                textStyle: {
                    fontSize: 9
                },
            },
            colors: ["#006733"],
        };
        var chart1 = new google.charts.Bar(document.getElementById('barchart_material1'));
        chart1.draw(data1, google.charts.Bar.convertOptions(options1));
      }
    </script>
@endsection
@section('content')
<div class="row row-cols-1 row-cols-md-3 g-4">
    <div class="col-lg-4 col-md-12">
        <div class="card card-bg2 h-100 p-2">
            <div class="card-body">
                <div class="d-flex">
                    <div>
                        <p class="mb-0 fs-20"><i> Total <b>Products</b></i></p>
                        <div class="d-flex mb-2">
                            <?php 
                            $product_count = DB::table('products')->count();     
                            ?>
                            <h1 class="mb-0">{{$product_count}}</h1>
                            <!-- <div class="ms-auto" id="total-orders"></div> -->
                        </div>
                        <p>{{$product_count}}+ total products...</p>
                    </div>
                    <div class="dropdown ms-auto">
                        <a href="#" data-bs-toggle="dropdown" class="btn btn-sm" aria-haspopup="true"
                        aria-expanded="false">
                            <i class="bi bi-three-dots-vertical fs-18 card-color1"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="" class="dropdown-item">View Pages</a>
                        </div>
                    </div>
                </div>
                <a href="{{route('admin.products')}}" class="card_link"></a>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12">
        <div class="card card-bg3 h-100 p-2">
            <div class="card-body">
                <div class="d-flex">
                    <div>
                        <p class="mb-0 fs-20"><i> Total <b>Orders</b></i></p>
                        <?php 
                            $order_count = DB::table('orders')->count();     
                        ?>
                        <div class="d-flex mb-2">
                            <h1 class="mb-0">{{$order_count}}</h1>
                            <!-- <div class="ms-auto" id="total-orders"></div> -->
                        </div>
                        <p>{{$order_count}}+ total orders...</p>
                    </div>
                    <div class="dropdown ms-auto">
                        <a href="#" data-bs-toggle="dropdown" class="btn btn-sm" aria-haspopup="true"
                        aria-expanded="false">
                            <i class="bi bi-three-dots-vertical fs-18 card-color1"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="" class="dropdown-item">View Posts</a>
                        </div>
                    </div>
                </div>
                <a href="{{route('admin.orders')}}" class="card_link"></a>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12">
        <div class="card card-bg1 h-100 p-2">
            <div class="card-body">
                <div class="d-flex">
                    <div>
                        <p class="mb-0 fs-20"><i> Total <b>Users</b></i></p>
                        <div class="d-flex mb-2">
                            <?php $user_count = DB::table('users')->where('role',2)->count(); ?>
                            <h1 class="mb-0">{{$user_count}}</h1>
                            <!-- <div class="ms-auto" id="total-orders"></div> -->
                        </div>
                        <p>{{$user_count}}+ total staff...</p>
                    </div>
                    <div class="dropdown ms-auto">
                        <a href="#" data-bs-toggle="dropdown" class="btn btn-sm" aria-haspopup="true"
                        aria-expanded="false">
                            <i class="bi bi-three-dots-vertical fs-18 card-color1"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="" class="dropdown-item">View Posts</a>
                        </div>
                    </div>
                </div>
                <a href="{{route('admin.users')}}" class="card_link"></a>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card p-3">
            <div class="card-body">
                <div class="row justify-content-end">
                    <div class="col-md-6">
                    <form action="" id="filtersForm">
                        <div class="input-group">
                        <input type="text" name="from-to" class="form-control mr-2" id="date_filter">
                        <span class="input-group-btn">
                            <input type="submit" class="btn btn-primary" value="Filter">
                        </span> 
                        </div>
                    </form>
                    </div>
                </div>
                <div class="row my-2" id="chart">
                    <div class="{{ $chart->options['column_class'] }}">
                        <h3>{!! $chart->options['chart_title'] !!}</h3>
                        {!! $chart->renderHtml() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class=" widget">
            {{-- <div class="d-flex align-items-center justify-content-between">
                <h6 class="card-title">Recent Notifications</h6>
            </div> --}}
            <div class="">
            <div class="tab-pane-body">
                <ul class="list-group list-group-flush">
                    @if(count($notifications) > 0)
                        <?php $i =0; ?>
                        @foreach($notifications as $notification)
                            <?php $i++; if($i<6){ ?>
                                <li class="mb-3 p-3 list-group-item notification-color">
                                    <p class="mb-0 fw-bold text-success d-flex justify-content-between">
                                        <a href="{{ $notification['data']['url'] }}">{{ $notification['data']['type'] }} - {{ $notification['data']['data'] }}</a>
                                    </p>
                                    <span class="text-muted small">
                                        <i class="bi bi-clock me-1"></i> {{ $notification['created_at'] }}
                                    </span>
                                </li>
                            <?php } ?>
                        @endforeach
                    @else
                        <li class="px-2 list-group-item">
                        <p>New Notifications Not Found.</p>
                        </li>
                    @endif
                </ul>
            </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-12">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="card-title">Orders By Products</h6>
        </div>
        <div id="barchart_material" class="p-3" style="width:100%; height:500px;"></div>
    </div>
    <div class="col-lg-6 col-md-12">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="card-title">Revenue By Products</h6>
        </div>
        <div id="barchart_material1" class="p-3" style="width:100%; height:500px;"></div>
    </div>
</div>
@endsection
@section('script')
    <script>
        let searchParams = new URLSearchParams(window.location.search)
        let dateInterval = searchParams.get('from-to');
        let start = moment().subtract(29, 'days');
        let end = moment();

        if (dateInterval) {
            dateInterval = dateInterval.split(' - ');
            start = dateInterval[0];
            end = dateInterval[1];
        }

        $('#date_filter').daterangepicker({
            "showDropdowns": true,
            "showWeekNumbers": true,
            "alwaysShowCalendars": true,
            startDate: start,
            endDate: end,
            locale: {
                format: 'DD-MM-YYYY',
                firstDay: 1,
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                'All time': [moment().subtract(30, 'year').startOf('month'), moment().endOf('month')],
            }
        });
       
    </script>
     {!! $chart->renderChartJsLibrary() !!}
     {!! $chart->renderJs() !!}
       <script>
        // Dashboard chart colors
        const body_styles = window.getComputedStyle(document.body);
        const colors = {
            primary: $.trim(body_styles.getPropertyValue('--bs-primary')),
            secondary: $.trim(body_styles.getPropertyValue('--bs-secondary')),
            info: $.trim(body_styles.getPropertyValue('--bs-info')),
            success: $.trim(body_styles.getPropertyValue('--bs-success')),
            danger: $.trim(body_styles.getPropertyValue('--bs-danger')),
            warning: $.trim(body_styles.getPropertyValue('--bs-warning')),
            light: $.trim(body_styles.getPropertyValue('--bs-light')),
            dark: $.trim(body_styles.getPropertyValue('--bs-dark')),
            blue: $.trim(body_styles.getPropertyValue('--bs-blue')),
            indigo: $.trim(body_styles.getPropertyValue('--bs-indigo')),
            purple: $.trim(body_styles.getPropertyValue('--bs-purple')),
            pink: $.trim(body_styles.getPropertyValue('--bs-pink')),
            red: $.trim(body_styles.getPropertyValue('--bs-red')),
            orange: $.trim(body_styles.getPropertyValue('--bs-orange')),
            yellow: $.trim(body_styles.getPropertyValue('--bs-yellow')),
            green: $.trim(body_styles.getPropertyValue('--bs-green')),
            teal: $.trim(body_styles.getPropertyValue('--bs-teal')),
            cyan: $.trim(body_styles.getPropertyValue('--bs-cyan')),
            chartTextColor: $('body').hasClass('dark') ? '#6c6c6c' : '#b8b8b8',
            chartBorderColor: $('body').hasClass('dark') ? '#444444' : '#ededed',
        };
       
        $( document ).ready(function() {
            $('.notification-color').each(function() {
                var back = ["#55d197","#80b3f3","#e95c6a","#d1893f","#7d3bb1","#e7c0c4","black","gray"];
                var rand = back[Math.floor(Math.random() * back.length)];
                var border = '3px solid '+rand;
                $(this).css('border-left',border);
            });
        });
    </script>
@endsection