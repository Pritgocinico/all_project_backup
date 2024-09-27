<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Holiday PDF</title>
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
    <h5 style="text-align: center">Holiday List</h5>

    <table class="table">
        <thead>
            <th class="table">#</th>
            <th class="table">Holiday Name</th>
            <th class="table">Holiday Date</th>
            <th class="table">Description</th>
            <th class="table">Status</th>
            <th class="table">Created By</th>
            <th class="table">Created At</th>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @forelse ($holidayList as $key=>$holiday)
                <tr>
                    <td class="table">{{ $i }}</td>
                    <td class="table">{{$holiday->holiday_name}}</td>
                    <td class="table">{{Utility::convertDMYFormat($holiday->holiday_date)}}</td>
                    @php $subject = $holiday->description @endphp
                    @if (strlen($holiday->description) > 30)
                        @php $subject = substr($holiday->description, 0, 30) . '...'; @endphp
                    @endif
                    <td data-bs-toggle="tooltip" data-bs-placement="top" class="table" title="{{ $holiday->description }}">{{ $subject }}</td>
                    <td class="table">
                        @php
                            $text = 'Active';
                            $color = 'success';
                            if ($holiday->status == 0) {
                                $color = 'danger';
                                $text = 'Inactive';
                            }
                        @endphp
                        <span class="badge bg-{{ $color }} w-120">{{ $text }}</span>
                    </td>
                    <td>{{ isset($holiday->userDetail) ? $holiday->userDetail->name :"-" }}</td>
                    <td class="table">{{Utility::convertDmyAMPMFormat($holiday->created_at)}}</td>
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
