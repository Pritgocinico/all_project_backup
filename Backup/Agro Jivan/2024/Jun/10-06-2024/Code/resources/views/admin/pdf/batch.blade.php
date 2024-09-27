<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Category PDF</title>
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
    <h5 style="text-align: center">Batch List</h5>

    <table class="table" id="kt_table_users">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th class="table">Batch Id</th>
                <th class="table">Sub District Name</th>
                <th class="table">Driver Name</th>
                <th class="table">Car Number</th>
                <th class="table">Status</th>
                <th class="table">Created At</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 ">
            @forelse ($batchList as $key=>$batch)
                <tr>
                    <td class="align-middle table">
                        {{ $batch->batch_id }}
                    </td>
                    <td class="table">
                        @php $subDistrictIdArray = [];
                $count = 0;
                @endphp
                @foreach ($batch->batchItemDetail as $key=>$item)
                @if (isset($item->orderDetail))
                @if (isset($item->orderDetail->subDistrictDetail))
                @if(!in_array($item->orderDetail->sub_district,$subDistrictIdArray))
                {{ $item->orderDetail->subDistrictDetail->sub_district_name }},
                @php $count++; @endphp
                @if ($count == 4)
                <br>
                @php
                $count = 0;
                @endphp
                @endif
                
                @endif
                @endif
                @php array_push($subDistrictIdArray,$item->orderDetail->sub_district) @endphp
                @endif

                @endforeach
                    </td>
                    <td class="table">{{ isset($batch->driverDetail) ? $batch->driverDetail->name : '' }}</td>
                    <td class="table">{{ $batch->car_no !== null ? $batch->car_no : '-' }}</td>
                    <td  class="table">
                        @php
                            $text = 'On Delivery';
                        @endphp

                        @if ($batch->status == '2')
                            @php
                                $text = 'Deliverd';
                            @endphp
                        @endif
                        {{ $text }}
                    </td>
                    <td class="table">{{ Utility::convertDmyWith12HourFormat($batch->created_at) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
