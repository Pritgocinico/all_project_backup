@if (collect($accesses)->where('menu_id', '18')->where('disable',1)->first())
<a href="{{ route('customer.index') }}" class="nav-link {{ request()->routeIs('customer.*') ? 'active' : '' }}">
    <i class="fa-solid fa-user-tie"></i>
    All Customers
</a>
@endif
