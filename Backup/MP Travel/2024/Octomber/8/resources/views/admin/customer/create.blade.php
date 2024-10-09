@extends('admin.partials.header', ['active' => 'user'])

@section('content')
    <div
        class="flex-fill  scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">

        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">

            <div class="mb-6 mb-xl-10">

                <div class="row g-3 align-items-center">

                    <div class="col">

                        <h1 class="ls-tight">Create Customer</h1>

                    </div>

                </div>

            </div>

            <div
                class="flex-fill scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">

                <main class="container-fluid px-6 pb-10">

                    <form action="{{ route('customer.store') }}" enctype="multipart/form-data" method="POST">

                        @method('POST')

                        @csrf

                        <div class="row align-items-center gap-3 mt-6">
                            <div class="col-md-6 row align-items-center g-3">


                                <div class="col-md-4"><label class="form-label mb-0">Customer Id:</label></div>

                                <div class="col-md-8 col-xl-6">
    
                                    {{ $customerId }}
    
                                </div>
                            </div>
                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Name <span
                                    class="text-danger">*</span></label></div>

                        <div class="col-md-8 col-xl-6">

                            <input type="text" name="name" class="form-control" placeholder="Enter Name"
                                value="{{ old('name') }}">
                            <span id="name_error" class="error_span"></span>

                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                        </div>

                            </div>


                            

                        </div>

                        <div class="row align-items-center gap-3 mt-6">
                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Phone Number <span
                                    class="text-danger">*</span></label></div>

                                        <div class="col-md-8 col-xl-6">

                                            <input type="number" name="mobile_number" class="form-control" min="0"
                                                placeholder="Enter Phone Number" value="{{ old('mobile_number') }}">
                                                <span id="mobile_number_error" class="error_span"></span>
                                            @error('mobile_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-md-6 row align-items-center g-3">
                                        
                            <div class="col-md-4"><label class="form-label mb-0">Email <span
                                class="text-danger">*</span></label></div>

                    <div class="col-md-8 col-xl-6">

                        <input type="text" name="email" class="form-control" placeholder="Enter Email"
                            value="{{ old('email') }}">
                            <span id="email_error" class="error_span"></span>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                                    </div>

                           




                        </div>

                        <div class="row align-items-center gap-3 mt-6">
                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Birth Date</label></div>

                                <div class="col-md-8 col-xl-6">
    
                                    <input type="date" class="form-control" name="birth_date" max="{{ date('Y-m-d') }}"
                                        value="{{ old('birth_date') }}">
    
                                    @error('birth_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
    
                                </div>
                            </div>

                       <div class="col-md-6 row align-items-center g-3">
                        <div class="col-md-4 gst_certificate_div">

                            <label class="form-label mb-0">GST Certificate</label>

                        </div>

                        <div class="col-md-8 col-xl-6 gst_certificate_div">

                            <input type="file" name="gst_certificate" class="form-control" id="gst_certificate">

                            @error('gst_certificate')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                        </div>
                       </div>

                            

                        </div>



                        <div class="row align-items-center gap-3 mt-6">

                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Pan Card Number</label></div>

                                <div class="col-md-8 col-xl-6">
    
                                    <input type="text" name="pan_card_number" class="form-control" id="pan_card_number"
                                        placeholder="Enter Pan Card Number" value="{{ old('pan_card_number') }}">
    
                                    @error('pan_card_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
    
                                </div>
                            </div>

                            <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Upload Pan Card</label></div>

                                    <div class="col-md-8 col-xl-6">

                                        <input type="file" name="pan_card_file" class="form-control" id="pan_card_file">

                                        @error('pan_card_file')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror

                                    </div>
                            </div>

                           

                        </div>



                        
                        <div class="row align-items-center gap-3 mt-6" id="aadhar_card_div">
                            <div class="col-md-6 row align-items-center g-3">

                                <div class="col-md-4"><label class="form-label mb-0">Aadhar Card Number</label></div>

                                <div class="col-md-8 col-xl-6">
    
                                    <input type="text" name="aadhar_number" class="form-control" id="aadhar_number"
                                        placeholder="Enter Aadhar Card Number" value="{{ old('aadhar_number') }}">
    
                                    @error('aadhar_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
    
                                </div>
                            </div>

                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Upload Aadhar Card</label></div>

                                <div class="col-md-8 col-xl-6">
    
                                    <input type="file" name="aadhar_card_file" class="form-control" id="aadhar_card_file">
    
                                    @error('aadhar_card_file')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
    
                                </div>
                            </div>

                            

                        </div>

                        <div class="row align-items-center gap-3 mt-6">

                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Passport Number</label></div>

                                <div class="col-md-8 col-xl-6">
    
                                    <input type="text" name="passport_number" class="form-control" id="passport_number"
                                        placeholder="Enter Passport Number" value="{{ old('passport_number') }}">
    
                                    @error('passport_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
    
                                </div>
                            </div>

                         
                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Passport File</label></div>

                                <div class="col-md-8 col-xl-6">

                                    <input type="file" name="passport_file" class="form-control" id="passport_file">

                                    @error('passport_file')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                </div>
                            </div>
                            

                        </div>

                        <div class="row align-items-center gap-3 mt-6">
                            <div class="col-md-6 row align-items-center g-3">

                                <div class="col-md-4"><label class="form-label mb-0">Customer Reference</label></div>

                                <div class="col-md-8 col-xl-6">
    
                                    <input type="text" name="reference" class="form-control" id="reference"
                                        placeholder="Enter Reference" value="{{ old('reference') }}">
    
                                    @error('reference')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
    
                                </div>
                            </div>


                        </div>

                        <hr class="my-6">

                        <h4>Address Detail</h4>

                        <div class="row align-items-center gap-3 mt-6">

                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Address</label></div>

                                <div class="col-md-8 col-xl-6">
    
                                    <textarea name="address" class="form-control" id="address" placeholder="Enter Address">{{ old('address') }}</textarea>
    
                                    @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
    
                                </div>
                            </div>

                      <div class="col-md-6 row align-items-center g-3">
                        <div class="col-md-4"><label class="form-label mb-0">Country</label></div>

                        <div class="col-md-8 col-xl-6">

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

                      

                        </div>

                        <div class="row align-items-center gap-3 mt-6">

                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">State</label></div>

                                <div class="col-md-8 col-xl-6">
    
                                    <select name="state" class="form-control" id="state" onchange="getCity()">
    
                                        <option value="">Select State</option>
    
                                    </select>
    
                                    @error('state')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
    
                                </div>
                            </div>

                        
                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">City</label></div>

                                <div class="col-md-8 col-xl-6">

                                    <select name="city" class="form-control" id="city">

                                        <option value="">Select City</option>

                                    </select>

                                    @error('city')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                </div>
                            </div>
                           

                        </div>

                        <div class="row align-items-center gap-3 mt-6">
                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Pin Code</label></div>

                                <div class="col-md-8 col-xl-6">
    
                                    <input type="text" name="pin_code" class="form-control" id="pin_code"
                                        placeholder="Enter Pin Code" value="{{ old('pin_code') }}">
    
                                    @error('pin_code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
    
                                </div>
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
        $('form').on('submit', function(e) {
            var name = $('#name').val();
            var number = $('#mobile_number').val();
            var email = $('#email').val();
            var cnt =0;
            $('#name_error').html('')
            $('#mobile_number_error').html('')
            $('#email_error').html('')
            if (name.trim() == "") {
                $('#name_error').html('Please Enter Name');
                cnt = 1;
            }
            if (number.trim() == "") {
                $('#mobile_number_error').html('Please Enter Mobile Number');
                cnt = 1;
            }
            if (email.trim() == "") {
                $('#email_error').html('Please Enter Email');
                cnt = 1;
            }
            if(cnt == 1){
                e.preventDefault();
                return false;
            }
            e.preventDefault();
            $('#saveSubmitButton').html('<i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                url: "{{ route('customer.store') }}",
                method: 'post',
                data: $(this).serialize(),
                success: function(res) {
                    toastr.success(res.message);
                    let response =res;
                    Swal.fire({

                        title: 'Are you sure?',

                        text: "Are you sure the add lead this Customer?",

                        icon: 'warning',

                        showCancelButton: true,

                        confirmButtonColor: '#3085d6',

                        cancelButtonColor: '#d33',

                        confirmButtonText: 'Yes, Add it!'

                    }).then((result)=> {
                        
                        if (result.isConfirmed) {
                            console.log(res.data);
                            window.location.href = `{{ route('leads.create') }}?id=${res.data.id}`;
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            window.location.href = "{{ route('customer.show','id') }}".replace('id',res.data.id);
                        }

                    });
                },
                error: function(e) {
                    $('#saveSubmitButton').html('Save');
                    toastr.error(e.responseJSON.message);
                }
            });

        });

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
    </script>
@endsection
