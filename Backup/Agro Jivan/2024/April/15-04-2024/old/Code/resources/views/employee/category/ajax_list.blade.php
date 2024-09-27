<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th>Sr. no</th>
            <th class="min-w-125px">Category Image</th>
            <th class="min-w-125px">Category Name</th>
            <th class="min-w-125px">Parent Category Name</th>
            <th class="min-w-125px">Created At</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($categoryList as $key=>$category)
            <tr>
                <td>{{ $categoryList->firstItem() + $key }}</td>
                <td class="align-middle">
                    @php 
                        $image = asset('public/assets/media/default/default.jpg');
                    @endphp
                    @if ($category->category_image !== null && File::exists('public/assets/upload/'.$category->category_image))
                        @php $image = asset('public/assets/upload/'.$category->category_image) @endphp
                    @endif
                    <img src="{{ $image }}" width="50px" alt="">
                </td>
                <td>{{ $category->name }}</td>
                <td>{{ isset($category->categoryDetail) ? $category->categoryDetail->name : '-' }}</td>
                <td>{{ Utility::convertDmyWith12HourFormat($category->created_at) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $categoryList->links('pagination::bootstrap-5') }}
</div>
