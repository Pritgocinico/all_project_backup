<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ganesh Alluminium</title>
    <link rel="shortcut icon" href="{{url('/')}}/assets/media/image/favicon.png"/>
    {{-- <link rel="shortcut icon" href="{{ URL::asset('settings/'.$setting->favicon) }}" /> --}}
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
                            <i data-feather="menu"></i>
                        </a>
                    </div>
                    <div class="header-logo">
                        <a href="{{route('quotation.dashboard')}}">
                            <img class="logo" src="{{url('/')}}/assets/media/image/logo-new.png" alt="logo">
                            {{-- <img class="logo" src="{{ URL::asset('settings/'.$setting->logo) }}" alt="logo"> --}}
                        </a>
                    </div>
                </div>

                <div class="header-body">
                    <div class="header-body-left">
                        <div class="px-md-5 page_title">
                            {{-- @if(isset($icon) && $icon != '')
                            <?php $icon=URL::asset("assets/media/image/".$icon); ?>
                            <img src="{{$icon}}" alt="" width="35px">
                            @endif --}}
                            <h5 class="page_title_header ">
                                @if(isset($page))
                                {{$page}}
                                @endif
                            </h5>
                        </div>
                    </div>

                    <div class="header-body-right">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link 
                                @if (isset($notifications) && count($notifications) > 0)
                                nav-link-notify" title="Notifications"
                                @endif
                                    data-toggle="dropdown">
                                    <i data-feather="bell"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-big">
                                    <div
                                        class="border-bottom px-4 py-3 text-center d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Notifications</h5>@if (isset($notifications) && count($notifications) > 0)
                                        <a href="#" class="btn btn-sm btn-primary me-3 read_all_notification">
                                            Clear
                                        </a>
                                    @endif
                                    </div>
                                    <div class="menu menu-sub menu-sub-dropdown custom-pre-menu-notification menu-column notification_scroll"
                                        data-kt-menu="true" style="">
                                <!--begin::Heading-->
                               
                                <!--end::Heading-->
                                <div class="notification_clear">
                                    <!--begin::View more-->
                                    @if (isset($notifications) && count($notifications) > 0)
                                        @foreach ($notifications as $notification)
                                            <div class="py-2 text-left border-top custom-pre-menu-notification-inner-div">
                                                <a href="{{ $notification['data']['url'] }}"
                                                    data-id="{{ $notification['id'] }}"
                                                    class="btn btn-active-color-primary read_notification">
                                                    <b>{{ $notification['data']['title'] }}</b>
                                                    </br>
                                                    <span>
                                                    {{ $notification['data']['text'] }}
                                                    </span>
                                                    <i class="ki-outline ki-arrow-right fs-5"></i>
                                                </a>
                                                <span class="small-btn small btn btn-sm ">
                                                    <i class="bi bi-clock me-1"></i>
                                                    {{ $notification['created_at'] }}
                                                </span>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="py-2 text-left border-top">
                                            <a href="#" class="btn btn-color-gray-600">
                                                No New Notification
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="{{ route('quotation_settings') }}" class="nav-link" title="Settings"
                                    data-sidebar-target="#settings">
                                    <i data-feather="settings"></i>
                                </a>
                            </li>

                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" title="User menu" data-toggle="dropdown">
                                    <?php $user = auth()->user(); ?>
                                    @if($user->image !== NULL)
                                    <?php $image=URL::asset("public/settings/".$user->image); ?>
                                    <img src="{{ $image }}" width="55" height="55"
                                        class="rounded-circle header_user_img">
                                    @else
                                    <img src="{{url('/')}}/public/settings/user-profile.png" width="55" height="55"
                                        class="rounded-circle header_user_img">
                                    @endif
                                    <span class="ml-2 d-sm-inline d-none">{{Auth::user()->name}}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-big">
                                    <div class="text-center pt-4">
                                        <?php $user = auth()->user(); ?>
                                        @if($user->image !== NULL)
                                        <?php $image=URL::asset("public/settings/".$user->image); ?>
                                        <img src="{{ $image }}" width="55" height="55" class="rounded-circle">
                                        @else
                                        <img src="{{url('/')}}/public/settings/user-profile.png" width="55" height="55"
                                            class="rounded-circle">
                                        @endif
                                        <h5 class="text-center">{{Auth::user()->name}}</h5>

                                        @if(!blank(Auth::user()->email))<div class="mb-3 small text-center text-muted">
                                            <i class="bi bi-envelope"></i><a href="mailto:{{Auth::user()->email}}">
                                                {{Auth::user()->email}} </a></div>@endif
                                    </div>
                                    <div class="list-group">
                                        <a href="{{ route('quotation_edit.profile') }}" class="list-group-item">View Profile</a>
                                        <a href="{{route('logout')}}" class="list-group-item text-danger"
                                            data-sidebar-target="#settings">Sign Out!</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item header-toggler">
                        <a href="#" class="nav-link">
                            <i data-feather="arrow-down"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>