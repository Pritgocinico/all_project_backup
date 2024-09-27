<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AARNA INSURANCE SERVICES</title>
    <link rel="icon" type="image/x-icon" href="{{ URL::asset('settings/'.$setting->favicon) }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/icofont.css">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/themify.css">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/flag-icon.css">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/feather-icon.css">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/style.css">
    <link id="color" rel="stylesheet" href="{{url('/')}}/assets/css/color-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/responsive.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/custom.css">
    @yield('style')
</head>

<body>
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">

        <!-- Page Body Start-->
        <div class="page-body-wrapper">
            <div class="">
                <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-sm-6 ps-0">
                                <h3 class="fw-bold">AARNA INSURANCE SERVICES - Privacy Policy </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    {!! $setting->privacy_policy !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid Ends-->
            </div>
        </div>
    </div>
    <script src="{{url('/')}}/assets/js/script.js"></script>
    <script src="{{url('/')}}/assets/js/jquery.min.js"></script>
    <!-- Bootstrap js-->
    <script src="{{url('/')}}/assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- feather icon js-->
    <script src="{{url('/')}}/assets/js/icons/feather-icon/feather.min.js"></script>
    <script src="{{url('/')}}/assets/js/icons/feather-icon/feather-icon.js"></script>
    <!-- scrollbar js-->
    <script src="{{url('/')}}/assets/js/scrollbar/simplebar.js"></script>
    <script src="{{url('/')}}/assets/js/scrollbar/custom.js"></script>
    <!-- Sidebar jquery-->
    <script src="{{url('/')}}/assets/js/config.js"></script>
</body>

</html>