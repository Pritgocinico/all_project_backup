@extends('admin.partials.header', ['active' => 'user'])

@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">

        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">

            <div class="mb-6 mb-xl-10">

                <div class="row g-3 align-items-center">

                    <div class="col">

                        <h1 class="ls-tight">Update Customer</h1>

                    </div>

                </div>

            </div>

            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">

                <main class="container-fluid px-6 pb-10">

                    <form action="{{ route('customer.update', $customer->id) }}" enctype="multipart/form-data" method="POST">

                        @method('PUT')

                        @csrf

                        <input type="hidden" name="id" value="{{ $customer->id }}">

                        <input type="hidden" name="customer_id" value="{{ $customer->customer_id }}">

                        <div class="row align-items-center g-3 mt-6">

                            <div class="col-md-2"><label class="form-label mb-0">Customer Id:</label></div>

                            <div class="col-md-4">

                                {{ $customer->customer_id }}

                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">Name <span
                                        class="text-danger">*</span></label></div>

                            <div class="col-md-4 col-xl-4">

                                <input type="text" name="name" class="form-control" placeholder="Enter Name"
                                    value="{{ $customer->name }}">

                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>

                        </div>

                        <div class="row align-items-center g-3 mt-6">

                            <div class="col-md-2"><label class="form-label mb-0">Phone Number <span
                                        class="text-danger">*</span></label></div>

                            <div class="col-md-4 col-xl-4">

                                <input type="text" name="mobile_number" class="form-control"
                                    placeholder="Enter Phone Number" value="{{ $customer->mobile_number }}">

                                @error('mobile_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">Email <span
                                        class="text-danger">*</span></label></div>

                            <div class="col-md-4 col-xl-4">

                                <input type="text" name="email" class="form-control" placeholder="Enter Email"
                                    value="{{ $customer->email }}">

                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>

                        </div>

                        <div class="row align-items-center g-3 mt-6">

                            <div class="col-md-2"><label class="form-label mb-0">Birth Date</label></div>

                            <div class="col-md-4 col-xl-4">

                                <input type="date" class="form-control" name="birth_date" max="{{ date('Y-m-d') }}"
                                    value="{{ $customer->birth_date }}">

                                @error('birth_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>

                            <div class="col-md-2 gst_certificate_div">

                                <label class="form-label mb-0">GST Certificate </label>

                            </div>

                            <div class="col-md-2 col-xl-2 gst_certificate_div">
                                <label for="gst_certificate" class="btn btn-sm btn-neutral"><span>Upload</span>

                                    <input type="file" name="gst_certificate" id="gst_certificate"
                                        class="visually-hidden gst_certificate" value="{{ $customer->gst_certificate }}">

                                </label>

                                @error('gst_certificate')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="col-md-1 col-xl-1 adhar_image_div">
                                @if (isset($customer->gst_certificate))
                                    <a href="{{ asset('storage/' . $customer->gst_certificate) }}" target="_blank"
                                        class="btn btn-sm btn-dark "><span>View</span></a>
                                @endif
                            </div>

                            <div class="col-md-1 col-xl-1">
                                @if (isset($customer->gst_certificate))
                                    <a href="#" class="btn btn-sm btn-danger remove_document"
                                        data-type="gst_certificate"><span>Remove</span></a>
                                @endif
                            </div>

                        </div>



                        <div class="row align-items-center g-3 mt-6">

                            <div class="col-md-2"><label class="form-label mb-0">Pan Card Number </label></div>

                            <div class="col-md-4 col-xl-4">

                                <input type="text" name="pan_card_number" class="form-control" id="pan_card_number"
                                    placeholder="Enter Pan Card Number" value="{{ $customer->pan_card_number }}">

                                @error('pan_card_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">Upload Pan Card</label></div>

                            <div class="col-md-2">
                                <label for="pan_card_file" class="btn btn-sm btn-neutral"><span>Upload</span>

                                    <input type="file" name="pan_card_file" id="pan_card_file"
                                        class="visually-hidden pan_card_file" value="{{ $customer->pan_card_file }}">

                                </label>

                                @error('pan_card_file')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="col-md-1 col-xl-1 adhar_image_div">
                                @if (isset($customer->pan_card_file))
                                    <a href="{{ asset('storage/' . $customer->pan_card_file) }}" target="_blank"
                                        class="btn btn-sm btn-dark"><span>View</span></a>
                                @endif
                            </div>

                            <div class="col-md-1 col-xl-1">
                                @if (isset($customer->pan_card_file))
                                    <a href="#" class="btn btn-sm btn-danger remove_document"
                                        data-type="pan_card_file"><span>Remove</span></a>
                                @endif
                            </div>

                        </div>

                        <div class="row align-items-center g-3 mt-6" id="aadhar_card_div">

                            <div class="col-md-2"><label class="form-label mb-0">Aadhar Card Number</label></div>

                            <div class="col-md-4 col-xl-4">

                                <input type="text" name="aadhar_number" class="form-control" id="aadhar_number"
                                    placeholder="Enter Aadhar Card Number" value="{{ $customer->aadhar_number }}">

                                @error('aadhar_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">Upload Aadhar Card</label></div>

                            <div class="col-md-2">
                                <label for="aadhar_card_file" class="btn btn-sm btn-neutral"><span>Upload</span>

                                    <input type="file" name="aadhar_card_file" id="aadhar_card_file"
                                        class="visually-hidden aadhar_card_file"
                                        value="{{ $customer->aadhar_card_file }}">

                                </label>
                                @error('aadhar_card_file')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="col-md-1 col-xl-1 adhar_image_div">
                                @if (isset($customer->aadhar_card_file))
                                    <a href="{{ asset('storage/' . $customer->aadhar_card_file) }}" target="_blank"
                                        class="btn btn-sm btn-dark "><span>View</span></a>
                                @endif
                            </div>

                            <div class="col-md-1 col-xl-1">
                                @if (isset($customer->aadhar_card_file))
                                    <a href="#" class="btn btn-sm btn-danger remove_document"
                                        data-type="aadhar_card_file"><span>Remove</span></a>
                                @endif
                            </div>

                        </div>

                        <div class="row align-items-center g-3 mt-6">

                            <div class="col-md-2"><label class="form-label mb-0">Passport Number</label></div>

                            <div class="col-md-4 col-xl-4">

                                <input type="text" name="passport_number" class="form-control" id="passport_number"
                                    placeholder="Enter Passport Number" value="{{ $customer->passport_number }}">

                                @error('passport_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">Passport File</label></div>

                            <div class="col-md-2">
                                <label for="passport_file" class="btn btn-sm btn-neutral"><span>Upload</span>

                                    <input type="file" name="passport_file" id="passport_file"
                                        class="visually-hidden passport_file" value="{{ $customer->passport_file }}">

                                </label>

                                @error('passport_file')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="col-md-1 col-xl-1 adhar_image_div">
                                @if (isset($customer->passport_file))
                                    <a href="{{ asset('storage/' . $customer->passport_file) }}" target="_blank"
                                        class="btn btn-sm btn-dark "><span>View</span></a>
                                @endif
                            </div>

                            <div class="col-md-1 col-xl-1">
                                @if (isset($customer->passport_file))
                                    <a href="#" class="btn btn-sm btn-danger remove_document"
                                        data-type="passport_file"><span>Remove</span></a>
                                @endif
                            </div>

                        </div>

                        <div class="row align-items-center g-3 mt-6">

                            <div class="col-md-2"><label class="form-label mb-0">Customer Reference</label></div>

                            <div class="col-md-4 col-xl-4">

                                <input type="text" name="reference" class="form-control" id="reference"
                                    placeholder="Enter Reference" value="{{ $customer->reference }}">

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

                                <textarea name="address" class="form-control" id="address" placeholder="Enter Address">{{ $customer->address }}</textarea>

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
                                            @if ($customer->country == $country->iso2) selected @endif>{{ $country->name }}

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
                                    placeholder="Enter Pin Code" value="{{ $customer->pin_code }}">

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
        $('form').on('submit', function(e) {

            $('#saveSubmitButton').html('<i class="fa fa-spinner fa-spin"></i>');

        });

        var oldState = "{{ $customer->state }}";

        var oldCountry = "{{ $customer->country }}";

        var oldCity = "{{ $customer->city }}";

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
        $('.remove_document').on('click', function(e) {

            var type = $(this).data('type');

            var id = "{{ $customer->id ?? '' }}";

            Swal.fire({

                title: 'Are you sure?',

                text: "Are you sure the delete this?",

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#3085d6',

                cancelButtonColor: '#d33',

                confirmButtonText: 'Yes, delete it!'

            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({

                        url: "{{ route('remove.card.customer') }}",

                        type: 'get',

                        data: {

                            type: type,

                            id: id,

                        },

                        success: function(data) {


                            location.reload();

                        },

                        error: function(error) {

                            toastr.error(error.responseJSON.message)

                        }

                    });

                }

            });

        })
    </script>
@endsection
