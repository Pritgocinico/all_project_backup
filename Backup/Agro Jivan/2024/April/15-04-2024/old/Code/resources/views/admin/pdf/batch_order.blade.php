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

        footer {
            position: fixed;
            bottom: 0;
            width: 10%;
            text-align: center;
            padding: 10px 0;
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
                <th class="table">Total Orders</th>
                <th class="table">Driver Name</th>
                <th class="table">Mobile Number</th>
                <th class="table">Car Number</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 ">
            <tr>
                <td class="align-middle table">
                    {{ $batchDetail->batch_id }}
                </td>
                <td class="table">{{ count($batchItem) }}</td>
                <td class="table">{{ isset($batchDetail->driverDetail) ? $batchDetail->driverDetail->name : '-' }}</td>
                <td class="table">{{ isset($batchDetail->driverDetail) ? $batchDetail->driverDetail->phone_number : '-' }}</td>
                <td class="table">{{ $batchDetail->car_no }}</td>
            </tr>
        </tbody>
    </table>
    <table class="table" id="kt_table_users">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th class="table">Order Id</th>
                <th class="table">Customer Name</th>
                <th class="table">Amount</th>
                <th class="table">Product Detail</th>
                <th class="table">District Name</th>
                <th class="table">Sub District Name</th>
                <th class="table">Village Name</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 ">
            @forelse ($batchItem as $key=>$order)
                <tr>
                    <td class="align-middle table">
                        {{ isset($order->orderDetail) ? $order->orderDetail->order_id : '' }}
                    </td>
                    <td class="table">
                        {{ isset($order->orderDetail) ? $order->orderDetail->customer_name : '' }}
                    </td>
                    <td class="table">{{ isset($order->orderDetail) ? $order->orderDetail->amount : '' }}</td>
                    <td class="table">
                        @if (isset($order->orderDetail))
                            @if (isset($order->orderDetail->orderItem))
                                @foreach ($order->orderDetail->orderItem as $key => $item)
                                    @if (isset($item->productDetail))
                                        {{ $item->productDetail->product_name }}
                                    @endif
                                    {{ '-' }}
                                    @if ($item->varientDetail)
                                        {{ $item->varientDetail->sku_name }}
                                    @endif
                                    <br />
                                @endforeach
                            @endif
                        @endif
                    </td>
                    <td class="table">
                        @php
                            $district = '';
                            $subDistrict = '';
                            $village = '';
                        @endphp
                        @if (isset($order->orderDetail))
                            @if ($order->orderDetail->districtDetail !== null)
                                @php $district  = $order->orderDetail->districtDetail->district_name @endphp
                            @endif
                            @if ($order->orderDetail->subDistrictDetail !== null)
                                @php $subDistrict  = $order->orderDetail->subDistrictDetail->sub_district_name @endphp
                            @endif
                            @if ($order->orderDetail->villageDetail !== null)
                                @php $village  = $order->orderDetail->villageDetail->village_name @endphp
                            @endif
                        @endif
                        {{ $district }}
                    </td>
                    <td class="table">{{ $subDistrict }}</td>
                    <td class="table">{{ $village }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <footer>
        <hr />
        <h4>Sign</h4>
    </footer>
</body>

</html>
