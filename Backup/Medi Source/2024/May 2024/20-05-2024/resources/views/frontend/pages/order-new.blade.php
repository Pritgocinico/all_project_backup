@extends('frontend.layouts.app')

@section('content')

<style>
    .three-way-main-sec .parent-three-way-box .parent-box-for-portals a {
    color: #70A7A6;
    text-decoration: none;
}
</style>
    <section class="banner-section about-parent  position-relative about-sec py-sm-5 py-1">
        <div class="container">
            <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start">
                <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
                <div class="col-md-8">
                    <h1 class="text-white text-start">
                    Order Product
                    </h1>
                    <div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}" class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="" class="text-decoration-none text-white">Order</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="three-way-main-sec">
        <div class="container">
            <h1>Three ways to order</h1>
            <div class="row parent-three-way-box">
                <div class="col-lg-4 col-md-12 text-center">
                    @if(!empty(auth()->user()))
                        <div class="parent-box-for-portals">
                        <a href="{{url('cart')}}" class="text-decoration-none">
                            <img src="{{url('/')}}/frontend/assets/images/online-order.png" alt="" class="img-fluid">
                            <h4>Online Order Portal</h4>
                            <p>Order online using our shopping cart. </p>
                            </a> 
                            <div class="about-btn">
                                <a class="text-decoration-none px-4 py-2 cart-btn clr-black justi d-flex gap-1 align-items-center parent-cartorder" href="{{url('cart')}}">
                                    <span class="inner-pdf-text-cart text-white">Shopping Cart</span>
                                </a>
                            </div>
                        </div>
                    @else
                    <div class="parent-box-for-portals">
                        <a href="{{ route('logindoctor') }}" class="text-decoration-none">
                            <img src="{{url('/')}}/frontend/assets/images/online-order.png" alt="" class="img-fluid">
                            <h4>Online Order Portal</h4>
                            <p>Order online using our shopping cart. </p>
                            </a> 
                            <div class="about-btn">
                                <a class="text-decoration-none px-4 py-2 cart-btn clr-black justi d-flex gap-1 align-items-center parent-cartorder" href="{{ route('logindoctor') }}">
                                    <span class="inner-pdf-text-cart text-white">Shopping Cart</span>
                                </a>
                            </div>
                        </div>
                    @endif
                          
                </div>
                <div class="col-lg-4 col-md-12 text-center">
                <div class="parent-box-for-portals">
                        <a href="{{url('order-form')}}" class="text-decoration-none">
                            <img src="{{url('/')}}/frontend/assets/images/fax.png" alt="" class="img-fluid">
                            <h4>Email/Fax Order Form</h4>
                            <p>Download our PDF Order Form.</p>
                            </a> 
                            <div class="about-btn">
                                <a class="text-decoration-none px-4 py-2 cart-btn clr-black justi d-flex gap-1 align-items-center parent-cartorder" href="{{url('order-form')}}">
                                    <span class="inner-pdf-text-cart text-white">USE ORDER FORM</span>
                                </a>
                            </div>
                        </div>
                </div>
                <div class="col-lg-4 col-md-12 text-center">
                <div class="parent-box-for-portals">
                        <a href="tel:+17144551300" class="text-decoration-none">
                            <img src="{{url('/')}}/frontend/assets/images/call-male.png" alt="" class="img-fluid">
                            <h4>Phone Call</h4>
                            <a href="tel:+17144551300">(714) 455-1300</a>
                            <p>Call to place your order.</p>
                        </a> 
                            <div class="about-btn">
                                <a class="text-decoration-none px-4 py-2 cart-btn clr-black justi d-flex gap-1 align-items-center parent-cartorder" href="tel:+17144551300">
                                    <span class="inner-pdf-text-cart text-white">CALL US NOW</span>
                                </a>
                            </div>
                        </div> 
                </div>
            </div>
        </div>
    </section>
    <section class="our-prdct-sec">
        <div class="container">
            <div class="search-section pt-0">
                <div class="container-fluid container-xl">
                    <div class="row main-content ml-md-0 mb-md-0 mb-4 justify-content-md-between justify-content-lg-center">
                    <h1>Available Product</h1>
                        <div class="content col-md-12 ps-3 ps-xl-5">
                            <!-- <h2 class="text-center clr-lightblue">PRODUCT CATALOG</h2> -->
                            <div class="row row-grid mt-4 mt-sm-5">
                                @foreach ($products as $product)
                                    <div class="card border-0 col-md-12 px-2 col-lg-6 col-xl-4 parent-prdct pb-3">
                                        <div class="main-boxprdct pb-4 card-body">
                                            <div class="parent-med-btl">
                                                <img class="about-img prdct-btl mb-4"
                                                    src="{{ asset('storage/images/' . $product->single_image) }}"
                                                    alt="med">
                                            </div>
                                            <div>
                                                <a class="text-decoration-none"
                                                    href="{{ route('product-detail', ['id' => $product->slug]) }}">
                                                    <p class="mx-auto fw-bold mt-0 mb-0 text-order text-main-prdct">
                                                        {{ $product->productname }}</p>
                                                </a>
                                                <p class="clr-grey font-15 mb-0">{{ $product->inactive_ingredients }}</p>
                                            </div>
                                            <!-- <div class="parent-detail-prdct">
                                                <p class="mx-auto clr-grey mb-0"><span
                                                        class="clr-black">{{ $product->unit_size_type }}</span> multi-use
                                                    bottles</p>
                                                <p class="clr-grey"><span
                                                        class="clr-black">{{ $product->package_size }}</span>
                                                    units per Box</p>
                                            </div> -->
                                            <div class="d-flex align-items-center mt-2 pdf-btn justify-content-between">
                                                @php
                                                    $latestLot = \App\Models\Lot::latest()->first();
                                                @endphp
                                                @auth('web')
                                                @if ($latestLot && $latestLot->file)
                                                    <a class="text-decoration-none gap-1 clr-black d-flex align-items-center"
                                                        href="{{ asset('storage/lot-files/' . $latestLot->file) }}"
                                                        download>
                                                        <img class="mb-0 pdf-icon"
                                                            src="{{url('/')}}/public/frontend/assets/images/pdf-two.png" alt="pdf">
                                                        <span class="inner-pdf-text clr-lightblue fw-bold">Available inventory BUD</span>
                                                    </a>
                                                @endif
                                                @endauth
                                                <a class="text-decoration-none clr-black d-flex gap-1 align-items-center parent-cartorder ms-auto"
                                                    href="{{ route('product-detail', ['id' => $product->slug]) }}">
                                                    <img class="mb-0 cart-icon" src="./frontend/assets/images/carthree.png"
                                                        alt="cart">
                                                    <span class="inner-pdf-text-cart text-white">Order</span>
                                                </a>

                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="location-section py-4 py-sm-5">
        <div class="container">
            <h2 class="text-center location-sec-header fw-bold">We are licensed to do buisness in the following states
            </h2>
            <div class="parent-location-sec row align-items-center mt-2 mt-sm-4">
                <div class="col-md-6 mt-3 img-sec mt-md-5">
                    <img class="img-fluid " src="{{ url('/') }}/frontend/assets/images/map-image.png" alt="med">
                </div>
                <div class="col-md-6">
                    <div class="parent-location-left-sec">

                        <div class="parent-menu-location">
                            <h4 class="text-center pt-4 pt-md-2 pt-lg-5">We are working to expand our reach!</h4>
                            <div class="d-flex li-div align-items-start li-div px-lg-4 px-md-2 px-sm-4  pt-3 pt-md-4 pb-3 pb-md-5">
                                <div class="text-center inner-menu-div in-li col-md-6 px-lg-4 px-md-2 px-sm-4 d-flex flex-column gap-2">
                                    <li class="text-white px-1 px-sm-5 px-md-0 py-2">
                                    Arizona 
                                    </li>
                                    <li class="text-white px-4 px-sm-5 px-md-0 py-2">
                                    California
                                    </li>
                                    <li class="text-white px-4 px-sm-5 px-md-0 py-2">
                                    Colorado 
                                    </li>
                                    <li class="text-white px-4 px-sm-5 px-md-0 py-2">
                                    Connecticut
                                    </li>
                                    <li class="text-white px-3 px-sm-5 px-md-0 py-2">
                                    Delaware
                                    </li>
                                    <li class="text-white px-3 px-sm-5 px-md-0 py-2">
                                    Hawaii 
                                    </li>
                                    <li class="text-white px-3 px-sm-5 px-md-0 py-2">
                                        Illinois
                                    </li>
                                    <li class="text-white px-3 px-sm-5 px-md-0 py-2">
                                    Indiana 
                                    </li>
                                    <li class="text-white px-4 px-sm-5 px-md-0 py-2">
                                    Pennsylvania 
                                    </li>
                                    <li class="text-white px-3 px-sm-5 px-md-0 py-2">
                                    Utah 
                                    </li>
                                    <li class="text-white px-3 px-sm-5 px-md-0 py-2">
                                    Rhode Island 
                                    </li>
                                    <li class="text-white px-3 px-sm-5 px-md-0 py-2">
                                    South Carolina 
                                    </li>

                                </div>
                                <div class="text-center  inner-menu-div col-md-6 in-li  px-lg-4 li-div px-md-2 px-sm-4 d-flex flex-column gap-2">
                                    <li class="text-white px-3 px-sm-5 px-md-0 py-2">
                                    Louisiana 
                                    </li>
                                    <li class="text-white px-sm-4 px-md-0 py-2">
                                    Maryland 
                                    </li>
                                    <li class="text-white px-sm-4 px-md-0 py-2">
                                    Massachusetts 
                                    </li>
                                    <li class="text-white px-3 px-sm-5 px-md-0 py-2">
                                    Minnesota [MN]
                                    </li>
                                    <li class="text-white px-3 px-sm-5 px-md-0 py-2">
                                    New Hampshire 
                                    </li>
                                    <li class="text-white px-3 px-sm-5 px-md-0 py-2">
                                    New Mexico 
                                    </li>
                                    <li class="text-white px-3 px-sm-5 px-md-0 py-2">
                                    North Carolina 
                                    </li>
                                    <li class="text-white px-4 px-sm-5 px-md-0 py-2">
                                   Tennessee 
                                    </li>
                                    <li class="text-white px-3 px-sm-5 px-md-0 py-2">
                                    Texas 
                                    </li>
                                    <li class="text-white px-3 px-sm-5 px-md-0 py-2">
                                    Washington 
                                    </li>
                                    <li class="text-white px-3 px-sm-5 px-md-0 py-2">
                                    West Virginia 
                                    </li>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @endsection

