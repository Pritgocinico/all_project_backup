<table class="table table-hover table-sm table-nowrap table-responsive mt-6 border" id="product_table">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>SKU</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Created By</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($productList as $key=>$product)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->sku }}</td>
                <td>
                    {{ isset($product->category) ? ucfirst($product->category->name) : '' }}
                </td>
                <td>
                    {{ $product->quantity }}
                </td>
                <td>
                    {{ isset($product->userDetail) ? $product->userDetail->name : '-' }}
                </td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '19')->first()->status == 2)
                        <a href="{{ route('product.edit', $product->id) }}" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Edit Product"><i class="fa-solid fa-pen-to-square me-3"></i></a>
                        <a href="javscript:void(0)" onclick="deleteProduct({{ $product->id }})" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Delete Product"><i class="fa fa-trash-can me-3"></i></a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
