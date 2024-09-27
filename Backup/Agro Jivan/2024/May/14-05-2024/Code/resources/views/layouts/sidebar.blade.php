<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-aside-enabled="true"
    data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true" data-kt-app-aside-push-footer="true"
    class="app-default">


    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">


            <!--begin::Header-->
            <div id="kt_app_header" class="app-header  d-flex flex-column flex-stack ">

                <!--begin::Header main-->
                <div class="d-flex flex-stack flex-grow-1">

                    <div class="app-header-logo d-flex align-items-center ps-lg-12" id="kt_app_header_logo">
                        <!--begin::Sidebar toggle-->
                        <div id="kt_app_sidebar_toggle"
                            class="app-sidebar-toggle btn btn-sm btn-icon bg-body btn-color-gray-500 btn-active-color-primary w-30px h-30px ms-n2 me-4 d-none d-lg-flex "
                            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
                            data-kt-toggle-name="app-sidebar-minimize">

                            <i class="ki-outline ki-abstract-14 fs-3 mt-1"></i>
                        </div>
                        <!--end::Sidebar toggle-->

                        <!--begin::Sidebar mobile toggle-->
                        <div class="btn btn-icon btn-active-color-primary w-35px h-35px ms-3 me-2 d-flex d-lg-none"
                            id="kt_app_sidebar_mobile_toggle">
                            <i class="ki-outline ki-abstract-14 fs-2"></i>
                        </div>
                        <!--end::Sidebar mobile toggle-->

                        <!--begin::Logo-->
                        @php $route = route('dashboard') @endphp
                        @if (Auth()->user() == null)
                            @php $route = route('login') @endphp
                        @elseif (Auth()->user()->role_id == 2)
                            @php $route = route('employee-dashboard') @endphp
                        @elseif (Auth()->user()->role_id == 3)
                            @php $route = route('hr-dashboard') @endphp
                        @elseif (Auth()->user()->role_id == 4)
                            @php $route = route('confirm-dashboard') @endphp
                        @elseif (Auth()->user()->role_id == 5)
                            @php $route = route('driver-dashboard') @endphp
                        @elseif (Auth()->user()->role_id == 6)
                            @php $route = route('system-engineer-dashboard') @endphp
                        @elseif (Auth()->user()->role_id == 7)
                            @php $route = route('transport-department-dashboard') @endphp
                        @elseif (Auth()->user()->role_id == 8)
                            @php $route = route('warehouse-dashboard') @endphp
                        @elseif (Auth()->user()->role_id == 9)
                            @php $route = route('sales-manager-dashboard') @endphp
                        @elseif (Auth()->user()->role_id == 10)
                            @php $route = route('sales-service-dashboard') @endphp
                        @endif
                        <a href="{{ $route }}" class="app-sidebar-logo">
                            <img alt="Logo" src="{{ asset('public/assets/media/svg/AgroJivan.svg') }}"
                                class="w-180 theme-light-show" />
                        </a>
                        <!--end::Logo-->
                    </div>

                    <!--begin::Navbar-->
                    <div class="app-navbar flex-grow-1 justify-content-end" id="kt_app_header_navbar">
                        <div class="app-navbar-item d-flex align-itmes-center flex-lg-grow-1">

                            <h4 class="mb-0 main-dash-title">
                                {{ isset($page) ? $page : 'Dashboard' }}
                            </h4>
                        </div>
                        @if (Auth()->user() !== null && Auth()->user()->role_id == '2')
                            <div class="app-navbar-item ms-2 ms-lg-6">
                                <!--begin::Menu- wrapper-->
                                <ul class="navbar-nav justify-content-end">
                                    <?php $att = DB::table('attendances')
                                        ->where('user_id', Auth::user()->id)
                                        ->whereDate('created_at', Carbon\Carbon::today())
                                        ->first(); ?>
                                    <?php $break = DB::table('break_log')
                                        ->where('user_id', Auth::user()->id)
                                        ->orderBy('id', 'DESC')
                                        ->whereDate('created_at', Carbon\Carbon::today())
                                        ->first();
                                    $breaks = DB::table('break_log')
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
                                            <a href="javascript:void(0);"
                                                class="btn btn-primary take_break mx-xs-3 mx-0 text-nowrap">
                                                <span>Take a Break</span>
                                                <i class="fa-solid fa-clock"></i>
                                            </a>
                                        </li>
                                    @endif
                                    </li>

                                </ul>

                            </div>
                        @endif
                        <!--end::Notifications-->
                        @if (Auth()->user() !== null && Auth()->user()->role_id !== 5)
                            <div class="app-navbar-item ms-2 ms-lg-6">
                                <a href="#" class="main-cmn-back-btn" onclick="window.history.back();"
                                    data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Back"><i
                                        class="fa-solid fa-arrow-left"></i></a>
                                <a href="{{ url('/') }}/chatify" class="btn btn-primary mx-xs-3 mx-0 text-nowrap"
                                    target="_blank"> <span class="chat-text">Chat</span>
                                    &nbsp; <i class="fa-solid fa-comment-dots"></i></a>
                            </div>
                            <div class="app-navbar-item ms-2 ms-lg-6">
                                <!--begin::Menu- wrapper-->
                                <div class="btn btn-icon btn-custom btn-color-gray-600 btn-active-color-primary w-35px h-35px w-md-40px position-relative"
                                    data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                                    data-kt-menu-placement="bottom-end">
                                    <i class="ki-outline ki-notification-on fs-1"></i>
                                    @if (isset($notifications) && count($notifications) > 0)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle  badge badge-circle badge-danger ms-n4 mt-3 notification_count">
                                            @if (count($notifications) > 50)
                                                {{ 50 . '+' }}
                                            @else
                                                {{ count($notifications) }}
                                            @endif
                                        </span>
                                    @endif
                                </div>

                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown custom-pre-menu-notification menu-column"
                                    data-kt-menu="true" style="">
                                    <!--begin::Heading-->
                                    <div
                                        class="d-flex inner-custom-notification justify-content-between bgi-no-repeat rounded-top px-9 py-5">
                                        <!--begin::Title-->
                                        <h3 class="text-black fw-semibold mt-2">
                                            Notifications
                                        </h3>
                                        @if (isset($notifications) && count($notifications) > 0)
                                            <a href="#" class="btn btn-sm btn-primary me-3 read_all_notification">
                                                Clear
                                            </a>
                                        @endif
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Heading-->
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
                                                        </br>
                                                        <span>
                                                            {{ $notification['data']['text'] }}
                                                        </span>
                                                        <i class="ki-outline ki-arrow-right fs-5"></i>
                                                    </a></br>
                                                    <span class="small-btn small btn btn-sm ">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ Utility::convertFullDateTime($notification['created_at']) }}
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
                                <!--end::Menu-->
                                <!--end::Menu wrapper-->
                            </div>
                        @endif

                        <!--begin::User menu-->
                        <div class="app-navbar-item ms-2 ms-lg-6" id="kt_header_user_menu_toggle">
                            <!--begin::Menu wrapper-->
                            <div class="cursor-pointer symbol symbol-circle symbol-30px symbol-lg-45px"
                                data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                                data-kt-menu-placement="bottom-end">
                            </div>

                        </div>

                    </div>
                    <!--end::Navbar-->
                    <div class="app-navbar-item ms-2 me-lg-6" id="kt_header_user_menu_toggle">
                        <!--begin::Menu wrapper-->
                        @php
                        $image = asset('public/assets/media/avatars/300-2.jpg'); @endphp

                        @if (Auth()->user() !== null && Auth()->user()->profile_image !== null)
                            @php $image = asset('public/assets/upload/'.Auth()->user()->profile_image); @endphp
                        @endif
                        <div class="cursor-pointer symbol symbol-circle symbol-30px symbol-lg-45px"
                            data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                            data-kt-menu-placement="bottom-end">
                            <img src="{{ $image }}" alt="user" />
                        </div>

                        <!--begin::User account menu-->
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                            data-kt-menu="true">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <div class="menu-content d-flex align-items-center px-3">
                                    <!--begin::Avatar-->
                                    <div class="symbol symbol-50px me-5">

                                        <img alt="Logo" src="{{ $image }}" />
                                    </div>
                                    <!--end::Avatar-->

                                    <!--begin::Username-->
                                    <div class="d-flex flex-column">
                                        <div class="fw-bold d-flex align-items-center fs-5">
                                            {{ Auth()->user() !== null ? Auth()->user()->name : '' }}
                                        </div>

                                        <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                                            {{ Auth()->user() !== null ? Auth()->user()->email : '' }}</a>
                                    </div>
                                    <!--end::Username-->
                                </div>
                            </div>
                            <!--end::Menu item-->

                            <!--begin::Menu separator-->
                            <div class="separator my-2"></div>
                            <!--end::Menu separator-->

                            <!--begin::Menu item-->
                            <div class="menu-item px-5">
                                @if (Auth()->user() !== null && Auth()->user()->role_id == 1)
                                    <a href="{{ route('edit-profile') }}" class="menu-link px-5">
                                        My Profile
                                    </a>
                                @endif
                                @if (Auth()->user() !== null && Auth()->user()->role_id == 2)
                                    <a href="{{ route('user-edit-profile') }}" class="menu-link px-5">
                                        My Profile
                                    </a>
                                @endif
                            </div>

                            <!--begin::Menu item-->
                            @if (Auth()->user() !== null && Auth()->user()->role_id == 1)
                                <div class="menu-item px-5">
                                    <a href="{{ route('admin.setting') }}" class="menu-link px-5">
                                        Setting
                                    </a>
                                </div>
                            @endif
                            <div class="menu-item px-5">
                                <a href="{{ route('logout') }}" class="menu-link px-5">
                                    Sign Out
                                </a>
                            </div>
                            <!--end::Menu item-->
                        </div>
                        <!--end::User account menu-->
                        <!--end::Menu wrapper-->
                    </div>
                </div>
                <!--end::Header main-->

                <!--begin::Separator-->
                {{-- <div class="app-header-separator"></div> --}}
                <!--end::Separator-->
            </div>
            <!--end::Header-->
            <!--begin::Wrapper-->
            <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">


                <div id="kt_app_sidebar" class="app-sidebar  flex-column " data-kt-drawer="true"
                    data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}"
                    data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start"
                    data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">

                    <!--begin::Wrapper-->
                    <div class="app-sidebar-wrapper">
                        <div id="kt_app_sidebar_wrapper" class="hover-scroll-y my-5 my-lg-2 mx-4"
                            data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                            data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_header"
                            data-kt-scroll-wrappers="#kt_app_sidebar_wrapper" data-kt-scroll-offset="5px">
                            @if (Auth()->user() !== null && Auth()->user()->role_id == 1)
                                <!--begin::Sidebar menu-->
                                <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
                                    class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5">
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'dashboard') active @endif"
                                            href="{{ $route }}">
                                            <span class="menu-icon"><i class="fa fa-home fs-2"
                                                    aria-hidden="true"></i></span><span class="menu-title">
                                                Dashboard</span>
                                        </a>
                                    </div>
                                    @php $sales = "" @endphp
                                    @if (
                                        \Route::currentRouteName() == 'employees.index' ||
                                            \Route::currentRouteName() == 'department.index' ||
                                            \Route::currentRouteName() == 'vip-customer-list')
                                        @php $sales = "show" @endphp
                                    @endif
                                    <!--begin:Menu item-->
                                    @if (Permission::checkPermission('employee-list') || Permission::checkPermission('department'))
                                        <div data-kt-menu-trigger="click"
                                            class="menu-item here {{ $sales }} menu-accordion">
                                            <!--begin:Menu link--><span class="menu-link"><span class="menu-icon"><i
                                                        class="fa-solid fa-user fs-2"></i></span><span
                                                    class="menu-title">Users</span><span
                                                    class="menu-arrow"></span></span><!--end:Menu link--><!--begin:Menu sub-->
                                            @if (Permission::checkPermission('department-list'))
                                                <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                                    <div class="menu-item"><!--begin:Menu link--><a
                                                            class="menu-link @if (\Route::currentRouteName() == 'department.index') active @endif"
                                                            href="{{ route('department.index') }}"><span
                                                                class="menu-bullet"><span
                                                                    class="bullet bullet-dot"></span></span><span
                                                                class="menu-title">Department</span></a><!--end:Menu link-->
                                                    </div>
                                                </div>
                                            @endif
                                            @if (Permission::checkPermission('employee-list'))
                                                <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                                    <div class="menu-item"><!--begin:Menu link-->
                                                        <a class="menu-link @if (\Route::currentRouteName() == 'employees.index') active @endif"
                                                            href="{{ route('employees.index') }}"><span
                                                                class="menu-bullet"><span
                                                                    class="bullet bullet-dot"></span></span><span
                                                                class="menu-title">Employee</span></a><!--end:Menu link-->
                                                    </div>
                                                </div>
                                            @endif
                                            @if (Permission::checkPermission('employee-list'))
                                                <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                                    <div class="menu-item"><!--begin:Menu link-->
                                                        <a class="menu-link @if (\Route::currentRouteName() == 'vip-customer-list') active @endif"
                                                            href="{{ route('vip-customer-list') }}"><span
                                                                class="menu-bullet"><span
                                                                    class="bullet bullet-dot"></span></span><span
                                                                class="menu-title">VIP
                                                                Customer</span></a><!--end:Menu link-->
                                                    </div>
                                                </div>
                                            @endif
                                        </div><!--end:Menu item--><!--begin:Menu item-->
                                    @endif
                                    @php $hr = "" @endphp
                                    @if (
                                        \Route::currentRouteName() == 'info-sheet.index' ||
                                            \Route::currentRouteName() == 'holiday-list.index' ||
                                            \Route::currentRouteName() == 'leave-list' ||
                                            \Route::currentRouteName() == 'ticket-list' ||
                                            \Route::currentRouteName() == 'attendance-list' ||
                                            \Route::currentRouteName() == 'daily-attendance' ||
                                            \Route::currentRouteName() == 'certificate-list')
                                        @php $hr = "show" @endphp
                                    @endif
                                    <div data-kt-menu-trigger="click"
                                        class="menu-item here menu-accordion {{ $hr }}">
                                        <!--begin:Menu link--><span class="menu-link"><span
                                                class="menu-icon danger"><i
                                                    class="fa-solid fa-users fs-2"></i></span><span
                                                class="menu-title">
                                                Human Resource</span><span
                                                class="menu-arrow"></span></span><!--end:Menu link--><!--begin:Menu sub-->
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'info-sheet.index') active @endif"
                                                    href="{{ route('info-sheet.index') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Info Sheet</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'holiday-list.index') active @endif"
                                                    href="{{ route('holiday-list.index') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Holiday</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'leave-list') active @endif"
                                                    href="{{ route('leave-list') }}"><span class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Leave</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'ticket-list') active @endif"
                                                    href="{{ route('ticket-list') }}"><span class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Ticket</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'attendance-list') active @endif"
                                                    href="{{ route('attendance-list') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Attendance</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'daily-attendance') active @endif"
                                                    href="{{ route('daily-attendance') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Daily
                                                        Attendance</span></a><!--end:Menu link-->
                                            </div>
                                        </div>

                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'certificate-list') active @endif"
                                                    href="{{ route('certificate-list') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Certificate</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                    </div>
                                    @php $order = "" @endphp
                                    @if (
                                        \Route::currentRouteName() == 'orders.index' ||
                                            \Route::currentRouteName() == 'scheme.index' ||
                                            \Route::currentRouteName() == 'all-confirm-order' ||
                                            \Route::currentRouteName() == 'all-cancel-order' ||
                                            \Route::currentRouteName() == 'all-deliver-order' ||
                                            \Route::currentRouteName() == 'on-delivery-order' ||
                                            \Route::currentRouteName() == 'confirm-order-query' ||
                                            \Route::currentRouteName() == 'top-order-product' ||
                                            \Route::currentRouteName() == 'admin-top-five-confirm-order' ||
                                            \Route::currentRouteName() == 'team.index')
                                        @php $order = "show" @endphp
                                    @endif
                                    <div data-kt-menu-trigger="click"
                                        class="menu-item here menu-accordion {{ $order }}">
                                        <!--begin:Menu link--><span class="menu-link"><span class="menu-icon"><i
                                                    class="fa-brands fa-first-order fs-2"></i></span><span
                                                class="menu-title">
                                                Sales</span><span
                                                class="menu-arrow"></span></span><!--end:Menu link--><!--begin:Menu sub-->
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'scheme.index') active @endif"
                                                    href="{{ route('scheme.index') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Create Scheme</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'orders.index') active @endif"
                                                    href="{{ route('orders.index') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">All Orders</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'all-confirm-order') active @endif"
                                                    href="{{ route('all-confirm-order') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Confirmed
                                                        Status</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'confirm-order-query') active @endif"
                                                    href="{{ route('confirm-order-query') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Today's Confirmed
                                                    </span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'all-cancel-order') active @endif"
                                                    href="{{ route('all-cancel-order') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Cancelled
                                                        Order</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'all-deliver-order') active @endif"
                                                    href="{{ route('all-deliver-order') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Completed
                                                        Order</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'on-delivery-order') active @endif"
                                                    href="{{ route('on-delivery-order') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">On Delivery
                                                        Orders</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'team.index') active @endif"
                                                    href="{{ route('team.index') }}"><span class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">All Teams</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'top-order-product') active @endif"
                                                    href="{{ route('top-order-product') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Top Product
                                                        Sale</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'admin-top-five-confirm-order') active @endif"
                                                    href="{{ route('admin-top-five-confirm-order') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Top Confirmed
                                                        Orders</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                    </div>
                                    @php $product = "" @endphp
                                    @if (\Route::currentRouteName() == 'category.index' || \Route::currentRouteName() == 'product.index')
                                        @php $product = "show" @endphp
                                    @endif
                                    @if (Permission::checkPermission('category-list') || Permission::checkPermission('product-list'))
                                        <div data-kt-menu-trigger="click"
                                            class="menu-item here menu-accordion {{ $product }}">
                                            <!--begin:Menu link--><span class="menu-link"><span class="menu-icon"><i
                                                        class="fab fa-product-hunt fs-2"></i></span><span
                                                    class="menu-title">Product</span><span
                                                    class="menu-arrow"></span></span><!--end:Menu link--><!--begin:Menu sub-->
                                            @if (Permission::checkPermission('product-list'))
                                                <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                                    <div class="menu-item"><!--begin:Menu link--><a
                                                            class="menu-link @if (\Route::currentRouteName() == 'product.index') active @endif"
                                                            href="{{ route('product.index') }}"><span
                                                                class="menu-bullet"><span
                                                                    class="bullet bullet-dot"></span></span><span
                                                                class="menu-title">Product</span></a><!--end:Menu link-->
                                                    </div>
                                                </div><!--end:Menu sub-->
                                            @endif
                                            @if (Permission::checkPermission('category-list'))
                                                <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                                    <div class="menu-item"><!--begin:Menu link--><a
                                                            class="menu-link @if (\Route::currentRouteName() == 'category.index') active @endif"
                                                            href="{{ route('category.index') }}"><span
                                                                class="menu-bullet"><span
                                                                    class="bullet bullet-dot"></span></span><span
                                                                class="menu-title">Category</span></a><!--end:Menu link-->
                                                    </div>
                                                </div><!--end:Menu sub-->
                                            @endif

                                        </div><!--end:Menu item--><!--begin:Menu item-->
                                    @endif
                                    @php $stock = ""; @endphp
                                    @if (
                                        \Route::currentRouteName() == 'admin-in-out-stock' ||
                                            \Route::currentRouteName() == 'admin-stock-list' ||
                                            \Route::currentRouteName() == 'bill-download')
                                        @php $stock = "show" @endphp
                                    @endif
                                    <div data-kt-menu-trigger="click"
                                        class="menu-item here menu-accordion {{ $stock }}">
                                        <!--begin:Menu link--><span class="menu-link"><span class="menu-icon"><i
                                                    class="fa-solid fa-warehouse fs-2"></i></span><span
                                                class="menu-title">
                                                Warehouse</span><span
                                                class="menu-arrow"></span></span><!--end:Menu link--><!--begin:Menu sub-->
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'admin-in-out-stock') active @endif"
                                                    href="{{ route('admin-in-out-stock') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">In Out Stock</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'admin-stock-list') active @endif"
                                                    href="{{ route('admin-stock-list') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Product Stock</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'bill-download') active @endif"
                                                    href="{{ route('bill-download') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Bill Download</span></a><!--end:Menu link-->
                                            </div>
                                        </div>

                                    </div>
                                    @php $confirm = ""; @endphp
                                    @if (
                                        \Route::currentRouteName() == 'manual-order' ||
                                            \Route::currentRouteName() == 'return-order' ||
                                            \Route::currentRouteName() == 'all-confirm-order-list' ||
                                            \Route::currentRouteName() == 'divert-transport')
                                        @php $confirm = "show" @endphp
                                    @endif
                                    <div data-kt-menu-trigger="click"
                                        class="menu-item here menu-accordion {{ $confirm }}">
                                        <!--begin:Menu link--><span class="menu-link"><span class="menu-icon"><i
                                                    class="fa fa-check-circle fs-2"></i></span><span
                                                class="menu-title">
                                                Confirmation</span><span
                                                class="menu-arrow"></span></span><!--end:Menu link--><!--begin:Menu sub-->
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'manual-order') active @endif"
                                                    href="{{ route('manual-order') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Manual Orders</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion">
                                            <div class="menu-item"><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'return-order') active @endif"
                                                    href="{{ route('return-order') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Return Order</span></a>
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion">
                                            <div class="menu-item"><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'divert-transport') active @endif"
                                                    href="{{ route('divert-transport') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Divert To
                                                        Transposrt</span></a>
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion">
                                            <div class="menu-item"><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'all-confirm-order-list') active @endif"
                                                    href="{{ route('all-confirm-order-list') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Confirm Order List</span></a>
                                            </div>
                                        </div>
                                    </div>
                                    @php $transport = "" @endphp
                                    @if (
                                        \Route::currentRouteName() == 'admin-confirm-order' ||
                                            \Route::currentRouteName() == 'admin-batch-list' ||
                                            \Route::currentRouteName() == 'driver' ||
                                            \Route::currentRouteName() == 'print_bill')
                                        @php $transport = "show" @endphp
                                    @endif
                                    <div data-kt-menu-trigger="click"
                                        class="menu-item here menu-accordion {{ $transport }}">
                                        <!--begin:Menu link--><span class="menu-link"><span class="menu-icon"><i
                                                    class="fa fa-truck fs-2" aria-hidden="true"></i></span><span
                                                class="menu-title">
                                                Transport</span><span class="menu-arrow"></span></span>
                                        <div class="menu-sub menu-sub-accordion">
                                            <div class="menu-item"><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'admin-confirm-order') active @endif"
                                                    href="{{ route('admin-confirm-order') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Batch
                                                        Manually</span></a>
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion">
                                            <div class="menu-item"><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'admin-batch-list') active @endif"
                                                    href="{{ route('admin-batch-list') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Batch List</span></a><!--end:Menu link-->
                                            </div>
                                        </div>

                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'driver') active @endif"
                                                    href="{{ route('driver') }}"><span class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Driver
                                                        Management</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        {{-- <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'print_bill') active @endif"
                                                    href="{{ route('print_bill') }}"><span class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Print Bills</span></a><!--end:Menu link-->
                                            </div>
                                        </div> --}}

                                    </div>
                                    @php $afterSale = "" @endphp
                                    @if (
                                        \Route::currentRouteName() == 'complete-order' ||
                                            \Route::currentRouteName() == 'order-report' ||
                                            \Route::currentRouteName() == 'sale-product' ||
                                            \Route::currentRouteName() == 'order-feedback-list')
                                        @php $afterSale = "show" @endphp
                                    @endif
                                    <div data-kt-menu-trigger="click"
                                        class="menu-item here menu-accordion {{ $afterSale }}">
                                        <!--begin:Menu link--><span class="menu-link"><span class="menu-icon"><i
                                                    class="fas fa-store fs-2"></i></span><span class="menu-title">
                                                After Sale Service</span><span
                                                class="menu-arrow"></span></span><!--end:Menu link--><!--begin:Menu sub-->
                                        {{-- <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'complete-order') active @endif"
                                                    href="{{ route('complete-order') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Complete
                                                        Order</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'order-report') active @endif"
                                                    href="{{ route('order-report') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Order Report</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'sale-product') active @endif"
                                                    href="{{ route('sale-product') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Product</span></a><!--end:Menu link-->
                                            </div>
                                        </div> --}}

                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'order-feedback-list') active @endif"
                                                    href="{{ route('order-feedback-list') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Feedback</span></a><!--end:Menu link-->
                                            </div>
                                        </div>

                                    </div>
                                    {{-- @php $account = "" @endphp
                                    @if (\Route::currentRouteName() == 'order-deliver' || Route::currentRouteName() == 'hr-salary-slip' || Route::currentRouteName() == 'all-stock')
                                        @php $account = "show" @endphp
                                    @endif
                                    <div data-kt-menu-trigger="click"
                                        class="menu-item here menu-accordion {{ $account }}">
                                        <!--begin:Menu link--><span class="menu-link"><span class="menu-icon"><i
                                                    class='fas fa-book fs-2'></i></i></span><span class="menu-title">
                                                Account</span><span
                                                class="menu-arrow"></span></span><!--end:Menu link--><!--begin:Menu sub-->
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'order-deliver') active @endif"
                                                    href="{{ route('order-deliver') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Delivered
                                                        Order</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'hr-salary-slip') active @endif"
                                                    href="{{ route('hr-salary-slip') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">HR Salary
                                                        Slip</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'all-stock') active @endif"
                                                    href="{{ route('all-stock') }}"><span class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">All Stock</span></a><!--end:Menu link-->
                                            </div>
                                        </div>

                                    </div> --}}
                                    @php $report = "" @endphp
                                    @if (
                                        \Route::currentRouteName() == 'order_report' ||
                                            \Route::currentRouteName() == 'product-order-report' ||
                                            \Route::currentRouteName() == 'order-product-report' ||
                                            \Route::currentRouteName() == 'staff-order-report')
                                        @php $report = "show" @endphp
                                    @endif
                                    <div data-kt-menu-trigger="click"
                                        class="menu-item here menu-accordion {{ $report }}">
                                        <!--begin:Menu link--><span class="menu-link"><span class="menu-icon"><i
                                                    class="fa fa-file-text fs-2"></i></span><span class="menu-title">
                                                Reports</span><span
                                                class="menu-arrow"></span></span><!--end:Menu link--><!--begin:Menu sub-->
                                        {{-- <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'order_report') active @endif"
                                                    href="{{ route('order_report') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Order Report</span></a><!--end:Menu link-->
                                            </div>
                                        </div> --}}
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'product-order-report') active @endif"
                                                    href="{{ route('product-order-report') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Sale By Product
                                                        Report</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'order-product-report') active @endif"
                                                    href="{{ route('order-product-report') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Order By Product Report</span></a><!--end:Menu link-->
                                            </div>
                                        </div>
                                        <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                            <div class="menu-item"><!--begin:Menu link--><a
                                                    class="menu-link @if (\Route::currentRouteName() == 'staff-order-report') active @endif"
                                                    href="{{ route('staff-order-report') }}"><span
                                                        class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span><span
                                                        class="menu-title">Staff Sales
                                                        Report</span></a><!--end:Menu link-->
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endif
                            @if (Auth()->user() !== null && Auth()->user()->role_id == 2)
                                <!--begin::Sidebar menu-->
                                <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
                                    class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5">
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'employee-dashboard') active @endif"
                                            href="{{ $route }}">
                                            <span class="menu-icon"><i class="fa-solid fa-home"></i></span><span
                                                class="menu-title">
                                                Dashboard</span>
                                        </a>
                                    </div>

                                    @php $product = "" @endphp
                                    @if (\Route::currentRouteName() == 'employee-category' || \Route::currentRouteName() == 'employee-product')
                                        @php $product = "show" @endphp
                                    @endif
                                    @if (Permission::checkPermission('category-list') || Permission::checkPermission('product-list'))
                                        <div data-kt-menu-trigger="click"
                                            class="menu-item here menu-accordion {{ $product }}">
                                            <!--begin:Menu link--><span class="menu-link"><span class="menu-icon"><i
                                                        class="fab fa-product-hunt fs-2"></i></span><span
                                                    class="menu-title">Product</span><span
                                                    class="menu-arrow"></span></span>
                                            @if (Permission::checkPermission('product-list'))
                                                <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                                    <div class="menu-item"><!--begin:Menu link--><a
                                                            class="menu-link @if (\Route::currentRouteName() == 'employee-product') active @endif"
                                                            href="{{ route('employee-product') }}"><span
                                                                class="menu-bullet"><span
                                                                    class="bullet bullet-dot"></span></span><span
                                                                class="menu-title">Product</span></a><!--end:Menu link-->
                                                    </div>


                                                </div>
                                            @endif
                                            @if (Permission::checkPermission('category-list'))
                                                <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                                    <div class="menu-item"><!--begin:Menu link--><a
                                                            class="menu-link @if (\Route::currentRouteName() == 'employee-category') active @endif"
                                                            href="{{ route('employee-category') }}"><span
                                                                class="menu-bullet"><span
                                                                    class="bullet bullet-dot"></span></span><span
                                                                class="menu-title">Category</span></a><!--end:Menu link-->
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    @endif
                                    @if (Permission::checkPermission('order-own-list') ||
                                            Permission::checkPermission('order-add') ||
                                            Permission::checkPermission('order-edit') ||
                                            Permission::checkPermission('order-view') ||
                                            Permission::checkPermission('pending-order-list') ||
                                            Permission::checkPermission('cancel-order-list') ||
                                            Permission::checkPermission('return-order-list') ||
                                            Permission::checkPermission('complete-order-list'))
                                        @php $order = "" @endphp
                                        @if (
                                            \Route::currentRouteName() == 'employee-orders.index' ||
                                                \Route::currentRouteName() == 'employee-pending-order' ||
                                                \Route::currentRouteName() == 'employee-cancel-order' ||
                                                \Route::currentRouteName() == 'employee-confirm-order' ||
                                                \Route::currentRouteName() == 'employee-return-order' ||
                                                \Route::currentRouteName() == 'employee-completed-order')
                                            @php $order = "show" @endphp
                                        @endif
                                        <div data-kt-menu-trigger="click"
                                            class="menu-item here menu-accordion {{ $order }}">
                                            <!--begin:Menu link--><span class="menu-link"><span class="menu-icon"><i
                                                        class="fa-brands fa-first-order fs-2"></i></span><span
                                                    class="menu-title">
                                                    Sales</span><span
                                                    class="menu-arrow"></span></span><!--end:Menu link--><!--begin:Menu sub-->
                                            @if (Permission::checkPermission('order-own-list'))
                                                <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                                    <div class="menu-item"><!--begin:Menu link--><a
                                                            class="menu-link @if (\Route::currentRouteName() == 'employee-orders.index') active @endif"
                                                            href="{{ route('employee-orders.index') }}"><span
                                                                class="menu-bullet"><span
                                                                    class="bullet bullet-dot"></span></span><span
                                                                class="menu-title">My
                                                                Orders</span></a><!--end:Menu link-->
                                                    </div>
                                                </div>
                                            @endif
                                            @if (Permission::checkPermission('pending-order-list'))
                                                <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                                    <div class="menu-item"><!--begin:Menu link--><a
                                                            class="menu-link @if (\Route::currentRouteName() == 'employee-pending-order') active @endif"
                                                            href="{{ route('employee-pending-order') }}"><span
                                                                class="menu-bullet"><span
                                                                    class="bullet bullet-dot"></span></span><span
                                                                class="menu-title">My Pending
                                                                Order</span></a><!--end:Menu link-->
                                                    </div>
                                                </div>
                                            @endif
                                            @if (Permission::checkPermission('cancel-order-list'))
                                                <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                                    <div class="menu-item"><!--begin:Menu link--><a
                                                            class="menu-link @if (\Route::currentRouteName() == 'employee-confirm-order') active @endif"
                                                            href="{{ route('employee-confirm-order') }}"><span
                                                                class="menu-bullet"><span
                                                                    class="bullet bullet-dot"></span></span><span
                                                                class="menu-title">My Confirm
                                                                Order</span></a><!--end:Menu link-->
                                                    </div>
                                                </div>
                                            @endif
                                            @if (Permission::checkPermission('cancel-order-list'))
                                                <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                                    <div class="menu-item"><!--begin:Menu link--><a
                                                            class="menu-link @if (\Route::currentRouteName() == 'employee-cancel-order') active @endif"
                                                            href="{{ route('employee-cancel-order') }}"><span
                                                                class="menu-bullet"><span
                                                                    class="bullet bullet-dot"></span></span><span
                                                                class="menu-title">My Cancel
                                                                Order</span></a><!--end:Menu link-->
                                                    </div>
                                                </div>
                                            @endif
                                            @if (Permission::checkPermission('complete-order-list'))
                                                <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                                    <div class="menu-item"><!--begin:Menu link--><a
                                                            class="menu-link @if (\Route::currentRouteName() == 'employee-completed-order') active @endif"
                                                            href="{{ route('employee-completed-order') }}"><span
                                                                class="menu-bullet"><span
                                                                    class="bullet bullet-dot"></span></span><span
                                                                class="menu-title">My Delivered
                                                                Order</span></a><!--end:Menu link-->
                                                    </div>
                                                </div>
                                            @endif
                                            @if (Permission::checkPermission('return-order-list'))
                                                <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                                    <div class="menu-item"><!--begin:Menu link--><a
                                                            class="menu-link @if (\Route::currentRouteName() == 'employee-return-order') active @endif"
                                                            href="{{ route('employee-return-order') }}"><span
                                                                class="menu-bullet"><span
                                                                    class="bullet bullet-dot"></span></span><span
                                                                class="menu-title">My Return
                                                                Order</span></a><!--end:Menu link-->
                                                    </div>
                                                </div>
                                            @endif


                                        </div>
                                    @endif
                                    @if (Permission::checkPermission('lead-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'employee-lead.index') active @endif"
                                                href="{{ route('employee-lead.index') }}">
                                                <span class="menu-icon"><i class="fa-solid fa-pencil"></i></span><span
                                                    class="menu-title">
                                                    Leads</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if (Permission::checkPermission('info-sheet-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'employee-info-sheet.index') active @endif"
                                                href="{{ route('employee-info-sheet.index') }}">
                                                <span class="menu-icon"><i
                                                        class="fa-solid fa-circle-info"></i></span><span
                                                    class="menu-title">
                                                    Info Sheets</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if (Permission::checkPermission('ticket-list'))
                                        <div class="menu-item">
                                            <a class="menu-link  @if (\Route::currentRouteName() == 'employee-ticket.index') active @endif"
                                                href="{{ route('employee-ticket.index') }}">
                                                <span class="menu-icon"><i class="fa fa-ticket"
                                                        aria-hidden="true"></i></span><span class="menu-title">
                                                    Create Ticket</span>
                                            </a>
                                        </div>
                                    @endif
                                    {{-- @if (Permission::checkPermission('certificate-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'employee-certificate') active @endif " href="{{ route('employee-certificate') }}">
                                                <span class="menu-icon"><i class="fa fa-certificate"
                                                        aria-hidden="true"></i></span><span class="menu-title">
                                                    Certificate</span>
                                            </a>
                                        </div>
                                    @endif --}}
                                    @if (Permission::checkPermission('attendance-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'attendance') active @endif"
                                                href="{{ route('attendance') }}">
                                                <span class="menu-icon"><i
                                                        class="fa-regular fa-clock"></i></span><span
                                                    class="menu-title">
                                                    Attendance</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if (Permission::checkPermission('leave-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'employee-leave.index') active @endif"
                                                href="{{ route('employee-leave.index') }}">
                                                <span class="menu-icon"><i class="fa-solid fa-house"></i></span><span
                                                    class="menu-title">
                                                    Apply Leave</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if (Permission::checkPermission('holiday-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'holiday') active @endif"
                                                href="{{ route('holiday') }}">
                                                <span class="menu-icon"><i class="fa-solid fa-pencil"></i></span><span
                                                    class="menu-title">
                                                    Holidays</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            @if (Auth()->user() !== null && Auth()->user()->role_id == 3)
                                <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
                                    class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5">
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'hr-dashboard') active @endif"
                                            href="{{ $route }}">
                                            <span class="menu-icon"><i class="fa-solid fa-home"></i></span><span
                                                class="menu-title">
                                                Dashboard</span>
                                        </a>
                                    </div>
                                    @if (Permission::checkPermission('employee-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'hr-employee') active @endif"
                                                href="{{ route('hr-employee') }}">
                                                <span class="menu-icon"><i class="fa-solid fa-users"></i></span><span
                                                    class="menu-title">
                                                    Employee</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if (Permission::checkPermission('info-sheet-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'hr-info-sheet.index') active @endif"
                                                href="{{ route('hr-info-sheet.index') }}">
                                                <span class="menu-icon"><i
                                                        class="fa-solid fa-circle-info"></i></span><span
                                                    class="menu-title">
                                                    Info Sheets</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if (Permission::checkPermission('holiday-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'hr-holiday') active @endif"
                                                href="{{ route('hr-holiday') }}">
                                                <span class="menu-icon"><i class="fa fa-ticket"
                                                        aria-hidden="true"></i></span><span class="menu-title">
                                                    Holiday</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if (Permission::checkPermission('leave-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'hr-leave') active @endif"
                                                href="{{ route('hr-leave') }}">
                                                <span class="menu-icon"><i class="fa fa-certificate"
                                                        aria-hidden="true"></i></span><span class="menu-title">
                                                    Leave</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if (Permission::checkPermission('ticket-view'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'hr-ticket') active @endif"
                                                href="{{ route('hr-ticket') }}">
                                                <span class="menu-icon"><i class="fas fa-clock"></i></span><span
                                                    class="menu-title">
                                                    Ticket</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if (Permission::checkPermission('attendance-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'hr-attendance') active @endif"
                                                href="{{ route('hr-attendance') }}">
                                                <span class="menu-icon"><i class="fa-solid fa-house"></i></span><span
                                                    class="menu-title">
                                                    Attendance</span>
                                            </a>
                                        </div>
                                    @endif
                                    {{-- <div class="menu-item">
                                        <a class="menu-link" href="{{ route('certificate') }}">
                                            <span class="menu-icon"><i class="fa-solid fa-clock-rotate-left"></i></span><span class="menu-title">
                                                Certificate</span>
                                        </a>
                                    </div> --}}
                                    @if (Permission::checkPermission('salary-slip-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'hr-salaryslip.index') active @endif"
                                                href="{{ route('hr-salaryslip.index') }}">
                                                <span class="menu-icon"><i class="fa fa-receipt fs-2"
                                                        aria-hidden="true"></i></span><span class="menu-title">
                                                    Salary Slip</span>
                                            </a>
                                        </div>
                                    @endif
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'hr-daily-attendance') active @endif"
                                            href="{{ route('hr-daily-attendance') }}">
                                            <span class="menu-icon"><i
                                                    class="fa-solid fa-calendar-day fs-2"></i></span><span
                                                class="menu-title">
                                                Daily Attendance</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @if (Auth()->user() !== null && Auth()->user()->role_id == 4)
                                <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
                                    class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5">
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'confirm-dashboard') active @endif"
                                            href="{{ $route }}">
                                            <span class="menu-icon"><i class="fa-solid fa-home"></i></span><span
                                                class="menu-title">
                                                Dashboard</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'confirm-product') active @endif"
                                            href="{{ route('confirm-product') }}">
                                            <span class="menu-icon"><i
                                                    class="fa-brands fa-product-hunt"></i></span><span
                                                class="menu-title">
                                                Products</span>
                                        </a>
                                    </div>
                                    @if (Permission::checkPermission('pending-order-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'confirm-orders.index') active @endif"
                                                href="{{ route('confirm-orders.index') }}">
                                                <span class="menu-icon"><i
                                                        class="fa-regular fa-calendar-days fs-2"></i></span><span
                                                    class="menu-title">
                                                    All Orders</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'confirm-manual-order') active @endif"
                                                href="{{ route('confirm-manual-order') }}">
                                                <span class="menu-icon"><i
                                                        class="fa-solid fa-hourglass-end fs-2"></i></span><span
                                                    class="menu-title">
                                                    Pending Orders</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'confirm-order') active @endif"
                                                href="{{ route('confirm-order') }}">
                                                <span class="menu-icon"><i
                                                        class="fa-solid fa-clipboard-check fs-2"></i></span><span
                                                    class="menu-title">
                                                    Confirmed Orders</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link  @if (\Route::currentRouteName() == 'cancel-order') active @endif"
                                                href="{{ route('cancel-order') }}">
                                                <span class="menu-icon"><i
                                                        class="fa-solid fa-store-slash fs-2"></i></span><span
                                                    class="menu-title">
                                                    Cancelled Orders</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'confirm-return-order') active @endif"
                                                href="{{ route('confirm-return-order') }}">
                                                <span class="menu-icon"><i
                                                        class="fa-solid fa-store-slash fs-2"></i></span><span
                                                    class="menu-title">
                                                    Return Orders</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'deliver-order') active @endif"
                                                href="{{ route('deliver-order') }}">
                                                <span class="menu-icon"><i
                                                        class="fa-solid fa-truck-fast fs-2"></i></span><span
                                                    class="menu-title">
                                                    Completed Orders</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'confirm-on-delivery-order') active @endif"
                                                href="{{ route('confirm-on-delivery-order') }}">
                                                <span class="menu-icon"><i
                                                        class="fa-solid fa-truck-fast fs-2"></i></span><span
                                                    class="menu-title">
                                                    On Delivery Orders</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if (Permission::checkPermission('assign-driver-order-list'))
                                        <!-- <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'confirm-order-list') active @endif" href="{{ route('confirm-order-list') }}">
                                                <span class="menu-icon"><i class="fa-solid fa-pencil"></i></span><span class="menu-title">
                                                    Assign Driver</span>
                                            </a>
                                        </div> -->
                                    @endif
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'top-five-confirm-order') active @endif"
                                            href="{{ route('top-five-confirm-order') }}">
                                            <span class="menu-icon"><i
                                                    class="fa-solid fa-clipboard-check fs-2"></i></span><span
                                                class="menu-title">
                                                Top Confirmed Orders</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'confirmation-order-list') active @endif"
                                            href="{{ route('confirmation-order-list') }}">
                                            <span class="menu-icon"><i
                                                    class="fa-solid fa-clipboard-check fs-2"></i></span><span
                                                class="menu-title">
                                                Today Confirmed</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'confirm-leave.index') active @endif"
                                            href="{{ route('confirm-leave.index') }}">
                                            <span class="menu-icon"><i class="fa-solid fa-house"></i></span><span
                                                class="menu-title">
                                                Leave</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'confirm-ticket.index') active @endif"
                                            href="{{ route('confirm-ticket.index') }}">
                                            <span class="menu-icon"><i class="fa-solid fa-ticket"></i></span><span
                                                class="menu-title">
                                                Ticket</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'confirm-holiday') active @endif"
                                            href="{{ route('confirm-holiday') }}">
                                            <span class="menu-icon"><i class="fa-solid fa-pencil"></i></span><span
                                                class="menu-title">
                                                Holidays</span>
                                        </a>
                                    </div>
                                    @if (Permission::checkPermission('vip-customer-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'confirm-vip-customer-list') active @endif"
                                                href="{{ route('confirm-vip-customer-list') }}">
                                                <span class="menu-icon"><i class="fa fa-star fs-2"
                                                        aria-hidden="true"></i></span><span class="menu-title">
                                                    VIP Customer</span>
                                            </a>
                                        </div>
                                    @endif

                                </div>
                            @endif
                            @if (Auth()->user() !== null && Auth()->user()->role_id == 5)
                                <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
                                    class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5">
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'driver-dashboard') active @endif"
                                            href="{{ route('driver-dashboard') }}">
                                            <span class="menu-icon"><i class="fa fa-home fs-2"
                                                    aria-hidden="true"></i></span><span class="menu-title">
                                                Dashboard</span>
                                        </a>
                                    </div>
                                    @if (Permission::checkPermission('driver-delivery-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'delivery-order-list') active @endif"
                                                href="{{ route('delivery-order-list') }}">
                                                <span class="menu-icon"><i
                                                        class="fa-solid fa-truck-fast fs-2"></i></span>
                                                <span class="menu-title">
                                                    On Delivery Orders</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            @if (Auth()->user() !== null && Auth()->user()->role_id == 6)
                                <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
                                    class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5">
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'system-engineer-dashboard') active @endif"
                                            href="{{ route('system-engineer-dashboard') }}">
                                            <span class="menu-icon"><i class="fa fa-home fs-2"
                                                    aria-hidden="true"></i></span><span class="menu-title">

                                                Dashboard</span>
                                        </a>
                                    </div>
                                    @if (Permission::checkPermission('driver-delivery-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'engineer-ticket') active @endif"
                                                href="{{ route('engineer-ticket') }}">
                                                <span class="menu-icon"><i class="fa fa-ticket fs-2"
                                                        aria-hidden="true"></i></span><span class="menu-title">
                                                    Ticket List</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'engineer-system-code') active @endif"
                                                href="{{ route('engineer-system-code') }}">
                                                <span class="menu-icon"><i class="fa fa-desktop fs-2"
                                                        aria-hidden="true"></i></span><span class="menu-title">
                                                    System Codes</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            @if (Auth()->user() !== null && Auth()->user()->role_id == 7)
                                <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
                                    class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5">
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'transport-department-dashboard') active @endif"
                                            href="{{ route('transport-department-dashboard') }}">
                                            <span class="menu-icon"><i class="fa fa-home fs-2"
                                                    aria-hidden="true"></i></span><span class="menu-title">
                                                Dashboard</span>
                                        </a>
                                    </div>
                                    @if (Permission::checkPermission('bulk-assign-driver'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'transport-confirm-order') active @endif"
                                                href="{{ route('transport-confirm-order') }}">
                                                <span class="menu-icon"><i class="fa-brands fa-first-order fs-2"
                                                        aria-hidden="true"></i></span><span class="menu-title">
                                                    Confirm Order</span>
                                            </a>
                                        </div>
                                    @endif
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'transport-delivery-order') active @endif"
                                            href="{{ route('transport-delivery-order') }}">
                                            <span class="menu-icon"><i class="fa-solid fa-truck-fast fs-2"></i></span>
                                            <span class="menu-title">
                                                On Delivery Orders</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'transport-deliver-order') active @endif"
                                            href="{{ route('transport-deliver-order') }}">
                                            <span class="menu-icon"><i class="fa-solid fa-truck-fast fs-2"></i></span>
                                            <span class="menu-title">
                                                Delivered Orders</span>
                                        </a>
                                    </div>
                                    @if (Permission::checkPermission('batch-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'batch-list') active @endif"
                                                href="{{ route('batch-list') }}">
                                                <span class="menu-icon"><i class="fa-brands fa-first-order fs-2"
                                                        aria-hidden="true"></i></span><span class="menu-title">
                                                    Batch</span>
                                            </a>
                                        </div>
                                    @endif
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'transport-order-list') active @endif"
                                            href="{{ route('transport-order-list') }}">
                                            <span class="menu-icon"><i class="fa-solid fa-truck-fast"></i></span><span
                                                class="menu-title">
                                                Today Confirmed</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'transport-leave.index') active @endif"
                                            href="{{ route('transport-leave.index') }}">
                                            <span class="menu-icon"><i class="fa-solid fa-house"></i></span><span
                                                class="menu-title">
                                                Leave</span>
                                        </a>
                                    </div>

                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'transport-ticket.index') active @endif"
                                            href="{{ route('transport-ticket.index') }}">
                                            <span class="menu-icon"><i class="fa-solid fa-ticket"></i></span><span
                                                class="menu-title">
                                                Ticket</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'transport-holiday') active @endif"
                                            href="{{ route('transport-holiday') }}">
                                            <span class="menu-icon"><i class="fa-solid fa-pencil"></i></span><span
                                                class="menu-title">
                                                Holidays</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @if (Auth()->user() !== null && Auth()->user()->role_id == 8)
                                <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
                                    class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5">
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'warehouse-dashboard') active @endif"
                                            href="{{ $route }}">
                                            <span class="menu-icon"><i class="fa fa-home fs-2"
                                                    aria-hidden="true"></i></span><span class="menu-title">
                                                Dashboard</span>
                                        </a>
                                    </div>
                                    @if (Permission::checkPermission('batch-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'batch-list') active @endif"
                                                href="{{ route('batch-list') }}">
                                                <span class="menu-icon"><i class="fa-brands fa-first-order fs-2"
                                                        aria-hidden="true"></i></span><span class="menu-title">
                                                    Batch Orders</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if (Permission::checkPermission('warehouse-stock-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'warehouse-stock-list') active @endif"
                                                href="{{ route('warehouse-stock-list') }}">
                                                <span class="menu-icon"><i class="fa-solid fa-warehouse"
                                                        aria-hidden="true"></i></span><span class="menu-title">
                                                    Stock Management</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if (Permission::checkPermission('batch-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'delivered-batch-list') active @endif"
                                                href="{{ route('delivered-batch-list') }}">
                                                <span class="menu-icon"><i class="fa-brands fa-first-order fs-2"
                                                        aria-hidden="true"></i></span><span class="menu-title">
                                                    Delivered Batch</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if (Permission::checkPermission('batch-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'in-out-stock') active @endif"
                                                href="{{ route('in-out-stock') }}">
                                                <span class="menu-icon"><i class="fa fa-exchange fs-2"
                                                        aria-hidden="true"></i></span><span class="menu-title">
                                                    In/out Stock</span>
                                            </a>
                                        </div>
                                    @endif
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'warehouse-leave.index') active @endif"
                                            href="{{ route('warehouse-leave.index') }}">
                                            <span class="menu-icon"><i class="fa-solid fa-house"></i></span><span
                                                class="menu-title">
                                                Leave</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'warehouse-ticket.index') active @endif"
                                            href="{{ route('warehouse-ticket.index') }}">
                                            <span class="menu-icon"><i class="fa-solid fa-ticket"></i></span><span
                                                class="menu-title">
                                                Ticket</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'warehouse-holiday') active @endif"
                                            href="{{ route('warehouse-holiday') }}">
                                            <span class="menu-icon"><i class="fa-solid fa-pencil"></i></span><span
                                                class="menu-title">
                                                Holidays</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @if (Auth()->user() !== null && Auth()->user()->role_id == 9)
                                <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
                                    class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5">
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'sales-manager-dashboard') active @endif"
                                            href="{{ $route }}">
                                            <span class="menu-icon"><i class="fa fa-home fs-2"
                                                    aria-hidden="true"></i></span><span class="menu-title">
                                                Dashboard</span>
                                        </a>
                                    </div>
                                    @if (Permission::checkPermission('employee-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'sale-employee') active @endif"
                                                href="{{ route('sale-employee') }}">
                                                <span class="menu-icon"><i class="fa fa-users fs-2"
                                                        aria-hidden="true"></i></span><span class="menu-title">
                                                    Employee List</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if (Permission::checkPermission('sales-stock-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'sales-stock-list') active @endif"
                                                href="{{ route('sales-stock-list') }}">
                                                <span class="menu-icon"><i class="fa-brands fa-first-order fs-2"
                                                        aria-hidden="true"></i></span><span class="menu-title">
                                                    Stock</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if (Permission::checkPermission('pending-order-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'sale-orders.index') active @endif"
                                                href="{{ route('sale-orders.index') }}">
                                                <span class="menu-icon"><i
                                                        class="fa-regular fa-calendar-days fs-2"></i></span><span
                                                    class="menu-title">
                                                    All Orders</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'sale-manual-order') active @endif"
                                                href="{{ route('sale-manual-order') }}">
                                                <span class="menu-icon"><i
                                                        class="fa-solid fa-hourglass-end fs-2"></i></span><span
                                                    class="menu-title">
                                                    Pending Orders</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'sale-confirm-order') active @endif"
                                                href="{{ route('sale-confirm-order') }}">
                                                <span class="menu-icon"><i
                                                        class="fa-solid fa-clipboard-check fs-2"></i></span><span
                                                    class="menu-title">
                                                    Confirmed Orders</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'sale-cancel-order') active @endif"
                                                href="{{ route('sale-cancel-order') }}">
                                                <span class="menu-icon"><i
                                                        class="fa-solid fa-store-slash fs-2"></i></span><span
                                                    class="menu-title">
                                                    Cancelled Orders</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'sale-cancel-order') active @endif"
                                                href="{{ route('sale-cancel-order') }}">
                                                <span class="menu-icon"><i
                                                        class="fa-solid fa-store-slash fs-2"></i></span><span
                                                    class="menu-title">
                                                    Cancel Orders</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'sale-return-order') active @endif"
                                                href="{{ route('sale-return-order') }}">
                                                <span class="menu-icon"><i
                                                        class="fa-solid fa-store-slash fs-2"></i></span><span
                                                    class="menu-title">
                                                    Return Orders</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'sale-deliver-order') active @endif"
                                                href="{{ route('sale-deliver-order') }}">
                                                <span class="menu-icon"><i
                                                        class="fa-solid fa-truck-fast fs-2"></i></span><span
                                                    class="menu-title">
                                                    Delivered Orders</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'sale-on-delivery-order') active @endif"
                                                href="{{ route('sale-on-delivery-order') }}">
                                                <span class="menu-icon"><i
                                                        class="fa-solid fa-truck-fast fs-2"></i></span><span
                                                    class="menu-title">
                                                    On Delivery Orders</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if (Permission::checkPermission('vip-customer-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'sale-manager-vip-customer-list') active @endif"
                                                href="{{ route('sale-manager-vip-customer-list') }}">
                                                <span class="menu-icon"><i class="fa fa-star fs-2"
                                                        aria-hidden="true"></i></span><span class="menu-title">
                                                    VIP Customer</span>
                                            </a>
                                        </div>
                                    @endif
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'sale-manager-team.index') active @endif"
                                            href="{{ route('sale-manager-team.index') }}">
                                            <span class="menu-icon"><i
                                                    class="fa-solid fa-people-group"></i></span><span
                                                class="menu-title">
                                                All Teams</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'sales-leave.index') active @endif"
                                            href="{{ route('sales-leave.index') }}">
                                            <span class="menu-icon"><i class="fa-solid fa-house"></i></span><span
                                                class="menu-title">
                                                Leave</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'sales-ticket.index') active @endif"
                                            href="{{ route('sales-ticket.index') }}">
                                            <span class="menu-icon"><i class="fa-solid fa-ticket"></i></span><span
                                                class="menu-title">
                                                Ticket</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'sales-holiday') active @endif"
                                            href="{{ route('sales-holiday') }}">
                                            <span class="menu-icon"><i class="fa-solid fa-pencil"></i></span><span
                                                class="menu-title">
                                                Holidays</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @if (Auth()->user() !== null && Auth()->user()->role_id == 10)
                                <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
                                    class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5">
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'sales-service-dashboard') active @endif"
                                            href="{{ $route }}">
                                            <span class="menu-icon"><i class="fa fa-home fs-2"
                                                    aria-hidden="true"></i></span><span class="menu-title">
                                                Dashboard</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'delivered-order-list') active @endif"
                                            href="{{ route('delivered-order-list') }}">
                                            <span class="menu-icon"><i class="fa-brands fa-first-order fs-2"
                                                    aria-hidden="true"></i></span><span class="menu-title">
                                                Delivered Order</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'sale-order-feedback-list') active @endif"
                                            href="{{ route('sale-order-feedback-list') }}">
                                            <span class="menu-icon"><i class="fa-brands fa-first-order fs-2"
                                                    aria-hidden="true"></i></span><span class="menu-title">
                                                Order Feedback</span>
                                        </a>
                                    </div>
                                    @if (Permission::checkPermission('vip-customer-list'))
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == 'sale-service-vip-customer-list') active @endif"
                                                href="{{ route('sale-service-vip-customer-list') }}">
                                                <span class="menu-icon"><i class="fa fa-star fs-2"
                                                        aria-hidden="true"></i></span><span class="menu-title">
                                                    VIP Customer</span>
                                            </a>
                                        </div>
                                    @endif
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'sale-service-leave.index') active @endif"
                                            href="{{ route('sale-service-leave.index') }}">
                                            <span class="menu-icon"><i class="fa-solid fa-house"></i></span><span
                                                class="menu-title">
                                                Leave</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'sale-service-ticket.index') active @endif"
                                            href="{{ route('sale-service-ticket.index') }}">
                                            <span class="menu-icon"><i class="fa-solid fa-ticket"></i></span><span
                                                class="menu-title">
                                                Ticket</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'sale-service-holiday') active @endif"
                                            href="{{ route('sale-service-holiday') }}">
                                            <span class="menu-icon"><i class="fa-solid fa-pencil"></i></span><span
                                                class="menu-title">
                                                Holidays</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == 'service-info-sheet.index') active @endif"
                                            href="{{ route('service-info-sheet.index') }}">
                                            <span class="menu-icon"><i
                                                    class="fa-solid fa-circle-info"></i></span><span
                                                class="menu-title">
                                                Info Sheets</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @if (Auth()->user() !== null &&
                                    Auth()->user()->role_id !== 1 &&
                                    Auth()->user()->is_manager == '1' &&
                                    Auth()->user()->role_id !== 9)
                                @php
                                    $manager = DB::table('team')
                                        ->where('manager_id', Auth()->user()->id)
                                        ->first();
                                @endphp
                                @if (!blank($manager))
                                    @php $teamUrl = route('emp-team.show',$manager->id) @endphp
                                    @if (Auth()->user()->role_id == 3)
                                        @php $teamUrl = route('hr-team.show',$manager->id) @endphp
                                    @elseif (Auth()->user()->role_id == 4)
                                        @php $teamUrl = route('confirm-team.show',$manager->id) @endphp
                                    @elseif (Auth()->user()->role_id == 5)
                                        @php $teamUrl = route('driver-team.show',$manager->id) @endphp
                                    @elseif (Auth()->user()->role_id == 6)
                                        @php $teamUrl = route('system-team.show',$manager->id) @endphp
                                    @elseif (Auth()->user()->role_id == 7)
                                        @php $teamUrl = route('transport-team.show',$manager->id) @endphp
                                    @elseif (Auth()->user()->role_id == 8)
                                        @php $teamUrl = route('warehouse-team.show',$manager->id) @endphp
                                    @elseif (Auth()->user()->role_id == 10)
                                        @php $teamUrl = route('sales_service-team.show',$manager->id) @endphp
                                    @endif
                                    <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
                                        class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5 log_menu">
                                        <div class="menu-item">
                                            <a class="menu-link @if (\Route::currentRouteName() == $teamUrl) active @endif"
                                                href="{{ $teamUrl }}">
                                                <span class="menu-icon"><i
                                                        class="fa-solid fa-people-group"></i></span><span
                                                    class="menu-title">
                                                    Team Detail</span>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            @if (Auth()->user() !== null && Auth()->user()->role_id !== 5)
                                @php
                                    $log = route('admin-all-logs');
                                    $text = 'admin-all-logs';
                                @endphp
                                @if (Auth()->user() == null)
                                    @php $log = route('login') @endphp
                                @elseif (Auth()->user()->role_id == 2)
                                    @php
                                        $log = route('employee-all-logs');
                                        $text = 'employee-all-logs';
                                    @endphp
                                @elseif (Auth()->user()->role_id == 3)
                                    @php
                                        $log = route('hr-all-logs');
                                        $text = 'hr-all-logs';
                                    @endphp
                                @elseif (Auth()->user()->role_id == 4)
                                    @php
                                        $log = route('confirm-all-logs');
                                        $text = 'confirm-all-logs';
                                    @endphp
                                @elseif (Auth()->user()->role_id == 6)
                                    @php
                                        $log = route('system-all-logs');
                                        $text = 'system-all-logs';
                                    @endphp
                                @elseif (Auth()->user()->role_id == 7)
                                    @php
                                        $log = route('transport-all-logs');
                                        $text = 'transport-all-logs';
                                    @endphp
                                @elseif (Auth()->user()->role_id == 8)
                                    @php
                                        $log = route('warehouse-all-logs');
                                        $text = 'warehouse-all-logs';
                                    @endphp
                                @elseif (Auth()->user()->role_id == 9)
                                    @php
                                        $log = route('sales-all-logs');
                                        $text = 'sales-all-logs';
                                    @endphp
                                @elseif (Auth()->user()->role_id == 10)
                                    @php
                                        $log = route('sale-service-all-logs');
                                        $text = 'sale-service-all-logs';
                                    @endphp
                                @endif
                                <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
                                    class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5 log_menu">
                                    <div class="menu-item">
                                        <a class="menu-link @if (\Route::currentRouteName() == $text) active @endif"
                                            href="{{ $log }}">
                                            <span class="menu-icon"><i
                                                    class="fa-solid fa-clock-rotate-left"></i></span><span
                                                class="menu-title">
                                                Logs</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
