@if (collect($accesses)->where('menu_id', '1')->first()->status != 0)
@php
    $routeDashboard = route('dashboard');
    if (Auth()->user()->role_id == '2') {
        $routeDashboard = route('emp-dashboard');
    }
    if (Auth()->user()->role_id == '4') {
        $routeDashboard = route('hr-dashboard');
    }
@endphp
<a href="{{ $routeDashboard }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <i class="fa-solid fa-house"></i>
    Dashboard
</a>
@endif