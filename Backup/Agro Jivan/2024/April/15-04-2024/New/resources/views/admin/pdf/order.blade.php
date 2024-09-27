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
    <h5 style="text-align: center">Order List</h5>

    <table class="table align-middle table-row-dashed fs-6 gy-5">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0 table">
                <th class="min-w-125px table">Order ID</th>
                <th class="min-w-125px table">Customer Name</th>
                <th class="min-w-125px">Phone Number</th>
                <th class="min-w-125px table">Amount</th>
                <th class="min-w-125px table">District</th>
                <th class="min-w-125px table">Created By</th>
                <th class="text-end min-w-100px table">Status</th>
                <th class="text-end min-w-100px table">Created AT</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 ">
            @forelse ($orderList as $key=>$order)
                <tr>
                    <td class="align-middle table">
                        {{ $order->order_id }}
                    </td>
                    <td class="table">{{ $order->customer_name }}</td>
                    <td class="table">{{ $order->phoneno }}</td>
                    <td class="table">{{ $order->amount }}</td>
                    <td class="table">{{ isset($order->districtDetail) ? $order->districtDetail->district_name : '' }}</td>

                    <td class="table">{{ isset($order->userDetail) ? $order->userDetail->name : '' }}</td>
                    <td class="table">
                        @if ($order->order_status == 1)
                            @php
                                $status = 'warning';
                                $text = 'Pending Order';
                            @endphp
                        @endif
                        @if ($order->order_status == 2)
                            @php
                                $status = 'success';
                                $text = 'Confirmed';
                            @endphp
                        @endif
                        @if ($order->order_status == 3)
                            @php
                                $status = 'info';
                                $text = 'On Deliver';
                            @endphp
                        @endif
                        @if ($order->order_status == 4)
                            @php
                                $status = 'danger';
                                $text = 'Cancel';
                            @endphp
                        @endif
                        @if ($order->order_status == 5)
                            @php
                                $status = 'danger';
                                $text = 'Returned';
                            @endphp
                        @endif
                        @if ($order->order_status == 6)
                            @php
                                $status = 'success';
                                $text = 'Completed';
                            @endphp
                        @endif
                        <span class="badge bg-{{ $status }}">{{ $text }}</span>
                    </td>

                    <td class="table">{{ Utility::convertDmyWith12HourFormat($order->created_at) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center table">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
