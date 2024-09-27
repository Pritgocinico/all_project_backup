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
