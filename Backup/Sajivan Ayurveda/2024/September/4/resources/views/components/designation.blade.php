@if (collect($accesses)->where('menu_id', '3')->first()->status != 0)
<a href="{{ route('designation.index') }}" class="nav-link {{request()->routeIs('designation.*')?'active':''}}">
    <i class="fa-solid fa-user-large"></i>
    Designation
</a>
@endif