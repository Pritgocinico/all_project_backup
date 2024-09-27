@extends('frontend.layouts.app')

@section('content')
<section class="banner-section  about-parent position-relative about-sec py-sm-5 py-1">
    <div class="container">
        <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start">
            <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
            <div class="col-md-8">
                <h1 class="text-white text-start">Pharmacy Agreement</h1>
                <div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}" class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="" class="text-decoration-none text-white">Pharmacy Agreement</a></li>
                            </ol>
                        </nav>
                    </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="privacy-inner">
            <p><b>By filling this enrollment form the Prescriber agrees:</b>
            </p>
            <div>
                <ul>
                    <li>All compounded preparations provided by MedisourceRx may only be administered to the patient and may not be dispensed to the patient or sold to any other person or entity</li>
                    <li>To include on a patient's chart, medication order or medication administration record the lot number and expiration date of the compounded preparation administered to the patient</li>
                    <li>To inform patients to contact them directly in order to report any adverse reaction and/or complaint. That information will then be relayed to MedisourceRx</li>
                    <li>Acknowledges and represents that all information listed above is true</li>
                    <li>Acknowledges and represents that The Office is legally able to order and solicit the services of The Pharmacy</li>
                    <li>To only use medication for patients with a medical need for compounded alternatives</li>
                </ul>
            </div>
        </div>
        <div class="privacy-inner">
            <p><b>By accepting this enrollment form, MedisourceRx agrees:</b></p>
            <div>
                <ul>
                    <li>To follow all safety standards of practice in regards to sterile products including USP 797 and cGMP compliance. Safety standards of practice include ensuring the final product is sterile and free of endotoxins, as well as within an acceptable range of potency. These tests are completed by a contracted analytical laboratory and are available for review, only upon request.</li>
                    <li>To assign a lot number and expiration date to every compounded preparation. A recall process will be implemented for any compounded preparation suspect to contamination and/or causative of adverse reactions. The identified lot number will be reported to the pharmacy by the office. Depending on the severity of the recall, all offices/patients receiving the affected preparation maybe contacted by the pharmacy to inform them of the recall.</li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection
