<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th>Sr. no</th>
            <th class="min-w-100px">Order ID</th>
            <th class="min-w-100px">Customer</th>
            <th class="min-w-100px">Phone Number</th>
            @if(Auth()->user() !== null && (Auth()->user()->role_id== 1 || Auth()->user()->role_id == 9 || Auth()->user()->role_id == 2))
            <th class="min-w-100px">Product Detail</th>
            @endif
            <th class="min-w-100px">Amount</th>
            <th class="min-w-100px">District</th>
            <th class="min-w-100px">Created By</th>
            <th class="min-w-100px">Status</th>
            <th class="min-w-100px">Created AT</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($cancel_order as $key=>$order)
            <tr>
                <td>{{ $cancel_order->firstItem() + $key }}</td>
                <td class="align-middle">
                    <a href="{{ route('employee-orders.show', $order->id) }}" class="pre-agro-emp">{{ $order->order_id }}</a>
                    @if($order->order_id !== null){!! DNS1D::getBarcodeHTML($order->order_id, 'C128', 1.4, 22) !!} @endif
                </td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->phoneno }}
                    @if (count($order->numberOrder) >= 3)
                        <span class="badge bg-success">VIP</span>
                    @endif
                </td>
                @if(Auth()->user() !== null && (Auth()->user()->role_id== 1 || Auth()->user()->role_id == 9 || Auth()->user()->role_id == 2))
                <td>
                    @foreach ($order->orderItem as $item)
                        {{isset($item->productDetail) ? $item->productDetail->product_name: ""}} [{{isset($item->varientDetail) ? $item->varientDetail->sku_name: ""}} - {{$item->quantity}}]<br />   
                    @endforeach
                </td>
                @endif
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
                            $text = 'Completed';
                        @endphp
                    @endif

                    <span class="badge bg-{{ $status }}">{{ $text }}</span><br />
                    {{$order->cancel_reason}}
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
    {{ $cancel_order->links('pagination::bootstrap-5') }}
</div>
