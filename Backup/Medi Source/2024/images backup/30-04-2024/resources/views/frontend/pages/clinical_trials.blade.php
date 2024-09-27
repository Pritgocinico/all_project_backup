@extends('frontend.layouts.app')

@section('content')
<section class="banner-section about-parent prdct-parent position-relative py-sm-5 py-1">
    <div class="container">
        <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start ">
            <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
            <div class="col-md-8">
                <h1 class="text-white text-start">
                    Clinical Trials </h1>
                    <div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}" class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="" class="text-decoration-none text-white">Clinical Trials</a></li>
                            </ol>
                        </nav>
                    </div>
            </div>
        </div>
    </div>
</section>

<section class="c-sec">
    <div class="container">
        <div class="clinicial-inner">
            <h2>Clinical Trial Services</h2>
            <div>
                <div class="inner-img">
                    <div class="fc-img">
                        <img src="{{url('/')}}/frontend/assets/images/c1.webp" alt="">
                    </div>
                    <div class="clinicial-info">
                        <h3>FOCUS ON YOUR PRACTICE</h3>
                        <p>Whatever your needs, we can fulfill them. MedisouceRx Pharmacy is a FDA registered facility
                            that
                            has experience in the preparation of medications for clinical trials and research.
                            MedisourceRx
                            employs pharmacists, quality assurance and other personnel that have experience with
                            clinical
                            trials. Senior management expertise includes: Pharmacology, Biochemistry, Microbiology,
                            Business
                            Management and other related technologically demanding disciplines.
                        </p>
                    </div>
                </div>
                <div class="inner-img">

                    <div class="clinicial-info">
                        <h3>RELIABLE OUTCOMES</h3>
                        <p>Strength, sterility, and endotoxin testing on all lots and validated processes on all
                            preparations.
                        </p>
                    </div>
                    <div class="fc-img">
                        <img src="{{url('/')}}/frontend/assets/images/c2.webp" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="trial-sec">
    <div class="container">
        <div class="trials-inner">
            <h2>Clinical Trials With MedisourceRx</h2>
            <div class="trial-info">
                <div>
                    <h3>OUR ACCOMPLISHMENTS AND SERVICES</h3>
                    <ul>
                        <li>Investigational new drug capabilities for phases 2A, and 2B clinical trials
                        </li>
                        <li>Sterile product development – aseptic filling, sterilization, batch compounding
                        </li>
                        <li>Meet cGMP and USP 797 quality standards
                        </li>
                        <li>Scaled production for as little as a few hundred units to up to thousands
                        </li>
                        <li>Less expensive option for small-scale production in early phase I and II trials
                        </li>
                        <li> Rapid processing
                        </li>
                        <li>Custom labeling and packaging
                        </li>
                        <li>Blinded or open label formats
                        </li>
                        <li> Active or placebo dosage forms
                        </li>
                        <li>Formulations include: sterile injections, sterile preservative free eye drops, nasal sprays,
                            transdermal creams and gels, topical sprays, suppositories, and sub-lingual drops
                        </li>
                        <li>Can provide patient trial kits with patient information or other required documentation
                        </li>
                    </ul>
                </div>
                <img src="{{url('/')}}/frontend/assets/images/c3.webp" alt="">
            </div>
        </div>
    </div>
</section>
@endsection
