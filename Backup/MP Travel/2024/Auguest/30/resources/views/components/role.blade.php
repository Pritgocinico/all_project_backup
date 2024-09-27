@if (collect($accesses)->where('menu_id', '4')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '5')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '22')->first()->status != 0)
    <li class="nav-item my-1">
        <a class="nav-link d-flex align-items-center {{ request()->routeIs('service-preference.*') || request()->routeIs('role.*') || request()->routeIs('user.*') ? 'active collapsed' : '' }}"
            href="#sidebar-user" data-bs-toggle="collapse" role="button"
            aria-expanded="{{ request()->routeIs('role.*') || request()->routeIs('service-preference.*') || request()->routeIs('user.*') ? 'true' : 'false' }}"
            aria-controls="sidebar-account"><i class="fa-solid fa-users"></i><span>User
                Management</span>
            <span class="badge badge-sm rounded-pill me-n2 bg-success-subtle text-success ms-auto"></span></a>
        <div class="collapse {{ request()->routeIs('role.*') || request()->routeIs('service-preference.*') || request()->routeIs('user.*') ? 'show' : '' }}"
            id="sidebar-user">
            <ul class="nav nav-sm flex-column mt-1  inner-ul">
                @if (collect($accesses)->where('menu_id', '4')->first() &&
                        collect($accesses)->where('menu_id', '4')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('role.index') }}"
                            class="nav-link {{ request()->routeIs('role.*') ? 'fw-bold' : '' }}"><i
                                class="fa-solid fa-user"></i>Role</a>
                    </li>
                @endif
                @if (collect($accesses)->where('menu_id', '5')->first() &&
                        collect($accesses)->where('menu_id', '5')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('user.index') }}"
                            class="nav-link {{ request()->routeIs('user.*') ? 'fw-bold' : '' }}"><i
                                class="fa-solid fa-users"></i>Employee</a>
                    </li>
                @endif
                @if (collect($accesses)->where('menu_id', '24')->first() &&
                        collect($accesses)->where('menu_id', '24')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('service-preference.index') }}"
                            class="nav-link {{ request()->routeIs('service-preference.*') ? 'fw-bold' : '' }}"><i class="fa fa-wrench"
                                aria-hidden="true"></i>
                            Service Preference</a>
                    </li>
                @endif
            </ul>
        </div>
    </li>
@endif
