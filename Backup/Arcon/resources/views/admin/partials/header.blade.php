<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Arcon</title>
    <?php $url=config('app.url') ?>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ $url }}/settings/{{ $setting->favicon }}"/>
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ $url }}/assets/admin/icons/themify-icons/themify-icons.css" type="text/css">  
    <link rel="stylesheet" href="{{ $url }}/assets/admin/icons/bootstrap-icons-1.4.0/bootstrap-icons.min.css" type="text/css">
    <!-- Bootstrap Docs -->
    <link href="{{ url('/') }}/assets/admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ url('/') }}/assets/admin/libs/dataTable/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="{{ $url }}/assets/admin/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="{{ $url }}/assets/admin/libs/select2/css/select2.min.css" type="text/css">
    <!-- Datepicker -->
    <link rel="stylesheet" href="{{ $url }}/assets/admin/libs/datepicker/daterangepicker.css">
    
    <link rel="stylesheet" href="{{ $url }}/assets/admin/libs/range-slider/css/ion.rangeSlider.min.css" type="text/css">

    <!-- Prism -->
    <link rel="stylesheet" href="{{ $url }}/assets/admin/libs/prism/prism.css" type="text/css">
 
    <!-- Animate.css -->
    <link rel="stylesheet" href="{{env('APP_URL') }}/assets/admin/libs/animate/animate.min.css" type="text/css">
    <!-- Slick -->
    <link rel="stylesheet" href="{{ $url }}/assets/admin/libs/slick/slick.css" type="text/css">
    <!-- Summernote -->
    <link rel="stylesheet" href="{{ $url }}/assets/admin/libs/summernote/summernote-lite.css" type="text/css">
    <link rel="stylesheet" href="{{ $url }}/assets/admin/css/fullcalendar.min.css" />
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: no ne;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="{{ url('/') }}/assets/admin/css/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ $url }}/assets/admin/libs/sweetalert/sweetalert.min.css">
    <link href="{{ url('/') }}/assets/admin/css/style.css" rel="stylesheet">
    @yield('style')
    <script src="{{ $url }}/assets/admin/jquery.min.js"></script>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
    <script src="{{ $url }}/assets/admin/custom.js"></script>
   
  </head>
<div id="loader" class="lds-dual-ring hidden overlay">
</div>

<body class="arcon-user-div">
<header class="navbar navbar-light sticky-top bg-light flex-md-nowrap p-0 shadow d-d-none">
  <div class="header-inner">
  <div class="gc-logo p-2 ">
    <a href="{{ route('admin.dashboard') }}" class="menu-header-logo">
        <img src="{{ URL::asset('settings/'.$setting->logo) }}" alt="logo">
    </a>
  </div>
 
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  </div>
</header>
