<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
</head>

<body>
    @php
        $clientChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Reviews Over Time',
                        'backgroundColor' => [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(255, 159, 64, 0.7)',
                            'rgba(0, 128, 0, 0.7)',
                            'rgba(0, 0, 128, 0.7)',
                            'rgba(128, 0, 128, 0.7)',
                            'rgba(0, 128, 128, 0.7)',
                            'rgba(128, 128, 0, 0.7)',
                            'rgba(128, 128, 128, 0.7)',
                        ],
                        'data' => $reviewCounts,
                        'borderWidth' => 1,
                    ],
                ],
            ],
            'options' => [
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                    ],
                ],
            ],
        ];
        $ratingChart = [
            'type' => 'pie',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Reviews Over Time',
                        'backgroundColor' => [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(255, 159, 64, 0.7)',
                            'rgba(0, 128, 0, 0.7)',
                            'rgba(0, 0, 128, 0.7)',
                            'rgba(128, 0, 128, 0.7)',
                            'rgba(0, 128, 128, 0.7)',
                            'rgba(128, 128, 0, 0.7)',
                            'rgba(128, 128, 128, 0.7)',
                        ],
                        'data' => $reviewCounts,
                        'borderWidth' => 1,
                    ],
                ],
            ],
            'options' => [
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                    ],
                ],
            ],
        ];
        $reviewChart = [
            'type' => 'polarArea',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Reviews Over Time',
                        'backgroundColor' => [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(255, 159, 64, 0.7)',
                            'rgba(0, 128, 0, 0.7)',
                            'rgba(0, 0, 128, 0.7)',
                            'rgba(128, 0, 128, 0.7)',
                            'rgba(0, 128, 128, 0.7)',
                            'rgba(128, 128, 0, 0.7)',
                            'rgba(128, 128, 128, 0.7)',
                        ],
                        'data' => $reviewCounts,
                        'borderWidth' => 1,
                    ],
                ],
            ],
            'options' => [
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                    ],
                ],
            ],
        ];
        $clientChartEncoded = json_encode($clientChart);
        $ratingChartEncoded = json_encode($ratingChart);
        $reviewChartEncoded = json_encode($reviewChart);
    @endphp
    <div class="gc_row px-md-4 px-2">
        <div class="card mt-3">
            <div class="card-body">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-profile-overview" role="tabpanel"
                        aria-labelledby="pills-profile-overview-tab">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h4>Total Reviews</h4>
                                        <h1>{{ $filteredReviews }}</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h4>New Reviews</h4>
                                        <h1>{{ $recentReviewCount }} <img
                                                src="{{ url('/') }}/assets/Images/statup-green.png"
                                                class="review-icon" alt="new-reviews"></h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h4>Rating</h4>
                                        <h1>{{ $filteredReviews }}</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h4>Rating Change</h4>
                                        <h1>0 <img src="{{ url('/') }}/assets/Images/stat-down-red.png"
                                                class="review-icon" alt="rating-change"></h1>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="card">
                                    <h4>Reviews Over Time</h4>
                                    <img src="https://quickchart.io/chart?c={{ urlencode($clientChartEncoded) }}"
                                        alt="Chart">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <h4>Rating Distribution</h4>
                                    <img src="https://quickchart.io/chart?c={{ urlencode($ratingChartEncoded) }}"
                                        alt="Chart">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <h4>Top Review Sites</h4>
                                    <img src="https://quickchart.io/chart?c={{ urlencode($reviewChartEncoded) }}"
                                        alt="Chart">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
