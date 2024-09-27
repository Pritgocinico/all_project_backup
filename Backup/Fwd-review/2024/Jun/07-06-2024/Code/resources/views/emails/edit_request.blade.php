<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <style>
        .table {
            border: 1px solid black;
        }

        .theme-light-show {
            background: black;
        }
    </style>
</head>

<body>
    <div style="text-align: center">
        <img alt="Logo" src="{{ asset($setting->logo) }}" class="theme-light-show " style="width: 150px;" />
    </div>
    @if ($type == 0)
        <h5 style="text-align: center">Old Business Detail</h5>
    @elseif ($type == 2)
        <h5 style="text-align: center">Stop Subscription Business Detail</h5>
    @else
        <h5 style="text-align: center">Updated Business Detail</h5>
    @endif
    <table class="table" id="kt_table_users">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th class="table">Business Name</th>
                <th class="table">Short Name</th>
                <th class="table">Client Name</th>
                <th class="table">Purchase From</th>
                <th class="table">Plan Title</th>
                <th class="table">Plan Amount</th>
                <th class="table">Place Id</th>
                <th class="table">Api Key</th>
                <th class="table">Sub End Date</th>
                <th class="table">Created At</th>
            </tr>
        </thead>
        <tbody class="text-gray-600">
            <tr>
                <td class="align-middle table">{{ $businessData->name }}</td>
                <td class="table">{{ $businessData->shortname }}</td>
                <td class="table">{{ $businessData->client->name }}</td>
                <td class="table">{{ $businessData->add_type }}</td>
                <td class="table">{{ $businessData->planDetail->plan_title }}</td>
                <td class="table">$ {{ number_format($businessData->planDetail->price, 2) }}</td>
                <td class="table">{{ $businessData->place_id }}</td>
                <td class="table">{{ $businessData->api_key }}</td>
                <td class="table">{{ date('Y-m-d', strtotime($businessData->sub_end_date)) }}</td>
                <td class="table">{{ date('Y-m-d h:i:s A', strtotime($businessData->created_at)) }}</td>
            </tr>

        </tbody>
    </table>
    @if ($type == 0)
        <h5 style="text-align: center">New Business Detail For Update</h5>

        <table class="table" id="kt_table_users">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="table">Business Name</th>
                    <th class="table">Short Name</th>
                    <th class="table">Purchase From</th>
                    <th class="table">Place Id</th>
                    <th class="table">Api Key</th>
                </tr>
            </thead>
            <tbody class="text-gray-600">
                <tr>
                    <td class="align-middle table">{{ $businessRequestDetail->name }}</td>
                    <td class="table">{{ $businessRequestDetail->shortname }}</td>
                    <td class="table">{{ $businessData->add_type }}</td>
                    <td class="table">{{ $businessRequestDetail->place_id }}</td>
                    <td class="table">{{ $businessRequestDetail->api_key }}</td>
                </tr>

            </tbody>
        </table>
    @endif
</body>

</html>
