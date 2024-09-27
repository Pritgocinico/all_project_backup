@if(Auth()->user()->role_id == "1")
<a href="{{ route('dashboard') }}" class="nav-link {{request()->routeIs('dashboard')?'active':''}}">
    <i class="fa-solid fa-house"></i>
    Dashboard
</a>
@endif
<a href="{{ route('department.index') }}" class="nav-link {{request()->routeIs('department.*')?'active':''}}">
    <i class="fas fa-building"></i>
    Department
</a>