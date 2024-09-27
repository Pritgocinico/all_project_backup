<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px">Order ID</th>
            <th class="min-w-125px">Customer</th>
            <th class="min-w-125px">Phone Number</th>
            @if(Auth()->user() !== null && (Auth()->user()->role_id== 1 || Auth()->user()->role_id == 9 || Auth()->user()->role_id == 2))
            <th class="min-w-125px">Product Detail</th>
            @endif
            <th class="min-w-125px">Amount</th>
            <th class="min-w-125px">In Stock</th>
            <th class="min-w-125px">Out Stock</th>
            @if(Auth()->user() !== null && Auth()->user()->role_id !== 1)
            <th class="min-w-100px">Action</th>
            @endif
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($orderList as $key=>$order)
            <tr>
                <td class="align-middle">
                    @php $route =  route('order-view',$order->id)@endphp
                @if(Auth()->user() !== null && Auth()->user()->role_id == 1)
                    @php $route =  route('orders.show',$order->id)@endphp
                @endif
                    <a href="{{ $route }}" class="pre-agro-emp">{{ $order->order_id }}</a>
                    {!! DNS1D::getBarcodeHTML($order->order_id, 'C128', 1.4, 22) !!}
                </td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->phoneno }}</td>
                @if(Auth()->user() !== null && (Auth()->user()->role_id== 1 || Auth()->user()->role_id == 9 || Auth()->user()->role_id == 2))
                <td>
                    @foreach ($order->orderItem as $item)
                        {{isset($item->productDetail) ? $item->productDetail->product_name: ""}} [{{isset($item->varientDetail) ? $item->varientDetail->sku_name: ""}} - {{$item->quantity}}]<br />   
                    @endforeach
                </td>
                @endif
                <td>&#x20B9; {{ $order->amount }}</td>
                <td>
                    @php
                        $inStock = 0;
                        $outStock = 0;
                    @endphp
                    @foreach ($order->stockDetail as $stock)
                        @php
                            $inStock += $stock->in_stock;
                            $outStock += $stock->out_stock;
                        @endphp
                    @endforeach
                    {{ $inStock }}
                </td>
                <td>{{ $outStock }}</td>
                @if(Auth()->user() !== null && Auth()->user()->role_id !== 1)
                <td>
                    <a class="btn btn-icon btn-info w-30px h-30px me-3" href="#"
                        onclick="getOrderDetail({{ $order->id }},'In')" data-bs-toggle="tooltip"
                        data-bs-placement="top" aria-label="Edit Employee" data-bs-original-title="In Stock"
                        aria-describedby="tooltip553274">
                        <i class="bi bi-box-arrow-left"></i>
                    </a>

                    <a class="btn btn-icon btn-danger w-30px h-30px me-3" href="#"
                        onclick="getOrderDetail({{ $order->id }},'Out')" data-bs-toggle="tooltip"
                        data-bs-placement="top" aria-label="Delete Employee" data-bs-original-title="Out Stock">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </td>
                @endif
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
