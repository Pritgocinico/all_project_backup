<table class="table align-middle table-row-dashed fs-6 gy-5 report_table_border" id="kt_table_users">
    <thead class="report_table_border">
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-100px sr_no_column">Sr. no</th>
            <th class="min-w-50px">Staff Name</th>
            <th class="min-w-50px">Pending Order</th>
            <th class="min-w-125px">Confirm Order</th>
            <th class="min-w-125px">On Delivery Order</th>
            <th class="min-w-125px">Cancel Order</th>
            <th class="min-w-125px">Return Order</th>
            <th class="min-w-125px">Delivered Order</th>
            <th class="min-w-125px">Total Orders</th>
            <th class="min-w-125px">Total Amount</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold report_table_border">
        @forelse ($orderList as $key=>$order)
            <tr>

                <td class="sr_no_column">{{ $orderList->firstItem() + $key }}</td>
                <td>{{$order->name}}</td>
                <td class="fw-bold text-dark">{{$order->pending_order_count}}</td>
                <td class="fw-bold text-dark">{{$order->confirm_orders_count}}</td>
                <td class="fw-bold text-dark">{{$order->on_delivery_order_count}}</td>
                <td class="fw-bold text-dark">{{$order->cancel_order_count}}</td>
                <td class="fw-bold text-dark">{{$order->return_order_count}}</td>
                <td class="fw-bold text-dark">{{$order->complete_order_count}}</td>
                <td class="fw-bold text-dark">{{$order->all_order_count}}</td>
                <td class="fw-bold text-dark">
                    &#x20B9; {{$order->complete_order_sum_amount??0}}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $orderList->links('pagination::bootstrap-5') }}
</div>
