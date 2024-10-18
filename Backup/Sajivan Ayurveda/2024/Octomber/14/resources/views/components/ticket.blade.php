@if (collect($accesses)->where('menu_id', '9')->first() &&
        collect($accesses)->where('menu_id', '9')->first()->status != 0)
    <li class="nav-item"><a href="{{ route('help.index') }}"
            class="nav-link {{ request()->routeIs('help.*') ? 'fw-bold' : '' }}"><i class="fa-solid fa-ticket"></i>
            Help
        </a>
    </li>
@endif
