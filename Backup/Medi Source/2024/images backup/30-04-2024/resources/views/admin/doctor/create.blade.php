@extends('admin.layouts.app')

@section('content')

<div class="content-page">
    <div class="content">
        <div class="card">
            <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Add Doctor</h3>
                        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Go Back</a>
                    </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.doctors.store') }}" method="POST" id="createDoctorForm">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="organization_name">Organization Name <span
                                    class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="organization_name" name="organization_name" required>
                        <span id="organizationNameError" class="text-danger"></span>

                    </div>

                    <div class="form-group mb-3">
                        <label for="first_name">First Name <span
                                    class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                        <span id="firstNameError" class="text-danger"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="last_name">Last Name <span
                                    class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                        <span id="lastNameError" class="text-danger"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="npi">NPI</label>
                        <input type="text" class="form-control" id="npi" name="npi">
                        <span id="npiError" class="text-danger"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="business_license_number">Business License Number</label>
                        <input type="text" class="form-control" id="business_license_number" name="business_license_number">
                        <span id="businessLicenseNumberError" class="text-danger"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="prescriber_state_license_number">Prescriber State License Number</label>
                        <input type="text" class="form-control" id="prescriber_state_license_number" name="prescriber_state_license_number">
                        <span id="prescriberStateLicenseNumberError" class="text-danger"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="dea_number">DEA Number</label>
                        <input type="text" class="form-control" id="dea_number" name="dea_number">
                        <span id="deaNumberError" class="text-danger"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="speciality">Speciality <span
                                    class="text-danger">*</span></label>
                        <select class="form-control" id="speciality" name="speciality" required>
                            <option value="Ophthalmology">Ophthalmology</option>
                            <option value="Optometry">Optometry</option>
                            <option value="Retina">Retina</option>
                            <option value="Anesthesia">Anesthesia</option>
                            <option value="Derm/Aesthetics">Derm/Aesthetics</option>
                            <option value="Dentist">Dentist</option>
                            <option value="Integrative/Other">Integrative/Other</option>
                            <option value="Vet">Vet</option>
                        </select>
                        <span id="specialityError" class="text-danger"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="phone">Phone <span
                                    class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="phone" name="phone" required>
                        <span id="phoneError" class="text-danger"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="practice_address_street">Practice Address Street <span
                                    class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="practice_address_street" name="practice_address_street" required>
                        <span id="practiceAddressStreetError" class="text-danger"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                        <select class="form-select" id="state" name="state" required>
                            <option value="" disabled selected>Select State</option>
                            @foreach($states as $state)
                            <option value="{{ $state['name'] }}" @if(old('state')==$state['name']) selected @endif>
                                {{ $state['name'] }}</option>
                            @endforeach
                        </select>
                        <span id="stateError" class="text-danger"></span>
                    </div>


                    <div class="form-group mb-3">
                        <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                        <select class="form-select" id="city" name="city" required>
                            <option value="" disabled selected>Select City</option>
                            @foreach($cities as $city)
                            <option value="{{ $city['name'] }}" @if(old('city')==$city['name']) selected @endif>
                                {{ $city['name'] }}</option>
                            @endforeach
                        </select>
                        <span id="cityError" class="text-danger"></span>
                    </div>


                    <div class="form-group mb-3">
                        <label for="zip_code">Zip Code <span
                                    class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="zip_code" name="zip_code" required>
                        <span id="zipCodeError" class="text-danger"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email">Email <span
                                    class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <span id="emailError" class="text-danger"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password">Password <span
                                    class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <span id="passwordError" class="text-danger"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="confirm_password">Confirm Password <span
                                    class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        <span id="confirmPasswordError" class="text-danger"></span>
                    </div>

                    <div class="form-group mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="consent_checkbox" name="consent_checkbox">
                        <label class="form-check-label" for="consent_checkbox">I consent to receive occasional product and promotional updates from MedisourceRx via email, text, and phone.</label>
                        <span id="consentCheckboxError" class="text-danger"></span>
                    </div>

                    <button type="button" onclick="validateForm()" class="btn btn-primary">Create</button>
                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Go Back</a>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add this in the head section or include from a CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   function populateCities() {
        var state = $('#state').val();

        $.ajax({
            url: '{{ route("get-cities-for-state") }}',
            method: 'POST',
            data: {state: state, _token: '{{ csrf_token() }}'},
            success: function(response) {
                // console.log('Cities response:', response);

                var cityDropdown = $('#city');
                cityDropdown.empty().append('<option value="" disabled selected>Select City</option>');

                if (response) {
                    // console.log('Cities found:', response);
                    $.each(response , function(index, val) { 
                        console.log(index, val)
                        });
                    $.each(response, function(index, city) {
                            console.log('Adding city:', city);  
                                cityDropdown.append($('<option>', {
                                    value: city.name,
                                    text: city.name
                                }));
                    });
                } else {
                    console.error('Error fetching cities. Response:', response);
                }
            },
            error: function(error) {
                console.error('Error fetching cities:', error);
            }
        });
    }

    $(document).ready(function() {
        populateCities();

        // Use 'change' event on both state and city dropdowns
        $('#state').on('change', function() {
            // Call populateCities when the state changes
            populateCities();
        });

        $('#city').on('change', function() {
            // You can add additional logic here if needed
        });
    });



    function validateForm() {
        // Basic jQuery validation example
        var isValid = true;

        // Reset previous error messages
        $("").text("");

        // Check each input field
        if ($("#organization_name").val() == "") {
            $("#organizationNameError").text("Organization Name is required.");
            isValid = false;
        }

        if ($("#first_name").val() == "") {
            $("#firstNameError").text("First Name is required.");
            isValid = false;
        }

        if ($("#last_name").val() == "") {
            $("#lastNameError").text("Last Name is required.");
            isValid = false;
        }

        if ($("#speciality").val() == "") {
            $("#specialityError").text("Speciality is required.");
            isValid = false;
        }

        if ($("#phone").val() == "") {
            $("#phoneError").text("Phone is required.");
            isValid = false;
        } else if (!isValidPhone($("#phone").val())) {
            $("#phoneError").text("Please enter a valid phone number.");
            isValid = false;
        }

        if ($("#practice_address_street").val() == "") {
            $("#practiceAddressStreetError").text("Practice Address Street is required.");
            isValid = false;
        }

        if ($("#city").val() == "") {
            $("#cityError").text("City is required.");
            isValid = false;
        }

        if ($("#state").val() == "") {
            $("#stateError").text("State is required.");
            isValid = false;
        }

        if ($("#zip_code").val() == "") {
            $("#zipCodeError").text("Zip Code is required.");
            isValid = false;
        }

        if ($("#email").val() == "") {
            $("#emailError").text("Email is required.");
            isValid = false;
        } else if (!isValidEmail($("#email").val())) {
            $("#emailError").text("Please enter a valid email address.");
            isValid = false;
        }

        if ($("#password").val() == "") {
            $("#passwordError").text("Password is required.");
            isValid = false;
        }

        if ($("#confirm_password").val() == "") {
            $("#confirmPasswordError").text("Confirm Password is required.");
            isValid = false;
        }

        if ($("#password").val() !== $("#confirm_password").val()) {
            $("#passwordError").text("Passwords do not match.");
            $("#confirmPasswordError").text("Passwords do not match.");
            isValid = false;
        }



        // Submit the form if valid, otherwise show error messages
        if (isValid) {
            $("#createDoctorForm").submit();
        }
    }

    function isValidEmail(email) {
        // Regular expression for a valid email address
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function isValidPhone(phone) {
        // Regular expression for a valid phone number
        var phoneRegex = /^\d{10}$/;
        return phoneRegex.test(phone);
    }
</script>



@endsection
