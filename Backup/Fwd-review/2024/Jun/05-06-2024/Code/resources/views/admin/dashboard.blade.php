@extends('admin.layouts.app')
<link rel="stylesheet" href="{{ url('/') }}/assets/Css/date-range-picker/daterangepicker.css">
@section('content')
    <div class="card my-3">
        <div class="card-body">
            <div class="pe-4 fs-5 form-floating">
                <input type="text" name="date_filter" id="date_filter" class="form-control w-25" placeholder="Search Date">
                <label>Search Date</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h4>Total Clients</h4>
                    <h1 id="total_client"></h1>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h4>Total Businesses</h4>
                    <h1 id="total_busines"></h1>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h4>Total Subscriptions</h4>
                    <h1 id="total_subscription"></h1>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h4>Total Revenue</h4>
                    <h1 id="total_revenue"></h1>
                </div>
            </div>
        </div>
        <div class="col-md-3 mt-3">
            <div class="card">
                <div class="card-body">
                    <h4>Today Subscription</h4>
                    <h1>{{ $todaySubscription }}</h1>
                </div>
            </div>
        </div>
        <div class="col-md-3 mt-3">
            <div class="card">
                <div class="card-body">
                    <h4>Today Expire</h4>
                    <h1>{{ $todayExpire }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card">
                <input type="text" class="year-picker form-control col-md-3" id="client-year-picker"
                    placeholder="Select Year">
                <canvas id="clientChart"></canvas>
                <div class="container" id="client_chartContainer"></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <input type="text" class="year-picker form-control col-md-3" id="business-year-picker"
                    placeholder="Select Year">
                <canvas id="businessChart"></canvas>
                <div class="container" id="business_chartContainer"></div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Bootstrap Datepicker CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ url('/') }}/assets/JS/momentjs/moment.min.js"></script>
    <script src="{{ url('/') }}/assets/JS/date-range-picker/daterangepicker.min.js"></script>

    <script>
        getDashboardCount();
        $('.year-picker').datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            autoclose: true,
        });
        $('#date_filter').daterangepicker({
            autoUpdateInput: false,
        }, function(start, end, label) {
            $('#date_filter').val(start.format('Y-MM-DD') + '/' + end.format('Y-MM-DD'));
            getDashboardCount();
        });
        // Get the selected year and update the chart data accordingly
        $('#client-year-picker').on('changeDate', function(e) {
            var selected_year = e.date.getFullYear();
            $.ajax({
                url: "{{ route('admin.dashboard') }}",
                type: 'GET',
                data: {
                    selectedYear: selected_year
                },
                success: function(data) {
                    var dataArray = Object.values(data).map(Number);
                    var dataString = dataArray.join(',');
                    // console.log(dataString);
                    // console.log(data);
                    $('#clientChart').remove();
                    $('#client_chartContainer').append('<canvas id="clientChart"></canvas>');
                    const client = document.getElementById('clientChart');
                    new Chart(client, {
                        type: 'bar',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June',
                                'July', 'August', 'September', 'October', 'November',
                                'December'
                            ],
                            datasets: [{
                                label: 'Clients Data',
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.7)', // Red
                                    'rgba(54, 162, 235, 0.7)', // Blue
                                    'rgba(255, 206, 86, 0.7)', // Yellow
                                    'rgba(75, 192, 192, 0.7)', // Green
                                    'rgba(153, 102, 255, 0.7)', // Purp le
                                    'rgba(255, 159, 64, 0.7)', // Orange
                                    'rgba(0, 128, 0, 0.7)', // Dark Green
                                    'rgba(0, 0, 128, 0.7)', // Dark Blue
                                    'rgba(128, 0, 128, 0.7)', // Dark Purple
                                    'rgba(0, 128, 128, 0.7)', // Teal
                                    'rgba(128, 128, 0, 0.7)', // Olive
                                    'rgba(128, 128, 128, 0.7)', // Gray
                                ],
                                data: dataArray,
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

                }
            })
        });

        $('#business-year-picker').on('changeDate', function(e) {
            var selected_year = e.date.getFullYear();
            $.ajax({
                url: "{{ route('admin.dashboard') }}",
                type: 'GET',
                data: {
                    businessYear: selected_year
                },
                success: function(data) {
                    var dataArray = Object.values(data).map(Number);
                    var dataString = dataArray.join(',');
                    // console.log(dataString);
                    // console.log(data);
                    $('#businessChart').remove();
                    $('#business_chartContainer').append('<canvas id="businessChart"></canvas>');
                    const business = document.getElementById('businessChart');
                    new Chart(business, {
                        type: 'bar',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June',
                                'July', 'August', 'September', 'October', 'November',
                                'December'
                            ],
                            datasets: [{
                                label: 'Business Data',
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.7)', // Red
                                    'rgba(54, 162, 235, 0.7)', // Blue
                                    'rgba(255, 206, 86, 0.7)', // Yellow
                                    'rgba(75, 192, 192, 0.7)', // Green
                                    'rgba(153, 102, 255, 0.7)', // Purple
                                    'rgba(255, 159, 64, 0.7)', // Orange
                                    'rgba(0, 128, 0, 0.7)', // Dark Green
                                    'rgba(0, 0, 128, 0.7)', // Dark Blue
                                    'rgba(128, 0, 128, 0.7)', // Dark Purple
                                    'rgba(0, 128, 128, 0.7)', // Teal
                                    'rgba(128, 128, 0, 0.7)', // Olive
                                    'rgba(128, 128, 128, 0.7)', // Gray
                                ],
                                data: dataArray,
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

                }
            })
        });
        function getDashboardCount(){
            var date_filter = $('#date_filter').val();
            $.ajax({
                url: "{{ route('dashboard.count') }}",
                type: 'GET',
                data: {
                    date_filter: date_filter
                },
                success: function(data) {
                    $('#total_busines').html(data.business_count);
                    $('#total_subscription').html(data.payment_count);
                    $('#total_client').html(data.clients);
                    $('#total_revenue').html("$ " + data.total_amount);
                }
            });
        }
    </script>
    <script>
        const client = document.getElementById('clientChart');
        const business = document.getElementById('businessChart');

        new Chart(client, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                    'October', 'November', 'December'
                ],
                datasets: [{
                    label: 'Clients Data',
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)', // Red
                        'rgba(54, 162, 235, 0.7)', // Blue
                        'rgba(255, 206, 86, 0.7)', // Yellow
                        'rgba(75, 192, 192, 0.7)', // Green
                        'rgba(153, 102, 255, 0.7)', // Purple
                        'rgba(255, 159, 64, 0.7)', // Orange
                        'rgba(0, 128, 0, 0.7)', // Dark Green
                        'rgba(0, 0, 128, 0.7)', // Dark Blue
                        'rgba(128, 0, 128, 0.7)', // Dark Purple
                        'rgba(0, 128, 128, 0.7)', // Teal
                        'rgba(128, 128, 0, 0.7)', // Olive
                        'rgba(128, 128, 128, 0.7)', // Gray
                    ],
                    data: [
                        @foreach ($monthWiseClients as $client)
                            {{ $client . ',' }}
                        @endforeach
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

        new Chart(business, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                    'October', 'November', 'December'
                ],
                datasets: [{
                    label: 'Businesses Data',
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)', // Red
                        'rgba(54, 162, 235, 0.7)', // Blue
                        'rgba(255, 206, 86, 0.7)', // Yellow
                        'rgba(75, 192, 192, 0.7)', // Green
                        'rgba(153, 102, 255, 0.7)', // Purple
                        'rgba(255, 159, 64, 0.7)', // Orange
                        'rgba(0, 128, 0, 0.7)', // Dark Green
                        'rgba(0, 0, 128, 0.7)', // Dark Blue
                        'rgba(128, 0, 128, 0.7)', // Dark Purple
                        'rgba(0, 128, 128, 0.7)', // Teal
                        'rgba(128, 128, 0, 0.7)', // Olive
                        'rgba(128, 128, 128, 0.7)', // Gray
                    ],
                    data: [
                        @foreach ($monthWiseBusinesses as $business)
                            {{ $business . ',' }}
                        @endforeach
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
    </script>
@endsection
