<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Customer PDF</title>
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
    <h5 style="text-align: center">Customer List</h5>

    <table class="table">
        <thead>
            <th class="table">#</th>
            <th class="table">Name</th>
            <th class="table">Email</th>
            <th class="table">Phone Number</th>
            <th class="table">Role Name</th>
            <th class="table">Created At</th>
            <th class="table">Created By</th>
        </thead>
        <tbody>
            @forelse ($customerList as $key=>$customer)
                <tr>
                    <td class="table">{{ $key +1 }}</td>
                    <td class="table">{{$customer->name}}</td>
                    <td class="table">{{$customer->email}}</td>
                    <td class="table">{{$customer->mobile_number}}</td>
                    <td class="table">{{isset($customer->roleDetail) ? ucfirst($customer->roleDetail->name) : ''}}</td>
                    <td class="table">{{isset($customer->userDetail) ? ucfirst($customer->userDetail->name) : ''}}</td>
                    <td class="table">{{Utility::convertDmyAMPMFormat($customer->created_at)}}</td>
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
