@extends('frontend.layouts.app')

@section('content')
<section class="banner-section  about-parent prdct-parent position-relative py-sm-5 py-1">
    <div class="container">
        <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start ">
            <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
            <div class="col-md-8">
                <h1 class="text-white text-start">
                    Login To Your Account
                </h1>
                	
					<div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}" class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page"><a href="" class="text-decoration-none text-white">Login To Your Account</a></li>
                            </ol>
                        </nav>
                    </div>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="container">
        <div class="loginyrac">
            <div class="loginyr-btn">
                <div class="inner-btns">
                    <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                        href="{{route('register')}}">
                        <span class="inner-pdf-text-cart  py-3 text-white">NEW CUSTOMER</span>
                    </a>
                </div>
                <div class="inner-btns">
                    <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                        href="{{route('logindoctor')}}">
                        <span class="inner-pdf-text-cart  py-3 text-white">EXISTING CUSTOMER</span>
                    </a>
                </div>
                <div class="inner-btns">
                    <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                        href="#">
                        <span class="inner-pdf-text-cart  py-3 text-white">CALL CUSTOMER SUPPORT</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
