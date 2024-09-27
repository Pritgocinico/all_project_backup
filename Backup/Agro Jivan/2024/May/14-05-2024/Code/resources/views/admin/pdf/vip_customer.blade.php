<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leave PDF</title>
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
    <h5 style="text-align: center">VIP Customer List</h5>

    <table class="table" id="kt_table_users">
        <thead>
            <tr class="table">
                <th class="table">Customer Name</th>
                <th class="table">Phone Number</th>
                <th class="table">State</th>
                <th class="table">District</th>
                <th class="table">Sub District</th>
                <th class="table">Village</th>
                <th class="table">Total Orders</th>
            </tr>
        </thead>
        <tbody class="text-gray-600">
            @forelse ($customerList as $key=>$customer)
                <tr>
                    <td class="table">{{ $customer->customer_name }}</td>
                    <td class="table">{{ $customer->phoneno }}</td>
                    <td class="table">{{ isset($customer->stateDetail) ? $customer->stateDetail->name : '-' }}</td>
                    <td class="table">{{ isset($customer->districtDetail) ? $customer->districtDetail->district_name : '-' }}</td>
                    <td class="table">{{ isset($customer->subDistrictDetail) ? $customer->subDistrictDetail->sub_district_name : '-' }}</td>
                    <td class="table">{{ isset($customer->villageDetail) ? $customer->villageDetail->village_name : '-' }}</td>
                    <td class="table">{{ $customer->total }}</td>
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
