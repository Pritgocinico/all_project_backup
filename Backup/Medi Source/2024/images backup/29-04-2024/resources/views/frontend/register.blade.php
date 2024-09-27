@extends('frontend.layouts.app')

@section('content')
    <section class="banner-section about-parent prdct-parent position-relative  py-sm-5 py-1">
        <div class="container">
            <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start">
                <div class="col-md-8">
                    <h1 class="text-white text-start">
                        Customer Registration
                    </h1>
                    <div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}"
                                        class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page"><a href=""
                                        class="text-decoration-none text-white"> Customer Registration</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="c-registration">
                <div class="registration-in">
                    <h2>New Customer Registration Form</h2>
                    <p>Register now to experience a fast and simple ordering experience in our Shopping Cart. If you have
                        any questions, please contact us at (714) 455-1300.</p>
                    <form action="{{ route('register') }}" method="POST" class="needs-validation" class="register-form"
                        novalidate enctype="multipart/form-data">
                        @csrf
                        <div class="res-shadow">
                            <div class="rgs-w">
                                <label for="organization_name" class="form-label">Organization Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="organization_name" class="form-control py-2"
                                    id="organization_name" value="{{ old('organization_name') }}" required>
                                <div class="invalid-feedback">
                                    Thispd field is required
                                </div>
                                @if ($errors->has('organization_name'))
                                    <span class="text-danger">{{ $errors->first('organization_name') }}</span>
                                @endif
                            </div>

                            <div class="d-lg-flex flex-lg-row flex-column  gap-2 name-f">
                                <div class="rgs-w">
                                    <label for="first_name" class="form-label">First Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control py-2"
                                        value="{{ old('first_name') }}" id="first_name" required>
                                    <div class="invalid-feedback">
                                        This field is required
                                    </div>
                                    @if ($errors->has('first_name'))
                                        <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                    @endif
                                </div>


                                <div class="rgs-w">
                                    <label for="last_name" class="form-label">Last Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control py-2"
                                        value="{{ old('last_name') }}" id="last_name" required>
                                    <div class="invalid-feedback">
                                        This field is required
                                    </div>
                                    @if ($errors->has('last_name'))
                                        <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="d-lg-flex flex-lg-row flex-column  gap-2 name-f">


                                <div class="rgs-w">
                                    <label for="npi" class="form-label">NPI</label>
                                    <input type="text" name="npi" class="form-control py-2"
                                        value="{{ old('npi') }}" id="npi">
                                    <div class="invalid-feedback">
                                        This field is required
                                    </div>
                                    @if ($errors->has('npi'))
                                        <span class="text-danger">{{ $errors->first('npi') }}</span>
                                    @endif
                                </div>

                                <div class="rgs-w">
                                    <label for="business_license_number" class="form-label">Business License Number</label>
                                    <input type="text" name="business_license_number"
                                        value="{{ old('business_license_number') }}" class="form-control py-2"
                                        id="business_license_number">
                                    <div class="invalid-feedback">
                                        This field is required
                                    </div>
                                    @if ($errors->has('business_license_number'))
                                        <span class="text-danger">{{ $errors->first('business_license_number') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="d-lg-flex flex-lg-row flex-column  gap-2 name-f">
                                <div class="rgs-w">
                                    <label for="prescriber_state_license_number" class="form-label">Prescriber State License
                                        Number</label>
                                    <input type="text" name="prescriber_state_license_number"
                                        value="{{ old('prescriber_state_license_number') }}" class="form-control py-2"
                                        id="prescriber_state_license_number">
                                    <div class="invalid-feedback">
                                        This field is required
                                    </div>
                                    @if ($errors->has('prescriber_state_license_number'))
                                        <span
                                            class="text-danger">{{ $errors->first('prescriber_state_license_number') }}</span>
                                    @endif
                                </div>

                                <div class="rgs-w">
                                    <label for="dea_number" class="form-label">DEA Number</label>
                                    <input type="text" name="dea_number" class="form-control py-2"
                                        value="{{ old('dea_number') }}" id="dea_number">
                                    <div class="invalid-feedback">
                                        This field is required
                                    </div>
                                    @if ($errors->has('dea_number'))
                                        <span class="text-danger">{{ $errors->first('dea_number') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="d-lg-flex flex-lg-row flex-column  gap-2 name-f">
                                <div class="rgs-w">
                                    <label for="ENI_number" class="form-label">EIN Number <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="ENI_number" class="form-control py-2" id="ENI_number" value="{{ old('ENI_number') }}"
                                        value="" required="">
                                    <div class="invalid-feedback">
                                        This field is required
                                    </div>
                                </div>

                                <div class="rgs-w">
                                    <label for="phone" class="form-label">Phone <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control py-2" id="phone"
                                        value="{{ old('phone') }}" required>
                                    <div class="invalid-feedback" id="phone-error">
                                        This field is required
                                    </div>
                                    @if ($errors->has('phone'))
                                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="rgs-w">
                                <label for="speciality" class="form-label">Speciality <span
                                        class="text-danger">*</span></label>
                                <select name="speciality" class="form-control py-2" id="speciality" required>
                                    <!-- Add options for the dropdown -->
                                    <option value="Ophthalmology" @if (old('speciality') == 'Ophthalmology') selected @endif>
                                        Ophthalmology</option>
                                    <option value="Optometry" @if (old('speciality') == 'Optometry') selected @endif>Optometry
                                    </option>
                                    <option value="Retina" @if (old('speciality') == 'Retina') selected @endif>Retina
                                    </option>
                                    <option value="Anesthesia" @if (old('speciality') == 'Anesthesia') selected @endif>
                                        Anesthesia</option>
                                    <option value="Derm/Aesthetics" @if (old('speciality') == 'Derm/Aesthetics') selected @endif>
                                        Derm/Aesthetics</option>
                                    <option value="Dentist" @if (old('speciality') == 'Dentist') selected @endif>Dentist
                                    </option>
                                    <option value="Integrative/Other" @if (old('speciality') == 'Integrative/Other') selected @endif>
                                        Integrative/Other</option>
                                    <option value="Vet" @if (old('speciality') == 'Vet') selected @endif>Vet</option>
                                </select>
                                <div class="invalid-feedback">
                                    This field is required
                                </div>
                                @if ($errors->has('speciality'))
                                    <span class="text-danger">{{ $errors->first('speciality') }}</span>
                                @endif
                            </div>
                            <div class="rgs-w">
                                <h4 class="text-start mt-4 mt-sm-5 mb-2">Practice Address</h4>
                            </div>

                            <div class="rgs-w">
                                <label for="practice_address_street" class="form-label">Street <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="practice_address_street"
                                    value="{{ old('practice_address_street') }}" class="form-control py-2"
                                    id="practice_address_street" required>
                                <div class="invalid-feedback">
                                    This field is required
                                </div>
                                @if ($errors->has('practice_address_street'))
                                    <span class="text-danger">{{ $errors->first('practice_address_street') }}</span>
                                @endif
                            </div>

                            {{-- <div class="rgs-w">
                    <label for="street" class="form-label">Street </label>
                    <input type="text" name="street" class="form-control py-2" id="street" required>
                    <div class="invalid-feedback">
                        This field is required
                    </div>
                    @if ($errors->has('street'))
                        <span class="text-danger">{{ $errors->first('street') }}</span>
                    @endif
                </div> --}}

                            <div class="d-lg-flex flex-lg-row flex-column  gap-2 name-f">
                                <div class="rgs-w">
                                    <label for="state" class="form-label">State <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select select2" id="state" name="state">
                                        <option value="" disabled selected>Select State</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state['name'] }}"
                                                @if (old('state') == $state['name']) selected @endif>
                                                {{ $state['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('state'))
                                        <span class="text-danger">{{ $errors->first('state') }}</span>
                                    @endif
                                </div>
                                <div class="rgs-w">
                                    <label for="city" class="form-label">City <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select select2-city" id="city" name="city">
                                        <option value="" disabled selected>Select City</option>
                                        {{-- @foreach ($cities as $city)
                                                <option value="{{ $city['name'] }}"
                                                    @if (old('city') == $city['name']) selected @endif>
                                                    {{ $city['name'] }}</option>
                                            @endforeach --}}
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a city.
                                    </div>
                                    @if ($errors->has('city'))
                                        <span class="text-danger">{{ $errors->first('city') }}</span>
                                    @endif
                                </div>

                                
                            </div>

                            <div class="d-lg-flex flex-lg-row flex-column  gap-2 name-f">

                                <div class="rgs-w">
                                    <label for="zip_code" class="form-label">Zip Code <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="zip_code" class="form-control py-2"
                                        value="{{ old('zip_code') }}" id="zip_code">
                                    <div class="invalid-feedback">
                                        This field is required
                                    </div>
                                    @if ($errors->has('zip_code'))
                                        <span class="text-danger">{{ $errors->first('zip_code') }}</span>
                                    @endif
                                </div>

                                <div class="rgs-w">
                                    <label for="email" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control py-2" id="email"
                                        value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="parent-upload-btn-lice">

                                <div class="text"><label for="fileupload"><i class="fa-solid fa-upload me-2"></i>Upload
                                        Bus./DEA LIC (OPTIONAL)</label><input multiple id='fileupload' type="file"
                                        class="fileupload" name="fileupload[]" /></div>


                                <div class="parent-attachment">
                                    <p class="mb-0">Attachments (<span id="file_upload_count">0</span>)</p>
                                </div>
                               
                            </div>
                            @if ($errors->has('fileupload'))
                                <span class="text-danger">{{ $errors->first('fileupload') }}</span>
                            @endif
                        <span class="text-danger" id="file_upload_error"></span>
                            <div class="rgs-w mt-4 d-flex align-items-start gap-3">
                                <input type="checkbox" class="form-check-input" id="consent" name="consent" @if(old('consent') == "on") {{'checked'}} @endif>
                                <label class="form-check-label" for="consent">I consent to receive occasional product and promotional updates from MedisourceRx via email, text, and phone.</label>

                                </div>
                                @if ($errors->has('consent'))
                                <span class="text-danger">{{ $errors->first('consent') }}</span>
                            @endif
                            <div class="rgs-w mt-4 d-flex align-items-start gap-3">
                                <input type="checkbox" class="form-check-input" id="pharmacy_agreement" @if(old('pharmacy_agreement') == "on") {{'checked'}} @endif
                                    name="pharmacy_agreement">
                                <label class="form-check-label" for="consent">By submitting this form, I agree to the Prescriber & Pharmacy Agreement and Terms &
                                    Conditions. <a href="{{ route('terms') }}">Prescriber & Pharmacy agreement</a></label>
                                    </div>
                                    @if ($errors->has('pharmacy_agreement'))
                                <span class="text-danger">{{ $errors->first('pharmacy_agreement') }}</span>
                            @endif
                            <div class="rgs-w mt-4 d-flex align-items-start gap-3">
                                <input type="checkbox" class="form-check-input" id="terms_condition" @if(old('terms_condition') == "on") {{'checked'}} @endif
                                    name="terms_condition">
                                <label class="form-check-label" for="consent"><a href="{{ route('terms') }}"><a
                                            href="{{ route('terms') }}">Terms & Conditions</a><a href="{{ route('terms') }}" target="_blank"><span> (Click Here) </span></a></label>
                                        </div>
                                        @if ($errors->has('terms_condition'))
                                <span class="text-danger">{{ $errors->first('terms_condition') }}</span>
                            @endif
                            <div class="f-in register">
                                <button class="btn text-white" type="submit">Submit</button>
                            </div>

                            {{-- <div class="res-last">


                                <p>This site is protected by reCAPTCHA and the Google <span><a
                                            href="{{ route('privacy.policy') }}">Privacy
                                            Policy</a></span> and <span><a href="{{ route('terms') }}">Terms of
                                            Service</a></span> apply.

                                <p><b> By submitting this form, I agree to the Prescriber & Pharmacy Agreement and Terms &
                                        Conditions.</b>
                                </p>
                                <p><a href="{{ route('terms') }}">Prescriber & Pharmacy agreement</a></p>
                                <p><a href="{{ route('terms') }}">Terms & Conditions</a></p>
                            </div> --}}
                    </form>

                    <!-- <p>Already have an account? <span><a href="{{ route('logindoctor') }}">Sign in</a></span></p>
                        <p>This site is protected by reCAPTCHA and the Google <span><a href="{{ route('privacy.policy') }}">Privacy
                                    Policy</a></span> and <br><span><a href="{{ route('terms') }}">Terms of Service</a></span> apply.
                        </p> -->
                </div>
            </div>
    </section>
    <!-- Add this in the head section of your HTML file -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#fileupload').on('change', function(e) {
                var files = document.getElementById('fileupload').files;
                var fileCount = files.length;
                $('#file_upload_count').html(fileCount);
                console.log(files);
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    $('#file_upload_error').html('');
                    // Get the file extension
                    var extension = file.name.split('.').pop().toLowerCase();

                    // List of allowed image file extensions
                    var allowedExtensions = ['jpg', 'jpeg', 'png', 'gif','pdf','doc' ,'docx'];

                    // Check if the file extension is allowed
                    if (allowedExtensions.indexOf(extension) === -1) {
                        $('#file_upload_error').html('Please select only files (JPG, JPEG, PNG, GIF, PDF, DOC, DOCX).');
                        return;
                    }
                }
            })
            $(document).on('change', '#state', function() {
                var state = $(this).val();
                $.ajax({
                    url: "{{ route('cityByState', '') }}" + "/" + state,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#city').html(data);
                    }
                });
            });
            // Phone number validation
            $('#phone').on('input', function() {
                // Remove non-numeric characters from the input
                var phoneValue = $(this).val().replace(/\D/g, '');

                // Check if the remaining value is a 10-digit number
                var isPhoneValid = /^\d{10}$/.test(phoneValue);

                if (isPhoneValid) {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                    $('#phone-error').text('');
                } else {
                    $(this).removeClass('is-valid');
                    $(this).addClass('is-invalid');
                    $('#phone-error').text('Please enter a valid 10-digit phone number.');
                }
            });

            // Email validation
            $('#email').on('input', function() {
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                var emailValue = $(this).val();

                if (emailRegex.test(emailValue)) {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                    $('#email-error').text('');
                } else {
                    $(this).removeClass('is-valid');
                    $(this).addClass('is-invalid');
                    $('#email-error').text('Please enter a valid email address.');
                }
            });

            // ... Your existing form submission code ...
        });
    </script>
@endsection
