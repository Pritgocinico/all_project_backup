@extends('frontend.layouts.app')

@section('content')
<section class="banner-section about-parent prdct-parent position-relative py-sm-5 py-1">
    <div class="container">
        <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start ">
            <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
            <div class="col-md-8">
                <h1 class="text-white text-start">
                    Surgery/EMS
                </h1>
                <div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}" class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="" class="text-decoration-none text-white">Surgery/EMS</a></li>
                            </ol>
                        </nav>
                    </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="surgery-inner">
            <div>
                <h2>MedisourceRx has you covered
                </h2>
                <div class="sur-inner">
                    <div class="sgy-info">
                        <div class=sgy-info-imgs>

                        <img src="{{url('/')}}/frontend/assets/images/s1.webp" alt="">
                        </div>

                        <div class="in-detail">
                            <h3>UNIT DOSE ANESTHESIA SYRINGES
                            </h3>
                            <p>Whether you are a hospital or outpatient surgery provider, consider MedisourceRx for your
                                unit-dose anesthesia needs.

                            </p>
                        </div>
                    </div>
                    <div class="sgy-info">
                        <div class="in-detail indetail">
                            <h3>PRE-FILLED SYRINGE INVENTORY

                            </h3>
                            <ul>
                                <li>Atropine Sulfate PF 0.4mg/mL
                                </li>
                                <li>Neostigmine Methylsulfate
                                </li>
                                <li>Phenylephrine HCL 100mcg/mL
                                </li>
                                <li>Succinylcholine Chloride 20mg/m
                                </li>
                                <li>More information upon request
                                </li>

                            </ul>

                            </p>
                        </div>
                        <div class=sgy-info-imgs>

<img src="{{url('/')}}/frontend/assets/images/s2.webp" alt="">
</div>
                    </div>
                    <div class="sgy-info">
                    <div class=sgy-info-imgs>

                        <img src="{{url('/')}}/frontend/assets/images/s3.webp" alt="">
                        </div>                        
                        <div class="in-detail ">
                            <h3>UNIT DOSE ANESTHESIA SYRINGES
                            </h3>
                            <ul>
                                <li>Ready to use
                                </li>
                                <li>Extended BUD supported by stability testing
                                </li>
                                <li>Come in the most popular drug concentration and fill volume
                                </li>
                                <li>DEA manufacturer's license allows for office-use compounded controlled substances
                                </li>
                                <li>Color-coded/ bar-coded labels and tamper-evident packaging for safety and
                                    convenience
                                </li>
                                <li>TALLman lettering to ensure the drug name is visible during medication selection
                                </li>
                                <li>Fit conveniently in anesthesia carts and automated dispensing cabinets
                                </li>
                                <li>Reduced medication errors
                                </li>
                                <li>Improved efficiency and reduced waste
                                </li>
                                <li>Reduced facility and labor costs
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection