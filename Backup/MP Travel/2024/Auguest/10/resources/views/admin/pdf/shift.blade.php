<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lead PDF</title>
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
        <img alt="Logo"
            src="https://trustedstaging.com/mpgroup-crm/storage/logo/Dlp1T9XBXCGtcB79Q0f92ybOv5X4lqcrU219iJLn.png"
            class="theme-light-show" style="width: 150px;" />
    </div>
    <h5 style="text-align: center">Shift List</h5>

    <table class="table">
        <thead>
            <th class="table">#</th>
            <th class="table">Shift Name</th>
            <th class="table">Shift Code</th>
            <th class="table">Shift Start Time</th>
            <th class="table">Shift End Time</th>
            <th class="table">Created By</th>
            <th class="table">Created At</th>
        </thead>
        <tbody>
            @forelse ($shiftTimeList as $key=>$shift)
                <tr>
                    <td class="table">{{ $key +1 }}</td>
                    <td class="table">{{ $shift->shift_name }}</td>
                    <td class="table">{{ $shift->shift_code }}</td>
                    <td class="table">{{ Utility::convertHIAFormat($shift->shift_start_time) }}</td>
                    <td class="table">{{ Utility::convertHIAFormat($shift->shift_end_time) }}</td>
                    <td class="table">{{ isset($shift->userDetail) ? $lead->userDetail->name : '-' }}</td>
                    <td class="table">{{ Utility::convertDmyAMPMFormat($shift->created_at) }}</td>
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
