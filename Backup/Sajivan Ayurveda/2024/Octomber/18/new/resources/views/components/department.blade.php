{{-- @if (collect($accesses)->where('menu_id', '2')->first()->status != 0) --}}
@if (collect($accesses)->where('menu_id', '2')->where('disable',1)->first() ||
        collect($accesses)->where('menu_id', '3')->where('disable',1)->first() ||
        collect($accesses)->where('menu_id', '4')->where('disable',1)->first() )


    <li class="nav-item my-1">
        <a
            class="nav-link d-flex align-items-center  {{ request()->routeIs('department.*') || request()->routeIs('designation.*') || request()->routeIs('role.*') ? 'active' : '' }}"
            href="javascript:void(0)"   role="button"
            aria-expanded="{{ request()->routeIs('department.*') || request()->routeIs('designation.*') || request()->routeIs('role.*') ? 'true' : 'false' }}"
            aria-controls="sidebar-account">
            <i class="fa-solid fa-people-group"></i>
         
            <span>Management</span>
            <i class="fas fa-angle-right icon-toggle" id="icon-management"></i>
            <span class="badge badge-sm rounded-pill me-n2 bg-success-subtle text-success ms-auto"></span>
        </a>
       
            <ul class="nav nav-sm flex-column mt-1 inner-ul {{ request()->routeIs('department.*') || request()->routeIs('designation.*') || request()->routeIs('role.*') ? ' ' : '' }}"
                id="sidebar-management">
                @if (collect($accesses)->where('menu_id', '2')->where('disable',1)->first())
                    <li class="nav-item"><a href="{{ route('department.index') }}"
                            class="nav-link {{ request()->routeIs('department.*') ? 'fw-bold' : '' }}"><i class="fas fa-building"></i>
                            Department
                        </a>
                    </li>
                @endif
                @if (collect($accesses)->where('menu_id', '3')->where('disable',1)->first())
                    <li class="nav-item"><a href="{{ route('designation.index') }}"
                            class="nav-link {{ request()->routeIs('designation.*') ? 'fw-bold' : '' }}"><i class="fa-solid fa-user-large"></i>
                                Designation
                        </a>
                    </li>
                @endif
                @if (collect($accesses)->where('menu_id', '4')->where('disable',1)->first())
                    <li class="nav-item"><a href="{{ route('role.index') }}"
                            class="nav-link {{ request()->routeIs('role.*') ? 'fw-bold' : '' }}"><i
                            class="fa-solid fa-user"></i>
                                Role
                        </a>
                    </li>
                @endif
            </ul>
   
    </li>
@endif
