@if (collect($accesses)->where('menu_id', '1')->first()->status != 0)
<a href="{{ route('all-log') }}" class="nav-link {{request()->routeIs('all-log')?'active':''}}">
    <i class="fa-solid fa-clock-rotate-left"></i>
    Logs
</a>
@endif