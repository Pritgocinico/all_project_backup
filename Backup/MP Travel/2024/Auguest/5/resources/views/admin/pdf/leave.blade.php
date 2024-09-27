<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leave PDF</title>
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
    <h5 style="text-align: center">Leave List</h5>

    <table class="table">
        <thead>
            <th class="table">#</th>
            <th class="table">Employee Name</th>
            <th class="table">Leave Type</th>
            <th class="table">Leave From</th>
            <th class="table">Leave To</th>
            <th class="table">Reason</th>
            <th class="table">Leave Statue</th>
            <th class="table">Leave Feature</th>
            <th class="table">Total Leave Day</th>
            <th class="table">Created At</th>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @forelse ($leaveList as $key=>$leave)
                <tr>
                    <td class="table">{{ $i }}</td>
                    <td class="table">{{ ucfirst($leave->userDetail->name) }}</td>
                    <td class="table">{{ $leave->leave_type }}</td>
                    <td class="table">{{ \Carbon\Carbon::parse($leave->leave_from)->format('d-m-Y') }}</td>
                    <td class="table">{{ \Carbon\Carbon::parse($leave->leave_to)->format('d-m-Y') }}</td>
                    <td class="table">{{ $leave->reason }}</td>
                    <td class="table">
                        @php
                            $status = 'pending';
                            $class = 'warning';
                            if ($leave->leave_status == 1) {
                                $status = 'Approved';
                                $class = 'success';
                            } elseif ($leave->leave_status == 2) {
                                $status = 'Rejected';
                                $class = 'danger';
                            }
                        @endphp
                        <span class="badge bg-{{ $class }}">{{ $status }}</span>
                    </td>
                    <td class="table">
                        @php
                            $text = 'Full Day';
                            $class = 'warning';
                            if ($leave->leave_feature == 0) {
                                $text = 'Half Day';
                                $class = 'success';
                            }
                        @endphp
                        <span class="badge bg-{{ $class }}">{{ $text }}</span>
                    </td>
                    <td class="table">{{ $leave->total_leave_day }}</td>
                    <td class="table">{{Utility::convertDmyAMPMFormat($holiday->created_at)}}</td>
                </tr>
                @php
                    $i++;
                @endphp
            @empty
                <tr>
                    <td style="text-center" colspan="10">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
