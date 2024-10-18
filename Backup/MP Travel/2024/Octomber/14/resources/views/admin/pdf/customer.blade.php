<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <title>Customer PDF</title>
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
    <img alt="Logo" src="{{$src}}"
                    class="theme-light-show" style="width: 150px;" />
                </div>
    <h5 style="text-align: center">Customer List</h5>

    <table class="table">
        <thead>
            <th class="table">#</th>
            <th class="table">Name</th>
            <th class="table">Phone Number</th>
            <th class="table">Created At</th>
            <th class="table">Created By</th>
            <th class="table">Last Modified At</th>
            <th class="table">Last Modified By</th>
        </thead>
        <tbody>
            @forelse ($customerList as $key=>$customer)
                <tr>
                    <td class="table">{{ $key +1 }}</td>
                    <td class="table">{{$customer->name}}</td>
                    <td class="table">{{$customer->mobile_number}}</td>
                    <td class="table">{{isset($customer->userDetail) ? ucfirst($customer->userDetail->name) : ''}}</td>
                    <td class="table">{{Utility::convertDmyAMPMFormat($customer->created_at)}}</td>
                    <td class="table">{{ isset($customer->moduleModifiedLog) && isset($customer->moduleModifiedLog->userDetail) ? $customer->moduleModifiedLog->userDetail->name : '-' }}</td>
                    <td class="table">{{ isset($customer->moduleModifiedLog) ? Utility::convertDmyAMPMFormat($customer->moduleModifiedLog->created_at)  : "-"}}</td>
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
