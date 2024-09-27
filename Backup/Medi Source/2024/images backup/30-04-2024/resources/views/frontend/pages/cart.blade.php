@extends('frontend.layouts.app')

@section('content')
    <section class="banner-section prdct-parent about-parent position-relative py-sm-5 py-1">
        <div class="container">
            <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start ">
                <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
                <div class="col-md-8">
                    <h1 class="text-white text-start">
                        Cart
                    </h1>
                    <div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}" class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page"><a href="" class="text-decoration-none text-white">Cart</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        @if(!blank($cart_items))
            <form action="{{ route('update.cart') }}" method="post">
                @csrf
                <div class="container">
                    <div class="inner-cart">
                        <label class="product-details d-md-none d-block">Products</label>
                        <div class="shopping-cart">
                            <div class="column-labels">
                                <label class="product-image">Image</label>
                                <label class="product-details">Product</label>
                                <label class="product-price">Price</label>
                                <label class="product-quantity">Quantity</label>
                                <label class="product-line-price">Total</label>
                                <label class="product-removal">Action</label>
                            </div>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($cart_items as $item)
                                @php
                                    $product = DB::table('products')
                                        ->where('id', $item->product_id)
                                        ->first();
                                        @endphp
                                @if (!blank($product))
                                @php $total = $total + $item->total; @endphp
                                    <div class="product">
                                        <div class="product-image">
                                            <img src="{{ asset('storage/images/' . $product->single_image) }}">
                                        </div>
                                        <div class="product-details">
                                            {{-- @dd($item) --}}
                                            <div class="product-title">{{ $product->productname }}</div>
                                            <p class="product-description">{{$item->package_name}}
                                            </p>
                                            <p class="product-necessity">{{$item->medical_necessity}}</p>
                                        </div>
                                        <div class="p-in">
                                            <p class="mb-0">price : </p>
                                            <div class="product-price"> {{ $item->package_total }}</div>
                                        </div>
                                        <div class="p-in">
                                            <p class="mb-0">price : </p>
                                            <div class="product-price"> {{ $item->package_total }}</div>
                                        </div>
                                        <div class="product-price in"> {{ $item->package_total }}</div>
                                        <div class="product-quantity"> {{ $item->quantity }}</div>
                                        {{-- <div class="product-quantity">
                                            <div class="quantity">
                                                <a href="#" class="quantity__minus" data-id="{{$item->id}}"><span>-</span></a>
                                                <input name="item[{{ $item->id }}][quantity]" type="text"
                                                    class="quantity__input quantity_input_{{$item->id}}" min="1" value="{{ $item->quantity }}">
                                                <a href="#" class="quantity__plus" data-id="{{$item->id}}"><span>+</span></a>
                                            </div> --}}
                                            {{-- <input type="number" value="" min="1"> --}}
                                        {{-- </div> --}}
                                        <input type="hidden" name="item[{{ $item->id }}][item_id]"
                                            value="{{ $item->id }}">
                                        <div class="p-in">
                                            <p class="mb-0">Total : </p>
                                            <input type="hidden" class="item-price item_price_{{$item->id}}" name="item[{{ $item->id }}][price]"
                                                value="{{$item->package_total }}">
                                            <div class="product-line-price">{{ $item->package_total * $item->quantity }}</div>
                                        </div>
                                        <input type="hidden" class="item_total_val_{{$item->id}}" name="item[{{ $item->id }}][total]"
                                            value="{{ $item->quantity * $item->package_total }}">
                                        <div class="product-line-price in item_total_{{$item->id}}">{{ $item->package_total * $item->quantity }}</div>
                                        <div class="product-removal">
                                            <div class="remove-product">
                                                <a href="#" data-id="{{$item->id}}"
                                                    class="header-btn border-0 d-lg-block d-none mt-lg-0 mt-4 text-white py-2 px-2 remove-cart-item">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                                <i class="fa fa-trash text-dark d-lg-none d-block" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            <div class="update-cart-sec">
                                {{-- <div class="inner-btn">
                                    <button type="submit"
                                        class="update header-btn border-0 mt-lg-0 mt-sm-4 text-white py-2 px-4 ">
                                        Update Cart</button>
                                </div> --}}
                                <div class="totals">
                                    <div class="totals-item">
                                        <label>Subtotal</label>
                                        <div class="totals-value" id="cart-subtotal">{{ $total }}</div>
                                    </div>
                                    <div class="totals-item">
                                        <label>Shipping</label>
                                        <div class="totals-value" id="cart-shipping">0.00</div>
                                    </div>
                                    <div class="totals-item totals-item-total">
                                        <label>Grand Total</label>
                                        <div class="totals-value" id="cart-total">{{ $total }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="inner-btn">
                                @if(NumberFormat::checkUserPermission('Checkout Page'))
                                <div class="checkout">
                                    <a href="{{ route('checkout') }}"
                                        class="header-btn border-0 mt-lg-0  text-white ">
                                        Checkout
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @else
        <div class="container">
            <div class="p-5 text-center">
                <h2>Your cart is empty!</h2>
            </div>
        </div>
        @endif
    </section>
    {{--  <section>
        <div class="container">
            <div class="my-5">
                @if (!blank($cart_items))
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cart_items as $item)
                                @php
                                    $product = DB::table('products')
                                        ->where('id', $item->product_id)
                                        ->first();

                                @endphp
                                @if (!blank($product))
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $product->productname }}</td>
                                        <td>{{ $item->price }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->price * $item->quantity }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <h5>Cart Items Not Found.</h5>
                @endif
            </div>
        </div>
    </section> --}}
@endsection
@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click','.quantity__minus, .quantity__plus',function(){
                var id = $(this).data('id');
                var qty = $('.quantity_input_'+id).val();
                var price = $('.item_price_'+id).val();
                var total = parseFloat(qty)*parseFloat(price);
                $('.item_total_val_'+id).val(total);
                $('.item_total_'+id).html(total);
            });
            $(document).on('click', '.remove-cart-item', function() {
                var item_id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('delete.cart.item', '') }}" + "/" + item_id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: "Cart item has been deleted.",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                       location.reload();
                                    }
                                });
                            }
                        });
                    }
                });
            });
            const minus = $('.quantity__minus');
            const plus = $('.quantity__plus');
            minus.click(function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var value = $('.quantity_input_'+id).val();
                if (value > 1) {
                    value--;
                }
                $('.quantity_input_'+id).val(value);
            });
            plus.click(function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var value = $('.quantity_input_'+id).val();
                value++;
                $('.quantity_input_'+id).val(value);
            })
        });
    </script>
@endsection
