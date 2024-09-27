@extends('frontend.layouts.app')
@section('content')
<section class="banner-section prdct-parent position-relative  py-sm-4 py-1 d-none">
   <div class="container">
      <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start ">
         <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
         <div class="col-md-8">
            <h1 class="text-white text-start">
               Events / News
            </h1>
            <div class="parent-banner-btn text-start mt-4 mb-2">
               <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="{{url('/')}}" class="text-decoration-none text-white">Home</a>
                     </li>
                     <li class="breadcrumb-item active" aria-current="page"><a href="" class="text-decoration-none text-white">Events / News</a></li>
                  </ol>
               </nav>
            </div>
         </div>
      </div>
   </div>
</section>


<section class="display">
   <div class="container-fluid px-0">
      <div class="ev-inner">
         <div class="event-info">
            <div class="event-info">
               <h2>We're at the event NOW!
               </h2>
               <div>
                  <p>We're at Booth 2105 We'll be here until December 16, 2023 4:00pm
                  </p>
                  <div class="event-btn">
                     <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                        href="https://www.a4m.com/longevity-fest-2024.html">
                     <span class="inner-pdf-text-cart  px-sm-5 fw-bold text-white">JOIN US!</span>
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>


<div class="event-info-inner event-ins col-md-6 display d-none">
   <img src="./assets/images/event.jpg" alt="">
   <div class="event-info">
      <h2>History of our past events!
      </h2>
   </div>
</div>

