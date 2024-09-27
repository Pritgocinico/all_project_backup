@extends('frontend.layouts.app')

@section('content')
<section class="banner-section about-parent prdct-parent position-relative py-sm-5 py-1">
    <div class="container">
        <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start ">
            <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
            <div class="col-md-8">
                <h1 class="text-white text-start mt-2">
                    Order Form

                </h1>
               	
					<div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}" class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="" class="text-decoration-none text-white">Order Form</a></li>
                            </ol>
                        </nav>
                    </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">

        <div class="orderf-inner">
            <div class="pdf-img-inner">
                <img src="{{url('/')}}/frontend/assets/images/pdfimg.webp" alt="">
            </div>

            <div class="order-info">
                <h4>Download MedisourceRx Order Form here: </h4>


                <a href="./assets/images/New Order Form.pdf" class="pdf-in" target="_blank">
                    <div class="pdf-dg">

                        <span>
                            New order Form [PDf]
                        </span>
                        <span class="text"><i class="fa fa-download text-white mr-2"></i>Download</span>
                    </div>
                </a>
                <p>Your Outsourcing Partner for High Quality Compounds
                </p>
                <h3>How to order product
                </h3>
                <div class="p-font">
                    <p><b>Existing customers:
                        </b></p>
                </div>
                <p>Email completed form to:<a class="text-call-mail-color text-decoration-none" href="mailto:sales@medisourcerx.com">sales@medisourcerx.com</a></p>
                <p>or Fax completed form to: <a class="text-call-mail-color text-decoration-none" href="tel:+(714)455-1300">+(714)455-1300</a></p>

                <div class="p-font">
                    <p><b>New customers:</b></p>
                </div>
                <p>Please contact MedisourceRx via email at:</p>
                <p><a class="text-call-mail-color text-decoration-none" href="mailto:sales@medisourcerx.com">sales@medisourcerx.com </a>or call us at <a class="text-call-mail-color text-decoration-none" href="tel:+(714)455-1300">+(714)455-1300</a> .</p>
                <div class="order-btn">
                    <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                        href="{{route('register')}}">
                        <span class="inner-pdf-text-cart   fw-bold text-white">NEW CUSTOMER INQUIRY</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
