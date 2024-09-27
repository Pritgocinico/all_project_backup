@extends('frontend.layouts.app')

@section('content')

<section class="banner-section about-parent prdct-parent position-relative py-sm-5 py-1">
    <div class="container">
        <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start ">
            <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
            <div class="col-md-8">
                <h1 class="text-white text-start">
                    Professional Profiles
                </h1>
                <div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}" class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page"><a href="" class="text-decoration-none text-white">Professional Profiles</a></li>
                            </ol>
                        </nav>
                    </div>
            </div>
        </div>
    </div>
</section>

<section class="profile-bg">
    <div class="container">
        <div class="profile-inner">
            <div class="proin-sec">
                <div class="p-inner-img">

                <img src="{{url('/')}}/frontend/assets/images/pro1.webp" alt="">
                </div>

                <div class="pers-info">
                    <h3>PALLAVI BADKAR - R.PH. ● MS ● BCSCP
                    </h3>
                    <p><b>Vice President of Operations and Pharmacist in Charge
                        </b></p>
                    <p>Pallavi Badkar is a proven, intrinsically driven, and effective leader with over 20 years of
                        experience in the life sciences industry. With expertise in sterile aseptic compounding, process
                        engineering, and validation, Pallavi has a strong track record of strategic vision and
                        operational excellence. She has successfully led and directed clinical research activities for
                        investigational drugs and has been recognized as a mentor, motivator, and thought leader.
                        Pallavi's exceptional leadership style and ability to foster strong relationships have earned
                        her credibility with health authorities and notified bodies. She has a demonstrated history of
                        regulatory compliance and has procured regulatory approvals for facilities with no observations
                        or warning letters. Pallavi’s can-do spirit and passion for peak performance make her a valuable
                        member of top performing teams.

                    </p>
                </div>
            </div>
            <div class="proin-sec">
                <div class="pers-info perin2">
                    <h3>VENUS FIROUZEHEE - PHARM.D. ● MBA ● R.PH.

                    </h3>
                    <p><b>Production Manager and Sterile Compounding Pharmacist


                        </b></p>
                    <p>Venus Firouzehee is a national IV certified clinical pharmacist and supervisor for more than 13
                        years in healthcare industry with a focused expertise in sterile and non-sterile compounding.
                        She worked at different settings including hospital, retail, home infusion, post-acute care, and
                        Long-term care facilities. She joined MedisourceRx as a per diem pharmacist and moved her way up
                        to be the production manager. She has been managing pharmaceutical product design & development
                        and overseeing compounding and distribution of sterile compounds, leading the production team,
                        and ensuring the facility is in conformance with state and federal laws and regulations.
                    </p>
                </div>
                <div class="p-inner-img">

<img src="{{url('/')}}/frontend/assets/images/pro2.webp" alt="">
</div>
            </div>

        </div>
    </div>
</section>

@endsection