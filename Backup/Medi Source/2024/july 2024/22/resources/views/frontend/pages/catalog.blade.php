@extends('frontend.layouts.app')

@section('content')
<section class="banner-section prdct-parent about-parent position-relative py-sm-5 py-1">
    <div class="container">
        <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start ">
            <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
            <div class="col-md-8">
                <h1 class="text-white text-start">
                    Our Catalog
                </h1>
                <div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}" class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page"><a href="" class="text-decoration-none text-white">Our  Catalog</a></li>
                            </ol>
                        </nav>
                    </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="consider-us-inner">
            <div class="consider-info">
                <h2>Consider Us
                </h2>
                <div>
                    <p>Whether you are a large integrative medical practice or simply administer the occasional vitamin
                        B12 shot, consider MedisourceRx for your wellness product needs.

                    </p>
                    <p>Please call MedisourceRx for more information on quantities, concentrations and BUD of the
                        following medications:

                    </p>
                </div>
            </div>
            
            <div class="catalog-items">

                <h2>Current Product Offerings
                </h2>




                <div class="owl-carousel home-product-slider  owl-theme">
                    @if (!blank($products))
                        @foreach ($products as $product)
                        <div class="item">
                            <div class="pro-in-cart">
                                <div class="parent-med-btl mt-5">
                                    <a href="{{ route('product-detail', ['id' => $product->slug]) }}">
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
                                                <input type="hidden" name="qty" value="1">
                                                <input type="hidden" name="doctor_id" value="{{ Auth::guard('web')->user()->id }}">

                                            </form>
                                        @else
                                            <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                                                href="{{ route('jump') }}">
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





            </div>
        </div>
    </div>
</section>


<section>
    <div class="catlog-last-info">

        <div class="catlog-limgs">
            <img src="{{url('/')}}/frontend/assets/images/3665487.jpg" alt="">
        </div>
        <div class="container">
            <div class="cat-inner-det">
                <h2>Our Future Product Pipeline :
                </h2>
                <h4>NOTE:
                </h4>
                <p>Please call MedisourceRx for more information on when these future offerings will be made available
                    for purchase.

                </p>
                <div class="c-us-btn">

                    <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center call-btn"
                        href="tel:+(714)455-1300">
                        <span class="inner-pdf-text-cart fw-bold "> CALL US NOW</span>
                    </a>
                </div>


                <h4>MIC IV
                </h4>
                <h4>VITAMIN B COMPLEX IV
                </h4>
                <p>(Riboflavin, Niacin, Pyridoxine, Thiamine)
                </p>
                <h4>ASCORBIC ACID 500MG/ML IV
                </h4>
                <h4>SEMAGLUTIDE IV
                </h4>
            </div>
        </div>
    </div>

</section>
@endsection
