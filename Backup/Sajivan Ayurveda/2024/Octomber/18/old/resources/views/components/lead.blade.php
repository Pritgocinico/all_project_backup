@if (collect($accesses)->where('menu_id', '17')->first()->status != 0)
<a href="{{ route('leads.index') }}" class="nav-link {{request()->routeIs('leads.*')?'active':''}}">

    <i class="fa-solid fa-pencil"></i>

    Lead

</a>
@endif