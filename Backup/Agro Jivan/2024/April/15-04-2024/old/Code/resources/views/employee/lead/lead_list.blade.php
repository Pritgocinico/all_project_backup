@foreach ($leadList as $lead)
    <div class="card">
        <div class="card-body my-3">
            <p class=""><strong>Lead ID: </strong>{{ $lead->lead_id }}</p>
            <span class=""><strong>Created By: </strong><span
                    class="created">{{ auth()->user()->name }}</span></span>
            <br>
            <span class=""><strong>Customer Name: </strong>{{ $lead->customer_name }}</span>
            <br>
            <span class=""><strong>Created At: </strong>{{ Utility::convertDmyWith12HourFormat($lead->created_at) }}</span>
            <br>
            <p class="mt-3"><strong>Items</strong></p>
            <div>
                @forelse ($lead->leadDetail as $item)
                    <div class="bg-light m-2 p-2">
                        <span class="mb-0"><strong>Product Name: </strong>{{$item->productDetail->product_name}}</span>
                        <br>
                        <span class="mb-0"><strong>Variant: </strong>{{$item->variantDetail->sku_name}}</span>
                        <br>
                        <span class="mb-0"><strong>Quantity: </strong>{{$item->quantity}}</span>
                        <br>
                        <span class="mb-0"><strong>Price: </strong>₹{{$item->price}}</span>
                        <br>
                    </div>
                @empty
                @endforelse
            </div>
            <hr>
            <p class="text-danger fs-20 text-end"><strong>Total: ₹ <span class="ttl1-AG-0845">{{$lead->amount}}</span></strong></p>
        </div>
    </div>
@endforeach