@if(Auth()->user()->role_id == "1")
<a href="{{ route('dashboard') }}" class="nav-link {{request()->routeIs('dashboard.*')?'active':''}}">
    <i class="bi bi-house-fill"></i>
    Dashboard
</a>
@endif
<a href="{{ route('department.index') }}" class="nav-link {{request()->routeIs('department.*')?'active':''}}">
    <i class="bi bi-file-break-fill"></i>
    Department
</a>