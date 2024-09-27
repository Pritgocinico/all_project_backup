<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
        <th>Sr. no</th>
            <th class="min-w-125px">Product Name</th>
            <th class="min-w-125px">SKU</th>
            <th class="min-w-125px">Image</th>
            <th class="min-w-125px">Category</th>
            <th class="min-w-125px">Stock</th>
            <th class="min-w-125px">Status</th>
            <th class="min-w-125px">Created At</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($productList as $key=>$product)
            <tr>
            <td>{{ $productList->firstItem() + $key }}</td>
                <td>
                    @php $route = route('confirm-product-view',$product->id); @endphp
                    @if(Auth()->user() !== null && Auth()->user()->role_id == 1)
                        @php $route = route('employee-product-view',$product->id); @endphp
                    @endif
                    <a href="{{$route}}" class="pre-agro-emp">{{ $product->product_name }}</a></td>
                <td>{{ $product->sku_name }}</td>
                <td><img src="{{ asset('public/assets/upload/' . $product->product_image) }}" height="60px"
                        alt=""></td>
                <td>{{ isset($product->categoryDetail) ? $product->categoryDetail->name : '' }}
                </td>
                <td>
                    @foreach ($product->productVariantDetail as $variant)
                        {{$variant->sku_name}} - {{$variant->stock}} <br />
                    @endforeach
                </td>
                <td>
                    @php
                        $text = 'Inactive';
                        $class = 'danger';
                    @endphp
                    @if ($product->status == 1)
                        @php
                            $text = 'Active';
                            $class = 'success';
                        @endphp
                    @endif
                    <div class="badge badge-light-{{ $class }} fw-bold">
                        {{ $text }}</div>
                </td>

                <td>{{ Utility::convertDmyWith12HourFormat($product->created_at) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $productList->links('pagination::bootstrap-5') }}
</div>
