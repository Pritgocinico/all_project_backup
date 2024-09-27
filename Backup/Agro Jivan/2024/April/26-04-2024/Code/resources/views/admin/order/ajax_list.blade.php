<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th>Sr. no</th>
            <th class="min-w-125px">Order ID</th>
            <th class="min-w-125px">Customer</th>
            <th class="min-w-125px">Phone Number</th>
            @if(Auth()->user() !== null && (Auth()->user()->role_id== 1 || Auth()->user()->role_id == 9 || Auth()->user()->role_id == 2))
            <th class="min-w-125px">Product Detail</th>
            @endif
            <th class="min-w-125px">Amount</th>
            <th class="min-w-125px">District</th>
            <th class="min-w-125px">Sub District</th>
            <th class="min-w-125px">Created By</th>
            <th class="min-w-100px">Status</th>
            @if($status == 2)
            <th class="min-w-100px">Confirm AT</th>
            @else
            <th class="min-w-100px">Created AT</th>
            @endif
            <th class="min-w-80px">Action</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($orderList as $key=>$order)
        <tr>
            <td>{{ $orderList->firstItem() + $key}}</td>
            <td class="align-middle">
                @php $route = route('confirm-orders.show', $order->id) @endphp
                @if (Auth()->user() == null)
                @php $route = route('login') @endphp
                @elseif(Auth()->user()->id == 1)
                @php $route = route('orders.show', $order->id) @endphp
                @elseif(Auth()->user()->id == 9)
                @php $route = route('sale-orders.show', $order->id) @endphp
                @endif
                <a href="{{ $route }}" class="pre-agro-emp">{{ $order->order_id }}</a>
                @if($order->order_id !== null){!! DNS1D::getBarcodeHTML($order->order_id, 'C128', 1.4, 22) !!}@endif
            </td>
            <td>{{ $order->customer_name }}</td>
            <td>{{ $order->phoneno }}
                @if(count($order->numberOrder) >= 3)
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
            <td>{{ isset($order->subDistrictDetail) ? $order->subDistrictDetail->sub_district_name : '' }}</td>

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

            <td>
                @php $date = "-"; @endphp
                @if($order->order_status == 2)
                    @if($order->confirm_date !== null)
                        @php $date = Utility::convertDmyWith12HourFormat($order->confirm_date) @endphp
                    @endif
                @else
                    @if($order->created_at !== null)
                        @php $date = Utility::convertDmyWith12HourFormat($order->created_at) @endphp
                    @endif
                @endif
                {{$date }}
            </td>
            <td>
                @if ($order->order_status == 2)
                <a class="btn btn-icon btn-info w-30px h-30px me-3" href="{{ route('generate-invoice-pdf', $order->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Generate Invoice">
                    <i class="fas fa-file-invoice"></i></a>
                <a class="btn btn-icon btn-danger w-30px h-30px me-3" onclick="cancelOrder({{ $order->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel Order">
                    <i class="fa-solid fa-xmark"></i>
                </a>

                @endif
                @if ($status !== 2)
                @if ($order->order_status !== '2')
                @php $route = route('confirm-orders.edit', $order->id) @endphp
                @if (Auth()->user() == null)
                @php $route = route('login') @endphp
                @elseif(Auth()->user()->id == 1)
                @php $route = route('orders.edit', $order->id) @endphp
                @elseif(Auth()->user()->id == 9)
                @php $route = route('sale-orders.edit', $order->id) @endphp
                @endif
                <a class="btn btn-icon btn-info w-30px h-30px me-3" href="{{ $route }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Order">
                    <i class="fas fa-edit"></i></a>
                @endif
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
<div class="d-flex justify-content-end">
    {{ $orderList->links('pagination::bootstrap-5') }}
</div>