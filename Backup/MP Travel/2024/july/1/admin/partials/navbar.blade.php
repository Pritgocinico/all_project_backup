<div class="d-none d-lg-flex py-3">
    <div class="flex-none">
    </div>
    <div class="hstack flex-fill justify-content-end flex-nowrap gap-6 ms-auto px-6 px-xxl-8">
        @if(Route::currentRouteName() !== 'dashboard')
        <a href="#" class="main-cmn-back-btn text-dark" onclick="window.history.back();" data-bs-toggle="tooltip"
            data-bs-placement="bottom" data-bs-original-title="Back" data-kt-initialized="1">
            <i class="bi bi-arrow-left"></i>
        </a>
        @endif
        <div class="dropdown"><a href="#" class="nav-link" id="dropdown-notifications" data-bs-toggle="dropdown"
                aria-expanded="false"><i class="bi bi-bell" aria-hidden="true"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end px-2" aria-labelledby="dropdown-notifications">
                <div class="dropdown-item d-flex align-items-center">
                    <h6 class="dropdown-header px-0">Notifications</h6><a href="#"
                        class="text-sm fw-semibold ms-auto">Clear all</a>
                </div>
                <div class="dropdown-item py-3 d-flex">
                </div>
                <div class="dropdown-divider"></div>
                <div class="dropdown-item py-2 text-center"><a href="#"
                        class="fw-semibold text-muted text-primary-hover">View all</a></div>
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
                        as</span> <span class="d-block text-heading fw-semibold">{{ Auth()->user()->name }}</span></div>
                <div class="dropdown-divider"></div>
                @if (Auth()->user(0)->role_id == 1)
                    <a class="dropdown-item" href="#"><i class="bi bi-pencil me-3"></i>Business Setting</a>
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
