@if (collect($accesses)->where('menu_id', '16')->first()->status != 0)
{{-- <a href="{{ route('follow-up.index') }}" class="nav-link {{request()->routeIs('follow-up.*')?'active':''}}">
    <i class="fa-solid fa-person-walking-arrow-right"></i>
    Follow Up
</a> --}}
@endif
