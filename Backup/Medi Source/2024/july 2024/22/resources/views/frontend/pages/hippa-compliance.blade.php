@extends('frontend.layouts.app')

@section('content')


    <section class="banner-section about-parent position-relative about-sec py-sm-5 py-1">
        <div class="container">
            <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start">
                <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
                <div class="col-md-8">
                    <h1 class="text-white text-start">
                    HIPAA Compliance
                    </h1>
                    <div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}" class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="" class="text-decoration-none text-white">HIPAA Compliance</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="three-way-main-sec sec-for-hippa">
        <div class="container">
            <h1>HIPAA Compliance </h1>
            <div class="row parent-three-way-box mt-4">
                <div class="col-md-6 text-center my-3">
                    <div class="parent-box-for-portals">
                        <a href="#" class="text-decoration-none">
                            <img src="{{url('/')}}/frontend/assets/images/overview.png" alt="" class="img-fluid">
                            <h4>Overview of HIPAA</h4>
                            <p>Explain what HIPAA (Health Insurance Portability and Accountability Act) is and why it's important for protecting health information.</p>
                        </a> 
                    </div>
                </div>
                <div class="col-md-6 text-center my-3">
                    <div class="parent-box-for-portals">
                        <a href="#" class="text-decoration-none">
                            <img src="{{url('/')}}/frontend/assets/images/trust.png" alt="" class="img-fluid">
                            <h4>Your Commitment to HIPAA Compliance</h4>
                            <p>State your commitment to complying with HIPAA regulations and safeguarding the privacy and security of health information.</p>
                        </a> 
                    </div>
                </div>
                <div class="col-md-6 text-center my-3">
                    <div class="parent-box-for-portals">
                        <a href="#" class="text-decoration-none">
                            <img src="{{url('/')}}/frontend/assets/images/health.png" alt="" class="img-fluid">
                            <h4>How You Handle Health Information</h4>
                            <p>Describe how you collect, use, and store health information, and how you ensure its confidentiality and security.</p>
                        </a> 
                    </div>
                </div>
                <div class="col-md-6 text-center my-3">
                    <div class="parent-box-for-portals">
                        <a href="#" class="text-decoration-none">
                            <img src="{{url('/')}}/frontend/assets/images/rights.png" alt="" class="img-fluid">
                            <h4>HIPAA Rights</h4>
                            <p>Explain the rights individuals have under HIPAA, such as the right to access their health information and request corrections.</p>
                        </a> 
                    </div>
                </div>
                <div class="col-md-6 text-center my-3">
                    <div class="parent-box-for-portals">
                        <a href="#" class="text-decoration-none">
                            <img src="{{url('/')}}/frontend/assets/images/contact-information.png" alt="" class="img-fluid">
                            <h4>Contact Information</h4>
                            <p>Provide contact information for questions or concerns about HIPAA compliance.</p>
                        </a> 
                    </div>
                </div>
            </div>
        </div>
    </section>

    @endsection

