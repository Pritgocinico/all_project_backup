@if (collect($accesses)->where('menu_id', '1')->first()->status != 0)
@php
    $routeDashboard = route('emp-dashboard');
    if (Auth()->user()->role_id == '1') {
        $routeDashboard = route('dashboard');
    }
    if (Auth()->user()->role_id == '8') {
        $routeDashboard = route('sale-approval-dashboard');
    }
@endphp
<a href="{{ $routeDashboard }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <i class="fa-solid fa-house"></i>
    Dashboard
</a>
@endif