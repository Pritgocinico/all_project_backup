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
                        <input type="hidden" name="customer_id" value="{{$customerId ?? ($customer->customer_id ?? '')}}">
                        <input type="hidden" name="insurance_type" value="{{ request('type') }}">
                        <div class="row align-items-center g-3 mt-6">
                            <input type="hidden" name="insurance" value="{{ request('type') }}">
                            <!-- <div class="col-md-2"><label class="form-label mb-0">Customer Type</label></div>
                            <div class="col-md-4">
                                <input class="form-check-input customer_department" type="radio"
                                    name="customer_department" id="disabled" value="individual" required
                                    {{ old('customer_department')?? "individual" || isset($customer) && $customer->customer_department == "individual" ?'checked' : '' }}>
                                <label class="form-check-label" for="disabled">
                                    Individual
                                </label>
                                <input class="form-check-input customer_department" type="radio"
                                    name="customer_department" id="view" value="corporate"
                                    {{ old('customer_department') == "corporate" || isset($customer) && $customer->customer_department == 'corporate' ? 'checked' : '' }}>
                                <label class="form-check-label" for="view">
                                    Corporate
                                </label>
                            </div> -->
                            <div class="col-md-2"><label class="form-label mb-0">Customer Type</label></div>
                            <div class="col-md-4">
                                <input class="form-check-input customer_department" type="radio" name="customer_department" id="disabled" value="individual" required {{ old('customer_department') == 'individual' || (isset($customer) && $customer->customer_department == 'individual') ? 'checked' : '' }}>
                                <label class="form-check-label" for="disabled">Individual</label>
                                <input class="form-check-input customer_department" type="radio" name="customer_department" id="view" value="corporate" {{ old('customer_department') == 'corporate' || (isset($customer) && $customer->customer_department == 'corporate') ? 'checked' : '' }}>
                                <label class="form-check-label" for="view">Corporate</label>
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Customer Id:</label></div>
                            <div class="col-md-4">
                                {{$customerId ?? ($customer->customer_id ?? '')}}
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            
                            <div class="col-md-2"><label class="form-label mb-0">Name <span class="text-danger">*</span></label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="name" class="form-control" placeholder="Enter Name"
                                    value="{{ old('name', $customer->name ?? '') }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Phone Number <span class="text-danger">*</span></label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="mobile_number" class="form-control"
                                    placeholder="Enter Phone Number"
                                    value="{{ old('mobile_number', $customer->mobile_number ?? '') }}">
                                @error('mobile_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Birth Date <span class="text-danger">*</span></label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="date" class="form-control" name="birth_date" max="{{ date('Y-m-d') }}"
                                    value="{{ old('birth_date', $customer->birth_date ?? '') }}">
                                @error('birth_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Email <span class="text-danger">*</span></label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="email" class="form-control" placeholder="Enter Email"
                                    value="{{ old('email', $customer->email ?? '') }}">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- <div class="row align-items-center g-3 mt-6"> 
                            <div class="col-md-2 gst_certificate_div" style="display: @if(old('customer_department')?? "individual" || isset($customer) && $customer->customer_department == "individual") none @endif;">
                                <label class="form-label mb-0">GST Certificate <span class="text-danger">*</span></label>
                            </div>
                        
                            <div class="col-md-4 col-xl-4 gst_certificate_div" style="display: @if(old('customer_department')?? "individual" || isset($customer) && $customer->customer_department == "individual") none @endif;">
                                <input type="file" name="gst_certificate" class="form-control" id="gst_certificate">
                                @error('gst_certificate')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> -->
                        <div class="row align-items-center g-3 mt-6"> 
                            <div class="col-md-2 gst_certificate_div" style="display: {{ (old('customer_department') == 'corporate' || (isset($customer) && $customer->customer_department == 'corporate')) ? 'block' : 'none' }};">
                                <label class="form-label mb-0">GST Certificate <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4 col-xl-4 gst_certificate_div" style="display: {{ (old('customer_department') == 'corporate' || (isset($customer) && $customer->customer_department == 'corporate')) ? 'block' : 'none' }};">
                                <input type="file" name="gst_certificate" class="form-control" id="gst_certificate">
                                @error('gst_certificate')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Pan Card Number <span class="text-danger">*</span></label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="pan_card_number" class="form-control" id="pan_card_number"
                                    placeholder="Enter Pan Card Number"
                                    value="{{ old('pan_card_number', $customer->pan_card_number ?? '') }}">
                                @error('pan_card_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Upload Pan Card</label></div>
                            <div class="col-md-4">
                                <input type="file" name="pan_card_file" class="form-control" id="pan_card_file">
                                @error('pan_card_file')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- <div class="row align-items-center g-3 mt-6" id="aadhar_card_div" style="display: @if((old('customer_department') &&  old('customer_department') !== 'individual') || old('customer_department') !== 'corporate' || isset($customer) && $customer->customer_department !== 'corporate') 'none' @endif">
                            <div class="col-md-2"><label class="form-label mb-0">Aadhar Card Number <span class="text-danger">*</span></label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="aadhar_number" class="form-control" id="aadhar_number"
                                    placeholder="Enter Aadhar Card Number"
                                    value="{{ old('aadhar_number', $customer->aadhar_number ?? '') }}">
                                @error('aadhar_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Upload Aadhar Card</label></div>
                            <div class="col-md-4">
                                <input type="file" name="aadhar_card_file" class="form-control"
                                    id="aadhar_card_file">
                                @error('aadhar_card_file')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> -->
                        <div class="row align-items-center g-3 mt-6" id="aadhar_card_div" style="display: {{ (old('customer_department') == 'individual' || (isset($customer) && $customer->customer_department == 'individual')) ? 'block' : 'none' }};">
                            <div class="col-md-2"><label class="form-label mb-0">Aadhar Card Number <span class="text-danger">*</span></label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="aadhar_number" class="form-control" id="aadhar_number" placeholder="Enter Aadhar Card Number" value="{{ old('aadhar_number', $customer->aadhar_number ?? '') }}">
                                @error('aadhar_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Upload Aadhar Card</label></div>
                            <div class="col-md-4">
                                <input type="file" name="aadhar_card_file" class="form-control" id="aadhar_card_file">
                                @error('aadhar_card_file')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Service Preference</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="service_preference" class="form-control"
                                    id="service_preference" placeholder="Enter Service Preference"
                                    value="{{ old('service_preference', $customer->service_preference ?? '') }}">
                                @error('service_preference')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Customer Reference</label></div>
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
                            <div class="col-md-2"><label class="form-label mb-0">Address <span class="text-danger">*</span></label></div>
                            <div class="col-md-4 col-xl-4">
                                <textarea name="address" class="form-control" id="address" placeholder="Enter Address">{{ old('address', $customer->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Country <span class="text-danger">*</span></label></div>
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
                            <div class="col-md-2"><label class="form-label mb-0">State <span class="text-danger">*</span></label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="state" class="form-control" id="state" onchange="getCity()">
                                    <option value="">Select State</option>
                                </select>
                                @error('state')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">City <span class="text-danger">*</span></label></div>
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
                        <div class="d-flexjustify-content-end gap-2">
                            <a href="{{ route('customer.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-dark">Save</button>
                        </div>
                    </form>
                </main>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script>
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
        // $('.customer_department').on('click', function(e) {
        //     var val = $(this).val();
        //     $('#aadhar_card_div').show();
        //     $('.gst_certificate_div').hide();
        //     if ($(this).val() == 'corporate') {
        //         $('#aadhar_card_div').hide();
        //         $('.gst_certificate_div').show();
        //     }
        // });
        $(document).ready(function() {
            function toggleFields() {
                var selectedValue = $('input[name="customer_department"]:checked').val();
                if (selectedValue === 'corporate') {
                    $('#aadhar_card_div').hide();
                    $('.gst_certificate_div').show();
                } else {
                    $('#aadhar_card_div').show();
                    $('.gst_certificate_div').hide();
                }
            }

            // Initial call to set the fields based on the current selection
            toggleFields();

            // Event listener for when the user changes the selection
            $('.customer_department').on('change', function() {
                toggleFields();
            });
        });
    </script>
@endsection
