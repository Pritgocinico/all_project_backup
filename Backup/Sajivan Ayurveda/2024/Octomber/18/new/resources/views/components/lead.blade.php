@if (collect($accesses)->where('menu_id', '15')->where('disable', 1)->first())
    <a href="{{ route('leads.index') }}" class="nav-link {{ request()->routeIs('leads.*') ? 'active' : '' }}">
        <i class="fa-solid fa-pencil"></i>
        Leads
    </a>
@endif

{{-- @if (collect($accesses)->where('menu_id', '15')->first()->status != 0 || collect($accesses)->where('menu_id', '23')->first()->status != 0)


    <li class="nav-item my-1">
        <a
            class="nav-link d-flex align-items-center  {{ request()->routeIs('leads.*') || request()->routeIs('pushleads.*') ? 'active' : '' }}"
            href="javascript:void(0)"   role="button"
            aria-expanded="{{ request()->routeIs('leads.*') || request()->routeIs('pushleads.*') ? 'true' : 'false' }}"
            aria-controls="sidebar-leads">
            <i class="fa-solid fa-people-group"></i>
         
            <span>All Leads</span>
            <i class="fas fa-angle-right icon-toggle" id="icon-leads"></i>
            <span class="badge badge-sm rounded-pill me-n2 bg-success-subtle text-success ms-auto"></span>
        </a>
       
            <ul class="nav nav-sm flex-column mt-1 inner-ul {{ request()->routeIs('leads.*') || request()->routeIs('pushleads.*') ? ' ' : '' }}"
                id="sidebar-leads">
                @if (collect($accesses)->where('menu_id', '15')->first() && collect($accesses)->where('menu_id', '23')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('leads.index') }}"
                            class="nav-link {{ request()->routeIs('leads.*') ? 'fw-bold' : '' }}"><i class="fas fa-building"></i>
                            Manual Lead
                        </a>
                    </li>
                @endif
                @if (collect($accesses)->where('menu_id', '23')->first() && collect($accesses)->where('menu_id', '15')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('pushleads.index') }}"
                            class="nav-link {{ request()->routeIs('pushleads.*') ? 'fw-bold' : '' }}"><i class="fa-solid fa-user-large"></i>
                                Push Lead
                        </a>
                    </li>
                @endif
            </ul>
   
    </li>
@endif --}}
