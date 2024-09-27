@extends('frontend.layouts.app')

@section('content')
<section class="banner-section about-parent prdct-parent position-relative py-sm-5 py-1">
    <div class="container">
        <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start ">
            <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
            <div class="col-md-8">
                <h1 class="text-white text-start">
                    Methylcobalamin 5mg/mL
                </h1>
                <div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}" class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page"><a href="{{ route('product') }}" class="text-decoration-none text-white">Products</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="" class="text-decoration-none text-white"> Methylcobalamin 5mg/mL</a></li>
                            </ol>
                        </nav>
                    </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">

        <div class="glutathione-inner">
            <img src="{{url('/')}}/frontend/assets/images/g1.webp" alt="">
            <div class="gluta-info">
                <h3>Methylcobalamin 5mg/mL

                </h3>
                <p>Methylcobalamin is a natural-occurring coenzyme form of vitamin B12 that circulates through the
                    bloodstream. It is required by our body to protect DNA and RNA, support in increasing the
                    availability of physical energy and also supports brain and nerve health.



                </p>
                <p>MedisourceRx has a 180 day BUD for Glutathione.

                </p>
                <div class="gluta-btn">
                    <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                        href="jump.php">
                        <span class="inner-pdf-text-cart text-white">ORDER NOW</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection