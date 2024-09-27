@extends('frontend.layouts.app')

@section('content')
<section class="banner-section about-parent prdct-parent position-relative py-sm-5 py-1">
    <div class="container">
        <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start ">
            <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
            <div class="col-md-8">
                <h1 class="text-white text-start">
                    How can we help?
                </h1>
                <div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}" class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="" class="text-decoration-none text-white"> How can we help?</a></li>
                            </ol>
                        </nav>
                    </div>
            </div>
        </div>
    </div>
</section>
<div id="successMessage" class="alert alert-success mt-3" style="display:none;">
    Your form has been submitted successfully.
</div>

<section class="help-sec">
    <div class="container">
        <div class="help-inner">
            <div class="help-form">

                <h3>DROP US A LINE!
                </h3>
                <form class="needs-validation" novalidate action="{{ route('submit.help.inquiry') }}" method="post">
            @csrf

            <div class="help-w mb-3">
                <label for="validationCustom01" class="form-label">Your Name</label>
                <input type="text" class="form-control" id="validationCustom01" name="name" required>
            </div>

            <div class="help-w mb-3">
                <label for="validationCustom02" class="form-label">Email</label>
                <input type="email" class="form-control" id="validationCustom02" name="email" required>
                <div class="invalid-feedback">
                    This field is required. Please enter a valid email address.
                </div>
            </div>

            <div class="help-w mb-3">
                <label for="validationCustom03" class="form-label">Phone</label>
                <input type="number" class="form-control" id="validationCustom03" name="phone" required>
            </div>

            <div class="help-w mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Message / Question</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="message" required></textarea>
            </div>

            <div class="help-w mb-3">
                <label for="exampleFormControlTextarea2" class="form-label">Team Member You're Contacting?</label>
                <textarea class="form-control" id="exampleFormControlTextarea2" rows="1" name="team_member_contact"></textarea>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="sign_up_for_email_list">
                <label class="form-check-label" for="flexCheckDefault">
                    Sign up for our email list for updates, promotions, shopping cart access, and more.
                </label>
            </div>
            <div class="help-btn">
                <button type="button" class="btn btn-primary" onclick="submitForm()">SEND</button>
            </div>
        </form>
        <p class="mt-3">
            This site is protected by reCAPTCHA and the Google <a href="{{ route('privacy.policy') }}">Privacy Policy</a> and <a href="{{ route('terms') }}">Terms of Service</a> apply.
        </p>
    </div>


            <div class="help-info">
                <h3>WE'RE LOCATED IN THE HUMBOLT BUSINESS PARK
                </h3>
                <p>We love our customers, so feel free to visit during normal business hours.

                </p>
                <h3>MEDISOURCERX
                </h3>
                <p class="mb-sm-4 mb-2">10525 Humbolt St, Los Alamitos, CA 90720

                </p>
                <p><span><a class="text-call-mail-color text-decoration-none" href="tel:+(714)455-1300">+(714)455-1300</a> |</span> Main Phone Number</p>
                <p><span><a class="text-call-mail-color text-decoration-none" href="tel:+(714)455-1300">+(714)455-1300</a> |</span> Facsimile (FAX) Number</p>

                <p class="mt-sm-4 mt-2"><span><a class="text-call-mail-color text-decoration-none" href="mailto:sales@medisourcerx.com">sales@medisourcerx.com</a> |</span> Company General Email Address</p>

                <h3>HOURS
                </h3>
                <div class="ddpp-div">
                    <p id="P">Open Today</p>
                    <div class="time-dd">
                        <div class="btn-group help-dd">
                            <button class="btn px-0 py-0 btn-lg dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                7:00 AM to 5:00 PM (PST) 
                            </button>
                            <ul class="dropdown-menu">
                                <li> Tue
                                7:00 AM to 5:00 PM (PST)</li>
                                <li> Wed

                                7:00 AM to 5:00 PM (PST)</li>
                                <li> Thu

                                7:00 AM to 5:00 PM (PST)</li>
                                <li>
                                    Fri

                                    7:00 AM to 5:00 PM (PST)</li>
                                <li> Saturday Closed</li>
                                <li> Sunday Closed</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="helpyr-btn">

                    <div class="inner-btns">

                        <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                            href="tel:+(714)455-1300">
                            <span class="inner-pdf-text-cart  py-3 text-white">CALL CUSTOMER SUPPORT</span>
                        </a>
                    </div>
                    <div class="inner-btns">

                        <a class="text-decoration-none cart-btn px-3  clr-black d-flex gap-1 align-items-center parent-cartorder"
                            href="https://www.fedex.com/en-us/tracking.html" target="_blank" >
                            <span class="inner-pdf-text-cart  py-3 text-white">TRACK  YOUR SHIPMENT</span>
                        </a>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
function submitForm() {
    // Serialize the form data
    var formData = new FormData($('form')[0]);
    // Submit the form using AJAX
    $.ajax({
        url: $('form').attr('action'),
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(response) {
            // If the submission was successful, show a success message
            Swal.fire({
                title: 'Success!',
                text: 'Your form has been submitted successfully.',
                icon: 'success',
                confirmButtonText: "Yes",
            }).then((result) => {
                if(result.value){
                    $('#successMessage').show();
                        location.reload();
                }
            });
            // Show the success message div

            // You can also redirect or perform other actions here if needed
        },
        error: function(error) {
            // If there's an error with the form submission, show an error message
            Swal.fire({
                title: 'Error!',
                text: 'There was an error submitting the form. Please try again later.',
                icon: 'error',
            });
        }
    });
}
</script>
@endsection
