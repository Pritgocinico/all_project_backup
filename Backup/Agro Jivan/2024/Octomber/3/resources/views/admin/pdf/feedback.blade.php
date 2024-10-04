<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Feedback PDF</title>
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
    <h5 style="text-align: center">Feedback List</h5>

    <table class="table">
        <thead>
            <th class="table">Order ID</th>
            <th class="table">District Name</th>
            <th class="table">Sub District Name</th>
            <th class="table">Rating</th>
            <th class="table">Description</th>
            <th class="table">Created At</th>
        </thead>
        <tbody>
            @forelse ($feedbackList as $feedback)
                <tr>
                    <td class="table">{{ isset($feedback->orderDetail) ? $feedback->orderDetail->order_id : '-' }}</td>
                    <td class="table">
                        @if (isset($feedback->orderDetail) && isset($feedback->orderDetail->districtDetail))
                            {{ $feedback->orderDetail->districtDetail->district_name }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="table">
                        @if (isset($feedback->orderDetail) && isset($feedback->orderDetail->subDistrictDetail))
                            {{ $feedback->orderDetail->subDistrictDetail->sub_district_name }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="table">
                        {{$feedback->rating ?? 0}}
                    </td>
                    <td class="table">
                        {{ $feedback->order_description }}
                    </td>
                    <td class="table">{{ Utility::convertDmyWith12HourFormat($feedback->created_at) }}</td>
                </tr>
            @empty
                <tr>
                    <td style="text-center" colspan="6">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
