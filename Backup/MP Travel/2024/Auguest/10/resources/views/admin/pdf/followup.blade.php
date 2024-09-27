<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Followup PDF</title>
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
    <h5 style="text-align: center">Followup List</h5>

    <table class="table">
        <thead>
            <th class="table">#</th>
            <th class="table">Event Name</th>
            <th class="table">Created By</th>
            <th class="table">Created At</th>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @forelse ($followUpList as $key=>$follow)
                <tr>
                    <td class="table">{{ $i }}</td>
                    <td class="table">{{ $follow->event_name }}</td>
                    <td class="table">{{ isset($follow->userDetail) ? $follow->userDetail->name : '-' }}</td>
                    <td class="table">{{ Utility::convertDmyAMPMFormat($follow->created_at) }}</td>
                </tr>
                @php
                    $i++;
                @endphp
            @empty
                <tr>
                    <td style="text-center" colspan="4">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
