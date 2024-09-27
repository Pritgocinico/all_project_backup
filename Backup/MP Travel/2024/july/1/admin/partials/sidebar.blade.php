<nav class="flex-none navbar navbar-vertical navbar-expand-lg navbar-light bg-transparent show vh-lg-100 px-0 py-2" id="sidebar">
    <div class="container-fluid px-3 px-md-4 px-lg-6">
        <button class="navbar-toggler ms-n2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse" aria-controls="sidebarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand d-inline-block py-lg-1 mb-lg-5" href="{{ route('dashboard') }}">
            <img src="{{ isset($settings) ? asset('/storage/' . $settings->logo) : asset('assets/img/login/mpfs - R.jpeg') }}" class="logo-dark" alt="Logo">
        </a>
        <div class="navbar-user d-lg-none">
            <a href="#" class="main-cmn-back-btn text-dark" onclick="window.history.back();" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Back">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="dropdown">
                <a class="d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
                    <div>
                        @php
                            $image = asset('assets/img/user/user.jpg');
                            if (Auth::user()->profile_image !== null) {
                                $image = asset('storage/' . Auth::user()->profile_image);
                            }
                        @endphp
                        <div class="avatar avatar-sm text-bg-secondary rounded-circle text-uppercase">
                            <img src="{{ $image }}" alt="User Profile Image">
                        </div>
                        <div class="d-none d-sm-block ms-3"><span class="h6">{{ Auth::user()->name }}</span></div>
                        <div class="d-none d-md-block ms-md-2"><i class="bi bi-chevron-down text-muted text-xs"></i></div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <div class="dropdown-header">
                        <span class="d-block text-sm text-muted mb-1">Signed in as</span>
                        <span class="d-block text-heading fw-semibold">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="dropdown-divider"></div>
                    @if (Auth::user()->role_id == 1)
                        <a class="dropdown-item" href="#"><i class="bi bi-pencil me-3"></i>Business Setting</a>
                        <a class="dropdown-item" href="{{ route('setting') }}"><i class="bi bi-gear me-3"></i>Dashboard Settings</a>
                    @endif
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-up me-3"></i>Logout</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="collapse navbar-collapse overflow-x-hidden" id="sidebarCollapse">
            <ul class="navbar-nav">
                @foreach ($accesses as $access)
                    @if ($access->status != "0" && $access->menu->name != 'user')
                        <li class="nav-item {{ $active == $access->menu->name ? 'nav-active' : '' }}">
                            @include('components.' . $access->menu->name)
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</nav>
