@if (collect($accesses)->where('menu_id', '6')->first() &&
        collect($accesses)->where('menu_id', '6')->first()->status != 0)
    <li class="nav-item">
        <a href="{{ route('incentive.index') }}"
            class="nav-link {{ request()->routeIs('incentive.*') ? 'active' : '' }}">
            <i class="fa fa-circle-info" aria-hidden="true"></i>Incentive</a>
    </li>
@endif

