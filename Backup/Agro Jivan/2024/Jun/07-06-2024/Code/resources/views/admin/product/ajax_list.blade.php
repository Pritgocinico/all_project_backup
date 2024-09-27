<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th>Sr. no</th>
            <th class="min-w-125px">Product Name</th>
            <th class="min-w-125px">SKU</th>
            <th class="min-w-125px">Image</th>
            <th class="min-w-125px">Category</th>
            <th class="min-w-125px">Status</th>
            <th class="min-w-125px">Created At</th>
            <th class="min-w-100px">Actions</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($productList as $key=>$product)
            <tr>
            <td>{{ $productList->firstItem() + $key }}</td>
                <td><a href="{{route('product.show',$product->id)}}" class="pre-agro-emp">{{ $product->product_name }}</a></td>
                <td>{{ $product->sku_name }}</td>
                <td><img src="{{ ImageHelper::getImageUrl($product->product_image)}}" height="60px"
                        alt=""></td>
                <td>{{ isset($product->categoryDetail) ? $product->categoryDetail->name : '' }}
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
                <td>
                    <a class="btn btn-icon btn-info w-30px h-30px me-3"
                    href="{{ route('product.edit', $product->id) }}"  data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Product">
                        <i class="fa-solid fa-edit"></i>
                    </a>
                    <a class="btn btn-icon btn-danger w-30px h-30px me-3"
                        onclick="deleteProduct({{ $product->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Product">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
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
