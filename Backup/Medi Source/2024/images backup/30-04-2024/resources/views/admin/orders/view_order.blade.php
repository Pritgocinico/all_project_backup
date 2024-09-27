@extends('admin.layouts.app')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h2 class="mb-0">View Order</h2>
                        <a href="{{ route('admin.orders') }}" class="btn btn-secondary ms-auto">Go Back</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <table class="table">
                            <tr>
                                <th>Order ID</th>
                                <td>{{$order->order_id}}</td>
                            </tr>
                            <tr>
                                <th>Order Created Date</th>
                                <td>{{date('d/m/Y',strtotime($order->created_at))}}</td>
                            </tr>
                            <tr>
                                <th>Customer Name</th>
                                <td>{{$order->first_name.' '.$order->last_name}}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{$order->email}}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{$order->phone}}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{$order->address}}</td>
                            </tr>
                            <tr>
                                <th>Address Line1</th>
                                <td>{{$order->address1}}</td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td>{{$order->city}}</td>
                            </tr>
                            <tr>
                                <th>State</th>
                                <td>{{$order->state}}</td>
                            </tr>
                            <tr>
                                <th>Zip Code</th>
                                <td>{{$order->zip_code}}</td>
                            </tr>
                            <tr>
                                <th>Billing Address</th>
                                <td>{{$order->billing_address}}</td>
                            </tr>
                            <tr>
                                <th>Billing Address Line1</th>
                                <td>{{$order->billing_address1}}</td>
                            </tr>
                            <tr>
                                <th>Billing City</th>
                                <td>{{$order->billing_city}}</td>
                            </tr>
                            <tr>
                                <th>Billing State</th>
                                <td>{{$order->billing_state}}</td>
                            </tr>
                            <tr>
                                <th>Billing Zip Code</th>
                                <td>{{$order->billing_zip_code}}</td>
                            </tr>
                            <tr>
                                <th>Total Amount</th>
                                <td>{{$order->total}}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <form action="" method="post">
                                        @csrf
                                        @method('POST')
                                        <select name="status" class="form-control order-status">
                                            <option value="">Select Status</option>
                                            <option value="0" {{ $order->status == 0 ? 'selected' : '' }}>Processing</option>
                                            <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>On Delivery</option>
                                            <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>Delivered</option>
                                            <option value="3" {{ $order->status == 3 ? 'selected' : '' }}>Canceled</option>
                                        </select>
                                        <input type="hidden" name="order_id" id="order_id" value="{{$order->id}}">
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="mt-3">
                        <h4>Order Items</h4>
                        <table class="table">
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Varient</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                            @if(!blank($order_item))
                                @foreach ($order_item as $item)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$item->product_name}}</td>
                                        <td>{{$item->package_name}}</td>
                                        <td>{{$item->price}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td>{{$item->total}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </div>
                </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    $(document).ready(function(){
        $('.order-status').on('change', function(){
            var status = $(this).val();
            var order_id = $('#order_id').val();
            $.ajax({
				type: 'POST',
				url: '{{route('changeStatus')}}',
                data: { 
                    "_token": "{{ csrf_token() }}",
                    status: status,
                    order_id: order_id
                 },
				success: function(data) {
                    console.log(data);
                    if(data.success == true){
                        window.location.reload();
                    }
				}
			});
        })
    });
</script>
@endsection
