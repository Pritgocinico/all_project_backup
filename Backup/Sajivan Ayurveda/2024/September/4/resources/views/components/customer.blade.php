@if (collect($accesses)->where('menu_id', '18')->first()->status != 0)
<a href="{{ route('customer.index') }}" class="nav-link {{ request()->routeIs('customer.*') ? 'active' : '' }}">
    <i class="fa-solid fa-user-tie"></i>
    Customers
</a>
@endif
