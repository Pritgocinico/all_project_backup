<!doctype html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <meta name="color-scheme" content="dark light">
    <link rel="shortcut icon" href="{{ $settings && isset($settings->fa_icon) ? asset('/storage/' . $settings->fa_icon) : asset('assets/img/fa_icon/fa_icon.png') }}" />
    <title>{{isset($page)?$page."-":""}}{{ $settings && $settings->site_name ? $settings->site_name : 'MP Group' }}</title>
    <link href="{{ asset('plugin/sweet-alert/sweetalert.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugin/select2/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugin/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugin/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugin/datatable/datatables/datatables.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/utility.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Jost:ital,wght@0,100..900;1,100..900&family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">   
    <link href="{{ asset('plugin/daterangepicker/daterangepicker.css') }}" rel="stylesheet">
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
