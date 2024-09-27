@extends('frontend.layouts.app')

@section('content')
<section class="banner-section about-parent prdct-parent position-relative py-sm-5 py-1">
    <div class="container">
        <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start ">
            <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
            <div class="col-md-8">
                <h1 class="text-white text-start">
                    Quality Assurance
                </h1>
                <div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}" class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page"><a href="" class="text-decoration-none text-white">Quality Assurance</a></li>
                            </ol>
                        </nav>
                    </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
    <div class="qua-arc-inner-sec">
        <div class="qu-img-inner">
            <img src="{{url('/')}}/frontend/assets/images/q1.webp" alt="">
        </div>
        <div class="qu-inner-info">
            <div class="">

                <h2>Quality Assurance
                </h2>

                <h4>WE TAKE IT SERIOUS.
                </h4>
                <p>Patient safety is a priority for MedisourceRx. As a leader in quality sterile preparations,
                    MedisourceRx is committed to fulfilling quality processes as guided by USP <797> and current
                        Good Manufacturing Practices (cGMP) that ensure the highest level of safety for
                        patients. MedisourceRx only uses the highest quality materials available to the drug
                        industry, advanced pharmaceutical technology, and robust quality systems to ensure
                        patient safety.

                </p>
                <h4>FDA 503B COMPLIANT
                </h4>
                <p>Our employees, equipment, facilities, training, processes, and compliance with state and
                    federal regulations ensure that MedisourceRx is the leading 503B Outsourcing Facility in the
                    nation.

                </p>
            </div>
        </div>
    </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="quality-info">
            <div class="quality-in">

                <div>
                    <h2> Current Good Manufacturing Practices (cGMP) </h2>

                </div>
                <div class="qty-img">
                    <img src="{{url('/')}}/frontend/assets/images/q2.webp" alt="">

                </div>
            </div>
            <h4>COMPOUNDING:
            </h4>
            <p>MedisourceRx products are compounded under current good manufacturing practice (cGMP) regulations.

            </p>
            <h4>QUALITY ASSURANCE PROGRAM:
            </h4>
            <p>Quality Assurance Program encompasses all aspects of preparation and testing to ensure accuracy and
                precision in weighing, measuring, and methods of sterilization.

            </p>
            <h4>QUALITY CONTROLS:
            </h4>
            <p>Quality controls are in place to confirm the absence of particulate matter, appropriate color,
                clarity, correct calculations, labeling accuracy, beyond use date assignment, and packaging/storage
                requirements.

            </p>
            <h4>ENVIRONMENTAL MONITORING:
            </h4>
            <p>Strict environmental monitoring and evaluation procedures for clean-room areas and personnel.

            </p>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="quality-info qua1">
            <h2>Our Team</h2>

            <div class="quality-in">
                <div class="qty-img">
                    <img src="{{url('/')}}/frontend/assets/images/q3.webp" alt="">

                </div>
                <div class="team-det">
                    <h4>OUR PROMISE TO YOU:
                    </h4>
                    <ul>
                        <li>All employees are required to complete a didactic and hands-on training programs before
                            performing any duties.
                        </li>
                        <li>Staff competency is evaluated prior to pharmacy compounding, which includes a thorough
                            training on each standard operating procedure, USP <797>, cGMP, cleaning and disinfection,
                                storage and handling, facilities and equipment, garb, aseptic technique and preparation,
                                process validation, expiration dating, labeling, end-preparation evaluation, and
                                documentation.
                        </li>
                        <li>Employees involved in compounding sterile preparations participate in media-fill testing
                            according to USP Chapter <797> standards.
                        </li>
                        <li>Training is given to the employees on a routine basis to ensure overall quality and
                            continuous development of our employees.
                        </li>
                    </ul>
                </div>

            </div>


        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="quality-info">
            <h2>Our Facilities
            </h2>

            <div class="quality-in">

                <div class="team-det">
                    <h4>ASEPTIC TECHNIQUES:
                    </h4>
                    <p>All aseptic techniques are performed in ISO 5 class hoods inside ISO 7 class clean-rooms supplied
                        with
                        High Efficiency Particulate Air (HEPA).
                    </p>
                    <h4>MAINTENANCE SCHEDULES:
                    </h4>
                    <p>Clean-rooms and laminar airflow hoods are certified for operational efficiency every 6 months by
                        an
                        independent agency.
                    </p>
                    <h4>ENVIRONMENT CONTROL:
                    </h4>
                    <p>Environmental testing is performed regularly, monitored, and acted upon according to strict
                        protocols.
                    </p>
                    <h4>DISINFECTING MAINTENANCE SCHEDULE:
                    </h4>
                    <p>Daily, weekly, and monthly cleaning procedures are followed and documented.
                    </p>
                </div>
                <div class="qty-img">
                    <img src="{{url('/')}}/frontend/assets/images/q2.webp" alt="">
                </div>
            </div>
        </div>
    </div>
