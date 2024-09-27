@if (collect($accesses)->where('menu_id', '2')->first()->status != 0)
<a href="{{ route('department.index') }}" class="nav-link {{request()->routeIs('department.*')?'active':''}}">
    <i class="fas fa-building"></i>
    Department
</a>
@endif