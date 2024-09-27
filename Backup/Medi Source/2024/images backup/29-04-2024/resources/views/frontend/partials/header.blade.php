<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="{{ url('/') }}/frontend/assets/bootstrap.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/frontend/assets/style.css" class="stylesheet">
    <link rel="stylesheet" href="{{ url('/') }}/frontend/assets/media.css" class="stylesheet">
    <link rel="stylesheet" href="{{ url('/') }}/frontend/assets/owl.carousel.css">
    <link rel="stylesheet" href="{{ url('/') }}/frontend/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/frontend/assets/owl.theme.default.css">
    <link rel="stylesheet" href="{{ url('/') }}/frontend/assets/all.min.css" class="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.3/animate.min.css"
        class="stylesheet">
    @if ($setting && $setting->favicon)
    <link rel="shortcut icon" href="{{ asset('/storage/' . $setting->favicon) }}">
    @endif

    <!-- font cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- slider link -->
    <link rel="stylesheet" href="{{ url('/') }}/frontend/assets/slick-theme.min.css" />
    <link rel="stylesheet" href="{{ url('/') }}/frontend/assets/slick.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" /> --}} -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
        rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css"
        rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css"
        rel="stylesheet" />
    <link href="{{ url('/') }}/frontend/assets/select2.min.css" rel="stylesheet" />
    <!-- Add these lines to your HTML file -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ url('/') }}/frontend/assets/axios.min.js"></script>

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     -->
    <title>MedisourceRx</title>
    @yield('style')
</head>

