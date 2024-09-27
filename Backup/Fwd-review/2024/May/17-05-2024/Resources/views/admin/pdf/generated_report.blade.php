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
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    const client = document.getElementById('clientChart');
    const rating = document.getElementById('ratingChart');
    const review = document.getElementById('reviewChart');
    new Chart(client, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Reviews Over Time',
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
                data: @json($reviewCounts),
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
            labels: @json($labels),
            datasets: [{
                label: 'Reviews Over Time',
                backgroundColor: [
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
                data: @json($reviewCounts),
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
            labels: @json($labels),
            datasets: [{
                label: 'Reviews Over Time',
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
                data: @json($reviewCounts),
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
    $(document).ready(function(e) {
            const { jsPDF } = window.jspdf;

            html2canvas(document.querySelector('.gc_row')).then(canvas => {
                const pdf = new jsPDF('p', 'mm', 'a4');
                const imgData = canvas.toDataURL('image/png');
                const imgWidth = 210; // A4 width in mm
                const pageHeight = 295; // A4 height in mm
                const imgHeight = canvas.height * imgWidth / canvas.width;
                let heightLeft = imgHeight;

                let position = 0;

                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;

                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }
                pdf.save('dashboard.pdf');
            });
        });
</script>
