@extends('frontend.layouts.app')

@section('content')

    <!--<section class="banner-section index-sec position-relative py-md-5 py-4 mb-md-5 mb-0">-->
    <!--    <div class="container">-->
    <!--        <div class="row align-items-center justify-content-start banner-inner">-->
    <!--             <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
    <!--            <div class="col-xl-6 col-lg-6 col-md-12 main-banner-left-inner">-->
    <!--                <p class="text-white fw-bold">California Licensed 503B Outsourcing Facility</p>-->
    <!--                <h1 class="text-white">-->
    <!--                    Your Outsourcing <br> Partner for High <br> Quality Pharmaceuticals-->
    <!--                </h1>-->
    <!--                <div class="parent-banner-btn mt-5">-->
    <!--                    <button type="button" class="banner-button border-0 text-white py-2 px-4 fw-bold">ORDER-->
    <!--                        NOW</button>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</section>-->

    <section class=" index-sec position-relative mb-md-0 mb-0">
        <div class="banner mb-0">
            <div>
                <section class="banner-section  py-md-5 py-4 index-sec  ">
                    <div class="container">
                        <div class="row align-items-center justify-content-start banner-inner">
                            <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
                            <div class="col-xl-6 col-lg-6 col-md-12 main-banner-left-inner">
                                {{-- <p class="text-white fw-bold">California based FDA Registered & Inspected 503B Outsourcing Facility.</p> --}}
                                <h1 class="text-white">
                                    Your Outsourcing Partner for High Quality Medicine at Affordable Pricing
                                </h1>

                                <div class="parent-banner-btn mt-5">
                                    <a href="{{ url('/') }}/product"
                                        class="banner-button border-0 text-white py-2 px-4 ">ORDER
                                        NOW</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div>
                <section class="banner-section second-slide py-md-5 py-4 index-sec ">
                    <div class="container">
                        <div class="row align-items-center justify-content-start banner-inner">
                            <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
                            <div class="col-xl-6 col-lg-6 col-md-12 main-banner-left-inner">
                                {{-- <p class="text-white fw-bold">California based FDA Registered & Inspected 503B Outsourcing Facility.</p> --}}
                                <h1 class="text-white">
                                    Helping You Create A Healthy Lifestyle For Your Patients
                                </h1>
                                <div class="parent-banner-btn mt-5">
                                    <a href="{{ url('/') }}/product"
                                        class="banner-button border-0 text-white py-2 px-4 ">ORDER
                                        NOW</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
    <section class="about-section">
        <div class="container">
            <div class="row about-info  margin-bottoms align-items-center">
                <div class="col-md-6">
                    <h2>About MedisourceRx</h2>
                    <p class="clr-lightblack ">MedisourceRx is FDA-inspected and registered Outsourcing facility located in Los Alamitos, California.</p>
                </div>
                <div class="col-md-6">
                    <img class="img-fluid about-img" src="./frontend/assets/images/about-img.png" alt="about-img">
                </div>
            </div>
        </div>
    </section>
    @if (Auth()->user() == null)
        <section class="three-core-section about-section">
            <div class="container">
                <div class="row py-0 py-sm-0 align-items-start gap-4 gap-sm-5 gap-lg-0 justify-content-between">
                    <div class="col-lg-4 col-md-12">
                        <div class="inner-about-parent text-center  px-4 py-4 py-xxl-5">
                            <img class="img-fluid about-img" src="./frontend/assets/images/medeitation.png"
                                alt="medeitation">
                            <h4 class="text-white mt-4">Accessible Medication</h4>
                            <p class="text-white text-start mt-4 mb-0">Our compounding pharmacy is dedicated to ensuring accessible medication solutions for all.
                            </p>
                            <div class="parent-banner-btn mt-3 mt-lg-4 mt-xxl-5 mb-2">

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="inner-about-parent text-center  px-4 py-4 py-xxl-5">
                            <img class="img-fluid about-img" src="./frontend/assets/images/gmp.png" alt="gmp">
                            <h4 class="text-white mt-4">MedisourceRx Credentials</h4>
                            <ul class="text-start text-white mt-4">
                                <li class="about-li">
                                    Registered FDA
                                </li>
                                <li class="about-li">
                                    Registered DEA facility
                                </li>
                                <li class="about-li">
                                    State Board of Pharmacy licensed
                                </li>
                            </ul>
                            <div class="parent-banner-btn mt-3 mt-lg-4 mt-xxl-5 mb-2">

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="inner-about-parent text-center px-4 py-4 py-xxl-5">
                            <img class="img-fluid about-img" src="./frontend/assets/images/outsource.png " alt="530d">
                            <h4 class="text-white mt-4">503B Outsourcing Facilities</h4>
                            <p class="text-white text-start mt-4 mb-0">We're here to help your practice grow with our diverse range of 503B-compliant pharmacy products.</p>
                            <div class="parent-banner-btn mt-3 mt-lg-4 mt-xxl-5 mb-2">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section>
            <div class="">
                <div id="home-product-image-slider" class="owl-carousel owl-theme">
                    <div class="item"><img src="{{ url('/') }}/assets/images/slider-1.jpg" alt=""></div>
                    <div class="item"><img src="{{ url('/') }}/assets/images/slider-2.jpg" alt=""></div>
                    <div class="item"><img src="{{ url('/') }}/assets/images/slider-3.jpg" alt=""></div>
                </div>
            </div>
        </section>
    @endif
    <section class="order-section">
        <div class="container">
            <div class="row my-4 my-sm-5 align-items-center">
                <div class="col-xl-6 col-lg-8 col-md-12">
                    <p class="clr-lightblue fw-bold">WE’VE GOT WHAT YOU’RE LOOKING FOR</p>
                    <h2>
                        California based FDA Registered & Inspected 503B Outsourcing Facility.
                    </h2>
                </div>
                <div class="col-md-6 text-end">
                    <div class="parent-banner-btn order-in-btn mt-3 mt-xl-5">
                        <div class="order-btn">
                            <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                                href="{{ url('/') }}/product">
                                <span class="inner-pdf-text-cart  text-white">ORDER NOW</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="owl-carousel home-product-slider owl-theme">
                @if (!blank($products))
                    @foreach ($products as $product)
                        <div class="item">
                            <div class="pro-in-cart">
                                <div class="parent-med-btl mt-5">
                                    <a href="{{ route('product-detail', ['id' => $product->slug]) }}">
                                        <div class="blue-box position-relative">
                                            <img class="about-img prdct-btl"
                                                src="{{ asset('storage/images/' . $product->single_image) }}"
                                                alt="med">
                                        </div>
                                    </a>
                                </div>
                                {{-- <a href="{{ route('product-detail', ['id' => $product->slug]) }}"> --}}
                                <p class="mx-auto fw-bold product-name mt-3"><a href="{{ route('product-detail', ['id' => $product->slug]) }}" class="text-dark text-decoration-none">{{ $product->productname }}</a></p>
                                {{-- </a> --}}
                                <div class="add-cart-btn">
                                    <div class="dw-btn">
                                        @auth
                                            <form action="{{ route('add.to.cart', $product->id) }}" method="post">
                                                @csrf
                                                @php
                                                    $userId = auth()->user() ? auth()->user()->id : null;
                                                    $doctorPrice = $userId
                                                        ? DB::table('doctor_prices')
                                                            ->where('doctor_id', $userId)
                                                            ->where('product_id', $product->id)
                                                            ->first()
                                                        : null;
                                                @endphp
                                                @auth
                                                    @if ($doctorPrice && $doctorPrice->price !== null)
                                                        <p>Price: ${{ $doctorPrice->price }}</p>
                                                        <input type="hidden" name="price" value="{{ $doctorPrice->price }}">
                                                    @else
                                                        <p>Price: ${{ $product->price }}</p>
                                                        <input type="hidden" name="price" value="{{ $product->price }}">
                                                    @endif

                                                    {{-- Check if $isDoctor is true --}}
                                                    @isset($isDoctor)
                                                        @if ($isDoctor)
                                                            <p>This information is visible to doctors only.</p>
                                                        @endif
                                                    @endisset
                                                @else
                                                @endauth
                                                @php
                                                    $packages = DB::table('product_packages')
                                                        ->where('product_id', $product->id)
                                                        ->first();
                                                @endphp
                                                @if (!blank($packages))
                                                    <input type="hidden" name="package_price" value="{{ $packages->id }}">
                                                @endif
                                                <input type="hidden" name="qty" value="1">
                                                <input type="hidden" name="doctor_id" value="{{ Auth::guard('web')->user()->id }}">
                                                <button
                                                    class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                                                    type="submit">
                                                    <span class="inner-pdf-text-cart text-white">ADD TO CART</span>
                                                </button>
                                            </form>
                                        @else
                                            <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                                                href="{{ route('logindoctor') }}">
                                                <span class="inner-pdf-text-cart  text-white">ADD TO CART</span>
                                            </a>
                                        @endauth
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
            <!-- <div class="row align-items-center  pt-5 justify-content-center">
                            <section class="page-sec">
                    <div class="container">
                        <div class="pages-main">
                            <div class="page-one">
                            @if (!blank($products))
    @foreach ($products as $product)
    <div class="col-md-4 pro-in-cart">
                                            <div class="parent-med-btl mt-5">
                                                <a href="{{ route('product-detail', ['id' => $product->id]) }}">
                                                    <div class="blue-box position-relative">
                                                        <img class="about-img prdct-btl"
                                                            src="{{ asset('storage/images/' . $product->single_image) }}" alt="med">
                                                    </div>
                                                </a>
                                            </div>
                                            <p class="mx-auto fw-bold mt-2">{{ $product->productname }}</p>
                                            <div class="add-cart-btn">
                                                <div class="dw-btn">
                                                    @auth
                                                                        <form action="{{ route('add.to.cart', $product->id) }}" method="post">
                                                                            @csrf
                                                                            @php
                                                                                $userId = auth()->user()
                                                                                    ? auth()->user()->id
                                                                                    : null;
                                                                                $doctorPrice = $userId
                                                                                    ? DB::table('doctor_prices')
                                                                                        ->where('doctor_id', $userId)
                                                                                        ->where(
                                                                                            'product_id',
                                                                                            $product->id,
                                                                                        )
                                                                                        ->first()
                                                                                    : null;
                                                                            @endphp
                                                                            @auth
                                                                                                @if ($doctorPrice && $doctorPrice->price !== null)
            <p>Price: ${{ $doctorPrice->price }}</p>
                                                                                                    <input type="hidden" name="price" value="{{ $doctorPrice->price }}">
        @else
            <p>Price: ${{ $product->price }}</p>
                                                                                                    <input type="hidden" name="price" value="{{ $product->price }}">
            @endif

                                                                                                {{-- Check if $isDoctor is true --}}
                                                                                                @isset($isDoctor)
                @if ($isDoctor)
                <p>This information is visible to doctors only.</p>
                @endif
            @endisset
        @else
        @endauth
                                                                            <input type="hidden" name="qty" value="1">
                                                                            <input type="hidden" name="doctor_id" value="{{ Auth::guard('web')->user()->id }}">
                                                                            <button
                                                                                class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                                                                                type="submit">
                                                                                <span class="inner-pdf-text-cart py-1  px-2 text-white">ADD TO CART</span>
                                                                            </button>
                                                                        </form>
    @else
        <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                                                                            href="{{ route('jump') }}">
                                                                            <span class="inner-pdf-text-cart py-1  px-2 text-white">ADD TO CART</span>
                                                                        </a>
                                                    @endauth
                                                </div>

                                            </div>
                                        </div>
    @endforeach
    @endif
                            </div>
                        </div>
                    </div>
                </section> -->
            <!-- @if (!blank($products))
    @foreach ($products as $product)
    <div class="col-md-4 pro-in-cart">
                                            <div class="parent-med-btl mt-5">
                                                <a href="{{ route('product-detail', ['id' => $product->id]) }}">
                                                    <div class="blue-box position-relative">
                                                        <img class="about-img prdct-btl"
                                                            src="{{ asset('storage/images/' . $product->single_image) }}" alt="med">
                                                    </div>
                                                </a>
                                            </div>
                                            <p class="mx-auto fw-bold mt-2">{{ $product->productname }}</p>
                                            <div class="add-cart-btn">
                                                <div class="dw-btn">
                                                    @auth
                                                                        <form action="{{ route('add.to.cart', $product->id) }}" method="post">
                                                                            @csrf
                                                                            @php
                                                                                $userId = auth()->user()
                                                                                    ? auth()->user()->id
                                                                                    : null;
                                                                                $doctorPrice = $userId
                                                                                    ? DB::table('doctor_prices')
                                                                                        ->where('doctor_id', $userId)
                                                                                        ->where(
                                                                                            'product_id',
                                                                                            $product->id,
                                                                                        )
                                                                                        ->first()
                                                                                    : null;
                                                                            @endphp
                                                                            @auth
                                                                                                @if ($doctorPrice && $doctorPrice->price !== null)
            <p>Price: ${{ $doctorPrice->price }}</p>
                                                                                                    <input type="hidden" name="price" value="{{ $doctorPrice->price }}">
        @else
            <p>Price: ${{ $product->price }}</p>
                                                                                                    <input type="hidden" name="price" value="{{ $product->price }}">
            @endif

                                                                                                {{-- Check if $isDoctor is true --}}
                                                                                                @isset($isDoctor)
                @if ($isDoctor)
                <p>This information is visible to doctors only.</p>
                @endif
            @endisset
        @else
        @endauth
                                                                            <input type="hidden" name="qty" value="1">
                                                                            <input type="hidden" name="doctor_id" value="{{ Auth::guard('web')->user()->id }}">
                                                                            <button
                                                                                class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                                                                                type="submit">
                                                                                <span class="inner-pdf-text-cart py-1  px-2 text-white">ADD TO CART</span>
                                                                            </button>
                                                                        </form>
    @else
        <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                                                                            href="{{ route('jump') }}">
                                                                            <span class="inner-pdf-text-cart py-1  px-2 text-white">ADD TO CART</span>
                                                                        </a>
                                                    @endauth
                                                </div>

                                            </div>
                                        </div>
    @endforeach
    @endif -->
            <!--<div class="col-md-4 pro-in-cart bt-in">-->
            <!--    <div class="parent-med-btl mt-5">-->
            <!--        <a href="product.html">-->
            <!--            <div class="blue-box position-relative">-->
            <!--                <img class="about-img prdct-btl" src="./frontend/assets/images/med-btl.png" alt="med">-->
            <!--            </div>-->
            <!--        </a>-->
            <!--    </div>-->
            <!--    <p class="mx-auto fw-bold mt-2">Methylcobalamin MDV 1mg/mL</p>-->
            <!--    <div class="add-cart-btn">-->
            <!--        <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"-->
            <!--            href="#">-->
            <!--            <span class="inner-pdf-text-cart  py-1 px-2 text-white">ADD TO CART</span>-->
            <!--        </a>-->

            <!--    </div>-->
            <!--</div>-->
            <!--<div class="col-md-4 pro-in-cart bt-in">-->
            <!--    <div class="parent-med-btl mt-5">-->
            <!--        <a href="product.html">-->
            <!--            <div class="blue-box position-relative">-->
            <!--                <img class="about-img prdct-btl" src="./frontend/assets/images/med-btl.png" alt="med">-->
            <!--            </div>-->
            <!--        </a>-->
            <!--    </div>-->
            <!--    <p class="mx-auto fw-bold mt-2">Methylcobalamin MDV 1mg/mL</p>-->
            <!--    <div class="add-cart-btn">-->
            <!--        <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"-->
            <!--            href="#">-->
            <!--            <span class="inner-pdf-text-cart  py-1 px-2 text-white">ADD TO CART</span>-->
            <!--        </a>-->

            <!--    </div>-->
            <!--</div>-->
        </div>
        </div>
    </section>
    <section class="location-section py-4 py-sm-5">
        <div class="container">
            <h2 class="text-center location-sec-header fw-bold">We are licensed to do buisness in the following states
            </h2>
            <div class="parent-location-sec row align-items-center mt-2 mt-sm-4">
                <div class="col-md-6 mt-2 mt-md-5">
                    <img class="img-fluid " src="{{ url('/') }}/frontend/assets/images/map-image.png"
                        alt="med">
                </div>
                <div class="col-md-6">
                    <div class="parent-location-left-sec">

                        <div class="parent-menu-location">
                            <h4 class="text-center pt-4 pt-md-2 pt-lg-5">We are working to expand our reach!</h4>
                            <div
                                class="d-flex li-div align-items-start li-div px-lg-4 px-md-2 px-sm-4  pt-3 pt-md-4 pb-3 pb-md-5">


                                <div
                                    class="text-center inner-menu-div in-li col-md-6 px-lg-4 px-md-2 px-sm-4 d-flex flex-column gap-2">
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



                                <div
                                    class="text-center inner-menu-div col-md-6 in-li  px-lg-4 li-div px-md-2 px-sm-4 d-flex flex-column gap-2">
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
                                        Minnesota
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
    <section class="contact-main-section">
        <div class="container">
            <h2 class="text-center">How can we help you today?</h2>
            <h4 class="text-center fw-normal">Our in-house team is at your service.</h4>
            <h4 class="text-center fw-normal">M-F 8:00 AM to 4:00 PM (PST)</h4>
            <div class="row py-4 py-sm-5 pb-sm-0 align-items-center inner-form">
                <div class="col-md-6 mb-md-0 mb-30 mb-sm-5">
                    <form class="row p-3 p-lg-5 g-3 contactform-home mx-0" action="{{ route('contact.submit') }}"
                        method="POST" id="inquiries">
                        @csrf
                        <div class="col-md-6">
                            <div class="c-field">

                                <label for="validationCustom01" class="form-label text-white">Your Name</label>

                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name">
                                <div class="invalid-feedback">
                                    @error('name')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="c-field">

                                <label for="validationCustom02" class="form-label text-white">Phone Number</label>

                                <input type="tel" class="form-control @error('contact') is-invalid @enderror"
                                    id="contact" name="contact">
                                <div class="invalid-feedback">
                                    @error('contact')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="c-field">

                                <label for="validationCustom01" class="form-label text-white ">Your Email</label>

                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email">
                                <div class="invalid-feedback">
                                    @error('email')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <label for="validationCustom02" class="form-label text-white">Your state</label>

                            <input type="text" class="form-control @error('state') is-invalid @enderror"
                                id="state" name="state">
                            <div class="invalid-feedback">
                                @error('state')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">

                            <label for="" class="mb-2 text-white">Message</label>

                            <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="5"></textarea>
                            <div class="invalid-feedback">
                                @error('message')
                                    {{ $message }}
                                @enderror
                            </div>

                        </div>
                        <div class="form-check text-start">
                            <input class="form-check-input check-box" type="checkbox" value="1"
                                id="flexCheckDefault" name="consent">
                            <label class="form-check-label text-white" for="flexCheckDefault">
                                I consent to receive occasional product and promotional updates via email, sms, and phone.
                            </label>
                        </div>
                        <div class="col-12 text-start pt-0">
                            <div class="parent-form-btn mt-3 mt-sm-1   mb-2">
                                <button type="button" class="submit-button border-0 text-white py-2 px-4 fw-bold"
                                    onclick="submitForm()">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="row px-1 px-xl-5 align-items-center parent-inner-box">
                        <div class="col-md-12 mb-md-0 mb-4 text-center">
                            <a class="text-decoration-none text-call-mail-color text-decoration-none"
                                href="mailto:sales@medisourcerx.com">
                                <div class="inner-contact-main-box py-4 py-lg-5">
                                    <img class="img-fluid" src="./frontend/assets/images/email.svg" alt="chat">
                                    <p class="clr-black mt-3 text-call-mail-color text-decoration-none fw-bold">
                                        sales@medisourcerx.com</p>
                                </div>
                            </a>
                        </div>
                        <!-- <div class="col-md-6  text-center">
                                <a class="text-decoration-none" href="{{ route('faq') }}">
                                    <div class="inner-contact-main-box py-4 py-lg-5">
                                        <img class="img-fluid" src="./frontend/assets/images/faq.svg" alt="faq">
                                        <p class="clr-black mt-3 fw-bold">FAQs</p>
                                    </div>
                                </a>
                            </div> -->
                    </div>
                    <div class="row px-1 px-xl-5 align-items-center parent-inner-box mt-4">
                        <div class="col-md-12  mb-md-0 mb-4 text-center">
                            <a class="text-decoration-none" href="tel:+17144551300">
                                <div class="inner-contact-main-box py-4 py-lg-5">
                                    <img class="img-fluid" src="./frontend/assets/images/call.svg" alt="call">
                                    <p class="mt-3 text-call-mail-color fw-bold">(714) 455-1300</p>
                                </div>
                            </a>
                        </div>
                        <!-- <div class="col-md-6 text-center">
                                <a class="text-decoration-none" href="tel:+1 714-455-1300">
                                    <div class="inner-contact-main-box py-4 py-lg-5">
                                        <img class="img-fluid" src="./frontend/assets/images/text.svg" alt="text">
                                        <p class="clr-black mt-3 fw-bold">+1 714-455-1300</p>
                                    </div>
                                </a>
                            </div> -->
                    </div>
                    <div class="parent-banner-btn text-center mt-4 mt-md-5 mb-2">
                        <div class="con-btn">
                            <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                                href="{{ route('product') }}">
                                <span class="inner-pdf-text-cart  text-white">ORDER
                                    NOW</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="bottomdiv hide" id="cookie-div" style="display:none;">
        <div class="open-bottom-card">
            <h4>This website uses cookies.</h4>
            <div>
                <p><span>We use cookies to analyze website traffic and optimize your website experience. By accepting our
                        use of cookies, your data will be aggregated with all other user data.</span></p>
            </div>

            <div class="inner-buttons">
                <a class="accept" id="accept-btn" href="javascript:void(0)">Accept </a>
                <a href="javascript:void(0)" id="close-btn">Close</a>
            </div>
        </div>
    </div>

    <!-- Your existing script -->
    <script>
        function submitForm() {
            var isValid = validateForm();
            if (isValid) {
                var formData = $('#inquiries').serialize();
                $.ajax({
                    url: $('#inquiries').attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Your form has been submitted successfully.',
                            icon: 'success',
                        });
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.error('AJAX Error:', errorThrown);
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was an error submitting the form. Please try again later.',
                            icon: 'error',
                        });
                    }
                });
            }
        }

        function validateForm() {
            var isValid = true;
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            isValid = validateField($('#name'), 'Name is required.') && isValid;
            isValid = validateField($('#contact'), 'Contact is required.') && isValid;
            isValid = validateEmailField($('#email')) && isValid;
            isValid = validateField($('#state'), 'State is required.') && isValid;
            isValid = validateField($('#message'), 'Message is required.') && isValid;
            // if (!$('#flexCheckDefault').prop('checked')) {
            //     $('.check-box').addClass('is-invalid');
            //     isValid = false;
            // }
            return isValid;
        }

        function validateField(field, errorMessage) {
            var value = field.val();
            if (value === '') {
                field.addClass('is-invalid');
                field.parent().append('<div class="invalid-feedback">' + errorMessage + '</div>');
                return false;
            }
            return true;
        }

        function validateEmailField(emailField) {
            var value = emailField.val().trim();
            var isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
            if (!isValid) {
                emailField.addClass('is-invalid');
                emailField.parent().append('<div class="invalid-feedback">Enter a valid email address.</div>');
            }
            return isValid;
        }
    </script>


@endsection
