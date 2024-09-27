<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th>Sr. no</th>
            <th class="min-w-125px">Product Name</th>
            <th class="min-w-125px">Category Name</th>
            <th class="min-w-125px">Product Variant / Stock</th>
            <th class="min-w-125px">Total Stock</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($productList as $key=>$product)
            <tr>
            <td>{{ $productList->firstItem() + $key }}</td>
                @php $route =  route('product-view',$product->id)@endphp
                @if(Auth()->user() !== null && Auth()->user()->role_id == 1)
                    @php $route =  route('product.show',$product->id)@endphp
                @endif
                <td><a href="{{$route}}" class="pre-agro-emp">{{ $product->product_name }}</a></td>
                <td><b>{{ isset($product->categoryDetail->categoryDetail) ? $product->categoryDetail->categoryDetail->name." - " : '' }}</b>  {{ isset($product->categoryDetail) ? $product->categoryDetail->name : '' }}</td>
                <td>
                    @php $totalStock = 0;@endphp
                    @foreach ($product->productVariantDetail as $variant)
                        {{ $variant->sku_name }} - {{ $variant->stock }}<br />
                        @php $totalStock += $variant->stock;@endphp
                    @endforeach
                </td>
                <td>{{ $totalStock }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $productList->links('pagination::bootstrap-5') }}
</div>