<body>
    <div class="top-f">


        <!-- <div class="container-fluid upheader py-3">
            <div class="container">
                <div class="d-flex flex-column flex-lg-row align-items-lg-center align-items-start gap-lg-0 gap-2">
                    {{-- <p class="text-white s-icons upper-headtext align-items-center d-flex gap-3 mb-0">
                    <img class="img-fluid" src="{{url('/')}}/frontend/assets/images/location.png" alt="location">
                    10525 Humbolt St, Los Alamitos, CA 90720
                    </p> --}}
                    <p class="text-white s-icons upper-headtext align-items-center d-flex gap-3 mb-0 ms-lg-auto ms-0">

                            <a href="mailto:info@medisourcerx.com"><i class="fa fa-envelope text-white me-3"></i> info@medisourcerx.com</a>
                    </p>
                    <p
                        class="text-white s-icons upper-headtext align-items-center d-flex gap-3 mb-0 ms-xl-5 ms-lg-3 ms-0">
                        

                        <a href="tel:+1 714-455-1300"> <i class="fa fa-phone text-white me-3" aria-hidden="true"></i>  +1 714-455-1300</a>

                    </p>
                </div>
            </div>
        </div> -->

        
        <header>
            <div class="top-header text-center">
                <div class="container">

                @if(Auth()->user() == null)     
                    <div class="top-header-inner">
                        <div class="register-detail">
                            <p class="m-0"><a href="{{route('register')}}">New To Rx?  Register Here</a></p>
                        </div>
                    </div>    
                @endif    
                @if(Auth()->user() !== null)        
                    <div class="justify-content-end top-header-inner">
                        <div class="top-header-detail">
                                
                                <ul class="navbar-nav top-ul gap-xl-2 gap-1 mx-auto ">

                                <li class="nav-item position-relative email-dropdown">
                                    <div class="dd-inner">
                                    <ul class="navbar-nav  gap-xl-2 gap-1 mx-auto">
                                        <a type="button" class="nav-link text-white dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" href="javascript:void(0);">Important Contact Information</a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <p> For Orders :</p>
                                                <a class="" href="mailto:Order@medisourcerx.com">Order@medisourcerx.com</a> 
                                        </li>
                                        <li>
                                            <p>For General Inquiries :</p>
                                            <a class="" href="mailto:gabrielle@medisourcerx.com">gabrielle@medisourcerx.com</a> 
                                        </li>
                                        <li>
                                            <p>For Shipment :</p>
                                            <a class="" href="mailto:gabrielle@medisourcerx.com">bryan@medisourcerx.com</a> 
                                        </li>
                                    </ul>
                                    </div>
                                    </li>
                                </ul>
                                
                        </div>
                    </div>        
                    @endif        
                </div>
            </div>
          
            <div class="container-fluid nav-out">
                <div class="parent-nav container">
                    <nav class="py-2 navbar navbar-expand-lg navbar-dark bg-transparent">
                        <a class="navbar-brand me-0" href="{{url('/')}}">
                            <img class="img-fluid main-logo" src="{{ url('/') }}/frontend/assets/images/main-logo-b.png"
                                alt="logo">
                        </a>
                        <!--<a href="cart.php">-->
                        <!--    <div class="add-not d-lg-none d-block ml-4">-->

                        <!--        <div class="num-note">2</div>-->

                        <!--    </div>-->
                        <!--</a>-->
                        @auth
                                    @php
                                    $cart_items = DB::table('cart_items')
                                    ->where('user_id', Auth::user()->id)
                                    ->count();
                                    @endphp
                                    @if ($cart_items > 0)
                                    <a href="{{route('cart')}}" class="me-1 m-s">
                                        <div class="add-not ml-4 d-none-lg d-block">
                                            <div class="num-note">{{$cart_items}}</div>
                                            <img src="{{ url('/') }}/frontend/assets/images/cart.png" alt="" width="30px">
                                            <!-- <i class="fa fa-shopping-cart text-white"></i> -->
                                        </div>
                                    </a>
                                    @endif
                                @endauth
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="ms-lg-0 ms-0 mb-lg-0 mb-5 dekstop nav-menus-ms mt-lg-0 mt-4 collapse navbar-collapse"
                            id="navbarText">
                            <ul class="navbar-nav  gap-xl-2 gap-1 mx-auto mb-5 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link text-white" aria-current="page"
                                        href="{{ route('home') }}">HOME</a>
                                </li>
                                <li class="nav-item position-relative">
                                    <a  type="button"class="nav-link text-white   dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" href="{{ route('about') }}">ABOUT</a>
                                    <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{route('about')}}">About MedisourceRx</a></li>
                                    <li><a class="dropdown-item"  href="{{route('meet-lead-pharmacy')}}">Meet Our Lead Pharmacis</a></li>
                                
                                </ul>
                                </li>
                               
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('product') }}">PRODUCTS</a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('catalog') }}">CATALOG</a>
                                </li> --}}
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('order-page') }}">ORDER</a>

                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('contactus') }}">CONTACT US</a>
                                </li>
                            </ul>
                            <span class="navbar-text cart-f">
                                @auth
                                    @php
                                    $cart_items = DB::table('cart_items')
                                    ->where('user_id', Auth::user()->id)
                                    ->count();
                                    @endphp
                                    @if ($cart_items > 0)
                                    <a href="{{route('cart')}}" class="me-1">


                                        <div class="add-not ml-4">

                                            <div class="num-note">{{$cart_items}}</div>

                                            <img src="{{ url('/') }}/frontend/assets/images/cart.png" alt="" width="30px">
                                            <!-- <i class="fa fa-shopping-cart text-white"></i> -->
                                        </div>
                                    </a>
                                    @endif
                                    <a href="{{route('myaccount')}}" class="text-decoration-none">
                                        <span class="header-btn border-0 mt-lg-0 mt-4 text-white py-2 px-4 ">
                                            MY ACCOUNT
                                        </span>
                                    </a>
                                    
                                    <a href="{{route('logout')}}" class="text-decoration-none">
                                        <i class="ri-logout-box-line fs-18 align-middle me-1"></i>
                                        <span class="header-btn border-0 mt-lg-0 mt-4 text-white py-2 px-4 ">
                                            Logout
                                        </span>
                                    </a>
                                @else
                                <a href="{{ route('logindoctor') }}"
                                    class="header-btn border-0 mt-lg-0 mt-4 text-white py-2 px-4 ">
                                    LOGIN / SIGN UP
                                </a>
                                @endauth
                            </span>
                        </div>
                    </nav>
                </div>
            </div>
        </header>
        <div class="ms-lg-5 ms-0 mb-lg-0 mb-5 nav-menus-ms mt-lg-0 mt-xl-4 collapse navbar-collapse m-nav" id="navbarText">
            <ul class="navbar-nav gap-2 mx-auto mb-4 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link text-white" aria-current="page" href="{{ route('home') }}">HOME</a>
                </li>
                <li class="nav-item position-relative">
                    <a  type="button" class="nav-link text-white   dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" href="{{ route('about') }}">ABOUT</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{route('about')}}">About MedisourceRx</a></li>
                        <li><a class="dropdown-item" href="{{route('meet-lead-pharmacy')}}">Meet Our Lead Pharmacis</a>
                    </ul>
                </li>
               
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('product') }}">PRODUCTS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('order.form') }}">ORDER</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('contactus') }}">CONTACT US</a>
                </li>
            </ul>
            <span class="navbar-text">
                {{-- @auth
        <span class="header-btn border-0 mt-lg-0 mt-4 text-white py-2 px-4 fw-bold">
            Welcome, {{ Auth::user()->name }}
            </span>
            @else --}}
            @auth
                                   
                                   
                                    <a href="{{route('myaccount')}}" class="text-decoration-none">
                                        <span class="header-btn border-0 mt-lg-0 mt-4 text-white py-2 px-4 ">
                                            MY ACCOUNT
                                        </span>
                                    </a>
                                @else
                                <a href="{{ route('logindoctor') }}"
                                    class="header-btn border-0 mt-lg-0 mt-4 text-white py-2 px-4 ">
                                    LOGIN / SIGN UP
                                </a>
                                @endauth
            <!-- <a href="{{ route('logindoctor') }}" class="header-btn border-0 mt-lg-0 mt-4 text-white py-2 px-4 fw-bold">
                Login / Sign Up
            </a> -->
            {{-- @endauth --}}
            </span>

        </div>
    </div>