<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px">Product Name</th>
            <th class="min-w-125px">District Name</th>
            <th class="min-w-125px">Sub District Name</th>
            <th class="min-w-125px">Order</th>
            <th class="min-w-125px">Quantity</th>
            <th class="min-w-125px">Amount</th>
            <th class="min-w-100px">Status</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($orderList as $key=>$order)
            <tr>
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
                <td>&#x20B9; {{ $order->amount }}</td>
                <td>{{ isset($order->districtDetail) ? $order->districtDetail->district_name : '' }}</td>

                <td>{{ isset($order->userDetail) ? $order->userDetail->name : '' }}</td>

                <td>
                    @if($order->confirm_date !== null)
                        {{ Utility::convertDmyWith12HourFormat($order->confirm_date) }}
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
<div class="d-flex justify-content-end">
    {{ $orderList->links('pagination::bootstrap-5') }}
</div>
