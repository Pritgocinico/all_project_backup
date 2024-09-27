<table id="" class="table align-middle table-row-dashed fs-6 gy-5" style="width:100%">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px">User</th>
            <th class="min-w-125px">Absent (Days)</th>
            <th class="min-w-125px">Present (Days)</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($employeeAttendanceList as $employee)
            <tr>
                <td>
                    {{-- @if (Permission::checkPermission('employee-view'))    --}}
                    <a href="{{ route('hr-employee-view', $employee->id) }}" class="text-primary">{{ $employee->name }}</a>
                    {{-- @else
                        {{ $employee->name }}
                    @endif --}}

                </td>
                <td>
                   {{$employee->absent_count}}
                </td>
                <td>
                    {{$employee->present_count}}
                </td>
            </tr>
        @empty
            <tr class="text-center">
                <td colspan="3">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $employeeAttendanceList->links('pagination::bootstrap-5') }}
</div>
