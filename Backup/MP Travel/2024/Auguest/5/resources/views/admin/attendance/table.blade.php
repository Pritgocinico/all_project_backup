<table class="table table-hover table-striped table-sm table-nowrap table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Absent(Days)</th>
            <th>Present(Days)</th>
            <th>Total Working Hours</th>
            <th>Total Break Time</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($userList as $key=>$user)
            <tr>
                <td>{{ $userList->firstItem() + $key }}</td>
                <td><a href="{{ route('user.show', $user->id) }}">{{ $user->name }}</a></td>
                <td>{{ $attendanceData[$user->id]['absent'] ?? 0 }}</td>
                <td>{{ $attendanceData[$user->id]['present'] ?? 0 }}</td>
                <td>{{ $attendanceData[$user->id]['totalHour'] ?? 0 }}</td>
                <td>{{ $attendanceData[$user->id]['totalBreak'] ?? 0 }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end me-2 mt-2">
    {{ $userList->links() }}
</div>