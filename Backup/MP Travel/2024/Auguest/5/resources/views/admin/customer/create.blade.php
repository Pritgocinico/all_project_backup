@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">{{ isset($customer) ? 'Update' : 'Create' }} Customer</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    @php
                        $route = route('customer.store');
                        $method = 'POST';
                        if (isset($customer)) {
                            $route = route('customer.update', $customer->id);
                            $method = 'PUT';
                        }
                    @endphp
                    <form action="{{ $route }}" enctype="multipart/form-data" method="POST">
                        @method($method)
                        @csrf
                        <input type="hidden" name="id" value="{{ $customer->id ?? '' }}">
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Insurance</label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="insurance" class="form-control" id="insurance">
                                    <option value="">Select Insurance</option>
                                    <option value="Mutual Fund" @if (old('insurance', $customer->insurance ?? '') == 'Mutual Fund') selected @endif>Mutual Fund
                                    </option>
                                    <option value="General Insurance" @if (old('insurance', $customer->insurance ?? '') == 'General Insurance') selected @endif>General Insurance
                                    </option>
                                    <option value="Travel" @if (old('insurance', $customer->insurance ?? '') == 'Travel') selected @endif>Travel
                                    </option>
                                </select>
                                @error('insurance')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Insurance Type</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input class="form-check-input" type="radio" name="insurance_type" id="disabled" value="0" required
                                    {{ isset($customer) && $customer->insurance_type == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="disabled">
                                        Individual
                                    </label>
                                    
                                    <input class="form-check-input" type="radio" name="insurance_type" id="view" value="1"
                                    {{ isset($customer) && $customer->insurance_type == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="view">
                                        Health & Motor
                                    </label>
                                    
                                    <input class="form-check-input" type="radio" name="insurance_type" id="corporate" value="2"
                                    {{ isset($customer) && $customer->insurance_type == '2' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="corporate">
                                        Corporate
                                    </label>
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="name" class="form-control" placeholder="Enter Name"
                                    value="{{ old('name', $customer->name ?? '') }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Email</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="email" class="form-control" placeholder="Enter Email"
                                    value="{{ old('email', $customer->email ?? '') }}">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Password</label></div>
                            <div class="col-md-4 col-xl-4">
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control" id="password"
                                        placeholder="Enter Password" value="{{ old('password') }}">
                                    <div class="input-group-append login_button_password">
                                        <span class="input-group-text" id="password_eye_button"><i
                                                class="bi bi-eye"></i></span>
                                    </div>
                                </div>
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Confirm Password</label></div>
                            <div class="col-md-4 col-xl-4">
                                <div class="input-group">
                                    <input type="password" name="confirm_password" class="form-control"
                                        placeholder="Enter Confirm Password" id="confirm_password"
                                        value="{{ old('confirm_password') }}">
                                    <div class="input-group-append confirm_button_password">
                                        <span class="input-group-text" id="confirm_eye_button"><i
                                                class="bi bi-eye"></i></span>
                                    </div>
                                </div>
                                @error('confirm_password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Phone Number</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="mobile_number" class="form-control"
                                    placeholder="Enter Phone Number"
                                    value="{{ old('mobile_number', $customer->mobile_number ?? '') }}">
                                @error('mobile_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Gender</label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="gender" class="form-control" id="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" @if (old('gender', $customer->gender ?? '') == 'male') selected @endif>Male
                                    </option>
                                    <option value="female" @if (old('gender', $customer->gender ?? '') == 'female') selected @endif>Female
                                    </option>
                                </select>
                                @error('gender')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Birth Date</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="date" class="form-control" name="birth_date" max="{{ date('Y-m-d') }}"
                                    value="{{ old('birth_date', $customer->birth_date ?? '') }}">
                                @error('birth_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Pan Card Number</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="pan_card_number" class="form-control" id="pan_card_number"
                                    placeholder="Enter Pan Card Number"
                                    value="{{ old('pan_card_number', $customer->pan_card_number ?? '') }}">
                                @error('pan_card_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Aadhar Card Number</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="aadhaar_number" class="form-control" id="aadhaar_number"
                                    placeholder="Enter Aadhar Card Number"
                                    value="{{ old('aadhaar_number', $customer->aadhaar_number ?? '') }}">
                                @error('aadhaar_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Service Preference</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="service_preference" class="form-control" id="service_preference"
                                    placeholder="Enter Service Preference"
                                    value="{{ old('service_preference', $customer->service_preference ?? '') }}">
                                @error('service_preference')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Reference</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="reference" class="form-control" id="reference"
                                    placeholder="Enter Reference"
                                    value="{{ old('reference', $customer->reference ?? '') }}">
                                @error('reference')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr class="my-6">
                        <h4>Address Detail</h4>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Address</label></div>
                            <div class="col-md-4 col-xl-4">
                                <textarea name="address" class="form-control" id="address" placeholder="Enter Address">{{ old('address', $customer->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Country</label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="country" class="form-control" id="country" onchange="getState()">
                                    <option value="">Select Country</option>
                                    @foreach ($countryList as $country)
                                        <option value="{{ $country->iso2 }}"
                                            @if (old('country', $customer->country ?? '') == $country->iso2) selected @endif>{{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">State</label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="state" class="form-control" id="state" onchange="getCity()">
                                    <option value="">Select State</option>
                                </select>
                                @error('state')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">City</label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="city" class="form-control" id="city">
                                    <option value="">Select City</option>
                                </select>
                                @error('city')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Pin Code</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="pin_code" class="form-control" id="pin_code"
                                    placeholder="Enter Pin Code"
                                    value="{{ old('pin_code', $customer->pin_code ?? '') }}">
                                @error('pin_code')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr class="my-6">
                        <h4>Bank Detail</h4>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Account Number</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" name="account_number" class="form-control"
                                    placeholder="Enter Account Number"
                                    value="{{ old('account_number', $customer->account_number ?? '') }}">
                                @error('account_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Account Name</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="account_name" class="form-control"
                                    placeholder="Enter Account Name"
                                    value="{{ old('account_name', $customer->account_name ?? '') }}">
                                @error('account_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">

                            <div class="col-md-2"><label class="form-label mb-0">Bank Name</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="bank_name" class="form-control"
                                    placeholder="Enter Bank Name"
                                    value="{{ old('bank_name', $customer->bank_name ?? '') }}">
                                @error('bank_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Branch Name</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="branch_name" class="form-control"
                                    placeholder="Enter Branch Name"
                                    value="{{ old('branch_name', $customer->branch_name ?? '') }}">
                                @error('branch_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">

                            <div class="col-md-2"><label class="form-label mb-0">IFSC Code</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="ifsc_code" class="form-control"
                                    placeholder="Enter IFSC Code"
                                    value="{{ old('ifsc_code', $customer->ifsc_code ?? '') }}">
                                @error('ifsc_code')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Card Number</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" name="card_number" class="form-control"
                                    placeholder="Enter Card Number"
                                    value="{{ old('card_number', $customer->card_number ?? '') }}">
                                @error('card_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">

                            <div class="col-md-2"><label class="form-label mb-0">Card Name</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="card_name" class="form-control"
                                    placeholder="Enter Card Name"
                                    value="{{ old('card_name', $customer->card_name ?? '') }}">
                                @error('card_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Card Month</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" name="card_month" class="form-control" min="1"
                                    max="12" placeholder="Enter Card Month"
                                    value="{{ old('card_month', $customer->card_month ?? '') }}">
                                @error('card_month')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">

                            <div class="col-md-2"><label class="form-label mb-0">Card Year</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="card_year" class="form-control" min="{{ date('Y') }}"
                                    placeholder="Enter Card Year"
                                    value="{{ old('card_year', $customer->card_year ?? '2024') }}">
                                @error('card_year')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Card CVV</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" name="card_cvv" class="form-control" 
                                    placeholder="Enter Card CVV"
                                    value="{{ old('card_cvv', $customer->card_cvv ?? '') }}">
                                @error('card_cvv')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr class="my-6">
                        <div class="d-flexjustify-content-end gap-2">
                            <a href="{{ route('customer.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </form>
                </main>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script>
        $(".login_button_password").click(function() {
            var input = $("#password");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
                $('#password_eye_button').html('<i class="bi bi-eye-slash"></i>');
            } else {
                input.attr("type", "password");
                $('#password_eye_button').html('<i class="bi bi-eye"></i>');
            }
        });
        $(".confirm_button_password").click(function() {
            var input = $("#confirm_password");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
                $('#confirm_eye_button').html('<i class="bi bi-eye-slash"></i>');
            } else {
                input.attr("type", "password");
                $('#confirm_eye_button').html('<i class="bi bi-eye"></i>');
            }
        });
        var oldState = "{{ old('state', $customer->state ?? '') }}";
        var oldCountry = "{{ old('country', $customer->country ?? '') }}";
        var oldCity = "{{ old('city', $customer->city ?? '') }}";
        $(document).ready(function(e) {
            $('#country').select2();
            if (oldCountry !== "") {
                getState();
            }
            if (oldState !== "") {
                getCity();
            }
        })

        function getState() {
            var country = $('#country').val();
            if (country == null) {
                country = oldCountry;
            }
            $.ajax({
                method: 'get',
                url: "{{ route('state-by-country') }}",
                data: {
                    country_code: country,
                },
                success: function(res) {
                    var html = "<option value=''>Select State</option>";
                    $.each(res.data, function(i, v) {
                        var select = "";
                        if (oldState == v.id) {
                            select = "selected";
                        }
                        html += "<option value='" + v.id + "'" + select + ">" + v.name + "</option>"
                    })
                    $('#state').html("")
                    $('#state').html(html)
                    $('#state').select2();
                }
            });
        }

        function getCity() {
            var state = $('#state').val();
            if (state == "") {
                state = oldState;
            }
            var country = $('#country').val();
            if (country == null) {
                country = oldCountry;
            }
            $.ajax({
                method: 'get',
                url: "{{ route('city-by-state') }}",
                data: {
                    state: state,
                    country_code: country,
                },
                success: function(res) {
                    var html = "<option value=''>Select City</option>";
                    $.each(res.data, function(i, v) {
                        var select = "";
                        if (oldCity == v.id) {
                            select = "selected";
                        }
                        html += "<option value='" + v.id + "'" + select + ">" + v.name + "</option>"
                    })
                    $('#city').html("")
                    $('#city').html(html)
                    $('#city').select2();
                }
            });
        }
    </script>
@endsection
