@extends('admin.layouts.app')

@section('content')
<div class="card mb-2 p-3">
    <div class="card-body">
        <div class="d-md-flex gap-4 align-items-center">
            <h4 class="mb-0">View {{$user->name}}</h4>
            <div class="ms-auto">
                <a href="{{ route('admin.users') }}" class="btn btn-primary ustify-content-end float-right">
                    Go Back
                </a>
            </div>
        </div>
    </div>
</div>
<div class="card p-3">
    <div class="card-body">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
              <a href="#pills-profile" class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="true">Profile</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#pills-orders" class="nav-link" id="pills-orders-tab" data-bs-toggle="pill" data-bs-target="#pills-orders" type="button" role="tab" aria-controls="pills-orders" aria-selected="false">Orders</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#pills-cart" class="nav-link" id="pills-cart-tab" data-bs-toggle="pill" data-bs-target="#pills-cart" type="button" role="tab" aria-controls="pills-cart" aria-selected="false">Cart Items</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#pills-statement" class="nav-link" id="pills-statement-tab" data-bs-toggle="pill" data-bs-target="#pills-statement" type="button" role="tab" aria-controls="pills-statement" aria-selected="false">Statement</a>
            </li>
          </ul>
          <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="ps-3 mt-3" style="border-left:3px solid #689efd; border-spacing: 10px 0px;">
                                    <h6>
                                        <span class="font-weight-bold">
                                        Name
                                        </span>
                                    </h6>
                                    <p class="mb-1">{{$user->name}} </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ps-3 mt-3" style="border-left:3px solid #fe7070; border-spacing: 10px 0px;">
                                    <h6>
                                        <span class="font-weight-bold">
                                        Phone Number
                                        </span>
                                    </h6>
                                    <p class="mb-1">{{$user->phone}}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ps-3 mt-3" style="border-left:3px solid #d081de; border-spacing: 10px 0px;">
                                    <h6>
                                        <span class="font-weight-bold">
                                        Email Address
                                        </span>
                                    </h6>
                                    <p class="mb-1">{{$user->email}}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ps-3 mt-3" style="border-left:3px solid #8560b8; border-spacing: 10px 0px;">
                                    <h6>
                                        <span class="font-weight-bold">
                                        Transport
                                        </span>
                                    </h6>
                                    <p class="mb-1">{{$user->transport}}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ps-3 mt-3" style="border-left:3px solid #8decec; border-spacing: 10px 0px;">
                                    <h6>
                                        <span class="font-weight-bold">
                                        GST Number
                                        </span>
                                    </h6>
                                    <p class="mb-1">
                                        @if(!blank($user->gst_number))
                                            {{$user->gst_number}}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ps-3 mt-3" style="border-left:3px solid #365436; border-spacing: 10px 0px;">
                                    <h6>
                                        <span class="font-weight-bold">
                                            Created At
                                        </span>
                                    </h6>
                                    <p class="mb-1">{{$user->created_at}}</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="ps-3 mt-3" style="border-left:3px solid #bdc54e; border-spacing: 10px 0px;">
                                    <h6>
                                        <span class="font-weight-bold">
                                            Address
                                        </span>
                                    </h6>
                                    <p class="mb-1">
                                        @if (!blank($user->floor_no))
                                            {{$user->floor_no}},
                                        @endif
                                        @if (!blank($user->address))
                                            {{$user->address}},
                                        @endif
                                        <br>
                                        @if (!blank($user->locality))
                                            {{$user->locality}},
                                        @endif
                                        @if (!blank($user->city))
                                            {{$user->city}},
                                        @endif
                                        <br>
                                        @if (!blank($user->state))
                                            {{$user->state}},
                                        @endif
                                        @if (!blank($user->country))
                                            {{$user->country}},
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-orders" role="tabpanel" aria-labelledby="pills-orders-tab">
                <div class="houmanity-card">
                    <div class="table-responsive"> 
                        <table id="" class="table table-custom rwd-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer Name</th>
                                    <th>Phone Number</th>
                                    <th>Address</th>
                                    <th>Order Amount</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!blank($orders))
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td data-th="Order ID"><a href="{{route('order.details',$order->id)}}" class="text-primary">{{$order->order_id}}</a></td>
                                            <td data-th="Customer Name">{{$order->customer_name}}</td>
                                            <td data-th="Phone Number">{{$order->phone}}</td>
                                            <td data-th="Address">
                                                @if (!blank($order->floor_no))
                                                    {{$order->floor_no}},
                                                @endif
                                                @if (!blank($order->address))
                                                    {{$order->address}},
                                                @endif
                                                <br>
                                                @if (!blank($order->locality))
                                                    {{$order->locality}},
                                                @endif
                                                @if (!blank($order->city))
                                                    {{$order->city}},
                                                @endif
                                                <br>
                                                @if (!blank($order->state))
                                                    {{$order->state}},
                                                @endif
                                                @if (!blank($order->country))
                                                    {{$order->country}},
                                                @endif
                                            </td>
                                            <td data-th="Order Amount">{{$order->total}}</td>
                                            <td data-th="Status">
                                                @if($order->status==0)
                                                    <span class="badge bg-warning">Pending Order</span>
                                                @elseif($order->status==1)
                                                    <span class="badge bg-success">Payment Done</span>
                                                @endif
                                            </td>
                                            <td data-th="Created At">{{date($setting->date_format,strtotime($order->created_at))}}</td>
                                            <td data-th="Action" class="text-md-end">
                                                <a href="{{route('order.details',$order->id)}}" class="btn btn-primary">View Order</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan="8">Orders Not Found.</th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-cart" role="tabpanel" aria-labelledby="pills-cart-tab">
                <div class="houmanity-card">
                    <div class="table-responsive">
                        <table id="" class="table table-custom rwd-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!blank($cart_items))
                                    @foreach ($cart_items as $item)
                                        <tr>
                                            <td data-th="#">
                                                {{$loop->index+1}}
                                            </td>
                                            <td data-th="Product">
                                                <?php 
                                                    $products = DB::table('products')->where('id',$item->product_id)->first();
                                                ?>
                                                 <?php 
                                                    $variant = DB::table('product_variants')->where('id',$item->variant_id)->first(); 
                                                   
                                                ?>
                                                @if(!blank($products))
                                                    <span class="fw-normal">{{$products->name}} </span> @if(!blank($variant)) ( {{$variant->sku}} [ {{$variant->capacity}} ] ) @endif
                                                @endif
                                            </td>
                                            <td data-th="Category">
                                                @if (!blank($products))
                                                    <?php 
                                                        $category = DB::table('categories')->where('id',$products->category)->first();
                                                    ?>
                                                    @if(!blank($category))
                                                        {{$category->name}}
                                                    @endif
                                                @endif
                                            </td>
                                            <td data-th="Price">&#x20B9;{{$item->price}}</td>
                                            <td data-th="Quantity">{{$item->quantity}}</td>
                                            <td data-th="Total">&#x20B9;{{$item->price*$item->quantity}}</td> 
                                            <td data-th="Created At">{{$item->created_at}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan="8">Cart Item Not Found.</th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-statement" role="tabpanel" aria-labelledby="pills-statement-tab">
                <div class="row">
                    <form action="{{route('get_statement_data')}}" method="post">
                        @csrf
                        <fieldset>
                            <h6>Select option for statement period:</h6>
                            <div class="">
                                <div>
                                    <input type="radio" id="today" class="period" name="period" value="1" checked>
                                    <label for="today">Today</label>
                                </div>
                                <div>
                                    <input type="radio" id="by_week" class="period" name="period" value="2">
                                    <label for="by_week">Week</label>
                                </div>
                                <div>
                                    <input type="radio" id="month" class="period" name="period" value="3">
                                    <label for="month">Month</label>
                                </div>
                                <div>
                                    <input type="radio" id="year" class="period" name="period" value="4">
                                    <label for="year">Year</label>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="mt-3">
                            <h6>Select appropriate option to view or download statement:</h6>
                            <div class="">
                                <div>
                                    <input type="radio" id="view" class="option" name="option" value="view" checked>
                                    <label for="view">View</label>
                                </div>
                                <div>
                                    <input type="radio" id="download" class="option"  name="option" value="download">
                                    <label for="download">Download</label>
                                </div>
                            </div>
                        </fieldset>
                        <div class="mt-3">
                            <input type="hidden" name="user_id" value="{{$user->id}}">
                            <button type="submit" class="submit_options btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
          </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        // $(document).on('click','.submit_options',function(){
        //     var period = $("input[name='period']:checked").val();
        //     var option = $("input[name='option']:checked").val();
        //     var user_id = {{$user->id}};
        //     $.ajax({
        //         url : "{{route('get_statement_data')}}",
        //         data: {"_token": "{{ csrf_token() }}",'period':period, 'option': option, 'user_id': user_id },
        //         type : 'POST',
        //         success : function(data) {
                   
        //         }
        //     });
            
        // });
        function showPassword() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
@endsection