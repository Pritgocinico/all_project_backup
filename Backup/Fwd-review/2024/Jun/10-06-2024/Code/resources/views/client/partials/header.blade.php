<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$setting->site_name}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{$setting->favicon}}">
    <link rel="stylesheet" href="{{ url('/') }}/assets/Css/style.css">
    <link rel="stylesheet" href="{{ url('/') }}/assets/Css/media.css">
    <link href="{{ url('/') }}/assets/Css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('/') }}/assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/assets/Css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/assets/Css/datatable_buttons.bootstrap.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/assets/Css/datepicker_semantic.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/assets/sweetalert/sweetalert.min.css">
    <link rel="stylesheet" href="{{url('/')}}/assets/Css/toastr/toastr.min.css">
    @yield('style')
    <script src="{{ url('/') }}/assets/JS/3.5.1_jquery.min.js" charset="utf-8"></script>
</head>

<body>
    <div class=" gc-main px-0">
        <div class="row header_row mx-0 py-2 sticky-top">
            <div class="col-2 p-0 d-none d-md-block">
                <div class="header_logo ps-5 mt-2">
                    <a href="{{ route('client.dashboard') }}"><img src="{{asset($setting->logo)}}" alt=""
                            class="header_logo "></a>
                </div>
            </div>
            <div class="col-md-10 col-12 p-0">
                <div class="header py-1 px-3">
                    <div class="d-flex align-items-center">
                        <div id="menu-btn" class="toggle_bar me-4 me-md-5"><img
                                src="{{ url('/') }}/assets/Images/Sidebar_Icons/toggle_bar.png" alt="">
                        </div>
                        <div class="d-none d-md-block">
                            <h4 class="mb-0 pt-1 ">{{ $page }}</h4>
                        </div>
                        <div class="d-block d-md-none"><a href="{{ url('/') }}/"><img
                                    src="{{ url('/') }}\assets\Images\footer-logo.png" alt="" class="header_logo "></a>
                        </div>
                        <div class="d-flex ms-auto align-items-center">
                            @if (!blank($client_business))
                            <div class="me-2">
                                <select name="client_business" id="client_business_dropdown" class="form-select p-2 client_business">
                                    @foreach ($client_business as $businesses)
                                    @php $type = ""; @endphp
                                    @if (date('Y-m-d', strtotime($businesses->sub_end_date)) < date('Y-m-d')) 
                                    @php $type="disabled" ; @endphp 
                                    @endif
                                        <option value="{{ $businesses->id }}" {{ $type
                                        }} @if (!blank($business)) @if ($business->id == $businesses->id) selected
                                        @endif
                                        @endif>{{ $businesses->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="nav-item">
                                <a href="#" onclick="openNav()" class="nav-link nav-link-notify pe-3 p-md-3"
                                    data-sidebar-target="#notifications">
                                    <img src="{{ url('/') }}\assets\Images\Sidebar_Icons\notification.png" alt=""
                                        class="noti_bell">
                                </a>
                            </div>
                            <div class="dropdown">
                                <a class="nav-link dropdown-toggle profile-dropdown sub-menu me-3"
                                    data-bs-toggle="dropdown" type="button" aria-expanded="false">
                                    <img src="{{ url('/') }}\assets\Images\Sidebar_Icons\profile.png"
                                        class="rounded-circle profile" alt='userProfile' />
                                </a>
                                <ul class="dropdown-menu">
                                    <li class=''><a class="dropdown-item fs-6 py-2"
                                            href="{{ route('client.view', Auth()->user()->id) }}">My Profile</a> </li>
                                    <li class=''><a class="dropdown-item fs-6 py-2"
                                            href="{{ route('edit.client') }}">Edit Profile</a> </li>
                                    <li class=''><a class="dropdown-item fs-6 py-2"
                                            href="{{ route('logout') }}/">Logout</a> </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sidebar sidepanel overflow-auto" id="notifications">
            <div class="p-3">
                <div class="sidebar-header d-block align-items-end">
                    <div class="align-items-center d-flex justify-content-between py-4">
                        <h4>Notifications</h4>
                        @if (isset($notifications) && count($notifications) > 0)
                        <a href="#" class="btn btn-sm btn-primary me-3 read_all_notification">
                            Clear
                        </a>
                        @endif
                        <div role='button' onClick="CloseNav()">
                            <h4>X</h4>
                        </div>
                    </div>
                </div>
                <div class="sidebar-content">
                    <div class="tab-content">
                        <div class="tab-pane active" id="activities">
                            <div class="tab-pane-body">
                                <ul class="list-unstyled list-group-flush">
                                    <li class="px-2 ">
                                        <div class="notification_clear mh-550px scroll-y">
                                            <!--begin::View more-->
                                            @if (isset($notifications) && count($notifications) > 0)
                                            @foreach ($notifications as $notification)
                                            <div
                                                class="py-2 text-left border-top custom-pre-menu-notification-inner-div">
                                                <a href="{{ $notification['data']['url'] }}"
                                                    data-id="{{ $notification['id'] }}"
                                                    class="btn btn-active-color-primary read_notification">
                                                    <b>{{ $notification['data']['title'] }}</b>
                                                    {{-- </br> --}}
                                                    <span>
                                                        {{ $notification['data']['text'] }}
                                                    </span>
                                                    <i class="ki-outline ki-arrow-right fs-5"></i>
                                                </a></br>
                                                <span class="small-btn small btn btn-sm " style="color: white;">
                                                    <i class="bi bi-clock me-1"></i>
                                                    {{ $notification['created_at'] }}
                                                </span>
                                            </div>
                                            @endforeach
                                            @else
                                            <div class="py-2 text-left border-top">
                                                <p class="mb-0 fw-bold houmanity-color d-flex justify-content-between">
                                                    No New Notification
                                                </p>
                                            </div>
                                            @endif
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid px-0">
            <div class="w-100 d-flex gc_main_content">
                <div id="sidebar" class="side_normal px-0">
                    