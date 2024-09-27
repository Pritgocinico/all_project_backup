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
    <h5 style="">Hello {{$businessData->client->name}},</h5> <br />
    <h5 style="">
        New business add of below table Details.
    </h5>
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
</body>

</html>