<section class="main-for-generel-news event-sections marginover-top">
   <div class="container">
      <div class="general-inner">
         <h2 class="mt-0">Events
         </h2>
         <div class="general-inner-img event-maintwo-section">
            <div class="event-sec-main">
               <img src="{{url('/')}}/frontend/assets/images/event1.png" alt="" class="img-fluid">
            </div>
            <div class="inner-past-event event-sec-detail">
               <div class="g-info inner-ct">
                  <h4>Pallavi Devurkar-Badkar,
                  </h4>
                  <h5>VP & Lead Pharmacist, MedisourceRx
                  </h5>
                 
                  <p>We are thrilled to have our very own Pallavi Devurkar-Badkar
                  sharing her insights on successes and challenges of a developing 503B industry at the FDA Compounding Quality Center of Excellence 2024 Annual Conference. Learn more about regulatory challenges, best practices and quality standards in the constantly evolving industry.
                  </p>
                  <div class="newevbtn-inner">
                    <a class="newevent-btn" href="https://www.fda.gov/drugs/news-events-human-drugs/2024-compounding-quality-center-excellence-annual-conference-08212024" target="_blank">Register Now</a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<section>
   <div class="container display">
      <h1 class="text-center history-head-text">Coming Soon
      </h1>
      <div class="compounding-inner inner-text">
         <h2>Compounding Pharmacy Compliance 2023
         </h2>
         <div class="parent-event-cards row">
            <div class="col-md-4">
               <div class="comimg">
                  <img src="{{url('/')}}/frontend/assets/images/event5.webp" alt="" class="img-fluid">
               </div>
               <div class="ins-detail">
                  <p><b>Conquer regulatory complexities and mitigate risk by developing first-class compliance and
                     quality standards.
                     </b>
                  </p>
                  <p>Ensure inspection readiness at the preeminent event for compounding professionals with timely
                     regulatory updates, innovative technology solutions, industry best practices and protocols to
                     drive quality assurance facility-wide.
                  </p>
                  <div class="com-btn">
                     <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                        href="#">
                     <span class="inner-pdf-text-cart py-1 px-2 px-sm-5 fw-bold text-white">LEARN MORE</span>
                     </a>
                  </div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="comimg">
                  <img src="{{url('/')}}/frontend/assets/images/event7.webp" alt="" class="img-fluid">
               </div>
               <div class="ins-detail">
                  <p><b>Meet one of our compounding pharmacy compliance industry experts!
                     </b>
                  </p>
                  <p><b>PALLAVI BADKAR, RPH, M.S, BCSCP
                     </b>
                  </p>
                  <p>Vice President of Operations & Pharmacist in Charge at MedisourceRx.
                  </p>
                  <div class="com-btn">
                     <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                        href="#">
                     <span class="inner-pdf-text-cart py-1 px-2 px-sm-5 fw-bold text-white">LEARN MORE</span>
                     </a>
                  </div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="comimg">
                  <img src="{{url('/')}}/frontend/assets/images/event6.webp" alt="" class="img-fluid">
               </div>
               <div class="ins-detail">
                  <p><b>Held at the Westin Copley Place, Boston, MA
                     </b>
                  </p>
                  <p>August 17-18, 2023
                  </p>
                  <div class="com-btn">
                     <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                        href="#">
                     <span class="inner-pdf-text-cart py-1 px-2 px-sm-5 fw-bold text-white">WESTIN COPLEY
                     PLACE</span>
                     </a>
                  </div>
               </div>
            </div>
            <div class="com-info">
               <div class="comimg">
                  <img src="{{url('/')}}/frontend/assets/images/event5.webp" alt="" class="img-fluid">
               </div>
               <div class="ins-detail">
                  <p><b>Conquer regulatory complexities and mitigate risk by developing first-class compliance and
                     quality standards.
                     </b>
                  </p>
                  <p>Ensure inspection readiness at the preeminent event for compounding professionals with timely
                     regulatory updates, innovative technology solutions, industry best practices and protocols to
                     drive quality assurance facility-wide.
                  </p>
                  <div class="com-btn">
                     <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                        href="#">
                     <span class="inner-pdf-text-cart py-1 px-2 px-sm-5 fw-bold text-white">LEARN MORE</span>
                     </a>
                  </div>
               </div>
            </div>
            <div class="com-info">
               <div class="comimg">
                  <img src="{{url('/')}}/frontend/assets/images/event7.webp" alt="" class="img-fluid">
               </div>
               <div class="ins-detail">
                  <p><b>Meet one of our compounding pharmacy compliance industry experts!
                     </b>
                  </p>
                  <p><b>PALLAVI BADKAR, RPH, M.S, BCSCP
                     </b>
                  </p>
                  <p>Vice President of Operations & Pharmacist in Charge at MedisourceRx.
                  </p>
                  <div class="com-btn">
                     <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                        href="#">
                     <span class="inner-pdf-text-cart py-1 px-2 px-sm-5 fw-bold text-white">LEARN MORE</span>
                     </a>
                  </div>
               </div>
            </div>
            <div class="com-info">
               <div class="comimg">
                  <img src="{{url('/')}}/frontend/assets/images/event6.webp" alt="" class="img-fluid">
               </div>
               <div class="ins-detail">
                  <p><b>Held at the Westin Copley Place, Boston, MA
                     </b>
                  </p>
                  <p>August 17-18, 2023
                  </p>
                  <div class="com-btn">
                     <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                        href="#">
                     <span class="inner-pdf-text-cart py-1 px-2 px-sm-5 fw-bold text-white">WESTIN COPLEY
                     PLACE</span>
                     </a>
                  </div>
               </div>
            </div>
         </div>
         <div class="com-info">
            <div class="ins-detail">
               <p><b>Conquer regulatory complexities and mitigate risk by developing first-class compliance and
                  quality standards.
                  </b>
               </p>
               <p>Ensure inspection readiness at the preeminent event for compounding professionals with timely
                  regulatory updates, innovative technology solutions, industry best practices and protocols to
                  drive quality assurance facility-wide.
               </p>
               <div class="com-btn">
                  <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                     href="https://informaconnect.com/compounding-pharmacy-compliance/">
                  <span class="inner-pdf-text-cart  px-sm-5 fw-bold text-white">LEARN MORE</span>
                  </a>
               </div>
            </div>
            <div class="comimg">
               <img src="{{url('/')}}/frontend/assets/images/event5.webp" alt="">
            </div>
         </div>
         <div class="com-info">
            <div class="comimg">
               <img src="{{url('/')}}/frontend/assets/images/event7.webp" alt="">
            </div>
            <div class="ins-detail">
               <p><b>Meet one of our compounding pharmacy compliance industry experts!
                  </b>
               </p>
               <p><b>PALLAVI BADKAR, RPH, M.S, BCSCP
                  </b>
               </p>
               <p>Vice President of Operations & Pharmacist in Charge at MedisourceRx.
               </p>
               <div class="com-btn">
                  <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                     href="https://informaconnect.com/compounding-pharmacy-compliance/speakers/pallavi-badkar-rph-ms-bcscp/">
                  <span class="inner-pdf-text-cart  px-sm-5 fw-bold text-white">LEARN MORE</span>
                  </a>
               </div>
            </div>
         </div>
         <div class="com-info">
            <div class="ins-detail">
               <p><b>Held at the Westin Copley Place, Boston, MA
                  </b>
               </p>
               <p>August 17-18, 2023
               </p>
               <div class="com-btn">
                  <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                     href="https://www.marriott.com/en-us/hotels/boswi-the-westin-copley-place-boston/overview/">
                  <span class="inner-pdf-text-cart  px-sm-5 fw-bold text-white">WESTIN COPLEY
                  PLACE</span>
                  </a>
               </div>
            </div>
            <div class="comimg">
               <img src="{{url('/')}}/frontend/assets/images/event6.webp" alt="">
            </div>
         </div>
      </div>
   </div>
</section>

