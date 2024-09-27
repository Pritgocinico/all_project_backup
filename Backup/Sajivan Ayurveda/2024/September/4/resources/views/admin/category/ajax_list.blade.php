<table class="table table-hover table-sm table-nowrap table-responsive mt-6 border" id="category_table">
    <thead>
        <tr>
            <th>#</th>
            <th>Category Name</th>
            <th>Created By</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categoryList as $key => $category)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ isset($category->userDetail) ? $category->userDetail->name : '-' }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($category->created_at) }}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '20')->first()->status == 2)
                        <a href="javascript:void(0)" class="text-dark" onclick="editCategory({{ $category->id }})"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Category">
                            <i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="javascript:void(0)" class="text-dark" onclick="deleteCategory({{ $category->id }})"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Category">
                            <i class="fa fa-trash-can me-3"></i>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>