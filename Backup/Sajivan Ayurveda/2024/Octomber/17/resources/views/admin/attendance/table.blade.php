<table class="table table-hover table-sm table-scrolling table-nowrap table-responsive mt-6 border" id="attendance_table">
    <thead>
        <tr>
            <th>No</th>
            <th>User</th>
            <th>Absent(Days)</th>
            <th>Present(Days)</th>
            <th>Total Working Hours</th>
            @if ($type == 1 && Auth()->user()->role_id == 1)
                <th>Action</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($userList as $key => $user)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>
                    <a href="{{ route('user.show', $user->id) }}">{{ $user->name }}
                        @if ($type == 1)
                            @php
                                $text = 'Absent';
                                $color = 'danger';
                                if ($attendanceData[$user->id]['attendance_status'] == "1") {
                                    $color = 'success';
                                    $text = 'Present';
                                }
                                if ($attendanceData[$user->id]['attendance_status'] == "2") {
                                    $color = 'success';
                                    $text = 'Half Day - Present';
                                }
                            @endphp
                            <span class="badge bg-{{ $color }} w-120">{{ $text }}</span>
                        @endif
                    </a>
                </td>
                <td>{{ $attendanceData[$user->id]['absent'] ?? 0 }}</td>
                <td>{{ $attendanceData[$user->id]['present'] ?? 0 }}</td>
                <td>{{ $attendanceData[$user->id]['totalHour'] ?? 0 }}</td>
                @if ($type == 1 && Auth()->user()->role_id == 1)
                    <td>
                        <a href="javascript:void(0)" onclick="editAttendance({{ $user->id }},1)" class="{{$attendanceData[$user->id]['attendance_status'] == "1" ? 'd-none' : ""}}"  data-bs-toggle="tooltip" data-bs-placement="top" title="Present Attendance"><i class="fa-solid fa-clock"></i></a>
                        <a href="javascript:void(0)" onclick="editAttendance({{ $user->id }},0)" class="{{$attendanceData[$user->id]['attendance_status'] == "0" ? 'd-none' : ""}}"  data-bs-toggle="tooltip" data-bs-placement="top" title="Absent Attendance"><i class="fa-regular fa-clock"></i></a>
                        <a href="javascript:void(0)" onclick="editAttendance({{ $user->id }},2)" class="{{$attendanceData[$user->id]['attendance_status'] == "2" ? 'd-none' : ""}}" data-bs-toggle="tooltip" data-bs-placement="top" title="Half Day Attendance"><i class="fa-solid fa-user-clock"></i></a>
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
