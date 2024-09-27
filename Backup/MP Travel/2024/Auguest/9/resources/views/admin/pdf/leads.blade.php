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
    <img alt="Logo" src="https://trustedstaging.com/mpgroup-crm/storage/logo/Dlp1T9XBXCGtcB79Q0f92ybOv5X4lqcrU219iJLn.png"
                    class="theme-light-show" style="width: 150px;" />
                </div>
    <h5 style="text-align: center">Lead List</h5>

    <table class="table">
        <thead>
            <th class="table">#</th>
            <th class="table">Lead</th>
            <th class="table">Customer Name</th>
            <th class="table">Lead Amount</th>
            <th class="table">Status</th>
            <th class="table">Created At</th>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @forelse ($leads as $key=>$lead)
                <tr>
                    <td class="table">{{ $i }}</td>
                    <td class="table">{{ $lead->lead_id }}</td>
                    <td class="table">{{ isset($lead->customerDetail) ? $lead->customerDetail->name : '-' }}</td>
                    <td class="table">&#x20B9; {{ number_format($lead->lead_amount ?? 0,2) }}</td>
                    <td class="table">
                        @php
                        $status = 'warning';
                        $text = 'Pending Lead';
                        if ($lead->lead_status == 2) {
                            $status = 'info';
                            $text = 'Assigned Lead';
                        }
                        if ($lead->lead_status == 3) {
                            $status = 'secondary';
                            $text = 'Hold Lead';
                        }
                        if ($lead->lead_status == 4) {
                            $status = 'success';
                            $text = 'Complete Lead';
                        }
                        if ($lead->lead_status == 5) {
                            $status = 'warning';
                            $text = 'Extends Lead';
                        }
                        if ($lead->lead_status == 6) {
                            $status = 'danger';
                            $text = 'Cancel Lead';
                        }
                    @endphp
                    <span class="badge bg-{{ $status }}">{{ $text }}</span>
                    </td>
                    <td class="table">{{Utility::convertDmyAMPMFormat($lead->created_at)}}</td>
                </tr>
                @php
                    $i++;
                @endphp
            @empty
                <tr>
                    <td style="text-center" colspan="9">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
