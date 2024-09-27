<!doctype html>
<html lang="en">
<!-- Mirrored from satoshi.webpixels.io/pages/dashboard.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 24 Jun 2024 12:14:17 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <meta name="color-scheme" content="dark light">
    <link rel="shortcut icon" href="{{ $settings ? asset('/storage/' . $settings->fa_icon) : asset('assets/img/fa_icon/fa_icon.png') }}" />
    <title>{{isset($page)?$page."-":""}}{{ $settings ? $settings->site_name : 'MP Group' }}</title>
    <link href="{{ asset('plugin/sweet-alert/sweetalert.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugin/select2/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugin/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugin/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugin/datatable/datatables/datatables.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/utility.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css"> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Jost:ital,wght@0,100..900;1,100..900&family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">   
    @yield('style')
</head>

<body class="bg-body-tertiary">
    <div class="d-flex flex-column flex-lg-row h-lg-100 gap-1">
        @include('admin.partials.sidebar')
        <div class="flex-lg-fill overflow-x-auto ps-lg-1 vstack vh-lg-100 position-relative">
            @include('admin.partials.navbar')
            @yield('content')
        </div>
    </div>
    @include('admin.partials.footer')
</body>

</html>