<section class="sec-for-summit display">
   <div class="container">
      <div class="aseptic-inner ">
         <h2>Aseptic Processing Summit 2022
         </h2>
         <div class="per-info">
            <div class="person-profile">
               <img src="{{url('/')}}/frontend/assets/images/eper.webp" alt="">
            </div>
            <div class="inner-info-per">
               <h4>OCTOBER 25–26, 2022, SAN DIEGO, CA
               </h4>
               <p>MedisourceRx's own pharmacist Pallavi Badkar is a key note speaker on the first day of the
                  summit!
               </p>
               <p>Aseptic Compounding in the 503B Industry: Challenges and Mitigations Pallavi Devurkar-Badkar,
                  Director of Operations-503b, MedisourceRx The NECC tragedy in the compounding industry prompted
                  Congress to sign the Drug Quality and Security Act (DQSA) with the goal of preventing future
                  tragedies.
               </p>
               <p>The 503B Outsourcing Facility designation originated from the DQSA. This type of facility is a
                  primary and preferred manufacturing source of drugs on shortage. They also may manufacture
                  sterile injectables from non-sterile APIs from the FDA approved bulk list. The challenge for
                  this entity is that 503B’s must follow cGMP but payers request small batch sizes of several
                  types of drugs packaged in various containers. While it is most ideal to employ automatic
                  filling machines to ensure sterility assurance of aseptic processing of drugs, these
                  technologies are cost prohibitive, inefficient in changeover, and often not flexible in
                  container choice. Therefore, manual aseptic processing (MAP) is often employed in this industry
                  as well as in traditional compounding pharmacies. While MAP allows for an increased flexibility
                  of the containers used and drug formulations produced, there is a high risk of contamination
                  from human interventions.
               </p>
            </div>
         </div>
         <p>The goal of this presentation is to discuss various approaches to mitigate the challenges of
            semi-automatic aseptic processing using best practice aseptic techniques to ensure sterility
            during critical human interventions. Select small batch filling and finishing equipment, closed
            ready-to-fill systems, and single-use aseptic processing supplies will also be discussed as aids
            in achieving sterility with MAP. Finally, the importance of sourcing injectable-grade APIs as a
            manufacturer’s responsibility in preventing adverse drug events will also be discussed.
         </p>
         <div class="l-more">
            <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
               href="https://www.pharmaedresources.com/wp-content/uploads/2021/12/Aseptic-Processing-2022.pdf">
            <span class="inner-pdf-text-cart   text-white">LEARN MORE</span>
            </a>
         </div>
      </div>
   </div>
</section>

<section>
   <div class="container display">
   <div class="year-inner">
      <div class="year-tit">
         <div>
            <p><b>October 25th & 26th, 2022 ● San Diego, CA
               </b>
            </p>
            <p>MedisourceRx's own pharmacist <b>Pallavi Badkar</b> is a key note speaker on the first day of the
               summit!
            </p>
            <a href="{{url('/')}}/frontend/assets/images/all.pdf">
               <div class="dwn-btn">
            <a id="downloadPdfBtn" class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder" href="https://localhost/medisource/storage/lot-files/2024030918124319.pdf" download="">
            <span class="inner-pdf-text-cart text-white"><i class="fa-solid fa-download me-2"></i>DOWNLOAD</span>
            </a>
            </div>
            </a>
         </div>
         <h2>ASEPTIC PROCESSING SUMMIT 2022</h2>
      </div>
   </div>
</section>

<section class="page-sec display">
   <div class="container">
      <div class="col-md-10 mt-5 mx-auto position-relative">
         <div class="owl-carousel btn-css event-page-slider owl-theme">
            <div class="item">
               <img class="img-fluid" src="{{url('/')}}/frontend/assets/images/p1.png" alt="">
            </div>
            <div class="item">
               <img class="img-fluid" src="{{url('/')}}/frontend/assets/images/p1.png" alt="">
            </div>
            <div class="item">
               <img class="img-fluid" src="{{url('/')}}/frontend/assets/images/p1.png" alt="">
            </div>
            <div class="item">
               <img class="img-fluid" src="{{url('/')}}/frontend/assets/images/p1.png" alt="">
            </div>
            <div class="item">
               <img class="img-fluid" src="{{url('/')}}/frontend/assets/images/p1.png" alt="">
            </div>
            <div class="item">
               <img class="img-fluid" src="{{url('/')}}/frontend/assets/images/p1.png" alt="">
            </div>
         </div>
      </div>
      <div class="pages-main">
         <div class="page">
            <div class="page-1">
               <img class="img-fluid" src="{{url('/')}}/frontend/assets/images/p1.png" alt="">
            </div>
            <div class="page-1">
               <img class="img-fluid" src="{{url('/')}}/frontend/assets/images/p2.png" alt="">
            </div>
            <div class="page-1">
               <img class="img-fluid" src="{{url('/')}}/frontend/assets/images/p3.png" alt="">
            </div>
            <div class="page-1">
               <img class="img-fluid" src="{{url('/')}}/frontend/assets/images/p4.png" alt="">
            </div>
            <div class="page-1">
               <img class="img-fluid" src="{{url('/')}}/frontend/assets/images/p5.png" alt="">
            </div>
            <div class="page-1">
               <img class="img-fluid" src="{{url('/')}}/frontend/assets/images/p6.png" alt="">
            </div>
         </div>
      </div>
   </div>
</section>
@endsection