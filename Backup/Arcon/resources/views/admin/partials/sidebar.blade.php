    <!-- notifications sidebar -->
    <div class="sidebar sidepanel" id="notifications">
        <div class="p-3">
            <div class="sidebar-header d-block align-items-end">
                <div class="align-items-center d-flex justify-content-between py-4">
                    <h4>Notifications</h4>
                    <a href="javascript:void(0);"  onclick="closeNav()">
                        <i class="bi bi-x fs-20"></i>
                    </a>
                </div>
            </div>
            <div class="sidebar-content">
                <div class="tab-content">
                    <div class="tab-pane active" id="activities">
                        <div class="tab-pane-body">
                            <ul class="list-group list-group-flush">
                                <?php if(count($notifications)>0){ ?>
                                    @foreach($notifications as $notification)
                                        <li class="px-0 list-group-item">
                                            <p class="mb-0 fw-bold text-success d-flex justify-content-between">
                                                <a href="{{ $notification['data']['url'] }}" class="read_notification" data-id="{{ $notification['id'] }}">{{ $notification['data']['type'] }} - {{ $notification['data']['data'] }}</a>
                                            </p>
                                            <span class="text-muted small">
                                                <i class="bi bi-clock me-1"></i> {{ $notification['created_at'] }}
                                            </span>
                                        </li>
                                    @endforeach
                                <?php }else{ ?>
                                    <li class="px-0 list-group-item">
                                        <p class="mb-0 fw-bold houmanity-color d-flex justify-content-between">
                                            No New Notification
                                        </p>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <?php if(count($notifications)>0){ ?>
                            <div class="tab-pane-footer">
                                <a href="{{ route('markasread') }}" class="btn btn-success">
                                    <i class="bi bi-check2 me-2"></i> Make All Read
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ./ notifications sidebar -->
    <div class="row justify-content-end m-0 p-0">
        <div class="col-md-10 ms-sm-auto col-lg-10 p-0 ">
        <div class="header">
            <div class="px-md-2 d-flex">
                @if(isset($icon) && $icon != '')
                    <?php $icon=URL::asset("assets/admin/images/".$icon); ?>
                    <img src="{{$icon}}" alt="" width="45px" height="45px">
                @endif
                <h5 class="page-title">
                    @if(isset($page))
                        {{$page}}
                    @endif
                </h5>
            </div>
            <div class="header-bar ms-auto">
                <ul class="navbar-nav justify-content-end">
                    {{-- <a href="javascript:void(0);" onclick="openFullscreen();" class="ms-3"><i class="bi bi-fullscreen"></i></a> --}}
                    <li class="nav-item">
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <a href="javascript:void(0);" onclick="openNav()" class="nav-link nav-link-notify p-3" data-count="{{ auth()->user()->unreadNotifications->count() }}" data-sidebar-target="#notifications">
                                <!--<i class="bi bi-bell icon-lg"></i>-->
                                <img src="{{url('/')}}/assets/admin/images/notification.png" width="25px">
                            </a>
                        @else
                            <a href="javascript:void(0);" onclick="openNav()" class="nav-link nav-link-notify p-3" data-count="{{ auth()->user()->unreadNotifications->count() }}" data-sidebar-target="#notifications">
                                <!--<i class="bi bi-bell icon-lg"></i>-->
                                <img src="{{url('/')}}/assets/admin/images/notification.png" width="25px" class="me-2">
                            </a>
                            {{-- <a href="javascript:void(0);" class="m-2 p-3 nav-link nav-link-notify" data-count="{{ auth()->user()->unreadNotifications->count() }}"><i class="bi bi-bell icon-lg"></i></a> --}}
                        @endif
                    </li>
                    <li>
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle profile-dropdown" id="dropdownMenuButton" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <?php $user = auth()->user(); ?>
                                @if($user->image !== NULL)
                                    <?php $image=URL::asset("settings/".$user->image); ?>
                                    <img src="{{ $image }}" width="55" class="rounded-circle">
                                @else
                                    <img src="{{url('/')}}/public/assets/user-profile.png" width="50" class="rounded-circle">
                                @endif
                                <i class="bi bi-three-dots-vertical fs-20" style="color: #c7c7c7;"></i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="{{ route('view.profile') }}">My Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('edit.profile') }}">Edit Profile</a></li>
                                <li><a class="dropdown-item" href="{{route('settings')}}">Settings</a></li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        </div>
    </div>
    <nav id="sidebarMenu" class="col-md-2 col-lg-2 col-xl-2 col-xxl-2 d-md-block bg-light sidebar collapse gc-sidebar">
        <div class="position-sticky pt-3">
            <div class="gc-logo pb-1 m-d-none text-center">
                <a href="{{ route('admin.dashboard') }}" class="menu-header-logo">
                    <img src="{{ URL::asset('settings/'.$setting->logo) }}" alt="logo">
                </a>
            </div>
            <ul class="sidenav admin-sidebar nav flex-column">
                <li class="menu-divider houmanity-color">Company</li>
                <li class="">
                    <a class="nav-link @if(\Request::route()->getName() == 'admin.dashboard') active @endif"
                        href="{{ route('admin.dashboard') }}">
                        <span class="nav-link-icon">
                            <img src="{{url('/')}}/assets/admin/images/dashboard.png" width="25px" class="me-2">
                        </span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="">
                    <a class="nav-link  @if(\Request::route()->getName() == 'admin.users') active @endif"
                        href="{{ route('admin.users') }}">
                        <span class="nav-link-icon">
                            <img src="{{url('/')}}/assets/admin/images/clients.png" width="25px" class="me-2">
                        </span>
                        <span>Dealers</span>
                    </a>
                </li>
                <li class="">
                    <a class="nav-link  @if(\Request::route()->getName() == 'admin.agents') active @endif"
                        href="{{ route('admin.agents') }}">
                        <span class="nav-link-icon">
                            <img src="{{url('/')}}/assets/admin/images/agent.png" width="25px" class="me-2">
                        </span>
                        <span>Super Agents</span>
                    </a>
                </li>
                <li class="">
                    <a href="#" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle sub-menu nav-link @if(\Request::route()->getName() == 'admin.products' || \Request::route()->getName() == 'admin.categories' || \Request::route()->getName() == 'admin.edit_category' || \Request::route()->getName() == 'admin.add_product')  active @endif">
                        <span class="nav-link-icon">
                            <img src="{{url('/')}}/assets/admin/images/products.png" width="25px" class="me-2">
                        </span>
                        <span>Products</span>
                    </a>
                    <ul class="dropdown-container collapse list-unstyled">
                        <li><a href="{{ route('admin.products') }}" class="@if(\Request::route()->getName() == 'admin.products' || \Request::route()->getName() == 'admin.add_product' || \Request::route()->getName() == 'admin.edit_product') active @endif">Products</a></li>
                        <li><a href="{{ route('admin.categories') }}" class="@if(\Request::route()->getName() == 'admin.categories' || \Request::route()->getName() == 'admin.add_category' || \Request::route()->getName() == 'admin.edit_category') active @endif">Categories</a></li>
                    </ul>
                </li>
                <li class="">
                    <a class="nav-link  @if(\Request::route()->getName() == 'admin.gallery') active @endif"
                        href="{{ route('admin.gallery') }}">
                        <span class="nav-link-icon">
                            <img src="{{url('/')}}/assets/admin/images/gallery.png" width="25px" class="me-2">
                        </span>
                        <span>Gallery</span>
                    </a>
                </li>
                <li class="">
                    <a class="nav-link  @if(\Request::route()->getName() == 'admin.banners') active @endif"
                        href="{{ route('admin.banners') }}">
                        <span class="nav-link-icon">
                            <img src="{{url('/')}}/assets/admin/images/banner.png" width="25px" class="me-2">
                        </span>
                        <span>Banners</span>
                    </a>
                </li>
                <li class="">
                    <a class="nav-link  @if(\Request::route()->getName() == 'admin.orders') active @endif"
                        href="{{ route('admin.orders') }}">
                        <span class="nav-link-icon">
                            <img src="{{url('/')}}/assets/admin/images/orders.png" width="25px" class="me-2">
                        </span>
                        <span>Orders</span>
                    </a>
                </li>
                <li class="">
                    <a class="nav-link  @if(\Request::route()->getName() == 'admin.inquiries') active @endif"
                        href="{{ route('admin.inquiries') }}">
                        <span class="nav-link-icon">
                            <img src="{{url('/')}}/assets/admin/images/inquiry.png" width="25px" class="me-2">
                        </span>
                        <span>Product Inquiries</span>
                    </a>
                </li>
                <li class="">
                    <a class="nav-link  @if(\Request::route()->getName() == 'admin.logs') active @endif"
                        href="{{ route('admin.logs') }}">
                        <span class="nav-link-icon">
                            <img src="{{url('/')}}/assets/admin/images/logs.png" width="25px" class="me-2">
                        </span>
                        <span>Logs</span>
                    </a>
                </li>
                <li class="">
                    <a href="#" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle sub-menu nav-link @if(\Request::route()->getName() == 'admin.orders')  active @endif">
                        <span class="nav-link-icon">
                            <img src="{{url('/')}}/assets/admin/images/assets.png" width="25px" class="me-2">
                        </span>
                        <span>Reports</span>
                    </a>
                    <ul class="dropdown-container collapse list-unstyled">
                        <li><a href="{{ route('sales_report') }}" class="@if(\Request::route()->getName() == 'sales_report') active @endif">Sales Report</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <main class="col-md-10 ms-sm-auto col-lg-10 col-xl-10 col-xxl-10 px-md-4 gc-main">
        <div class="content" id="fullscreen">
