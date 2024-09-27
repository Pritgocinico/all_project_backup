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

    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0 table">
                <th class="min-w-125px table">Order ID</th>
                <th class="min-w-125px table">Customer</th>
                <th class="min-w-125px table">Phone Number</th>
                <th class="min-w-125px table">Amount</th>
                <th class="min-w-125px table">District</th>
                <th class="min-w-125px table">Created By</th>
                <th class="min-w-100px table">Status</th>
                <th class="min-w-100px table">Date</th>
            </tr>
        </thead>
        <tbody class="text-gray-600">
            @forelse ($orderList as $key=>$order)
                <tr class="table">
                    <td class="align-middle table">
                        {{ $order->order_id }}
                    </td>
                    <td class="table">{{ $order->customer_name }}</td>
                    <td class="table">{{ $order->phoneno }}
                        @if(count($order->numberOrder) >= 3)
                            <span class="badge bg-success">VIP</span>
                        @endif
                    </td>
                    <td class="table">&#x20B9; {{ $order->amount }}</td>
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
                                $status = 'warning';
                                $text = 'On Delivery';
                            @endphp
                        @endif
                        @if ($order->order_status == 4)
                            @php
                                $status = 'danger';
                                $text = 'Cancelled';
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
                                $text = 'Delivered';
                            @endphp
                        @endif
                        <span class="badge bg-{{ $status }}">{{ $text }}</span>
                    </td>
    
                    <td class="table">
                        @php $date = $order->created_at @endphp
                        @if ($order->order_status == 2)
                            @php
                                $date = $order->confirm_date;
                            @endphp
                        @endif
                        @if ($order->order_status == 4)
                            @php
                                $date = $order->cancel_date;
                            @endphp
                        @endif
                        @if ($order->order_status == 5)
                            @php
                                $date = $order->return_date;
                            @endphp
                        @endif
                        @if ($order->order_status == 6)
                            @php
                                $date = $order->delivery_date;
                            @endphp
                        @endif
                        @if($date !== null)
                            {{ Utility::convertDmyWith12HourFormat($date) }}
                        @else
                            {{'-'}}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
