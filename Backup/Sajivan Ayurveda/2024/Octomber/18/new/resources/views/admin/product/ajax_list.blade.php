<table class="table table-hover table-sm table-scrolling table-nowrap table-responsive mt-6 border" id="product_table">
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Price</th>
            <th>Batch Number</th>
            <th>Mfg. Lic. Number</th>
            <th>Mfg. Date</th>
            <th>Created By</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($productList as $key=>$product)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->batch_number }}</td>
                <td>{{ $product->mfg_lic_number }}</td>
                <td>{{ $product->mfg_date }}</td>
                <td>
                    {{ isset($product->userDetail) ? $product->userDetail->name : '-' }}
                </td>
                <td class="text-end">
                <div class="icon-td">
                
                    @if (collect($accesses)->where('menu_id', '19')->where('edit', 1)->first())
                    <a href="{{ route('product.edit', $product->id) }}" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Edit Product"><i class="fa-solid fa-pen-to-square me-3"></i></a>
                    @endif
                        @if (collect($accesses)->where('menu_id', '19')->where('delete', 1)->first())
                        <a href="javscript:void(0)" onclick="deleteProduct({{ $product->id }})" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Delete Product"><i class="fa fa-trash-can me-3"></i></a>
                    @endif
                 
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
