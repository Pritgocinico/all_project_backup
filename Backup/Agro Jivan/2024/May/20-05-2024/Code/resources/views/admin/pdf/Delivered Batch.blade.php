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
    <h5 style="text-align: center">Team List</h5>

    <table class="table" id="kt_table_users">
        <thead>
            <tr class="table">
                <th class="table">Batch ID</th>
                <th class="table">Village Name</th>
                <th class="table">Driver Name</th>
                <th class="table">Status</th>
                <th class="table">Created AT</th>
            </tr>
        </thead>
        <tbody class="text-gray-600">
            @forelse ($batchList as $key=>$batch)
                <tr>
                    <td class="table">{{ $batch->batch_id }}</td>
                    <td class="table">
                        @foreach ($batch->batchItemDetail as $item)
                        @if (isset($item->orderDetail))
                            @if (isset($item->orderDetail->villageDetail))
                                {{ $item->orderDetail->villageDetail->village_name }},
                            @endif
                        @endif
                    @endforeach
                    </td>
                    <td class="table">{{ isset($batch->driverDetail) ? $batch->driverDetail->name : '' }}</td>
                    <td class="table">
                        @php
                        $text = 'On Delivery';
                    @endphp

                    @if ($batch->status == '2')
                        @php
                            $text = 'Delivered';
                        @endphp
                    @endif
                    {{$text}}
                    </td>
                    <td class="table">{{ Utility::convertDmyWith12HourFormat($batch->created_at) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
