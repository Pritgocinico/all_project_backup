<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Employee PDF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <style>
        .table {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <div style="text-align: center">
        <img alt="Logo" src="{{ asset('public/assets/media/svg/AgroJivanLogoDash.png') }}" class="theme-light-show"
            style="width: 150px;" />
    </div>
    <h5 style="text-align: center">Lead List</h5>

    <table class="table">
        <thead>
            <tr class="table">
                <th class="table">Order ID</th>
                <th class="table">Customer Name</th>
                <th class="table">Phone Number</th>
                <th class="table">Amount</th>
                <th class="table">District</th>
                <th class="table">Created By</th>
                <th class="table">Created AT</th>
            </tr>
        </thead>
        <tbody class="text-gray-600">
            @forelse ($orderList as $key=>$order)
                <tr>

                    <td class="align-middle table">
                        {{ $order->lead_id }}
                    </td>
                    <td class="table">{{ $order->customer_name }}</td>
                    <td class="table">{{ $order->phone_no }}</td>
                    <td class="table">{{ $order->amount }}</td>
                    <td class="table">{{ isset($order->districtDetail) ? $order->districtDetail->district_name : '' }}</td>

                    <td class="table">{{ isset($order->userDetail) ? $order->userDetail->name : '' }}</td>
                    

                    <td class="table">{{ Utility::convertDmyWith12HourFormat($order->created_at) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
