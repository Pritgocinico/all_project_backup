<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ganesh Alluminium</title>
    {{-- <link rel="shortcut icon" href="{{url('/')}}/assets/media/image/favicon.png"/> --}}
    <link rel="shortcut icon" href="{{ URL::asset('public/settings/'.$setting->favicon) }}" />
    <link rel="stylesheet" href="{{url('/')}}/vendors/bundle.css" type="text/css">
    <link href="{{url('/')}}/assets/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500;600;700&amp;display=swap" rel="stylesheet"> --}}

    <link rel="stylesheet" href="{{url('/')}}/vendors/datepicker/daterangepicker.css" type="text/css">
    <link rel="stylesheet" href="{{url('/')}}/assets/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="{{url('/')}}/assets/css/datatable_buttons.bootstrap.min.css">
    {{-- <link rel="stylesheet" href="{{url('/')}}/vendors/dataTable/datatables.min.css" type="text/css"> --}}

    <link rel="stylesheet" href="{{url('/')}}/vendors/prism/prism.css" type="text/css">

    <link rel="stylesheet" href="{{url('/')}}/assets/libs/sweetalert/sweetalert.min.css">
    <link rel="stylesheet" href="{{url('/')}}/assets/css/app.min.css" type="text/css">
    <link rel="stylesheet" href="{{url('/')}}/assets/css/select2.min.css" />
    @yield('style')
    <script src="{{url('/')}}/assets/js/3.5.1_jquery.min.js" charset="utf-8"></script>
