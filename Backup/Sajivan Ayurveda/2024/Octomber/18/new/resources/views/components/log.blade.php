@if (collect($accesses)->where('menu_id', '21')->where('disable',1)->first())
<a href="{{ route('all-log') }}" class="nav-link {{request()->routeIs('all-log')?'active':''}}">
    <i class="fa-solid fa-clock-rotate-left"></i>
    Logs
</a>
@endif