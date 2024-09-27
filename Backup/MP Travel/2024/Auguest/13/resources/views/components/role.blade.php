<li class="nav-item my-1">
    <a class="nav-link d-flex align-items-center {{ request()->routeIs('role.*') || request()->routeIs('user.*') ? 'active collapsed' : '' }}" href="#sidebar-user" data-bs-toggle="collapse"
        role="button" aria-expanded="{{ request()->routeIs('role.*') || request()->routeIs('user.*') ? 'true':'false' }}" aria-controls="sidebar-account"><i class="bi bi-people-fill"></i><span>User
            Management</span>
        <span class="badge badge-sm rounded-pill me-n2 bg-success-subtle text-success ms-auto"></span></a>
    <div class="collapse {{ request()->routeIs('role.*') || request()->routeIs('user.*')?'show':''}}" id="sidebar-user">
        <ul class="nav nav-sm flex-column mt-1">
            <li class="nav-item"><a href="{{ route('role.index') }}" class="nav-link {{request()->routeIs('role.*')?'fw-bold':''}}">Role</a>
            </li>
            <li class="nav-item"><a href="{{ route('user.index') }}" class="nav-link {{request()->routeIs('user.*')?'fw-bold':''}}">Employee</a>
            </li>
        </ul>
    </div>
</li>
