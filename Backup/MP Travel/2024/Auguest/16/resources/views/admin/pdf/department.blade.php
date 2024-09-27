<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Department PDF</title>
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
    <h5 style="text-align: center">Department List</h5>

    <table class="table">
        <thead>
            <th class="table">#</th>
            <th class="table">Department Name</th>
            <th class="table">Created By</th>
            <th class="table">Created At</th>
        </thead>
        <tbody>
            @forelse ($departList as $key=>$department)
                <tr>
                    <td class="table">{{ $key +1 }}</td>
                    <td class="table">{{ $department->name }}</td>
                    <td class="table">{{ isset($department->userDetail) ? $department->userDetail->name : '-' }}</td>
                    <td class="table">{{ Utility::convertDmyAMPMFormat($department->created_at) }}</td>
                </tr>
            @empty
                <tr>
                    <td style="text-center" colspan="4">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
