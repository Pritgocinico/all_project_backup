@if (collect($accesses)->where('menu_id', '6')->where('disable', 1)->first())
    <li class="nav-item">
        <a href="{{ route('incentive.index') }}"
            class="nav-link {{ request()->routeIs('incentive.*') ? 'active' : '' }}">
            <i class="fa fa-circle-info" aria-hidden="true"></i>Incentive</a>
    </li>
@endif

