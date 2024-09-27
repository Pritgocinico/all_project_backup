@extends('frontend.layouts.app')

@section('content')


    <section class="banner-section about-parent  position-relative about-sec py-sm-5 py-1">
        <div class="container">
            <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start">
                <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
                <div class="col-md-8">
                    <h1 class="text-white text-start">
                        About Us
                    </h1>
                    <div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}" class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="" class="text-decoration-none text-white">About</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="about-in">



                <div class="about-in-ctn">

                    <div class="about-content">
                        <h5>Intravenous Benefits</h5>
                        <p>For many people, getting the adequate absorption of needed vitamins, minerals, and amino acids can be difficult. Intravenous nutrition allows for these nutrients to be infused directly into the body when barriers exist.</p>

                        <div class="about-btn">
                            <a class="text-decoration-none px-4 py-2 cart-btn clr-black justi d-flex gap-1 align-items-center parent-cartorder"
                                href="{{ route('product') }}">
                                <span class="inner-pdf-text-cart text-white">Go To Wellness Products</span>
                            </a>
                        </div>

                    </div>
                    <div class="ab-inner">
                        <div class="about-inner-img">
                            <img src="{{url('/')}}/frontend/assets/images/ab.jpeg" alt="">

                        </div>

                    </div>
                </div>
                <div class="about-in-ctn">
                    <div class="ab-inner">
                        <div class="about-inner-img">
                            <img src="{{url('/')}}/frontend/assets/images/a5.jpg" alt="">
                        </div>
                    </div>
                    <div class="about-content">


                        <h5>Emergency Medical Products</h5>
                        <p>MedisourceRx is proud to offer high quality surgery and EMS supplies. Whether you are a hospital, outpatient surgery facility, or EMS provider, we understand that these products have the ability to help save lives and we are here for your needs. View our products today!</p>


                        <div class="about-btn">
                            <a class="text-decoration-none px-4 py-2 cart-btn clr-black justi d-flex gap-1 align-items-center parent-cartorder"
                                href="{{ route('surgery') }}">
                                <span class="inner-pdf-text-cart text-white">Go To Surgery / EMS Products</span>
                            </a>

                        </div>

                    </div>

                </div>
                <div class="about-in-ctn">

                    <div class="about-content">
                        <h5>Commitment to Our Clients</h5>
                        <p>Clinical trials are a crucial element of the drug development process. MedisourceRx has experience and a passion for clinical trials and research. Our dedicated staff will ensure our partnership provides the value you deserve.</p>

                        <div class="about-btn">
                            <a class="text-decoration-none px-4 py-2 cart-btn clr-black justi d-flex gap-1 align-items-center parent-cartorder"
                                href="{{ route('clinical.trials') }}">
                                <span class="inner-pdf-text-cart text-white">Go To Clinical Trials</span>
                            </a>

                        </div>

                    </div>
                    <div class="ab-inner">
                        <div class="about-inner-img">
                            <img src="{{url('/')}}/frontend/assets/images/a6.jpg" alt="">

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="what-we-sec ">
            <div class="about-img-sec">
                <img src="{{url('/')}}/frontend/assets/images/what-we-do-new.jpg" alt="">
            </div>
            <div class="about-last-inner-info">
                <div>
                    <h4>
                        WHAT WE DO
                    </h4>
                    <p>From day one, our commitment has been simple - produce the highest quality products, at the best possible value and with truly superior client service.</p>

                    <p>MedisourceRx continues to be a leader in the industry by bringing innovation to the field, adopting cutting-edge technologies and expanding to new customer sectors.</p>
                    <p>MedisourceRx is an FDA Registered Outsourcing facility based in Los Alamitos, California. We
                        are a leader in
                        customized sterile production for hospitals, surgery centers, and wellness clinics. </p>
                    <p>In addition, we are experienced with the production of medications used in Clinical Trials. We adhere to the stringent cGMP procedures and USP <797> requirements put forth by the FDA under the new 503b guidelines. Our processes ensure our products are appropriately potent, sterile, and endotoxin free. Healthcare providers, Clinical Trials Investigators considering MedisourceRx are invited to visit our facility and learn about the processes that have been instrumental in helping us to attain excellence in quality and patient safety.   </p>
                    <p>We are available to discuss how we can help you improve your economic efficiency and achieve superior health outcomes.  </p>
                    <div class="we-btn">
                        <a class="text-decoration-none px-4 py-2 cart-btn clr-black justi d-flex gap-1 align-items-center parent-cartorder"
                            href="tel:+(714)455-1300">
                            <span class="inner-pdf-text-cart text-white">Call Us Today</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        var scroll = "{{$scrollTo}}";
        if(scroll == 'MeetOurLeadPharmacis'){
            var scrollTo = $('#meetourteam').offset().top-200;
            $('body, html').animate({scrollTop: scrollTo+'px'}, 800);
        }
        </script>
    @endsection

