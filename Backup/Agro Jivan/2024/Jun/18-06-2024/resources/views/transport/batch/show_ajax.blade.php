<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px">Order ID</th>
            <th class="min-w-125px">Customer</th>
            <th class="min-w-125px">Phone Number</th>
            <th class="min-w-125px">Amount</th>
            @if(Auth()->user() !== null && (Auth()->user()->role_id== 1 || Auth()->user()->role_id == 9 || Auth()->user()->role_id == 2))
            <th class="min-w-125px">Product Detail</th>
            @endif
            <th class="min-w-125px">District</th>
            <th class="min-w-125px">Created By</th>
            <th class="min-w-100px">Status</th>
            <th class="min-w-100px">Created AT</th>
            <th class="min-w-100px">Action</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($batchItem as $key=>$order)
            <tr>
                <td class="align-middle">
                    @php $orderID= isset($order->orderDetail) ? $order->orderDetail->id : ''; @endphp
                    @php $route = route('order-view', $orderID) @endphp
                    @if(Auth()->user() !== null && Auth()->user()->role_id == 1)
                        @php $route = route('orders.show', $orderID) @endphp
                    @endif
                    <a href="{{ $route }}" class="pre-agro-emp">
                        {{ isset($order->orderDetail) ? $order->orderDetail->order_id : '' }}</a>
                        @if (isset($order->orderDetail))
                        {!! DNS1D::getBarcodeHTML($order->orderDetail->order_id, 'C128', 0.8, 22) !!}
                    @endif
                </td>
                <td>{{ isset($order->orderDetail) ? $order->orderDetail->customer_name : '' }}</td>
                <td>{{ isset($order->orderDetail) ? $order->orderDetail->phoneno : '' }}</td>
                <td>&#x20B9; {{ isset($order->orderDetail) ? $order->orderDetail->amount : '' }}</td>
                @if(Auth()->user() !== null && (Auth()->user()->role_id== 1 || Auth()->user()->role_id == 9 || Auth()->user()->role_id == 2))
                <td>
                    @if(isset($order->orderDetail))
                    @foreach ($order->orderDetail->orderItem as $item)
                        {{isset($item->productDetail) ? $item->productDetail->product_name: ""}} [{{isset($item->varientDetail) ? $item->varientDetail->sku_name: ""}} - {{$item->quantity}}]<br />   
                    @endforeach
                    @endif
                </td>
                @endif
                <td>{{ isset($order->orderDetail) ? isset($order->orderDetail->districtDetail)?$order->orderDetail->districtDetail->district_name:"" : '' }}</td>

                <td>{{ isset($order->orderDetail) ? isset($order->orderDetail->userDetail)?$order->orderDetail->userDetail->name:"" : '' }}</td>
                <td>
                    @php
                        $status = 'warning';
                        $text = 'Pending Order';
                    @endphp
                    @if (isset($order->orderDetail))
                        @if ($order->orderDetail->order_status == 1)
                            @php
                                $status = 'warning';
                                $text = 'Pending Order';
                            @endphp
                        @endif
                        @if ($order->orderDetail->order_status == 2)
                            @php
                                $status = 'success';
                                $text = 'Confirmed';
                            @endphp
                        @endif
                        @if ($order->orderDetail->order_status == 3)
                            @php
                                $status = 'info';
                                $text = 'On Deliver';
                            @endphp
                        @endif
                        @if ($order->orderDetail->order_status == 4)
                            @php
                                $status = 'danger';
                                $text = 'Cancel';
                            @endphp
                        @endif
                        @if ($order->orderDetail->order_status == 5)
                            @php
                                $status = 'danger';
                                $text = 'Returned';
                            @endphp
                        @endif
                        @if ($order->orderDetail->order_status == 6)
                            @php
                                $status = 'success';
                                $text = 'Delivered';
                            @endphp
                        @endif
                    @endif
                    <span class="badge bg-{{ $status }}">{{ $text }}</span>
                </td>
                <td>{{ Utility::convertDmyWith12HourFormat($order->created_at) }}</td>
                <td>
                    @if(Auth()->user() !== null && Auth()->user()->role_id == '7')
                        <a class="btn btn-icon btn-success w-30px h-30px me-3" href="{{route('transport-order-edit',$order->orderDetail->id)}}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Order">
                            <i class="fa-solid fa-edit"></i>
                        </a>
                    @endif
                    <a class="btn btn-icon btn-danger w-30px h-30px me-3" onclick="removeOrder({{ $order->orderDetail->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove Order">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                    <a class="btn btn-icon w-30px h-30px me-3 driver_button_color" onclick="OrderStatusUpdate({{ $order->orderDetail->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="Complete Order">
                        <i class="fa fa-check"></i>
                    </a>
                    <a class="btn btn-icon btn-success w-30px h-30px me-3" onclick="cancelOrderStatus({{ $order->orderDetail->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="Return Order">
                        <i class="fa fa-times"></i>
                    </a>
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
    {{ $batchItem->links('pagination::bootstrap-5') }}
</div>
