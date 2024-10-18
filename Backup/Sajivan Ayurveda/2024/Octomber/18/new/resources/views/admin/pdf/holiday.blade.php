<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <title>Holiday PDF</title>
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
    <h5 style="text-align: center">Holiday List</h5>

    <table class="table">
        <thead>
            <th class="table">No</th>
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
                    <td class="table">{{ isset($holiday->userDetail) ? $holiday->userDetail->name :"-" }}</td>
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
