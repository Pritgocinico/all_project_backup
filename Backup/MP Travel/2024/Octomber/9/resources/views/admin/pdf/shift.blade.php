<!DOCTYPE html>

<html lang="en">



<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Lead PDF</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">

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

$imagePath = public_path('assets/img/login/mp-group.png');
            if(isset($setting) && $setting->logo !== null){
                $imagePath = public_path('storage/'.$setting->logo);
            }

            $imageData = base64_encode(file_get_contents($imagePath));

            $src = 'data:' . mime_content_type($imagePath) . ';base64,' . $imageData;

        @endphp

        <img alt="Logo"

            src="{{$src}}"

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

                    <td class="table">{{ isset($shift->userDetail) ? $shift->userDetail->name : '-' }}</td>

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

