<table class="table table-hover table-striped table-sm table-nowrap table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Absent(Days)</th>
            <th>Present(Days)</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($allAttendanceLists as $key=>$attendance)
            <tr>
                <td>{{ $allAttendanceLists->firstItem() + $key }}</td>
                <td><a href="{{ route('user.show', $attendance->employee->id) }}">{{ $attendance->employee->name }}</a></td>
                <td>{{ $attendance->status == 1 ? 0 : 1 }}</td>
                <td>{{ $attendance->status == 1 ? 1 : 0 }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end me-2 mt-2">
    {{ $allAttendanceLists->links() }}
</div>