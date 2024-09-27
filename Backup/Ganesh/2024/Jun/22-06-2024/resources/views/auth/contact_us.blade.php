<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ganesh Alluminium</title>
    {{-- <link rel="shortcut icon" href="{{url('/')}}/assets/media/image/favicon.png"/> --}}
    <link rel="shortcut icon" href="{{ URL::asset('public/settings/' . $setting->favicon) }}" />
    <link rel="stylesheet" href="{{ url('/') }}/vendors/bundle.css" type="text/css">
    <link href="{{ url('/') }}/assets/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500;600;700&amp;display=swap" rel="stylesheet"> --}}

    <link rel="stylesheet" href="{{ url('/') }}/vendors/datepicker/daterangepicker.css" type="text/css">
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/datatable_buttons.bootstrap.min.css">
    {{-- <link rel="stylesheet" href="{{url('/')}}/vendors/dataTable/datatables.min.css" type="text/css"> --}}

    <link rel="stylesheet" href="{{ url('/') }}/vendors/prism/prism.css" type="text/css">

    <link rel="stylesheet" href="{{ url('/') }}/assets/libs/sweetalert/sweetalert.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/app.min.css" type="text/css">
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/select2.min.css" />
    @yield('style')
    <script src="{{ url('/') }}/assets/js/3.5.1_jquery.min.js" charset="utf-8"></script>
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-menu">
                                <line x1="3" y1="12" x2="21" y2="12"></line>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <line x1="3" y1="18" x2="21" y2="18"></line>
                            </svg>
                        </a>
                    </div>
                    <div class="header-logo">
                        <a href="https://crm.sganeshaluminium.com/admin/dashboard">
                            <img class="logo" src="https://crm.sganeshaluminium.com/assets/media/image/logo-new.png"
                                alt="logo">

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
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <polyline points="19 12 12 19 5 12"></polyline>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="content-wrapper">
            <div class="navigation">
                <div class="navigation-menu-body">
                    <h1>Contact Us</h1>
                </div>
            </div>
            <div class="content-body">
                <div class="content">
                    <form method="post" action="{{ route('store-contact-us') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name:-</label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="enter name">
                                </div>
                                @if ($errors->has('name'))
                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email:-</label>
                                    <input type="email" class="form-control" name="email"
                                        placeholder="enter email">
                                </div>
                                @if ($errors->has('email'))
                                    <p class="text-danger">{{ $errors->first('email') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone Number:-</label>
                                    <input type="number" class="form-control" name="phone_number"
                                        placeholder="enter phone number">
                                </div>
                                @if ($errors->has('phone_number'))
                                    <p class="text-danger">{{ $errors->first('phone_number') }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Message:-</label>
                                    <textarea name="message" class="form-control" placeholder="enter message"></textarea>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @section('content')
        <div class="page-header d-md-flex justify-content-between">
            <div class="mt-3 mt-md-0">

                <div>
                    <input type="text" name="dashboard_daterangepicker" id="dashboard_daterange"
                        class="form-control">
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

        $(document).on('click', '.read_notification', function() {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('notification.mark_as_read') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': id
                },
                dataType: 'json',
                success: function(data) {}
            });
        });
        $(document).on('click', '.read_all_notification', function() {
            $.ajax({
                url: "{{ route('notification.mark_all_as_read') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(data) {
                    // Display a toast notification on success
                    $('.notification_clear').empty();
                    var clerDiv = $(
                        '<div class="py-2 text-left border-top"> <a href="#" class="btn btn-color-gray-600">No New Notification</a></div>'
                    );
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