</section>


<section>
    <div class="container">
        <div class="quality-info">
            <h2>Labels</h2>

            <div class="quality-in">
                <div class="qty-img">
                    <img src="{{url('/')}}/frontend/assets/images/q4.webp" alt="">
                </div>
                <div class="team-det">
                    <h4>OUR LABELING PRACTICES:
                    </h4>
                    <ul>
                        <li>Designed to enhance patient safety.
                        </li>
                        <li>Include the name, dosage form and strength of the drug; lot number, volume, date that the
                            drug was compounded, beyond use date, storage and handling instructions, the statement
                            “compounded drug”, the name, address, and phone number of MedisourceRx, the statement “not
                            for resale” or “office use only” where applicable, and a list of active and inactive
                            ingredients identified by established name and the quantity or proportion of each
                            ingredient.
                        </li>
                        <li>Bar codes are easily read by scanners, commonly used in point-of-care medication
                            administration systems, smart pump technology and automated dispensing machines.
                        </li>
                        <li>Quality controls have been established for issuing labels, examining issued labels, and
                            reconciliation of used labels to prevent mix-ups.

                        </li>
                        <li>
                            All labeled drug products are examined for accuracy and thoroughness before release.

                        </li>
                    </ul>
                </div>

            </div>


        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="quality-info">
            <h2>Container and Closure

            </h2>

            <div class="quality-in">

                <div class="team-det">
                    <h4>STERILITY:

                    </h4>
                    <p>Capable of ensuring the sterility and integrity of the product until it is administered to a
                        patient.


                    </p>
                    <h4>CLOSURE INTEGRITY:

                    </h4>
                    <p>Closure integrity testing confirms that our container, closure, and packaging systems provide
                        adequate protection against external factors in storage, shipment, and use that could cause
                        contamination or deterioration of the finished product.


                    </p>

                </div>
                <div class="qty-img">
                    <img src="{{url('/')}}/frontend/assets/images/q5.webp" alt="">
                </div>
            </div>
        </div>
    </div>
</section>


<section>
    <div class="container">
        <div class="quality-info">
            <h2>End-Product Testing


            </h2>

            <div class="quality-in">
                <div class="qty-img">
                    <img src="{{url('/')}}/frontend/assets/images/q6.webp" alt="">
                </div>
                <div class="team-det">
                    <h4>BATCH PROCESSING:


                    </h4>
                    <p>We test and hold every batch of medication that is compounded at MedisourceRx before we release
                        it for use.




                    </p>

                    <p>This quality assurance program ensures every compound produced at MedisourceRx meets
                        specifications for sterility, endotoxin and potency tests prior to release.




                    </p>

                </div>

            </div>
        </div>
    </div>
</section>


<section>
    <div class="container">
        <div class="quality-info">
            <h2>Beyond Use Dating



            </h2>

            <div class="quality-in">

                <div class="team-det">
                    <h4>OUR PROCESS:



                    </h4>
                    <p>MedisourceRx bases its beyond use dating on stability testing to ensure safety, stability,
                        reliability and efficacy. Testing, specific to drug, concentration, fill volume, container and
                        diluents, ensures effective therapy through the labeled expiration date.






                    </p>
                    <h4>STERILITY:
                    </h4>

                    <p>Sterility is ensured through process validation, sterile closure integrity testing and ongoing
                        preparation monitoring.






                    </p>

                </div>
                <div class="qty-img">
                    <img src="{{url('/')}}/frontend/assets/images/q7.webp" alt="">
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="quality-info l-btn">
            <h2>Certificate Of Analysis
            </h2>
            <div class="quality-in">
            <div class="qty-img">
                    <img src="{{url('/')}}/frontend/assets/images/q8.webp" alt="">
                </div>
                <div class="team-det">
                    <h4>OUR PROMISE:
                    </h4>
                    <p>Every sterile compound is tested in-house or by a 3rd party FDA registered analytical laboratory for container-close integrity, method suitability, pH, particulate, clarity, appearance, preservative effectiveness, sterility, endotoxin and potency according to USP <51>, <71>, <85>, <788>, <797>, and <1207> guidelines.
                    </p>
                    <h4>AVAILABLE UPON REQUEST:
                    </h4>
                    <p>A Certificate of Analysis of a product produced at MedisourceRx can be provided upon request.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>




@endsection