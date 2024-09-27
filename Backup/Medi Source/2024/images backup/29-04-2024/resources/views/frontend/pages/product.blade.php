@extends('frontend.layouts.app')

@section('content')
    <section class="banner-section prdct-parent mobile-inner-background position-relative py-sm-5 py-1">
        <div class="container">
            <div class="row align-items-center mt-0 pdt-banner mb-0 justify-content-start">
                <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
                <div class="col-lg-8 col-12">

                    <h1 class="text-white text-start text-w">
                        Price, Quality, and Exceptional Customer Service.
                    </h1>
                    <div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}"
                                        class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page"><a href=""
                                        class="text-decoration-none text-white">Product</a></li>
                            </ol>
                        </nav>
                    </div>
                    <h2 class="text-white text-start mt-3 order-text text-w">Order from 503B Compounding Pharmacy</h2>
                    <p class="text-white text-start text-w">Order sterile injectables or topical formulations from our
                        California-licensed FDA-Registered Outsourcing Facility</p>
                    <div class="parent-banner-btn text-start mt-4 mb-2">
                        <a class="banner-button border-0 text-white py-2 px-4 ">ORDER NOW</a>
                    </div>
                    @if (Auth()->user() == null)
                        <div class="text-start p-link mt-3">
                            <a class="text-white fw-bold" href="{{ route('register') }}">
                                Don't have an account? Register here
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="search-section">
                <div class="container-fluid container-xl">
                    <div class="row main-content ml-md-0 mb-md-0 mb-5 justify-content-md-between justify-content-lg-center">
                        <!-- <div class="sidebar mt-0 mt-sm-0 col-xl-2 col-lg-3 col-md-4 pe-0">
                                    <div class="sidebar__inner mt-0 mt-sm-0">
                                        <div class="filter-body">
                                            <div>
                                                <div class="accordion accordion-flush mt-4" id="accordionFlushExample">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="flush-headingOne">
                                                            <button class="accordion-button p-0 position-relative" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                                                aria-expanded="false" aria-controls="flush-collapseOne">
                                                                <h2 class="filter-title w-100">PRODUCT CATEGORY</h2>
                                                            </button>
                                                        </h2>
                                                        <div id="flush-collapseOne" class="accordion-collapse collapse show"
                                                            aria-labelledby="flush-headingOne"
                                                            data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body pb-0 pt-2 px-0">
                                                                <div class="mb-30 filter-options" id="categoryContainer">
                                                                    @foreach ($categories as $index => $category)
    <div class="custom-control custom-checkbox mb-1">
                                                                            <input type="checkbox" class="custom-control-input"
                                                                                id="category{{ $index + 1 }}" checked>
                                                                            <label class="custom-control-label"
                                                                                for="category{{ $index + 1 }}">{{ strtoupper($category->name) }}</label>
                                                                        </div>
    @endforeach
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion accordion-flush mt-3" id="accordionFlushExampleOne">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="flush-headingtwo">
                                                            <button class="accordion-button p-0 collapsed position-relative"
                                                                type="button" data-bs-toggle="collapse"
                                                                data-bs-target="#flush-collapsetwo" aria-expanded="false"
                                                                aria-controls="flush-collapseOne">
                                                                <h2 class="filter-title w-100">DOSAGE FORM</h2>
                                                            </button>
                                                        </h2>
                                                        <div id="flush-collapsetwo" class="accordion-collapse collapse"
                                                            aria-labelledby="flush-headingtwo"
                                                            data-bs-parent="#accordionFlushExampleOne">
                                                            <div class="accordion-body pb-0 pt-2 px-0">
                                                                <div class="mb-30 filter-options">
                                                                    @foreach ($dosageForms as $dosageForm)
    <div class="custom-control custom-checkbox mb-1">
                                                                            <input type="checkbox" class="custom-control-input"
                                                                                id="{{ strtolower(str_replace(' ', '', $dosageForm)) }}"
                                                                                checked>
                                                                            <label class="custom-control-label"
                                                                                for="{{ strtolower(str_replace(' ', '', $dosageForm)) }}">
                                                                                {{ $dosageForm->name }}

                                                                            </label>
                                                                        </div>
    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                        <div class="content col-md-12 ps-3 ps-xl-5">
                            <!-- <h2 class="text-center clr-lightblue">PRODUCT CATALOG</h2> -->
                            <div class="row row-grid mt-4 mt-sm-5">
                                @foreach ($products as $product)
                                    <div class="card border-0 col-md-12 px-2 col-lg-6 col-xl-4 parent-prdct pb-3">
                                        @if (NumberFormat::checkUserPermission('product-show'))
                                            <a href="{{ route('product-detail', ['id' => $product->slug]) }}">
                                        @endif
                                        <div class="main-boxprdct pb-4 card-body">
                                            <div class="parent-med-btl">
                                                <img class="about-img prdct-btl mb-4"
                                                    src="{{ asset('storage/images/' . $product->single_image) }}"
                                                    alt="med">
                                            </div>
                                            <div>
                                                @if (NumberFormat::checkUserPermission('product-show'))
                                                    <a class="text-decoration-none"
                                                        href="{{ route('product-detail', ['id' => $product->slug]) }}">
                                                        <p class="mx-auto fw-bold mt-0 mb-0 text-main-prdct">
                                                            {{ $product->productname }}</p>
                                                    </a>
                                                @else
                                                    <p class="mx-auto fw-bold mt-0 mb-0 text-main-prdct">
                                                        {{ $product->productname }}</p>
                                                @endif
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
                                            <div class="d-flex align-items-center pdf-inner-btn justify-content-between">
                                                @php
                                                    $latestLot = \App\Models\Lot::latest()->first();
                                                @endphp

                                                @if ($latestLot && $latestLot->file)
                                                    <a class="text-decoration-none gap-1 clr-black d-flex align-items-center"
                                                        href="{{ asset('storage/lot-files/' . $latestLot->file) }}"
                                                        download>
                                                        <img class="mb-0 pdf-icon"
                                                            src="{{ url('/') }}/public/frontend/assets/images/pdf-two.png"
                                                            alt="pdf">
                                                        <span class="inner-pdf-text clr-lightblue fw-bold">Product
                                                            Insert</span>
                                                    </a>
                                                @endif
                                                @if (NumberFormat::checkUserPermission('product-show'))
                                                    <a class="text-decoration-none clr-black d-flex gap-1 or-btn  align-items-center parent-cartorder"
                                                        href="{{ route('product-detail', ['id' => $product->slug]) }}">
                                                        <img class="mb-0 cart-icon"
                                                            src="{{ url('/') }}/public/frontend/assets/images/carthree.png"
                                                            alt="cart">
                                                        <span class="inner-pdf-text-cart text-white">Order</span>
                                                    </a>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
