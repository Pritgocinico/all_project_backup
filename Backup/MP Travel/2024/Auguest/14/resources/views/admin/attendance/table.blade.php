<table class="table table-hover table-sm table-nowrap table-responsive mt-6 border" id="attendance_table">
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
                <td>{{ $key + 1 }}</td>
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