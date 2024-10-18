@if (collect($accesses)->where('menu_id', '16')->where('disable',1)->first())
{{-- <a href="{{ route('follow-up.index') }}" class="nav-link {{request()->routeIs('follow-up.*')?'active':''}}">
    <i class="fa-solid fa-person-walking-arrow-right"></i>
    Follow Up
</a> --}}
@endif
