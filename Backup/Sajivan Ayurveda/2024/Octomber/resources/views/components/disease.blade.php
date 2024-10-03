@if (collect($accesses)->where('menu_id', '22')->first()->status != 0)
<a href="{{ route('disease.index') }}" class="nav-link {{request()->routeIs('disease.*')?'active':''}}">
    <i class="fa fa-heartbeat" aria-hidden="true"></i>
    Disease
</a>
@endif