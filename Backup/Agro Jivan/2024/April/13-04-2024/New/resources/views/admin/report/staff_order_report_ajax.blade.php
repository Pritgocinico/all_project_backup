<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
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
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($orderList as $key=>$order)
            <tr>
                <td>{{$order->name}}</td>
                <td>{{$order->pending_order_count}}</td>
                <td>{{$order->confirm_order_count}}</td>
                <td>{{$order->on_delivery_order_count}}</td>
                <td>{{$order->cancel_order_count}}</td>
                <td>{{$order->return_order_count}}</td>
                <td>{{$order->complete_order_count}}</td>
                <td>{{$order->all_order_count}}</td>
                <td>
                    @php $totalAmount = 0; @endphp
                    @foreach ($order->completeOrder as $complete)
                        @php $totalAmount += $complete->amount; @endphp
                    @endforeach
                    &#x20B9; {{$totalAmount}}
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
