@extends('frontend.layouts.app')

@section('content')


    <section class="banner-section about-parent  position-relative about-sec py-sm-5 py-1">
        <div class="container">
            <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start">
                <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
                <div class="col-md-8">
                    <h1 class="text-white text-start">
                    Meet Lead Pharmacist
                    </h1>
                    <div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}" class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="" class="text-decoration-none text-white">Meet Lead Pharmacist</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="meet-section" id="meetourteam">
        <div class="container">
            <div class="meet-in">
                <h2></h2>
            </div>
            <div class="row align-items-center parent-dr-pallavi-box">
                <div class="col-md-7">
                <h4 class="dr-title-about">Pallavi Devurkar-Badkar,
                        </h4>
                        <!-- <ul class="dr-title-about-detail">
                            <li>Lorem ipsum</li>
                            <li>Lorem ipsum</li>
                            <li>Lorem ipsum</li>
                        </ul> -->
                        <p>VP & Lead Pharmacist, MedisourceRx

                        </p>
                        <p>Pallavi Badkar serves as the VP of Operations and PIC at MedisourceRx in Los Angeles, where she oversees company operations and drives expansion into emerging markets. With over two decades in the life sciences sector, Pallavi is a seasoned leader known for her expertise in sterile aseptic compounding, quality assurance, and compliance. She has consistently demonstrated strategic vision and operational excellence, successfully managing commercial production and leading clinical research initiatives for investigational drugs. Pallavi is recognized as a mentor, motivator, and influential thought leader, known for her adept relationship-building skills and credibility with health authorities and notified bodies. Her unwavering commitment to regulatory compliance has ensured facility approvals without any observations or warnings. Pallavi’s relentless pursuit of peak performance and her can-do attitude contribute significantly to the success of high-performing teams.<br/>Pallavi holds a pharmacy degree with a master's in Pharmaceutical Sciences from Northeastern University in Boston and is board-certified as a sterile compounding pharmacist by ASHP.</p>
                        
                   
                </div>
                <div class="col-md-5">
                <div class="">
                    <img src="{{url('/')}}/frontend/assets/images/eper.webp" alt="" class="w-100 inner-one-dr-img">

                </div>
                </div>
            </div>
            <div class="meet-inner d-none">
                <div class="main-in-meet"> 

                    <div class="meet-inner-section">
                        <div class="img-div-pro">
                            <img src="{{url('/')}}/frontend/assets/images/m6.webp" alt="">
                        </div>
                        <h4>Pallavi Badkar
                        </h4>
                        <p>R.Ph. ● MS ● BCSCP
                            Vice President of Operations
                            Pharmacist in Charge
                        </p>
                        <div class="m-btn">
                            <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                                href="#">
                                <span class="inner-pdf-text-cart text-white">LEARN MORE</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="main-in-meet">

                    <div class="meet-inner-section sin1">
                        <div class="img-div-pro">
                            <img src="{{url('/')}}/frontend/assets//images/m2.6666666666667,cg_true" alt="">
                        </div>
                        <h4>Venus Firouzehee

                        </h4>
                        <p>Pharm.D. ● MBA ● R.Ph.

                            Production Manager

                            Sterile Compounding Pharmacist
                        </p>
                        <div class="m-btn">
                            <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                                href="#">
                                <span class="inner-pdf-text-cart text-white">LEARN MORE</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="main-in-meet">

                    <div class="meet-inner-section in1">
                        <div class="img-div-pro">
                            <img src="{{url('/')}}/frontend/assets//images/m3.webp" alt="">
                        </div>
                        <h4>Sridhar Aravapalli

                        </h4>
                        <p>Ph.D.

                            Quality Manager
                        </p>
                        <div class="m-btn">
                            <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                                href="#">
                                <span class="inner-pdf-text-cart text-white">LEARN MORE</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="main-in-meet">

                    <div class="meet-inner-section in1">
                        <div class="img-div-pro">
                            <img src="{{url('/')}}/frontend/assets//images/m4.6666666666667,cg_true" alt="">
                        </div>
                        <h4>Kenny Harrington

                        </h4>
                        <p>Vice President

                            of

                            Institutional Sales
                        </p>
                        <div class="m-btn">
                            <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                                href="#">
                                <span class="inner-pdf-text-cart text-white">LEARN MORE</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="meet-inner d-none">
                <div class="main-in-meet">

                    <div class="meet-inner-section">
                        <div class="img-div-pro">
                            <img src="{{url('/')}}/frontend/assets//images/m5.6666666666667,cg_true" alt="">
                        </div>
                        <h4>Deena Ovist

                        </h4>
                        <p>Account Manager


                        </p>
                        <div class="m-btn">
                            <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                                href="#">
                                <span class="inner-pdf-text-cart text-white">LEARN MORE</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="main-in-meet">

                    <div class="meet-inner-section sin2">
                        <div class="img-div-pro">
                            <img src="{{url('/')}}/frontend/assets//images/m6.webp" alt="">
                        </div>
                        <h4>Bryan Espinosa


                        </h4>
                        <p>Customer Service & Inventory Specialist Manager


                        </p>
                        <div class="m-btn">
                            <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                                href="#">
                                <span class="inner-pdf-text-cart text-white">LEARN MORE</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="main-in-meet">

                    <div class="meet-inner-section in2">
                        <div class="img-div-pro">
                            <img src="{{url('/')}}/frontend/assets//images/m7.webp" alt="">
                        </div>
                        <h4>Kanksha Shah


                        </h4>
                        <p>Marketing Manager


                        </p>
                        <div class="m-btn">
                            <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                                href="#">
                                <span class="inner-pdf-text-cart text-white">LEARN MORE</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="main-in-meet">

                    <div class="meet-inner-section in2">
                        <div class="img-div-pro">
                            <img src="{{url('/')}}/frontend/assets//images/m8.webp" alt="">
                        </div>
                        <h4>Emma Sheehan


                        </h4>
                        <p>QA / QC Specialist

                            Quality Assurance / Quality Control


                        </p>
                        <div class="m-btn">
                            <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                                href="#">
                                <span class="inner-pdf-text-cart text-white">LEARN MORE</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

                <div class="meet-inner d-none">
                    <div class="main-in-meet">

                        <div class="meet-inner-section">
                            <div class="img-div-pro">
                                <img src="{{url('/')}}/frontend/assets//images/m9.webp" alt="">
                            </div>
                            <h4>Sabita Eappan


                            </h4>
                            <p>Quality Technician




                            </p>
                            <div class="m-btn">
                                <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                                    href="#">
                                    <span class="inner-pdf-text-cart text-white">LEARN MORE</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="main-in-meet">

                        <div class="meet-inner-section">
                            <div class="img-div-pro">
                                <img src="{{url('/')}}/frontend/assets//images/m10.webp" alt="">
                            </div>
                            <h4>Anjum Shaikh


                            </h4>
                            <p>Manufacturing Technician II




                            </p>
                            <div class="m-btn">
                                <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                                    href="#">
                                    <span class="inner-pdf-text-cart text-white">LEARN MORE</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="main-in-meet">

                        <div class="meet-inner-section in3">
                            <div class="img-div-pro">
                                <img src="{{url('/')}}/frontend/assets//images/m11.webp" alt="">
                            </div>
                            <h4>Araceli Martinez



                            </h4>
                            <p>Manufacturing Technician II




                            </p>
                            <div class="m-btn">
                                <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                                    href="#">
                                    <span class="inner-pdf-text-cart text-white">LEARN MORE</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="main-in-meet">

                        <div class="meet-inner-section in3">
                            <div class="img-div-pro">
                                <img src="{{url('/')}}/frontend/assets//images/m12.webp" alt="">
                            </div>
                            <h4>Sharmaarke Purcell



                            </h4>
                            <p>Manufacturing Technician II




                            </p>
                            <div class="m-btn">
                                <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                                    href="#">
                                    <span class="inner-pdf-text-cart text-white">LEARN MORE</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            <div class="meet-inner d-none">
                <div class="main-in-meet">

                    <div class="meet-inner-section">
                        <div class="img-div-pro">
                            <img src="{{url('/')}}/frontend/assets/images/m13.6666666666667" alt="">
                        </div>
                        <h4>Victor Valencia



                        </h4>
                        <p>Manufacturing Technician II






                        </p>
                        <div class="m-btn">
                            <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                                href="#">
                                <span class="inner-pdf-text-cart text-white">LEARN MORE</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="main-in-meet">

                    <div class="meet-inner-section">
                        <div class="img-div-pro">
                            <img src="{{url('/')}}/frontend/assets/images/m14.webp" alt="">
                        </div>
                        <h4>Ron Penrose



                        </h4>
                        <p>Manufacturing Technician II






                        </p>
                        <div class="m-btn">
                            <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                                href="#">
                                <span class="inner-pdf-text-cart text-white">LEARN MORE</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="main-in-meet">

                    <div class="meet-inner-section in4">
                        <div class="img-div-pro">
                            <img src="./assets//images/m15.6666666666667" alt="">
                        </div>
                        <h4>Neha Khatter




                        </h4>
                        <p>Grad Intern






                        </p>
                        <div class="m-btn">
                            <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                                href="#">
                                <span class="inner-pdf-text-cart text-white">LEARN MORE</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection

