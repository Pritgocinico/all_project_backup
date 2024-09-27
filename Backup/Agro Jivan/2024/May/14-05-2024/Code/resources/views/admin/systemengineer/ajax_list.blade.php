<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="w-10px pe-2">No</th>
            <th class="min-w-125px">User</th>
            <th class="min-w-125px">Email</th>
            <th class="min-w-125px">Phone Number</th>
            <th class="min-w-125px">Role</th>
            <th class="min-w-125px">Status</th>
            <th class="min-w-125px">Created At</th>
            <th class="text-end min-w-100px">Actions</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($systemengineerList as $key=>$systemengineer)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $systemengineer->name }}</td>
                <td>{{ $systemengineer->email }}</td>
                <td>{{ $systemengineer->phone_number }}</td>
                <td>{{ isset($systemengineer->roleDetail) ? $systemengineer->roleDetail->name : '' }}
                </td>
                <td>
                    @php
                        $text = 'Inactive';
                        $class = 'danger';
                    @endphp
                    @if ($systemengineer->status == 1)
                        @php
                            $text = 'Active';
                            $class = 'success';
                        @endphp
                    @endif
                    <div class="badge badge-light-{{ $class }} fw-bold">
                        {{ $text }}</div>
                </td>

                <td>{{ Utility::convertDmyWith12HourFormat($systemengineer->created_at) }}</td>
                <td>
                    <a class="btn btn-icon btn-active-light-primary w-30px h-30px me-3"
                        href="{{ route('systemengineer.edit', $employee->id) }}">
                        <img src="{{ asset('public/assets/images/icons/edit.png') }}" width="20px" class="me-2"
                            aria-hidden="true"></img>
                    </a>
                    <a class="btn btn-icon btn-active-light-primary w-30px h-30px me-3" href="#"
                        onclick="deleteEmployee({{ $employee->id }})">
                        <img src="{{ asset('public/assets/images/icons/delete.png') }}" width="20px" class="me-2"
                            aria-hidden="true"></img>
                    </a>
                </td>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $systemengineerList->links('pagination::bootstrap-5') }}
</div>
