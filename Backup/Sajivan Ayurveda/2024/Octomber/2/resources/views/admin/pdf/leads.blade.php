<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <title>Lead PDF</title>
    <style>
        .table {
            border: 1px solid black;
            border-collapse: collapse;
        }
        body {
            font-family: "Lexend", sans-serif !important;
        }
    </style>
</head>

<body>
    <div style="text-align: center">
        @php
        $imagePath = public_path('assets/img/login/sajivan-logo.png');
            if(isset($setting) && $setting->logo !== null){
                $imagePath = public_path('storage/'.$setting->logo);
            }
        $imageData = base64_encode(file_get_contents($imagePath));
        $src = 'data:' . mime_content_type($imagePath) . ';base64,' . $imageData;
    @endphp
    <img alt="Logo" src="{{$src}}"
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
            <th class="table">Created By</th>
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
                    <td class="table">{{ number_format($lead->lead_amount ?? 0,2) }}</td>
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
                    <td class="table">{{  isset($lead->userDetail) ? $lead->userDetail->name :"-" }}</td>
                    <td class="table">{{Utility::convertDmyAMPMFormat($lead->created_at)}}</td>
                </tr>
                @php
                    $i++;
                @endphp
            @empty
                <tr>
                    <td style="text-center" colspan="7">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
