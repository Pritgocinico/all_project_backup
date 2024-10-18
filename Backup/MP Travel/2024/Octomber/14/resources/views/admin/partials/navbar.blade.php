<div class="d-none d-lg-flex py-3">

    <div class="row align-items-center g-3 ms-5 auth-name-nav">
        <h4>Hello, {{ Auth()->user()->name }}  @if((Int)Auth()->user()->role_id != 1) - {{ Auth()->user()->designationDetail->name }}  @endif <span class="navbar-span"
                style="font-size: 1rem">{{ isset(Auth()->user()->departmentDetail) ? ' ( ' . Auth()->user()->departmentDetail->name . ' ) ' : '' }}</span>

        </h4>

    </div>

    <div class="hstack flex-fill justify-content-end flex-nowrap gap-3 ms-auto px-6 px-xxl-8 d-nav-time">

        <div class="col text-end">

            <h4 id="clock"></h4>

        </div>

        @if (Auth()->user() !== null && Auth()->user()->role_id !== '1')

            <div class="app-navbar-item ms-2 ms-lg-6">

                <!--begin::Menu- wrapper-->

                <ul class="navbar-nav justify-content-end  begin-menu-in">

                    <?php $att = DB::table('attendances')
                    
                        ->where('user_id', Auth::user()->id)
                    
                        ->whereDate('created_at', Carbon\Carbon::today())
                    
                        ->first(); ?>

                    <?php $break = DB::table('break_logs')
                    
                        ->where('user_id', Auth::user()->id)
                    
                        ->orderBy('id', 'DESC')
                    
                        ->whereDate('created_at', Carbon\Carbon::today())
                    
                        ->first();
                    
                    $breaks = DB::table('break_logs')
                    
                        ->where('user_id', Auth::user()->id)
                    
                        ->orderBy('id', 'DESC')
                    
                        ->whereDate('created_at', Carbon\Carbon::today())
                    
                        ->get();
                    
                    $diff_in_days = [];
                    
                    if (!empty($breaks)) {
                        foreach ($breaks as $br) {
                            $to = new \Carbon\Carbon($br->break_start);
                    
                            $from = new \Carbon\Carbon($br->break_over);
                    
                            $diff_in_days[] = $to->diff($from)->format('%H:%I:%S');
                        }
                    }
                    ?>

                    @if (!empty($break))

                        @if ($break->break_over != '')
                            <li class="nav-item">

                                <p class="m-0">Break Time: <span
                                        class="break-time text-danger">{{ Utility::sumTime($diff_in_days) }}</span>

                                </p>

                            </li>
                        @else
                            <li class="nav-item">

                                <span>Break Start From: <span
                                        class="text-danger">{{ $break->break_start }}</span></span>

                            </li>
                        @endif
                    @else
                        <li class="nav-item">

                            <p class="m-0">Break Time: <span
                                    class="break-time text-danger">{{ Utility::sumTime($diff_in_days) }}</span>

                            </p>

                        </li>



                    @endif

                    </li>



                </ul>



            </div>

            <div class="app-navbar-item ms-2 ms-lg-6">

                <ul class="navbar-nav justify-content-end ">

                    @if (!empty($break))

                        @if ($break->break_over != '')
                            <li class="nav-item">

                                <a href="javascript:void(0);" class="btn btn-primary mx-sm-3 mx-0 text-nowrap btn-sm"
                                    data-bs-target="#addDepartmentModal" data-bs-toggle="modal">

                                    <span>Take a Break</span>

                                    <i class="fa-solid fa-clock"></i>

                                </a>

                            </li>
                        @else
                            <li class="nav-item">

                                <a href="#" class="btn btn-primary mx-3 text-nowrap btn-sm">Complete

                                    Break</a>

                            </li>
                        @endif
                    @else
                        <li class="nav-item">

                            <a href="javascript:void(0);" class="btn btn-dark mx-xs-3 mx-0 text-nowrap btn-sm"
                                data-bs-target="#addDepartmentModal" data-bs-toggle="modal">

                                <span>Take a Break</span>

                                <i class="fa-solid fa-clock"></i>

                            </a>

                        </li>

                    @endif

                    </li>



                </ul>



            </div>

        @endif

        <div class="app-navbar-item ms-2 ms-lg-6">

            <a href="{{ url('/') }}/chatify" class="btn btn-dark mx-xs-3 mx-0 text-nowrap btn-sm d-nav-btn"
                target="_blank">

                <span class="chat-text">Chat</span>

                &nbsp; <i class="fa-regular fa-comment-dots"></i></a>

        </div>

        @if (Route::currentRouteName() !== 'dashboard')
            <a href="#" class="main-cmn-back-btn text-dark header-btn-arrows btn-dark"
                onclick="window.history.back();" data-bs-toggle="tooltip" data-bs-placement="bottom"
                data-bs-original-title="Back" data-kt-initialized="1">

                <i class="fa-solid fa-arrow-left"></i>

            </a>
        @endif

        <div class="dropdown"><a href="#" class="nav-link" id="dropdown-notifications " data-bs-toggle="dropdown"
                aria-expanded="false">

                <i class="fa-solid fa-bell"></i>

                @if (isset($notifications) && count($notifications) > 0)

                    <span
                        class="position-absolute bell-bg top-0 start-100 translate-middle  badge badge-circle  notification_count  text-white">

                        @if (count($notifications) > 50)
                            {{ 50 . '+' }}
                        @else
                            {{ count($notifications) }}
                        @endif

                    </span>

                @endif

            </a>

            <div class="dropdown-menu dropdown-menu-end px-2 top-notification-bar"
                aria-labelledby="dropdown-notifications">

                <div class="dropdown-item d-flex align-items-center notification-header">

                    <h6 class="dropdown-header px-0">Notifications</h6>

                    <a href="#" class="text-sm fw-semibold ms-auto read_all_notification">Clear all</a>

                </div>

                @forelse ($notifications as $notification)
                    <div class="dropdown-item py-3 d-flex inner-notification ">

                        <div class="py-2 text-left border-top custom-pre-menu-notification-inner-div">

                            <a href="{{ $notification['data']['url'] }}" data-id="{{ $notification['id'] }}"
                                class="btn btn-active-color-primary read_notification">

                                <b>{{ $notification['data']['title'] }}</b>

                                </br>

                                <span>

                                    {{ $notification['data']['text'] }}

                                </span>

                                <i class="ki-outline ki-arrow-right fs-5"></i>

                            </a></br>

                            <span class="small-btn small btn btn-sm ">

                                <i class="fa-regular fa-clock me-1"></i>

                                {{ Utility::convertDmyAMPMFormat($notification['created_at']) }}

                            </span>

                        </div>

                    </div>

                @empty

                    <div class="dropdown-item py-3 d-flex inner-notification">

                        <div class="py-2 text-left border-top custom-pre-menu-notification-inner-div">

                            <a href="#" class="btn btn-active-color-primary">

                                No New Notification

                            </a>

                        </div>

                    </div>
                @endforelse

            </div>

        </div>

        <div class="dropdown"><a class="avatar avatar-sm rounded-circle" href="#" role="button"
                id="user_profile_image_dropdown" data-bs-toggle="dropdown" aria-haspopup="false" aria-expanded="false">

                @php

                    $image = asset('assets/img/user/user.jpg');

                    if (Auth()->user()->profile_image !== null) {
                        $image = asset('storage/' . Auth()->user()->profile_image);
                    } elseif (isset($settings)) {
                        $image = asset('storage/' . $settings->fa_icon);
                    }

                @endphp



                <img src="{{ $image }}"></a>

            <div class="dropdown-menu dropdown-menu-end">

                <div class="dropdown-header"><span class="d-block text-sm text-muted mb-1">Signed in

                        as</span> <span class="d-block text-heading fw-semibold">{{ Auth()->user()->name }}</span>

                </div>

                <div class="dropdown-divider"></div>

                @if (Auth()->user(0)->role_id == 1)
                    <a class="dropdown-item {{ request()->routeIs('business-setting') ? 'fw-bold' : '' }}"
                        href="{{ route('business-setting') }}"><i class="fa-solid fa-pen-to-square me-3"></i>Business
                        Setting</a>

                    <a class="dropdown-item {{ request()->routeIs('setting') ? 'fw-bold' : '' }}"
                        href="{{ route('setting') }}"><i class="fa-solid fa-gear me-3"></i>Dashboard

                        Settings </a>
                @endif

                @if (Auth()->user()->role_id !== '1')
                    <a class="dropdown-item {{ request()->routeIs('profile-view') ? 'fw-bold' : '' }}"
                        href="{{ route('profile-view') }}"><i class="fa-solid fa-circle-user me-3"></i>User Profile</a>
                @else
                    <a class="dropdown-item {{ request()->routeIs('change.password') ? 'fw-bold' : '' }}"
                        href="{{ route('change.password') }}"><i class="fa-solid fa-pen-to-square me-3"></i>Change
                        Password</a>
                @endif

                <div class="dropdown-divider"></div>

                <form method="POST" action="{{ route('logout') }}" id="logout-form">

                    @csrf

                    <button type="submit" class="dropdown-item"><i
                            class="fa-solid fa-arrow-up-from-bracket me-3"></i>Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
    style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content overflow-hidden">
            <div class="modal-header pb-0 border-0">
                <h1 class="modal-title h4">Break</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="vstack" method="POST" id="braekTimeForm" action="#">
                @csrf
                <div class="modal-body undefined">
                    <div class="vstack gap-1">
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-3"><label class="form-label mb-0">Break Type<span
                                        class="error_span">*</span></label></div>
                            <div class="col-md-9 col-xl-9">
                                <select id="break_type" class="form-select" name="break_type">
                                    <option value="">Select Break Type</option>
                                    <option value="lunch">Lunch Break</option>
                                    <option value="meeting">Meeting Break</option>
                                    <option value="tea_coffee">Tea/Coffee Break</option>
                                </select>
                                <span class="error_span" id="break_type_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-dark take_break" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
