<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-black fw-bold fs-7 text-uppercase gs-0">
            <th>Sr. no</th>
            <th class="min-w-125px">Scheme Name</th>
            <th class="min-w-125px">Discount Code</th>
            <th class="min-w-125px">Product Name</th>
            <th class="min-w-125px">Free Product</th>
            <th class="min-w-125px">Discount Percentage</th>
            <th class="min-w-125px">Created At</th>
            <th class="w-100px">Actions</th>
        </tr>
    </thead>
    <tbody class="text-black-600 fw-semibold">
        @forelse ($schemeList as $key=>$scheme)
            <tr>
                <td>{{ $schemeList->firstItem() + $key }}</td>
                <td>{{ isset($scheme->discountTypeDetail) ? $scheme->discountTypeDetail->title : '' }}</td>
                <td>{{ $scheme->discount_code }}</td>
                <td>
                    @foreach ($scheme->discountItemDetail as $key=>$discountItem)
                        {{isset($discountItem->productDetail)?$discountItem->productDetail->sku_name : "";}} <br />
                    @endforeach
                </td>
                <td>
                    @foreach ($scheme->discountItemDetail as $discountItem)
                        {{isset($discountItem->freeProductDetail)?$discountItem->freeProductDetail->sku_name : "";}} <br />
                    @endforeach
                </td>
                <td>{{ $scheme->discount_percentage !== 0?$scheme->discount_percentage:"100" }} %</td>
                <td>{{ Utility::convertDmyWith12HourFormat($scheme->created_at) }}</td>
                <td class="btn-actions">
                        <a class="btn btn-icon btn-info w-30px h-30px me-3"
                            href="{{ route('scheme.edit', $scheme->id) }}" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="Edit Scheme" data-bs-original-title="Edit Scheme"
                            aria-describedby="tooltip553274">
                            <i class="fa-solid fa-edit"></i>
                        </a>
                        <a class="btn btn-icon btn-danger w-30px h-30px me-3" href="#"
                            onclick="deleteScheme({{ $scheme->id }})" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="Delete Scheme"
                            data-bs-original-title="Delete Scheme">
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
    {{ $schemeList->links('pagination::bootstrap-5') }}
</div>
