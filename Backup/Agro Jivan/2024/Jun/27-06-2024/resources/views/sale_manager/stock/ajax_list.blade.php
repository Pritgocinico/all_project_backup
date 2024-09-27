<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
        <th>Sr. no</th>
            <th class="min-w-125px">Order ID</th>
            <th class="min-w-125px">Customer</th>
            <th class="min-w-125px">Product Name</th>
            <th class="min-w-125px">Product Variant Name</th>
            <th class="min-w-125px">In Stock</th>
            <th class="min-w-125px">In Stock Date</th>
            <th class="min-w-100px">Out Stock Date</th>
            <th class="min-w-100px">Out Stock Date</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($stockList as $key=>$order)
            <tr>
            <td>{{ $stockList->firstItem() + $key}}</td>
                <td class="align-middle">
                    <a href="{{ route('confirm-orders.show', $order->id) }}" class="pre-agro-emp">{{ isset($order->orderDetail)?$order->orderDetail->order_id:"-" }}</a>
                    {!! isset($order->orderDetail)?DNS1D::getBarcodeHTML($order->orderDetail->order_id, 'C128', 1.4, 22) :"" !!}
                </td>
                <td>{{ isset($order->orderDetail)?$order->orderDetail->customer_name : "-" }}</td>
                <td>{{ isset($order->productDetail)?$order->productDetail->product_name : "-" }}</td>
                <td>{{ isset($order->variantDetail)?$order->variantDetail->sku_name : "-" }}</td>
                <td>{{$order->in_stock}}</td>
                <td>{{ Utility::convertDmyWith12HourFormat($order->in_stock_date_time) }}</td>
                <td>{{$order->out_stock}}</td>
                <td>{{ Utility::convertDmyWith12HourFormat($order->in_stock_date_time) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $stockList->links('pagination::bootstrap-5') }}
</div>
