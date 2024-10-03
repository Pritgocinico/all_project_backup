<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <title>Department PDF</title>
    <style>
        table{
            width: 100%;
        }
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
        <img alt="Logo" src="{{ $src }}" class="theme-light-show" style="width: 150px"/>
    </div>
    <h5 style="text-align: center">Department List</h5>

    <table class="table">
        <thead>
            <th class="table">No</th>
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
