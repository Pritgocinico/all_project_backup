<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-black fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px">Product Name</th>
            <th class="min-w-125px">SKU Name</th>
            <th class="min-w-125px">Total Orders</th>
        </tr>
    </thead>
    <tbody class="text-black-600 fw-semibold">
        @forelse ($productList as $key=>$product)
            <tr>
                <td>{{ isset($product->productDetail)?$product->productDetail->product_name:"-" }}</td>
                <td>{{ isset($product->productDetail)?$product->productDetail->sku_name:"-" }}</td>
                <td>{{ $product->total_orders }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
