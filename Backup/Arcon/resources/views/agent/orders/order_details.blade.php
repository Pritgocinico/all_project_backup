@extends('agent.layouts.app')

@section('content')
    <div class="card mb-2 p-3">
        <div class="card-body">
            <div class="d-md-flex gap-4 align-items-center agent-order-details-outer-div">
                <div class="d-flex gap-3 agent-order-details-outer">
                    <h4 class="mb-0">{{$order->order_id}}</h4>
                    <div class="agent-order-details-status">
                        @if($order->status==1)
                            <button class="btn btn-warning text-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Pending Order
                            </button>
                        @elseif($order->status==2)
                            <button class="btn btn-success text-white btn-sm" >
                                Confirmed
                            </button>
                        @elseif($order->status==3)
                            <button class="btn btn-info text-white btn-sm" >
                                Delivered
                            </button>
                        @endif
                        <ul class="dropdown-menu task-status">
                            <li><a class="dropdown-item status @if($order->status == 1) hide @endif" href="#" data-status="1" data-task="{{ $order->id }}">Pending Order</a></li>
                            <li><a class="dropdown-item status @if($order->status == 2) hide @endif" href="#" data-status="2" data-task="{{ $order->id }}">Confirm Order</a></li>
                            <!--<li><a class="dropdown-item status @if($order->status == 3) hide @endif" href="#" data-status="3" data-task="{{ $order->id }}">Delivered</a></li>-->
                        </ul>
                    </div>
                </div>
                <div class="agent-order-details-back ms-auto">
                    @if ($order->status != 1)
                        <a href="{{route('order.invoice',$order->id)}}" class="btn btn-primary me-2">
                            Download Invoice <i class="bi bi-download ms-2"></i>
                        </a>
                    @endif
                    <button type="submit" onclick="window.history.go(-1); return false;" class="btn btn-primary ustify-content-end float-right">
                        Go Back
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card p-3">
        <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="ps-3 mt-3" style="border-left:3px solid #689efd; border-spacing: 10px 0px;">
                                    <h6>
                                        <span class="font-weight-bold">
                                        Customer Name
                                        </span>
                                    </h6>
                                    <p class="mb-1">{{$order->customer_name}} </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ps-3 mt-3" style="border-left:3px solid #fe7070; border-spacing: 10px 0px;">
                                    <h6>
                                        <span class="font-weight-bold">
                                        Phone Number
                                        </span>
                                    </h6>
                                    <p class="mb-1">{{$order->phone}}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ps-3 mt-3" style="border-left:3px solid #4fa0ab; border-spacing: 10px 0px;">
                                    <h6>
                                        <span class="font-weight-bold">
                                        Email Address
                                        </span>
                                    </h6>
                                    <p class="mb-1">{{$order->email}}</p>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="ps-3 mt-3" style="border-left:3px solid #fe7070; border-spacing: 10px 0px;">
                                    <h6>
                                        <span class="font-weight-bold">
                                        Order Amount
                                        </span>
                                    </h6>
                                    <p class="mb-1">{{$order->total}}</p>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="ps-3 mt-3" style="border-left:3px solid #535072; border-spacing: 10px 0px;">
                                    <h6>
                                        <span class="font-weight-bold">
                                            Transport Name
                                        </span>
                                    </h6>
                                    <p class="mb-1">{{$order->transport}}</p>
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
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                @if ($order->status == 3)
                    <div class="lr-copy">
                        <h6>LR Copy</h6>
                        <div class="row mt-3">
                            @if(!blank($order->lr_copy))
                                <div class="col-md-6">
                                    <a href="{{url('/')}}/lr_copy/{{$order->lr_copy}}" download class="btn btn-primary">Download LR Copy</a>
                                    <!--<img src="{{url('/')}}/lr_copy/{{$order->lr_copy}}" class="w-100">-->
                                </div>
                            @endif
                        </div>
                    </div>
                    <hr>
                 @endif
                <h6>Order Items</h6>
                <div class="variants mt-3">
                    <table class="table table-striped table-hover rwd-table">
                        <thead>
                            <tr>
                                <th>Packing</th>
                                <th>Case Qty (Kg / Ltr)</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $amt = 0; ?>
                            @if (count($order_items) > 0)
                                @foreach ($order_items as $item)
                                <?php
                                    $variant = DB::table('product_variants')->where('id',$item->variant_id)->first();
                                    $amt = $amt + ($item->quantity*$item->price)
                                ?>
                                <tr>
                                    <td data-th="Variant SKU">@if(!blank($variant)) {{$variant->sku}} @endif</td>
                                    <td data-th="Capacity">@if(!blank($variant)) {{$variant->capacity}} @endif</td>
                                    <td data-th="Product">
                                        <?php
                                            $products = DB::table('products')->where('id',$item->product_id)->first();
                                        ?>
                                        @if(!blank($products))
                                        {{$products->name}}
                                        @endif
                                    </td>
                                    <td data-th="Category">
                                        <?php
                                            $category = DB::table('categories')->where('id',$products->category)->first();
                                        ?>
                                        @if(!blank($category))
                                        {{$category->name}}
                                        @endif
                                    </td>
                                    <td data-th="Price">&#x20B9;{{$item->price}}</td>
                                    <td data-th="Quantity">{{$item->quantity}}</td>
                                    <td data-th="Total">&#x20B9;{{$item->price*$item->quantity}}</td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5"></th>
                                <th colspan="6">Total : </th>
                                <th colspan="1">
                                    <h6 class="text-danger">&#x20B9;{{$amt}}</h6>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('.task-status li a').on('click', function(){
            var status = $(this).attr('data-status');
            var task_id = $(this).attr('data-task');
            $('#loader').removeClass('hidden');
            $.ajax({
                url : "{{ route('change_order_status') }}",
                type : 'POST',
                data : {"_token": "{{ csrf_token() }}",'status': status,'order' : task_id},
                // dataType:'json',
                success : function(data) {
                    $('#loader').addClass('hidden');
                    Swal.fire({
                        title: 'Status Changed!',
                        text: "Status Changed Successfully.",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok'
                        }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload(true);
                        }
                    });
                }
            });
        });
    </script>
@endsection