</head>
<body class="horizontal-navigation">
    <div class="sidebar-group">
        <div class="sidebar" id="settings">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title d-flex justify-content-between">
                        Settings
                        <a class="btn-sidebar-close" href="#">
                            <i class="ti-close"></i>
                        </a>
                    </h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item pl-0 pr-0">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="customSwitch1" checked>
                                <label class="custom-control-label" for="customSwitch1">Allow notifications.</label>
                            </div>
                        </li>
                        <li class="list-group-item pl-0 pr-0">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="customSwitch2">
                                <label class="custom-control-label" for="customSwitch2">Hide user requests</label>
                            </div>
                        </li>
                        <li class="list-group-item pl-0 pr-0">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="customSwitch3" checked>
                                <label class="custom-control-label" for="customSwitch3">Speed up demands</label>
                            </div>
                        </li>
                        <li class="list-group-item pl-0 pr-0">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="customSwitch4" checked>
                                <label class="custom-control-label" for="customSwitch4">Hide menus</label>
                            </div>
                        </li>
                        <li class="list-group-item pl-0 pr-0">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="customSwitch5">
                                <label class="custom-control-label" for="customSwitch5">Remember next visits</label>
                            </div>
                        </li>
                        <li class="list-group-item pl-0 pr-0">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="customSwitch6">
                                <label class="custom-control-label" for="customSwitch6">Enable report
                                    generation.</label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="layout-wrapper">
        <div class="header d-print-none">
            <div class="header-container">
                <div class="header-left">
                    <div class="navigation-toggler">
                        <a href="#" data-action="navigation-toggler">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                        </a>
                    </div>
                    <div class="header-logo">
                        <a href="https://crm.sganeshaluminium.com/admin/dashboard">
                            <img class="logo" src="https://crm.sganeshaluminium.com/assets/media/image/logo-new.png" alt="logo">
                            
                        </a>
                    </div>
                </div>

                <div class="header-body">
                    

                    <div class="header-body-right">
                        
                    </div>
                </div>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item header-toggler">
                        <a href="#" class="nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div><div class="content-wrapper">
    <div class="navigation">
        <div class="navigation-menu-body">
            <h1>Project Details</h1>
        </div>
    </div>
    <div class="content-body">
        <div class="content ">

    <div class="row">
        <div class="card w-100">
            <div class="card-body pb-0">
                <div class="row w-100 view_project">
                    <h4 class="pb-2">
                        @if ($projects->type == 1)
                            Project Details
                        @else
                            Lead Details
                        @endif
                    </h4>
                    <hr>
                    <h3>
                        @if ($projects->type == 1)
                            {{ $projects->project_generated_id }} -
                        @else
                            {{ $projects->lead_no }} -
                        @endif
                        <span>
                            @if ($projects->customer->name)
                                {{ $projects->customer->name }}
                            @endif
                        </span>
                    </h3>
                    <div class="col-md-6 mt-3">
                        <div class="view_project_details border_first">
                            <h6>Phone Number</h6>
                            <p>
                                {{ $projects->phone_number }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="view_project_details border_first">
                            <h6>Email Address</h6>
                            <p>
                                {{ $projects->email }}
                            </p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="view_project_details border_first">
                            <h6>Reference Name</h6>
                            <p>
                                {{ $projects->reference_name }}
                            </p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="view_project_details border_first">
                            <h6>Reference Number</h6>
                            <p>
                                {{ $projects->reference_phone }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="view_project_details">
                            <h6>Estimated Measurement Date</h6>
                            <p>
                                @if ($projects->measurement_date != '')
                                    {{ date('d/m/Y', strtotime($projects->measurement_date)) }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="view_project_details border_first">
                            <h6>Address</h6>
                            <p>
                                {{ $projects->address }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="view_project_details">
                            <h6>Project Description</h6>
                            <p>
                                @if ($projects->description != '')
                                    {{ $projects->description }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (!blank($measurements))
            <div class="card">
                <div class="card-body pb-0">
                    <div class="row w-100 view_project">
                        <div class="d-flex align-items-center pb-3">
                            <h4 class="mb-0">Measurement Details </h4>
                        </div>
                        <hr>
                        <div class="col-12">
                            <h5 class="mb-0">Measurement Files</h5>
                        </div>
                        <div class="col-12 ">
                            <div class="mt-3">
                                @foreach ($measurementfiles as $file)
                                    <?php $image = URL::asset('public/measurementfile/' . $file->measurement); ?>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <img src="{{ url('/') }}/assets/media/image/docs.png" alt=""
                                                class="measurementfiles">
                                        </div>
                                        <a href="{{ $image }}" download>{{ $file->measurement }}
                                            <p class="mb-0 text-danger">
                                                <small>{{ date('d-m-Y H:i:s', strtotime($file->created_at)) }}</small></p>
                                        </a>
                                        <div class="ms-auto">
                                            <a href="{{ $image }}" class="btn btn-primary" download><i
                                                    class="fa fa-download"></i></a>
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
                            </div>
                        </div>
        
                    </div>
                </div>
            </div>
        @endif
        @if (!blank($quotations))
            <div class="card">
                <div class="card-body">
                    <div class="row w-100 view_project">
                        <div class="d-flex align-items-center pb-3">
                            <h4 class="mb-0">Quotation Details </h4>
                            <span class="ms-2">({{ date('d/m/Y H:i:s',strtotime($quotations->created_at)) }})</span>
                            <p class="mb-0 ms-auto"><strong>Selction Done ? : </strong>
                                @if ($projects->material_selection == 1)
                                    Yes
                                @else
                                    No
                                @endif
                            </p>
                        </div>
                        <hr>
                    <div class="col-md-12">
                            @if(!blank($quotations))
                                @if(!blank($quotationfiles))
                                    @foreach ($quotationfiles as $quo)
                                        <div class="align-items-center">
                                            <div class="d-flex align-items-center">
                                                <h5 class="">Quotation
                                                    @if($quo->final == 1)
                                                        <span class="text-success">( Final Quotation )</span>
                                                    @endif
                                                </h5>
                                                <div class="ms-auto">
                                                    {{date('d/m/Y H:i:s',strtotime($quo->created_at))}}
                                                </div>
                                            </div>
                                            <div class="ms-auto">
                                                @if($quo->status == 0 || $quo->status == NULL)
                                                @else
                                                    @if ($quo->status == 1)
                                                        <span class="badge bg-success">Finalize by Admin</span>
                                                    @elseif($quo->status == 2)
                                                    <span class="text-danger">
                                                        Rejected :
                                                        @if($quo->reject_reason == 1)
                                                            Delayed/Cool of
                                                        @elseif ($quo->reject_reason == 2)
                                                            Cancel
                                                        @elseif ($quo->reject_reason == 3)
                                                            Addon
                                                        @endif
                                                    </span>
                                                    <p class="mb-0">Reject Note : {{$quo->reject_note}}</p>
                                                    @endif
                                                @endif
                                                <div class="card bg-light card-body mt-3 rounded">
                                                    <h5>Uploaded Files</h5>
                                                    @php
                                                        $quotation_uploads = DB::table('quotation_uploads')->where('quotation_file_id',$quo->id)->get();
                                                    @endphp
                                                        @foreach ($quotation_uploads as $file)
                                                            @if($file->quotation_file_id == $quo->id)
                                                                <?php $image = URL::asset("public/quotationfile/".$file->file); ?>
                                                                <div class="d-flex align-items-center mt-3">
                                                                    <div class="me-2">
                                                                        <img src="{{url('/')}}/assets/media/image/docs.png" alt="" class="measurementfiles">
                                                                    </div>
                                                                    <a href="{{$image}}" download>{{$file->file_name}}
                                                                        <p class="mb-0 text-danger"><small>{{date('d-m-Y H:i:s',strtotime($file->created_at))}}</small></p>
                                                                    </a>
                                                                    <div class="ms-auto">
                                                                        <a href="{{$image}}" class="btn btn-primary" download><i class="fa fa-download"></i></a>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                            @endif
                                                        @endforeach
                                                    <h5>Description</h5>
                                                    <p>{{$quo->description}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @endif
                    </div>
                    </div>
                </div>
            </div>
        @endif
        @if (!blank($workshops))
            <div class="card">
                <div class="card-body">
                    <div class="row view_project">
                        <div class="d-flex align-items-center pb-3">
                            <h4 class="mb-0">Workshop Details </h4>
                        </div>
                        @foreach ($workshops as $workshop)
                            <div class="form-group col-12">
                                <span class="fw-bold fs-6 ">{{ $workshop->measurement }} - </span>
                                <span>{{ $workshop->description }} </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        @if (!blank($projects->transit_date))
            <div class="card">
                <div class="card-body">
                    <div class="row view_project">
                        <div class="d-flex align-items-center pb-3">
                            <h4 class="mb-0">Delivery Details </h4> <span
                                class="ms-2">({{ $projects->transit_date }})</span>
                        </div>
                        <div class="form-group col-12">
                            <p class="mb-0">{{ $projects->transit_desc }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (!blank($projects->fitting_date))
            <div class="card">
                <div class="card-body">
                    <div class="row view_project">
                        <div class="d-flex align-items-center pb-3">
                            <h4 class="mb-0">Fitting Details </h4> <span
                                class="ms-2">({{ $projects->fitting_date }})</span>
                        </div>
                        <div class="form-group col-12">
                            <p class="mb-0">{{ $projects->fitting_desc }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (!blank($projects->fitting_complete_date))
            <div class="card">
                <div class="card-body">
                    <div class="row view_project">
                        <div class="d-flex align-items-center pb-3">
                            <h4 class="mb-0">Fitting Complete Details </h4> <span
                                class="ms-2">({{ $projects->fitting_complete_date }})</span>
                        </div>
                        <div class="form-group col-12">
                            <p class="mb-0">{{ $projects->fitting_complete_desc }}</p>
                        </div>
                        <div class="form-group col-12">
                            @if (!blank($projects->id))
                                <div class="d-flex align-items-center measurement_f">
                                    @foreach ($fittings as $file)
                                        <a href="{{ url('/') }}/public/fittingfile/{{ $file->Fitting_image }}" download
                                            class="d-flex align-items-center">
                                            <img src="{{ url('/') }}/public/assets/media/image/image.png" alt=""
                                                id="featured_image_preview">
                                            <span>{{ $file->Fitting_image }}</span><i class="fa fa-download ms-3"></i>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
            </div>
            </div>
@section('content')
<div class="page-header d-md-flex justify-content-between">
    
        {{-- <h3>Welcome back, {{Auth::user()->name}}</h3> --}}
    <div class="mt-3 mt-md-0">
    
        <div>
            <input type="text" name="dashboard_daterangepicker" id="dashboard_daterange" class="form-control">
        </div>
        <div>
            
        </div>
    </div>
</div>
@endsection
        </div>
    </div>
    </div>

    <script src="{{ url('/') }}/vendors/bundle.js"></script>
    <script src="{{ url('/') }}/assets/js/bootstrap.bundle.min.js"></script>
    {{-- <script src="{{url('/')}}/vendors/prism/prism.js"></script> --}}
    <script src="{{ url('/') }}/vendors/datepicker/daterangepicker.js"></script>
    {{-- <script src="{{url('/')}}/vendors/dataTable/datatables.min.js"></script> --}}
    {{-- <script src="{{url('/')}}/assets/js/examples/pages/dashboard.js"></script> --}}
    <script src="{{ url('/') }}/assets/js/app.min.js"></script>
    <script src="{{ url('/') }}/assets/js/feather.min.js"></script>
    <script src="{{ url('/') }}/assets/js/datatable/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/assets/js/datatable/dataTables.buttons.min.js"></script>
    <script src="{{ url('/') }}/assets/js/datatable/buttons.flash.min.js"></script>
    <script src="{{ url('/') }}/assets/js/datatable/jszip.min.js"></script>
    <script src="{{ url('/') }}/assets/js/datatable/js_buttons.colVis.min.js"></script>
    <script src="{{ url('/') }}/assets/js/datatable/js_buttons.html5.min.js"></script>
    <script src="{{ url('/') }}/assets/js/datatable/js_buttons.print.min.js"></script>
    <script src="{{ url('/') }}/assets/js/datatable/js_dataTables.bootstrap.min.js"></script>
    <script src="{{ url('/') }}/assets/js/datatable/buttons.bootstrap.min.js"></script>
    <script src="{{ url('/') }}/assets/js/datatable/pdfmake_vfs_fonts.js"></script>
    <script src="{{ url('/') }}/assets/js/datatable/pdfmake.min.js"></script>
    <script src="{{ url('/') }}/assets/libs/sweetalert/sweetalert.min.js"></script>
    <script src="{{ url('/') }}/vendors/jquery.repeater.min.js"></script>
    <script src="{{ url('/') }}/assets/js/select2.min.js"></script>

    @yield('script')
    <script>
        feather.replace();
        $(document).ready(function() {
            var table = $('#example2').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excelFlash', 'excel', 'print', {
                        text: 'Reload',
                        action: function(e, dt, node, config) {
                            dt.ajax.reload();
                        }
                    }
                ],
            });
            
            
            $("#example1 tfoot th").each(function() {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="Search ' + title + '" />');
            });
            var table = $('#example1').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excelFlash', 'excel', 'print', {
                        text: 'Reload',
                        action: function(e, dt, node, config) {
                            dt.ajax.reload();
                        }
                    }
                ],
                initComplete: function(settings, json) {
                    var footer = $("#example1 tfoot tr");
                    $("#example1 thead").append(footer);
                }
            });
            $("#example1 thead").on("keyup", "input", function() {
                table.column($(this).parent().index())
                    .search(this.value)
                    .draw();
            });
        });
    </script>
    <script>
        $('input[name="startdaterangepicker"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD-MM-YYYY'
            },
        });
        $('input[name="enddaterangepicker"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD-MM-YYYY'
            },
        });
        $('input[name="projectconfirmdate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD-MM-YYYY'
            },
        });
        $('input[name="measurementdate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD/MM/YYYY'
            },
        });
        $('input[name="quotationdate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD-MM-YYYY'
            },
        });
        $('input[name="deliverydate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD-MM-YYYY'
            },
        });
        $('input[name="fittingdate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD-MM-YYYY'
            },
        });
        $('input[name="completedate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD-MM-YYYY'
            },
        });

        $(document).on('click','.read_notification',function(){
            var id = $(this).data('id');
            $.ajax({
                url : "{{ route('notification.mark_as_read') }}",
                type : 'POST',
                data: {"_token": "{{ csrf_token() }}",'id':id},
                dataType:'json',
                success : function(data) {
                }
            });
        });
        $(document).on('click', '.read_all_notification', function() {
            $.ajax({
                url: "{{ route('notification.mark_all_as_read') }}",
                type: 'POST',
                data: {"_token": "{{ csrf_token() }}"},
                dataType: 'json',
                success: function(data) {
                    // Display a toast notification on success
                    $('.notification_clear').empty();
                    var clerDiv = $('<div class="py-2 text-left border-top"> <a href="#" class="btn btn-color-gray-600">No New Notification</a></div>');
                    $('.notification_clear').append(clerDiv);
                    $('.notification_count, .read_all_notification').remove();
                    window.location.reload();
                    toastr.success('All Notifications Are Cleared');
                },
                error: function(xhr, status, error) {
                    // Display a toast notification on error if needed
                    toastr.error('Failed to mark notifications as read');
                }
            });
        });
    </script>
</body>
</html>
