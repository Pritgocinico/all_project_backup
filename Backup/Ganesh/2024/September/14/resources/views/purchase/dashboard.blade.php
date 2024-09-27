@extends('purchase.layouts.app')

@section('content')
<div class="page-header d-md-flex justify-content-between">
    
        <h3>Welcome back, {{Auth::user()->name}}</h3>
        {{-- <p class="text-muted">This page shows an overview for your account summary.</p> --}}
    <div class="mt-3 mt-md-0">
    
        <div>
            <input type="text" name="dashboard_daterangepicker" id="dashboard_daterange" class="form-control">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h6 class="card-title mb-2">Monthly Financial Status</h6>
                    {{-- <div class="d-flex justify-content-between">
                        <a href="#" class="btn btn-floating">
                            <i class="ti-reload"></i>
                        </a>
                        <div class="dropdown">
                            <a href="#" data-toggle="dropdown"
                            class="btn btn-floating"
                            aria-haspopup="true" aria-expanded="false">
                                <i class="ti-more-alt"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                    </div> --}}
                </div>
                <p class="text-muted mb-4">Check how you're doing financially for current month</p>
                <div class="card">
                    <div class="card-body">
                        <canvas id="chartjs_one"></canvas>
                        <div class="container" id="chartContainer"></div>
                    </div>
                </div>
                {{-- <div class="text-center mt-3">
                    <a href="#" class="btn btn-primary">
                        <i class="ti-download mr-2"></i> Create Report
                    </a>
                </div> --}}
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <a href="{{route('quotation_task-management')}}?task_status=completed">
                        <div class="card-body">
                            <h6 class="card-title">Completed Task</h6>
                            <div class="d-flex align-items-center mb-3">
                                <div>
                                    <div class="avatar">
                                        <span class="avatar-title bg-secondary-bright text-secondary rounded-pill">
                                            <i class="ti-check-box"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="font-weight-bold ml-1 font-size-30 ml-3">{{count($completedTasks)}}</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <a href="{{route('quotation_feedbacks')}}">
                        <div class="card-body">
                            <h6 class="card-title">Total Feedback</h6>
                            <div class="d-flex align-items-center mb-3">
                                <div>
                                    <div class="avatar">
                                        <span class="avatar-title bg-danger-bright text-danger rounded-pill">
                                            <i class="ti-check-box"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="font-weight-bold ml-1 font-size-30 ml-3" id="total_feedback_count">
                                    {{count($feedback)}}</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <a href="{{route('quotation_projects')}}">
                        <div class="card-body">
                            <h6 class="card-title">Total Projects</h6>
                            <div class="d-flex align-items-center mb-3">
                                <div>
                                    <div class="avatar">
                                        <span class="avatar-title bg-primary-bright text-primary rounded-pill">
                                            <i class="ti-ruler-pencil"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="font-weight-bold ml-1 font-size-30 ml-3">{{count($projects)}}</div>
                            </div>
                            {{-- <p class="mb-0"><a href="#" class="link-1">See comments</a> and respond to customers' comments.</p> --}}
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <a href="{{route('quotation_projects')}}?status=1">
                        <div class="card-body">
                            <h6 class="card-title">Running Projects</h6>
                            <div class="d-flex align-items-center mb-3">
                                <div>
                                    <div class="avatar">
                                        <span class="avatar-title bg-warning-bright text-warning rounded-pill">
                                            <i class="ti-dashboard"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="font-weight-bold ml-1 font-size-30 ml-3">{{count($runningProjects)}}</div>
                            </div>
                            {{-- <p class="mb-0"><a class="link-1" href="#">See clients</a> that accepted your invitation to
                                connect.</p> --}}
                        </div>
                    </a>
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Sales</h6>
                        <div class="d-flex align-items-center mb-3">
                            <div>
                                <div class="avatar">
                                    <span class="avatar-title bg-info-bright text-info rounded-pill">
                                        <i class="ti-map"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="font-weight-bold ml-1 font-size-30 ml-3">{{$sales}}</div>
                        </div>
                        {{-- <p class="mb-0"><a class="link-1" href="#">See visits</a> that had a higher than expected
                            bounce rate.</p> --}}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <a href="{{route('quotation_leads')}}">
                        <div class="card-body">
                            <h6 class="card-title">Leads in Queue</h6>
                            <div class="d-flex align-items-center mb-3">
                                <div>
                                    <div class="avatar">
                                        <span class="avatar-title bg-secondary-bright text-secondary rounded-pill">
                                            <i class="ti-ruler-pencil"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="font-weight-bold ml-1 font-size-30 ml-3">{{count($leads)}}</div>
                            </div>
                            {{-- <p class="mb-0"><a class="link-1" href="#">See referring</a> domains that sent most visits
                                last month.</p> --}}
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <a href="{{route('quotation_task-management')}}?task_status=pending">
                        <div class="card-body">
                            <h6 class="card-title">Pending Tasks</h6>
                            <div class="d-flex align-items-center mb-3">
                                <div>
                                    <div class="avatar">
                                        <span class="avatar-title bg-danger-bright text-danger rounded-pill">
                                            <i class="ti-check-box"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="font-weight-bold ml-1 font-size-30 ml-3">{{count($pendingTasks)}}</div>
                            </div>
                            {{-- <p class="mb-0"><a class="link-1" href="#">See clients</a> that accepted your invitation to
                                connect.</p> --}}
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Cancel Lead</h6>
                            <div class="d-flex align-items-center mb-3">
                                <div>
                                    <div class="avatar">
                                        <span class="avatar-title bg-danger-bright text-danger rounded-pill">
                                            <i class="ti-check-box"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="font-weight-bold ml-1 font-size-30 ml-3" id="cancel_lead_count">{{$cancelLead}}</div>
                            </div>
                        </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <a href="{{route('quotation_projects')}}?status=2">
                        <div class="card-body">
                            <h6 class="card-title">Completed Projects</h6>
                            <div class="d-flex align-items-center mb-3">
                                <div>
                                    <div class="avatar">
                                        <span class="avatar-title bg-success-bright text-success rounded-pill">
                                            <i class="ti-ruler-pencil"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="font-weight-bold ml-1 font-size-30 ml-3">{{count($completedProjects)}}</div>
                            </div>
                            {{-- <p class="mb-0"><a class="link-1" href="#">See clients</a> that accepted your invitation to
                                connect.</p> --}}
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="row my-3">
                    <div class="col-md-6 ml-auto mr-auto">
                        <figure>
                            <img class="img-fluid" src="https://gogi.laborasyon.com/assets/media/svg/upgrade.svg" alt="upgrade">
                        </figure>
                    </div>
                </div>
                <h4 class="mb-3 text-center">Get an Upgrade</h4>
                <div class="row my-3">
                    <div class="col-md-10 ml-auto mr-auto">
                        <p class="text-muted">Get additional 500 GB space for your documents and files. Expand your storage and enjoy your business. Change plan for more space.</p>
                        <button class="btn btn-primary" data-dismiss="modal">Plan Upgrade</button>
                    </div>
                </div>
                <a href="#" class="align-items-center d-flex link-1 small justify-content-center" data-dismiss="modal">
                    <i class="ti-close font-size-10 mr-1"></i>Close
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{url('/')}}/vendors/charts/chartjs/chart.min.js"></script>
{{-- <script src="{{url('/')}}/assets/js/examples/charts/chartjs.js"></script> --}}
<div class="colors"> <!-- To use theme colors with Javascript -->
    <div class="bg-primary"></div>
    <div class="bg-primary-bright"></div>
    <div class="bg-secondary"></div>
    <div class="bg-secondary-bright"></div>
    <div class="bg-info"></div>
    <div class="bg-info-bright"></div>
    <div class="bg-success"></div>
    <div class="bg-success-bright"></div>
    <div class="bg-danger"></div>
    <div class="bg-danger-bright"></div>
    <div class="bg-warning"></div>
    <div class="bg-warning-bright"></div>
</div>
<script>
    $(document).ready(function () {
        chartjs_one();

        function chartjs_one() {
            var element = document.getElementById("chartjs_one");
            element.height = 100;
            new Chart(element, {
                type: 'bar',
                data: {
                    labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                    datasets: [
                        {
                            label: "Sales",
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
                               @foreach ($monthWiseSales as $sale) 
                                {{ $sale."," }}
                                @endforeach    
                            ]
                        }
                    ]
                },
                options: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Sales in ' + new Date().getFullYear()
                    }
                }
            });
        }
    });
</script>
<script>
    $('input[name="dashboard_daterangepicker"]').daterangepicker({
        singleDatePicker: false,
        showDropdowns: true,
        locale: {
            format: 'DD/MM/YYYY'
        }
    });
    $('#dashboard_daterange').on('change', function(){
        var date_range = $(this).val();
        $.ajax({
            url : "{{ route('quotation.dashboard') }}",
            type : 'GET',
            data : {
                dateRange : date_range
            },
            success: function(data){
                var dataArray = Object.values(data).map(Number);
                var dataString = dataArray.join(',');
                console.log(dataString);
                $('#chartjs_one').remove();
                $('#chartContainer').append('<canvas id="chartjs_one" height="100"></canvas>');
                chartjs_one(dataArray);

                function chartjs_one(dataArray) {
                    var element = document.getElementById("chartjs_one");
                    element.height = 100;
                    new Chart(element, {
                        type: 'bar',
                        data: {
                            labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                            datasets: [
                                {
                                    label: "Sales",
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
                                    data: dataArray
                                }
                            ]
                        },
                        options: {
                            legend: { display: false },
                            title: {
                                display: true,
                                text: 'Sales in ' + new Date().getFullYear()
                            }
                        }
                    });
                }
            }
        })

    })
</script>

@endsection