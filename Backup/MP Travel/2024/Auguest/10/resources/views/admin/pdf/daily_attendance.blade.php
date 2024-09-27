<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daily Attendance PDF</title>
    <style>
        .table {
            border: 1px solid black;
            border-collapse: collapse;
        }

        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Jost:ital,wght@0,100..900;1,100..900&family=Space+Grotesk:wght@300..700&display=swap');

        .cursive {
            font-family: Cambria, Georgia, serif;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div style="text-align: center">
        <img alt="Logo" src="https://trustedstaging.com/mpgroup-crm/storage/logo/Dlp1T9XBXCGtcB79Q0f92ybOv5X4lqcrU219iJLn.png"
            class="theme-light-show" style="width: 150px;" />
    </div>
    <h5 style="text-align: center">Daily Attendance List</h5>

    <table class="table">
        <thead>
            <th class="table">#</th>
            <th class="table">User</th>
            <th class="table">Status</th>
            <th class="table">Login Time</th>
            <th class="table">Logout Time</th>
            <th class="table">Total Hour</th>
            <th class="table">Total Break Time</th>
        </thead>
        <tbody>
            @forelse ($dailyAttendanceLists as $key=>$today_Attendance)
            <tr>
                <td class="table">{{ $key +1 }}</td>
                <td class="table">{{ $today_Attendance->employee->name }}</td>
                <td class="table">
                    @php
                    $text = 'Present';
                    $color = 'success';
                    if ($today_Attendance->status == 0) {
                    $color = 'danger';
                    $text = 'Absent';
                    }
                    @endphp
                    <span class="badge bg-{{ $color }}">{{ $text }}</span>
                </td>
                <td class="table">{{ $today_Attendance->login_time ? Utility::convertDmyAMPMFormat($today_Attendance->login_time) : '-' }}</td>
                <td class="table">{{ $today_Attendance->logout_time ? Utility::convertDmyAMPMFormat($today_Attendance->logout_time) : '-' }}</td>
                <td class="table">{{ $today_Attendance->total_work_hour }}</td>
                <td class="table">{{ $today_Attendance->break_time }}</td>
            </tr>
            @empty
            <tr>
                <td style="text-center" colspan="7">No Data Available.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>