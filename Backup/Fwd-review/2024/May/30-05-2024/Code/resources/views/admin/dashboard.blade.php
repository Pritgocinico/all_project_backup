@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h4>Total Clients</h4>
                <?php $clients = DB::table('users')->where('role',2)->count(); ?>
                <h1>{{$clients}}</h1>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h4>Total Businesses</h4>
                <?php $businesses = DB::table('businesses')->where('active',1)->count(); ?>
                <h1>{{$businesses}}</h1>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h4>Total Subscriptions</h4>
                <?php $businesses = DB::table('users')->count(); ?>
                <h1>{{$businesses}}</h1>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h4>Total Revenue</h4>
                <?php $businesses = DB::table('businesses')->count(); ?>
                <h1>$ {{$businesses}}</h1>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <div class="card">
            <input type="text" class="year-picker form-control col-md-3" id="client-year-picker" placeholder="Select Year">
            <canvas id="clientChart"></canvas>
            <div class="container" id="client_chartContainer"></div>
          </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <input type="text" class="year-picker form-control col-md-3" id="business-year-picker" placeholder="Select Year">
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

<!-- Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>
    $('.year-picker').datepicker({
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
    autoclose: true,
});

// Get the selected year and update the chart data accordingly
$('#client-year-picker').on('changeDate', function (e) {
    var selected_year = e.date.getFullYear();
    $.ajax({
        url : "{{ route('admin.dashboard') }}",
        type : 'GET',
        data : {
            selectedYear : selected_year
        },
        success: function(data){
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
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Clients Data',
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)', // Red
                        'rgba(54, 162, 235, 0.7)', // Blue
                        'rgba(255, 206, 86, 0.7)', // Yellow
                        'rgba(75, 192, 192, 0.7)', // Green
                        'rgba(153, 102, 255, 0.7)', // Purple
                        'rgba(255, 159, 64, 0.7)', // Orange
                        'rgba(0, 128, 0, 0.7)',    // Dark Green
                        'rgba(0, 0, 128, 0.7)',    // Dark Blue
                        'rgba(128, 0, 128, 0.7)',  // Dark Purple
                        'rgba(0, 128, 128, 0.7)',  // Teal
                        'rgba(128, 128, 0, 0.7)',  // Olive
                        'rgba(128, 128, 128, 0.7)',// Gray
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

$('#business-year-picker').on('changeDate', function (e) {
    var selected_year = e.date.getFullYear();
    $.ajax({
        url : "{{ route('admin.dashboard') }}",
        type : 'GET',
        data : {
            businessYear : selected_year
        },
        success: function(data){
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
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Business Data',
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)', // Red
                        'rgba(54, 162, 235, 0.7)', // Blue
                        'rgba(255, 206, 86, 0.7)', // Yellow
                        'rgba(75, 192, 192, 0.7)', // Green
                        'rgba(153, 102, 255, 0.7)', // Purple
                        'rgba(255, 159, 64, 0.7)', // Orange
                        'rgba(0, 128, 0, 0.7)',    // Dark Green
                        'rgba(0, 0, 128, 0.7)',    // Dark Blue
                        'rgba(128, 0, 128, 0.7)',  // Dark Purple
                        'rgba(0, 128, 128, 0.7)',  // Teal
                        'rgba(128, 128, 0, 0.7)',  // Olive
                        'rgba(128, 128, 128, 0.7)',// Gray
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
</script>
<script>
  const client = document.getElementById('clientChart');
  const business = document.getElementById('businessChart');

  new Chart(client, {
    type: 'bar',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      datasets: [{
        label: 'Clients Data',
        backgroundColor: [
            'rgba(255, 99, 132, 0.7)', // Red
            'rgba(54, 162, 235, 0.7)', // Blue
            'rgba(255, 206, 86, 0.7)', // Yellow
            'rgba(75, 192, 192, 0.7)', // Green
            'rgba(153, 102, 255, 0.7)', // Purple
            'rgba(255, 159, 64, 0.7)', // Orange
            'rgba(0, 128, 0, 0.7)',    // Dark Green
            'rgba(0, 0, 128, 0.7)',    // Dark Blue
            'rgba(128, 0, 128, 0.7)',  // Dark Purple
            'rgba(0, 128, 128, 0.7)',  // Teal
            'rgba(128, 128, 0, 0.7)',  // Olive
            'rgba(128, 128, 128, 0.7)',// Gray
        ],
        data: [
            @foreach($monthWiseClients as $client)
            {{ $client."," }}
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
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      datasets: [{
        label: 'Businesses Data',
        backgroundColor: [
            'rgba(255, 99, 132, 0.7)', // Red
            'rgba(54, 162, 235, 0.7)', // Blue
            'rgba(255, 206, 86, 0.7)', // Yellow
            'rgba(75, 192, 192, 0.7)', // Green
            'rgba(153, 102, 255, 0.7)', // Purple
            'rgba(255, 159, 64, 0.7)', // Orange
            'rgba(0, 128, 0, 0.7)',    // Dark Green
            'rgba(0, 0, 128, 0.7)',    // Dark Blue
            'rgba(128, 0, 128, 0.7)',  // Dark Purple
            'rgba(0, 128, 128, 0.7)',  // Teal
            'rgba(128, 128, 0, 0.7)',  // Olive
            'rgba(128, 128, 128, 0.7)',// Gray
        ],
        data: [
            @foreach($monthWiseBusinesses as $business)
            {{ $business."," }}
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