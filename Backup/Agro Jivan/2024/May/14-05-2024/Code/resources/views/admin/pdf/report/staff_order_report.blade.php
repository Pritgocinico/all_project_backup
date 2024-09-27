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
                <th class="min-w-125px table">User Name</th>
                <th class="min-w-125px table">Pending Order</th>
                <th class="min-w-125px table">Confirm Order</th>
                <th class="min-w-125px table">On Delivery Order</th>
                <th class="min-w-125px table">Cancel Order</th>
                <th class="min-w-125px table">Return Order</th>
                <th class="min-w-125px table">Delivered Order</th>
                <th class="min-w-125px table">Total Order</th>
                <th class="min-w-125px table">Total Amount</th>
            </tr>
        </thead>
        <tbody class="text-gray-600">
            @forelse ($orderList as $key=>$order)
                <tr class="table">
                    <td class="table">{{$order->name}}</td>
                    <td class="table">{{$order->pending_order_count}}</td>
                    <td class="table">{{$order->confirm_orders_count}}</td>
                    <td class="table">{{$order->on_delivery_order_count}}</td>
                    <td class="table">{{$order->cancel_order_count}}</td>
                    <td class="table">{{$order->return_order_count}}</td>
                    <td class="table">{{$order->complete_order_count}}</td>
                    <td class="table">{{$order->all_order_count}}</td>
                    <td>
                        {{$order->complete_order_sum_amount}}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
