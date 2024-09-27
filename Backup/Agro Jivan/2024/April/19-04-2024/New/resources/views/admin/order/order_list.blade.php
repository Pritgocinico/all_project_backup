@foreach ($ordersList as $orderlist)
    <div class="card">
        <div class="card-body my-3">
            <p class=""><strong>Order ID: </strong>{{ $orderlist->order_id }}</p>
            <span class=""><strong>Created By: </strong><span class="created">{{ auth()->user()->name }}</span></span>
            <br>
            <span class=""><strong>Customer Name: </strong>{{ $orderlist->customer_name }}</span>
            <br>
            <span class=""><strong>Created At: </strong>{{ $orderlist->created_at }}</span>
            <br>
            @if ($orderlist->order_status == 1)
                @php $status = "Pending" @endphp
            @endif
            @if ($orderlist->order_status == 2)
                @php $status = "Confirmed" @endphp
            @endif
            @if ($orderlist->order_status == 3)
                @php $status = "On Delivery" @endphp
            @endif
            @if ($orderlist->order_status == 4)
                @php $status = "Cancelled" @endphp
            @endif
            @if ($orderlist->order_status == 5)
                @php $status = "Returned" @endphp
            @endif
            @if ($orderlist->order_status == 6)
                @php $status = "Completed" @endphp
            @endif
            <span class=""><strong>Status: </strong>{{ $status }}</span>
            <p class="mt-3"><strong>Items</strong></p>
            <div>
                @forelse ($orderlist->orderItem as $item)
                    <div class="bg-light m-2 p-2">
                        <span class="mb-0"><strong>Product Name:
                            </strong>{{ $item->productDetail->product_name }}</span>
                        <br>
                        <span class="mb-0"><strong>Variant: </strong>{{ $item->varientDetail->sku_name }}</span>
                        <br>
                        <span class="mb-0"><strong>Quantity: </strong>{{ $item->quantity }}</span>
                        <br>
                        <span class="mb-0"><strong>Price: </strong>₹{{ $item->price }}</span>
                        <br>
                        <br>
                    </div>
                @empty
                @endforelse

            </div>
            <hr>
            <p class="text-danger fs-20 text-end"><strong>Total: ₹ <span
                        class="ttl1-AG-0845">{{ $orderlist->amount }}</span></strong></p>
        </div>
    </div>
@endforeach
