<div class="d-none d-lg-flex py-3">
    <div class="flex-none">
    </div>
    <div class="hstack flex-fill justify-content-end flex-nowrap gap-6 ms-auto px-6 px-xxl-8">
        @if (Auth()->user() !== null && Auth()->user()->role_id == '2')
            <div class="app-navbar-item ms-2 ms-lg-6">
                <!--begin::Menu- wrapper-->
                <ul class="navbar-nav justify-content-end">
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
                                <span>Break Start From: <span class="text-danger">{{ $break->break_start }}</span></span>
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
                <ul class="navbar-nav justify-content-end">
                    @if (!empty($break))
                        @if ($break->break_over != '')
                            <li class="nav-item">
                                <a href="javascript:void(0);"
                                    class="btn btn-primary mx-sm-3 mx-0 take_break text-nowrap">
                                    <span>Take a Break</span>
                                    <i class="fa-solid fa-clock"></i>
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="#" class="btn btn-primary mx-3 text-nowrap">Complete
                                    Break</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item">
                            <a href="javascript:void(0);" class="btn btn-primary take_break mx-xs-3 mx-0 text-nowrap">
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
            <a href="{{ url('/') }}/chatify" class="btn btn-primary mx-xs-3 mx-0 text-nowrap" target="_blank">
                <span class="chat-text">Chat</span>
                &nbsp; <i class="bi bi-chat-dots"></i></a>
        </div>
        @if (Route::currentRouteName() !== 'dashboard')
            <a href="#" class="main-cmn-back-btn text-dark" onclick="window.history.back();"
                data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Back"
                data-kt-initialized="1">
                <i class="bi bi-arrow-left"></i>
            </a>
        @endif
        <div class="dropdown"><a href="#" class="nav-link" id="dropdown-notifications " data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="bi bi-bell" aria-hidden="true"></i>
                @if (isset($notifications) && count($notifications) > 0)
                    <span
                        class="position-absolute top-0 start-100 translate-middle  badge badge-circle bg-danger notification_count  text-white">
                        @if (count($notifications) > 50)
                            {{ 50 . '+' }}
                        @else
                            {{ count($notifications) }}
                        @endif
                    </span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-end px-2" aria-labelledby="dropdown-notifications">
                <div class="dropdown-item d-flex align-items-center">
                    <h6 class="dropdown-header px-0">Notifications</h6>
                    <a href="#" class="text-sm fw-semibold ms-auto read_all_notification">Clear all</a>
                </div>
                @forelse ($notifications as $notification)
                    <div class="dropdown-item py-3 d-flex">
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
                                <i class="bi bi-clock me-1"></i>
                                {{ Utility::convertDmyAMPMFormat($notification['created_at']) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="dropdown-item py-3 d-flex">
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
                data-bs-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
                @php
                    $image = asset('assets/img/user/user.jpg');
                    if (Auth()->user()->profile_image !== null) {
                        $image = asset('storage/' . Auth()->user()->profile_image);
                    }
                @endphp

                <img src="{{ $image }}"></a>
            <div class="dropdown-menu dropdown-menu-end">
                <div class="dropdown-header"><span class="d-block text-sm text-muted mb-1">Signed in
                        as</span> <span class="d-block text-heading fw-semibold">{{ Auth()->user()->name }}</span>
                </div>
                <div class="dropdown-divider"></div>
                @if (Auth()->user(0)->role_id == 1)
                    <a class="dropdown-item" href="{{ route('business-setting') }}"><i
                            class="bi bi-pencil me-3"></i>Business Setting</a>
                    <a class="dropdown-item" href="{{ route('setting') }}"><i class="bi bi-gear me-3"></i>Dashboard
                        Settings </a>
                @endif
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-up me-3"></i>Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
