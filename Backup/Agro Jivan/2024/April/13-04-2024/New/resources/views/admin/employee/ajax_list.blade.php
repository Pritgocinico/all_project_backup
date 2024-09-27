<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-black fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px">User</th>
            <th class="min-w-125px">Email</th>
            <th class="min-w-125px">Phone Number</th>
            <th class="min-w-125px">Role</th>
            <th class="min-w-125px">Status</th>
            <th class="min-w-125px">Created At</th>
            <th class="min-w-100px">Actions</th>
        </tr>
    </thead>
    <tbody class="text-black-600 fw-semibold">
        @forelse ($employeeList as $key=>$employee)
            <tr>
                <td><a href="{{ route('employees.show', $employee->id) }}" class="pre-agro-emp"> {{ $employee->name }}</a></td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->phone_number }}</td>
                <td>{{ isset($employee->roleDetail) ? $employee->roleDetail->name : '' }}
                </td>
                <td>
                    @php
                        $text = 'Inactive';
                        $class = 'danger';
                    @endphp
                    @if ($employee->status == 1)
                        @php
                            $text = 'Active';
                            $class = 'success';
                        @endphp
                    @endif
                    <div class="badge badge-light-{{ $class }} fw-bold">
                        {{ $text }}</div>
                </td>

                <td>{{ Utility::convertDmyWith12HourFormat($employee->created_at) }}</td>
                <td class="btn-actions">
                    <a class="btn btn-icon btn-secondary-custom w-30px h-30px me-3"
                    href="#" onclick="openOfferModel({{$employee->id}})" data-bs-toggle="tooltip" data-bs-placement="top" title="Generate Offer Letter">
                        <i class="fa-solid fa-certificate"></i>
                    </a>
                    <a class="btn btn-icon btn-info w-30px h-30px me-3"
                    href="{{ route('employees.edit', $employee->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Employee">
                        <i class="fa-solid fa-edit"></i>
                    </a>
                    <a class="btn btn-icon btn-danger w-30px h-30px me-3"
                        onclick="deleteEmployee({{ $employee->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Employee">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
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
    {{ $employeeList->links('pagination::bootstrap-5') }}
</div>
