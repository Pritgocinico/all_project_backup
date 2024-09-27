<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px">Order ID</th>
            <th class="min-w-125px">Customer</th>
            <th class="w-150px">Action</th>
            <th class="w-150px">Phone Number</th>
            <th class="min-w-125px">Amount</th>
            <th class="min-w-125px">District</th>
            <th class="min-w-100px">Created By</th>
            <th class="min-w-100px">Status</th>
            <th class="min-w-100px">Created AT</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($orderList as $key=>$order)
            <tr>
                <td>
                    {{-- <a href="{{route('orders.show',$order->id)}}"> --}}
                    {{ $order->order_id }}
                </td>
                <td>{{ $order->customer_name }}</td>
                <td>
                    @if(Permission::checkPermission('driver-order-status-update'))
                    <a class="btn btn-icon w-30px h-30px me-3 driver_button_color" href="#" onclick="OrderStatusUpdate({{ $order->id }})" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Complete Order" data-bs-original-title="Complete Order" aria-describedby="tooltip553274">
                        <i class="fas fa-check"></i>
                    </a>
                    <a class="btn btn-icon btn-danger w-30px h-30px me-3" href="#" onclick="cancelOrderStatus({{ $order->id }})" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Return Order" data-bs-original-title="Return Order">
                        <i class="fa fa-times"></i>
                    </a>
                    @endif
                </td>
                <td>{{ $order->phoneno }}
                    @if(count($order->numberOrder) >= 3)
                    <span class="badge bg-success">VIP</span>
                    @endif
                </td>
                <td>&#x20B9; {{ $order->amount }}</td>
                <td>{{ isset($order->districtDetail) ? $order->districtDetail->district_name : '' }}</td>

                <td>{{ isset($order->userDetail) ? $order->userDetail->name : '' }}</td>
                <td>
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

                <td>{{ Utility::convertDmyWith12HourFormat($order->created_at) }}</td>
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
