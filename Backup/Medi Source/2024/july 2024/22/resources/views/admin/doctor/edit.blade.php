@extends('admin.layouts.app')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Edit Doctor User</h3>
                            <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Go Back</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.doctor.update', ['id' => $user->id]) }}" method="POST"
                        id="createDoctorForm">
                        @csrf
                        @method('PUT')
                        <!-- Organization Name -->
                        <div class="form-group mb-3">
                            <label for="organization_name">Organization Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="organization_name" name="organization_name"
                                value="{{ old('first_name', $user->organization_name) }}" required>
                            <span id="organizationNameError" class="text-danger"></span>
                            @if ($errors->has('organization_name'))
                                <span class="text-danger">{{ $errors->first('organization_name') }}</span>
                            @endif
                        </div>

                        <!-- First Name -->
                        <div class="form-group mb-3">
                            <label for="first_name">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                value="{{ old('first_name', $user->first_name) }}" required>
                            <span id="firstNameError" class="text-danger"></span>
                            @if ($errors->has('first_name'))
                                <span class="text-danger">{{ $errors->first('first_name') }}</span>
                            @endif
                        </div>

                        <!-- Last Name -->
                        <div class="form-group mb-3">
                            <label for="last_name">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                value="{{ old('last_name', $user->last_name) }}" required>
                            <span id="lastNameError" class="text-danger"></span>
                            @if ($errors->has('last_name'))
                                <span class="text-danger">{{ $errors->first('last_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="last_name">Account Manager Name</label>
                            <select class="form-control" id="account_manager_name" name="account_manager_name">
                            <option value="">Select Account Manager</option>
                            @foreach($sales_manager as $manager) 
                            <option value="{{ $manager->id }}" {{ $user->account_manager_name == $manager->name ? 'selected' : '' }}>{{ $manager->name }}</option> 
                            @endforeach
                            </select>
                            @if ($errors->has('account_manager_name'))
                                <span class="text-danger">{{ $errors->first('account_manager_name') }}</span>
                            @endif
                        </div>
                        <!-- NPI -->
                        <div class="form-group mb-3">
                            <label for="npi">NPI</label>
                            <input type="text" class="form-control" id="npi" name="npi"
                                value="{{ old('npi', $user->npi) }}">
                            <span id="npiError" class="text-danger"></span>
                            @if ($errors->has('npi'))
                                <span class="text-danger">{{ $errors->first('npi') }}</span>
                            @endif
                        </div>

                        <!-- Business License Number -->
                        <div class="form-group mb-3">
                            <label for="business_license_number">Business License Number</label>
                            <input type="text" class="form-control" id="business_license_number"
                                name="business_license_number"
                                value="{{ old('business_license_number', $user->business_license_number) }}">
                            <span id="businessLicenseNumberError" class="text-danger"></span>
                            @if ($errors->has('business_license_number'))
                                <span class="text-danger">{{ $errors->first('business_license_number') }}</span>
                            @endif
                        </div>

                        <!-- Prescriber State License Number -->
                        <div class="form-group mb-3">
                            <label for="prescriber_state_license_number">Prescriber State License Number</label>
                            <input type="text" class="form-control" id="prescriber_state_license_number"
                                name="prescriber_state_license_number"
                                value="{{ old('prescriber_state_license_number', $user->prescriber_state_license_number) }}">
                            <span id="prescriberStateLicenseNumberError" class="text-danger"></span>
                            @if ($errors->has('prescriber_state_license_number'))
                                <span class="text-danger">{{ $errors->first('prescriber_state_license_number') }}</span>
                            @endif
                        </div>

                        <!-- DEA Number -->
                        <div class="form-group mb-3">
                            <label for="dea_number">DEA Number</label>
                            <input type="text" class="form-control" id="dea_number" name="dea_number"
                                value="{{ old('dea_number', $user->dea_number) }}">
                            <span id="deaNumberError" class="text-danger"></span>
                            @if ($errors->has('dea_number'))
                                <span class="text-danger">{{ $errors->first('dea_number') }}</span>
                            @endif
                        </div>                        
                        <div class="form-group mb-3">
                            <label for="vat_reg_no"> VAT Registration No</label>
                            <input type="text" class="form-control" id="vat_reg_no" name="vat_reg_no"
                                value="{{ old('vat_reg_no', $user->vat_reg_no) }}">
                            <span id="deaNumberError" class="text-danger"></span>
                            @if ($errors->has('vat_reg_no'))
                                <span class="text-danger">{{ $errors->first('vat_reg_no') }}</span>
                            @endif
                        </div>

                        <!-- Speciality -->
                        <div class="form-group mb-3">
                        <label for="speciality" class="form-label">Speciality <span class="text-danger">*</span></label>
                                @php 
                                    $array = ['DO', 'MD', 'NP', 'PA', 'ND']; 
                                @endphp
                                <select class="form-control" id="speciality" name="speciality" required>
                                    <!-- Placeholder option -->
                                    <option value="" disabled {{ empty($user->speciality) ? 'selected' : '' }}>Select your speciality</option>
                                    <!-- Add options for the dropdown -->
                                    @foreach ($array as $option)
                                        <option value="{{ $option }}" {{ $user->speciality == $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                    <!-- Option for 'other' -->
                                    <option value="other" @if (!in_array($user->speciality, $array) && !empty($user->speciality)) selected @endif>Other</option>
                                </select>
                            <span id="specialityError" class="text-danger"></span>
                            @if ($errors->has('speciality'))
                                <span class="text-danger">{{ $errors->first('speciality') }}</span>
                            @endif
                        </div>
                        <div class="rgs-w  mb-3 @if (in_array($user->speciality, $array)) d-none @endif"
                            id="other_speciality_div">
                            <label for="speciality" class="form-label">Other Speciality <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="other_speciality" id="other_speciality"
                                value="{{ $user->speciality }}">
                            @if ($errors->has('other_speciality'))
                                <span class="text-danger">{{ $errors->first('other_speciality') }}</span>
                            @endif
                        </div>
                        <!-- Phone -->
                        <div class="form-group mb-3">
                            <label for="phone">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                value="{{ old('phone', $user->phone) }}" required>
                            <span id="phoneError" class="text-danger"></span>
                            @if ($errors->has('phone'))
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>

                        <!-- Practice Address Street -->
                        <div class="form-group mb-3">
                            <label for="practice_address_street">Practice Address Street <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="practice_address_street"
                                name="practice_address_street"
                                value="{{ old('practice_address_street', $user->practice_address_street) }}" required>
                            <span id="practiceAddressStreetError" class="text-danger"></span>
                            @if ($errors->has('practice_address_street'))
                                <span class="text-danger">{{ $errors->first('practice_address_street') }}</span>
                            @endif
                        </div>

                        <!-- City -->
                        <div class="form-group mb-3">
                            <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" name="city" value="{{ old('city', $user->city) }}"
                                class="form-control py-2" id="city" required>
                            <span id="cityError" class="text-danger"></span>
                            @if ($errors->has('city'))
                                <span class="text-danger">{{ $errors->first('city') }}</span>
                            @endif
                        </div>

                        <!-- State -->
                        <div class="form-group mb-3">
                            <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                            <select name="state" class="form-control select2" id="state">
                                <option value="">Select State</option>
                                @foreach (NumberFormat::stateListDropdown() as $state)
                                    <option value="{{ $state['code'] }}"
                                        @if ($user->state == $state['code']) {{ 'selected' }} @endif>
                                        {{ $state['name'] }}</option>
                                @endforeach
                            </select>
                            <span id="stateError" class="text-danger"></span>
                            @if ($errors->has('state'))
                                <span class="text-danger">{{ $errors->first('state') }}</span>
                            @endif
                        </div>

                        <!-- Zip Code -->
                        <div class="form-group mb-3">
                            <label for="zip_code">Zip Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="zip_code" name="zip_code"
                                value="{{ old('zip_code', $user->zip_code) }}" required>
                            <span id="zipCodeError" class="text-danger"></span>
                            @if ($errors->has('zip_code'))
                                <span class="text-danger">{{ $errors->first('zip_code') }}</span>
                            @endif
                        </div>

                        <!-- Email -->
                        <div class="form-group mb-3">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email', $user->email) }}" required>
                            <span id="emailError" class="text-danger"></span>
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <!-- Password -->
                        <div class="form-group mb-3">
                            <label for="password">Password <span class="text-danger">(Leave Blank if don't want to
                                    change)</span></label>
                            <input type="password" class="form-control" id="password" name="password">
                            <span id="passwordError" class="text-danger"></span>
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>

                        <!-- Confirm Password -->
                        {{-- <div class="form-group mb-3">
                        <label for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" >
                        <span id="confirmPasswordError" class="text-danger"></span>
                    </div> --}}

                        <!-- Status -->
                        <div class="form-group mb-3">
                            <label for="status">Status:</label>
                            <div class="switch-container">
                                <input type="hidden" name="status" value="inactive">
                                <!-- Hidden input to hold the selected value -->
                                @if ($errors->has('status'))
                                    <div class="error">{{ $errors->first('status') }}</div>
                                @endif
                                <label class="switch">
                                    <input type="checkbox" class="status-switch" id="status_switch" name="status"
                                        {{ old('status', $user->status) === 'active' ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                                <span class="status-label"></span>
                            </div>
                        </div>

                        <!-- Consent Checkbox -->
                        <div class="form-group mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="consent_checkbox"
                                name="consent_checkbox" @if (old('consent_checkbox', $user->consent_checkbox)) checked @endif>
                            <label class="form-check-label" for="consent_checkbox">I consent to receive occasional product
                                and promotional updates from MedisourceRx via email, text, and phone.</label>
                            <span id="consentCheckboxError" class="text-danger"></span>
                        </div>

                        <button type="button" onclick="validateForm()" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Go Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function validateForm() {
            // Basic jQuery validation example
            var isValid = true;

            // Reset previous error messages
            $(".text-danger").text("");

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
        $('#speciality').on('change', function(e) {
            var special = $(this).val();
            $('#other_speciality_div').addClass('d-none');
            if (special == "other") {
                $('#other_speciality_div').removeClass('d-none');
            }
        })
    </script>
@endsection
