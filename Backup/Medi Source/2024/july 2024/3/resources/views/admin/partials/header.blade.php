<!-- Your HTML content continues -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>MedisourceRx</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="" name="author" />

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


    @if ($setting && $setting->favicon)
    <link rel="shortcut icon" href="{{ asset('/storage/' . $setting->favicon) }}">
    @endif

    <link rel="stylesheet" href="{{url('/')}}/assets/vendor/daterangepicker/daterangepicker.css">

    <link rel="stylesheet"
        href="{{url('/')}}/assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css">

    <script src="{{url('/')}}/assets/js/config.js"></script>

    <link href="{{url('/')}}/assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

    <link href="{{url('/')}}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    @yield('style')
</head>
<body>
    <div class="wrapper">
        <div class="navbar-custom">
            <div class="topbar container-fluid">
                <div class="d-flex align-items-center gap-1">
                    <div class="logo-topbar">
                        <a href="{{url('/')}}" class="logo-light">
                            <span class="logo-lg">
                                <img src="{{url('/')}}/assets/images/logo.png" alt="logo">
                            </span>
                            <span class="logo-sm">
                                <img src="{{url('/')}}/assets/images/logo-sm.png" alt="small logo">
                            </span>
                        </a>
                        <a href="{{url('/')}}" class="logo-dark">
                            <span class="logo-lg">
                                <img src="{{url('/')}}/assets/images/logo-dark.png" alt="dark logo">
                            </span>
                            <span class="logo-sm">
                                <img src="{{url('/')}}/assets/images/logo-sm.png" alt="small logo">
                            </span>
                        </a>
                    </div>
                    <button class="button-toggle-menu">
                        <i class="ri-menu-line"></i>
                    </button>
                    <button class="navbar-toggle" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                        <div class="lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </button>
                </div>
                <ul class="topbar-menu d-flex align-items-center gap-3">
                    <li class="dropdown d-lg-none">
                        <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button"
                            aria-haspopup="false" aria-expanded="false">
                            <i class="ri-search-line fs-22"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                            <form class="p-3">
                                <input type="search" class="form-control" placeholder="Search ..."
                                    aria-label="Recipient's username">
                            </form>
                        </div>
                    </li>
                    <li class="d-none d-sm-inline-block">
                        <a class="nav-link" href="{{ route('admin.settings') }}">
                            <i class="ri-settings-3-line fs-22"></i>
                        </a>
                    </li>

                    <li class="dropdown">
                    @if (auth()->check())
                        <a class="nav-link dropdown-toggle arrow-none nav-user" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="account-user-avatar">
                                <img src="{{ asset('public/storage/' . Auth::guard('admin')->user()->profile_image) }}" alt="user-image" width="32"
                                    class="rounded-circle">
                            </span>
                            <span class="d-lg-block d-none" id="dynamicNameContainer">
                                <h5 class="my-0 fw-normal">{{ Auth::guard('admin')->user()->name }} <i
                                        class="ri-arrow-down-s-line d-none d-sm-inline-block align-middle"></i></h5>
                            </span>
                        </a>
                    @endif
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">
                            <div class=" dropdown-header noti-title">
                                <h6 class="text-overflow m-0">{{ Auth::guard('admin')->user()->role_name }} </h6>
                            </div>
                            <a href="{{ route('admin.profile.show') }}" class="dropdown-item">
                                <i class="ri-eye-line fs-18 align-middle me-1"></i>
                                <span>View</span>
                            </a>
                            <a href="{{ route('adminLogout') }}" class="dropdown-item">
                                <i class="ri-logout-box-line fs-18 align-middle me-1"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
