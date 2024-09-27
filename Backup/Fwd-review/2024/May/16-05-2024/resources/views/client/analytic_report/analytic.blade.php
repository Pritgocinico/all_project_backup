@extends('client.layouts.app')

@section('content')
<div class="gc_row px-md-4 px-2">
    <div class="card mt-md-3 mb-3">
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-3">
                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                <li class="breadcrumb-item active" aria-current="page">Analytics</li>
            </ol>
        </nav>
    </div>

    <div class="card mt-md-3 mb-3 p-3 d-flex">
        <div class="card-body">
            <ul class="nav nav-pills gap-2 justify-content-center" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="#pills-profile-overview" class="nav-link active" id="pills-profile-overview-tab" data-bs-toggle="pill" data-bs-target="#pills-profile-overview" type="button" role="tab" aria-controls="pills-profile-overview" aria-selected="true">Profile Overview</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#pills-campaings" class="nav-link" id="pills-campaings-tab" data-bs-toggle="pill" data-bs-target="#pills-campaings" type="button" role="tab" aria-controls="pills-campaings" aria-selected="false">Campaigns and Funnel</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-profile-overview" role="tabpanel" aria-labelledby="pills-profile-overview-tab">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h4>Total Reviews</h4>
                                    <h1>{{count($data)}}</h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h4>New Reviews</h4>
                                    <h1>{{$recentReviewCount}} <img src="{{url('/')}}/assets/Images/statup-green.png" class="review-icon" alt="new-reviews"></h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h4>Rating</h4>
                                    <h1>{{count($data)}}</h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h4>Rating Change</h4>
                                    <h1>0 <img src="{{url('/')}}/assets/Images/stat-down-red.png" class="review-icon" alt="rating-change"></h1>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card">
                                <h4>Reviews Over Time</h4>
                                <canvas id="clientChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <h4>Rating Distribution</h4>
                                <canvas id="ratingChart"></canvas>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <h4>Top Review Sites</h4>
                                <canvas id="reviewChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-campaings" role="tabpanel" aria-labelledby="pills-campaings-tab">
                    <div class="table-responsive">
                        <table id="example3" class="table rwd-table mb-0" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Email/Phone</th>
                                    <th>Name</th>
                                    <th>Sent</th>
                                    <th>Last Sent</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="records">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const client = document.getElementById('clientChart');
    const rating = document.getElementById('ratingChart');
    const review = document.getElementById('reviewChart');
    new Chart(client, {
    type: 'bar',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      datasets: [{
        label: 'Reviews Over Time',
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
        data: [51,20,30,40,15,62,45,75,12,32,61,25],
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

  new Chart(rating, {
    type: 'pie',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      datasets: [{
        label: 'Reviews Over Time',
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
        data: [51,20,30,40,15,62,45,75,12,32,61,25],
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

  new Chart(review, {
    type: 'polarArea',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      datasets: [{
        label: 'Reviews Over Time',
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
        data: [51,20,30,40,15,62,45,75,12,32,61,25],
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
