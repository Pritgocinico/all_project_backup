<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px">Sr. No</th>
            <th class="min-w-125px">Department Name</th>
            <th class="min-w-125px">Status</th>
            <th class="min-w-125px">Created At</th>
            <th class="w-100px">Actions</th>
        </tr>
    </thead>
    <tbody class="text-black-600 fw-semibold">
        @forelse ($departmentList as $key=>$department)
            <tr>
                <td>{{ $departmentList->firstItem() + $key }}</td>
                <td>{{ $department->department_name }}</td>
                <td>
                    @php
                        $text = 'Inactive';
                        $class = 'danger';
                    @endphp
                    @if ($department->status == 1)
                        @php
                            $text = 'Active';
                            $class = 'success';
                        @endphp
                    @endif
                    <div class="badge badge-light-{{ $class }} fw-bold">
                        {{ $text }}</div>
                </td>

                <td>{{ Utility::convertDmyWith12HourFormat($department->created_at) }}</td>
                <td class="btn-actions">
                    <a class="btn btn-icon btn-info w-30px h-30px me-3"
                        href="#" onclick="editDepartment({{$department->id}})" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Department">
                        <i class="fa-solid fa-edit"></i></a>
                    <a class="btn btn-icon btn-danger w-30px h-30px me-3"
                        href="#" onclick="deleteDepartment({{$department->id}})" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Department">
                        <i class="fa-solid fa-trash"></i></a>
                </td>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $departmentList->links('pagination::bootstrap-5') }}
</div>
