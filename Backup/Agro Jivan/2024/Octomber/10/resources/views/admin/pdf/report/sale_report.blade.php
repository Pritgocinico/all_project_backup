<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Employee PDF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <style>
        .table {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <div style="text-align: center">
        <img alt="Logo" src="{{ asset('public/assets/media/svg/AgroJivanLogoDash.png') }}" class="theme-light-show" style="width: 150px;" />
    </div>
    <h5 style="text-align: center">Order List</h5>
    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0 table">
                <th class="min-w-125px table">Product Name</th>
                <th class="min-w-125px table">District Name</th>
                @if($order_sub_district !== null)
                <th class="min-w-125px">Sub District Name</th>
                @endif
                <th class="min-w-125px table">Order</th>
                <th class="min-w-125px table">Quantity</th>
                <th class="min-w-125px table">Amount</th>
                <th class="min-w-100px table">Status</th>
            </tr>
        </thead>
        <tbody class="text-gray-600">
            @php
            $totalQuantity = 0;
            $totalAmount = 0;
            $totalOrder = 0;
            @endphp
            @forelse ($orderList as $key=>$variant)
            <tr class="table">
                <td class="align-middle table">
                    {{ $variant->sku_name }}<br /> {{ '[' . $variant->productDetail->sku_name . ']' }}
                </td>
                <td class="table">
                    @php
                    $districtIdArray = [];
                    $count = 0;
                    @endphp
                    @foreach ($variant->orderItems as $key => $item)
                    @if (isset($item->orderDetail))
                    @if (isset($item->orderDetail->districtDetail))
                    @if (!in_array($item->orderDetail->district, $districtIdArray))
                    {{ $item->orderDetail->districtDetail->district_name }},
                    @php $count++; @endphp
                    @if ($count == 4)
                    <br>
                    @php
                    $count = 0;
                    @endphp
                    @endif
                    @endif
                    @endif
                    @php array_push($districtIdArray,$item->orderDetail->district) @endphp
                    @endif
                    @endforeach
                </td>
                @if($order_sub_district !== null)
                <td>
                    @php
                    $subDistrictIdArray = [];
                    $count = 0;
                    @endphp
                    @foreach ($variant->orderItems as $key => $item)
                    @if (isset($item->orderDetail))
                    @if (isset($item->orderDetail->subDistrictDetail))
                    @if (!in_array($item->orderDetail->sub_district, $subDistrictIdArray))
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
                @endif
                <td class="table">@php $totalOrder = $totalOrder + count($variant->orderItems);@endphp
                    {{ count($variant->orderItems) }}
                </td>
                <td class="table">@php $quantity = 0;$amount = 0;@endphp
                    @foreach ($variant->orderItems as $key => $item)
                    @php $quantity = $quantity + $item->quantity;@endphp
                    @php $totalQuantity = $totalQuantity + $item->quantity;@endphp
                    @php $amount = $amount + $item->amount;@endphp
                    @php $totalAmount = $totalAmount + $item->amount;@endphp
                    @endforeach
                    {{ $quantity }}
                </td>

                <td class="table">${{ number_format($amount, 2) }}</td>

                <td class="table">
                    @if ($order_status == 1)
                    @php
                    $status = 'warning';
                    $text = 'Pending Order';
                    @endphp
                    @endif
                    @if ($order_status == 2)
                    @php
                    $status = 'success';
                    $text = 'Confirmed';
                    @endphp
                    @endif
                    @if ($order_status == 3)
                    @php
                    $status = 'warning';
                    $text = 'On Delivery';
                    @endphp
                    @endif
                    @if ($order_status == 4)
                    @php
                    $status = 'danger';
                    $text = 'Cancelled';
                    @endphp
                    @endif
                    @if ($order_status == 5)
                    @php
                    $status = 'danger';
                    $text = 'Returned';
                    @endphp
                    @endif
                    @if ($order_status == 6)
                    @php
                    $status = 'success';
                    $text = 'Delivered';
                    @endphp
                    @endif
                    <span class="badge bg-{{ $status }}">{{ $text }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">No Data Available.</td>
            </tr>
            @endforelse
            <tr>
            <td colspan="3" class="text-end"><b>Total</b></td>
            <td>{{ $totalOrder }}</td>
            <td>{{ $totalQuantity }}</td>
            <td>${{ number_format($totalAmount, 2) }}</td>
        </tr>
        </tbody>
    </table>
</body>

</html>