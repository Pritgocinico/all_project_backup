@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Create Customer</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('customer.store') }}" enctype="multipart/form-data" method="POST">
                        @method('POST')
                        @csrf
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Customer Id:</label></div>
                            <div class="col-md-4">
                                {{ $customerId }}
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Name <span class="text-danger">*</span></label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="name" class="form-control" placeholder="Enter Name"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Phone Number <span class="text-danger">*</span></label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="mobile_number" class="form-control"
                                    placeholder="Enter Phone Number" value="{{ old('mobile_number') }}">
                                @error('mobile_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">Age <span class="text-danger">*</span></label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" name="cust_age" class="form-control" placeholder="Enter Age"
                                    value="{{ old('cust_age') }}">
                                @error('cust_age')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>   
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Height</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" class="form-control" name="cust_height" placeholder="Enter Height" step="0.01"
                                    value="{{ old('cust_height') }}">
                                @error('cust_height')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-2"><label class="form-label mb-0">Weight</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" class="form-control" name="cust_weight" placeholder="Enter Weight" step="0.01"
                                    value="{{ old('cust_weight') }}">
                                @error('cust_weight')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row align-items-center g-3 mt-6" id="aadhar_card_div">
                            <div class="col-md-2"><label class="form-label mb-0">WhatsApp Exist?</label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="wa_exist" class="form-control">
                                    <option value="">Select Option</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                @error('wa_exist')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">Disease</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="cust_disease" class="form-control"
                                    placeholder="Enter Customer Disease" value="{{ old('cust_disease') }}">
                                @error('cust_disease')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2">
                                <label class="form-label mb-0">Alternate Number</label>
                            </div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="cust_alt_num[]" class="form-control" placeholder="Enter Alternate Number" value="">
                                @error('cust_alt_num')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary add-alt-num">Add More</button>
                            </div>
                        </div>
                        
                        <div id="alt-num-container"></div>
                        
                        <hr class="my-6">
                        <h4>Address Detail</h4>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Address</label></div>
                            <div class="col-md-4 col-xl-4">
                                <textarea name="address" class="form-control" id="address" placeholder="Enter Address">{{ old('address') }}</textarea>
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
                                            @if (old('country') == $country->iso2) selected @endif>{{ $country->name }}
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
                                    placeholder="Enter Pin Code" value="{{ old('pin_code') }}">
                                @error('pin_code')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr class="my-6">
                        <div class="d-flexjustify-content-end gap-2">
                            <a href="{{ route('customer.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-dark" id="saveSubmitButton">Save</button>
                        </div>
                    </form>
                </main>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script>
        // $('form').on('submit', function(e) {
        //     $('#saveSubmitButton').html('<i class="fa fa-spinner fa-spin"></i>');
        // });
        var oldState = "{{ old('state') }}";
        var oldCountry = "{{ old('country') }}";
        var oldCity = "{{ old('city') }}";
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

        $(document).ready(function () {
            $('.add-alt-num').on('click', function () {
                var newField = `
                    <div class="row align-items-center g-3 mt-3">
                        <div class="col-md-2"><label class="form-label mb-0">Alternate Number</label></div>
                        <div class="col-md-4 col-xl-4">
                            <input type="text" name="cust_alt_num[]" class="form-control" placeholder="Enter Alternate Number" >
                            @error('cust_alt_num')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-alt-num">Remove</button>
                        </div>
                    </div>
                `;
                $('#alt-num-container').append(newField);
            });

            // Remove the alternate number field
            $(document).on('click', '.remove-alt-num', function () {
                $(this).closest('.row').remove();
            });
        });
    </script>
@endsection
