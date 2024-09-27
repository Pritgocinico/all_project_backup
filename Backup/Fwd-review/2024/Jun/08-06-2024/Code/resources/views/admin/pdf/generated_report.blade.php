@extends('client.layouts.app')

@section('content')
    <div class="gc_row px-md-4 px-2">
        <div class="card mt-md-3 mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-3">
                    <li class="breadcrumb-item"><a href="#">Reports</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Analytics</li>
                </ol>
            </nav>
        </div>
        <div class="card mt-3 bg-white text-dark" id="report_pdf_page">
            @php
                $clientChart = [
                    'type' => 'bar',
                    'data' => [
                        'labels' => $labels,
                        'datasets' => [
                            [
                                'label' => 'Reviews Over Time',
                                'backgroundColor' => 'rgba(54, 162, 235, 0.7)',
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
                                'backgroundColor' => [
                                    'rgba(255, 99, 132, 0.7)',
                                    'rgba(54, 162, 235, 0.7)',
                                    'rgba(255, 206, 86, 0.7)',
                                    'rgba(75, 192, 192, 0.7)',
                                    'rgba(153, 102, 255, 0.7)',
                                    'rgba(255, 159, 64, 0.7)',
                                ],
                                'data' => $reviewCounts,
                                'borderWidth' => 1,
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
                                'backgroundColor' => [
                                    'rgba(255, 99, 132, 0.7)',
                                    'rgba(54, 162, 235, 0.7)',
                                    'rgba(255, 206, 86, 0.7)',
                                    'rgba(75, 192, 192, 0.7)',
                                    'rgba(153, 102, 255, 0.7)',
                                    'rgba(255, 159, 64, 0.7)',
                                ],
                                'data' => $reviewCounts,
                                'borderWidth' => 1,
                            ],
                        ],
                    ],
                ];
                $campaignChart = [
                    'type' => 'bar',
                    'data' => [
                        'labels' => $labels,
                        'datasets' => [
                            [
                                'label' => 'Reviews Over Time',
                                'data' => $campaign,
                                'backgroundColor' => 'rgba(54, 162, 235, 0.7)',
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
            @endphp

            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-white text-dark">
                            <div class="card-body">
                                <h4>Total Reviews</h4>
                                <h1>{{ $filteredReviews }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-white text-dark">
                            <div class="card-body">
                                <h4>New Reviews</h4>
                                <h1>{{ $recentReviewCount }} <img src="{{ url('/') }}/assets/Images/statup-green.png"
                                        class="review-icon" alt="new-reviews"></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-white text-dark">
                            <div class="card-body">
                                <h4>Rating</h4>
                                <h1>{{ $filteredReviews }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-white text-dark">
                            <div class="card-body">
                                <h4>Rating Change</h4>
                                <h1>0 <img src="{{ url('/') }}/assets/Images/stat-down-red.png" class="review-icon"
                                        alt="rating-change"></h1>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card bg-white text-dark">
                            <h4>Reviews Over Time</h4>
                            <canvas id="clientChart" width="400px"></canvas>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card bg-white text-dark">
                            <h4>Rating Distribution</h4>
                            <canvas id="ratingChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 15rem !important">
                        <div class="card bg-white text-dark">
                            <h4>Top Review Sites</h4>
                            <canvas id="reviewChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-white text-dark">
                            <div class="card-body">
                                <h4>Total Campaigns</h4>
                                <h1>{{ $filteredReviews }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-white text-dark">
                            <div class="card-body">
                                <h4>Rating</h4>
                                <h1>{{ $filteredReviews }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-white text-dark">
                            <div class="card-body">
                                <h4>Rating Change</h4>
                                <h1>0 <img src="{{ url('/') }}/assets/Images/stat-down-red.png" class="review-icon"
                                        alt="rating-change"></h1>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card bg-white text-dark">
                            <h4>Campaigns</h4>
                            <canvas id="campaignChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="row " style="margin-top: 17rem !important">
                    <div class="card bg-white text-dark">
                        <div class="card-body">
                            <h3>{{ $mapReport->name }}</h3>
                            <h5><b>{{ date('Y-m-d', strtotime($mapReport->start_date)) }} -
                                    {{ date('Y-m-d', strtotime($mapReport->end_date)) }}</b></h5>
                            @foreach ($filteredReviewData as $review)
                                <div class="card bg-white text-dark report_pdf_card">
                                    <div id="report_pdf_card_body" class="m-auto pt-3"><span
                                            class="text-muted">{{ date('Y-m-d', $review->time) }}</span>
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating)
                                                @if ($i == 5)
                                                    <span class="text-dark">&#9733;</span>
                                                @else
                                                    &#9733;
                                                @endif
                                            @else
                                                &#9734;
                                            @endif
                                        @endfor
                                        ({{ number_format($review->rating, 1) }})
                                    </div>
                                    <div id="report_pdf_card_text" class="m-auto">{{ $review->text }}</div>
                                    <div id="report_pdf_card_body" class="m-auto pb-4">
                                        <span class="me-2 text-white">
                                            <img src="{{ $review->profile_photo_url }}" width="30px">
                                        </span>
                                        <span>{{ $review->author_name }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="customer_name" value="{{ $mapReport->name }}">
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function(e) {
            const clientChartData = @json($clientChart);
            const ratingChartData = @json($ratingChart);
            const reviewChartData = @json($reviewChart);
            const campaignChartData = @json($campaignChart);

            new Chart(document.getElementById('clientChart').getContext('2d'), clientChartData);
            new Chart(document.getElementById('ratingChart').getContext('2d'), ratingChartData);
            new Chart(document.getElementById('reviewChart').getContext('2d'), reviewChartData);
            new Chart(document.getElementById('campaignChart').getContext('2d'), campaignChartData);

            // HTML to PDF conversion
            var name = $('#customer_name').val();
            var fileName = name + ".pdf";
            const element = document.getElementById('report_pdf_page');

            setTimeout(function() {
                html2pdf().set({
                    filename: fileName
                }).from(element).save();
            }, 100);

            setTimeout(function() {
                window.location.href = "{{ route('client.report.generated') }}";
            }, 1000);
        });
    </script>
@endsection
