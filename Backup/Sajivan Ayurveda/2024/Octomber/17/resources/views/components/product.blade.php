@if (collect($accesses)->where('menu_id', '19')->first()->status != 0)
<a href="{{ route('product.index') }}" class="nav-link {{request()->routeIs('product.*')?'active':''}}">
    <i class="fa-brands fa-product-hunt"></i>
    Product
</a>
@endif
