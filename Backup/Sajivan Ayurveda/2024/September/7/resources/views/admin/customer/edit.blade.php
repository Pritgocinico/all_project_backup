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

                            <div class="col-md-2"><label class="form-label mb-0">Age <span class="text-danger">*</span></label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" name="cust_age" class="form-control" placeholder="Enter Age"
                                    value="{{ $customer->cust_age }}">
                                @error('cust_age')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Height</label></div>
                            <div class="col-md-1 col-xl-1 height_unit_outer">
                                <select class="form-control height_feet" name="height_unit">
                                    <option value="foot" {{ $customer->height_unit == 'foot' ? 'selected' : '' }}>Ft</option>
                                    <option value="cm" {{ $customer->height_unit == 'cm' ? 'selected' : '' }}>CM</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-xl-3 height_unit_input">
                                <input type="number" class="form-control" name="cust_height" placeholder="Enter Height" step="0.01"
                                    value="{{ $customer->cust_height }}">
                                @error('cust_height')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-2"><label class="form-label mb-0">Weight</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" class="form-control" name="cust_weight" placeholder="Enter Weight" step="0.01"
                                    value="{{ $customer->cust_weight }}">
                                @error('cust_weight')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">WhatsApp Exist?</label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="wa_exist" class="form-control">
                                    <option value="">Select Option</option>
                                    <option value="1" {{ $customer->wa_exist == 1 ? "selected" :"" }}>Yes</option>
                                    <option value="0" {{ $customer->wa_exist == 0 ? "selected" :"" }}>No</option>
                                </select>
                                @error('wa_exist')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">Disease</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="cust_disease" class="form-control"
                                    placeholder="Enter Customer Disease" value="{{ $customer->cust_disease }}">
                                @error('cust_disease')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        @foreach($alternateNumbers as $index => $number)
                            <div class="row align-items-center g-3 mt-3 exist_alt_num_{{$number->id}}">
                                <div class="col-md-2">
                                    <label class="form-label mb-0">Alternate Number</label>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="hidden" name="alt_num_ids[]" value="{{ $number->id }}">
                                    <input type="text" name="cust_alt_num[]" class="form-control" placeholder="Enter Alternate Number" value="{{ old('cust_alt_num.' . $index, $number->cust_alt_num) }}">
                                    @error('cust_alt_num.' . $index)
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label mb-0">WhatsApp exist?</label>
                                </div>
                                <div class="col-md-3 col-xl-3">
                                    <select name="alt_wa_exist[]" class="form-control">
                                        <option value="">Select Option</option>
                                        <option value="1" {{ old('alt_wa_exist.' . $index, $number->alt_wa_exist) == 1 ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ old('alt_wa_exist.' . $index, $number->alt_wa_exist) == 0 ? 'selected' : '' }}>No</option>
                                    </select>
                                    @error('alt_wa_exist.' . $index)
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-1">
                                    <button type="button" data-id="{{$number->id}}" class="btn btn-danger del-num">Remove</button>
                                </div>
                            </div>
                        @endforeach

                        <!-- Button to add more fields -->
                        <div class="row align-items-center g-3 mt-3">
                            <div class="col-md-2">
                                <label class="form-label mb-0">Alternate Number</label>
                            </div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="cust_alt_num[]" class="form-control" placeholder="Enter Alternate Number" value="{{ old('cust_alt_num.0') }}">
                                
                            </div>
                            <div class="col-md-2">
                                <label class="form-label mb-0">Whatsaap exist?</label>
                            </div>
                            <div class="col-md-3 col-xl-3">
                                <select name="alt_wa_exist[]" class="form-control">
                                    <option value="">Select Option</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                @error('alt_wa_exist')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-primary add-alt-num">Add</button>
                            </div>
                        </div>

                        <div id="alt-num-container"></div>

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
        var number = 1;
        $(document).ready(function () {
            $('.add-alt-num').on('click', function () {
                var newField = `
                    <div class="row align-items-center g-3 mt-3 new_alt_num_${number}">
                        <div class="col-md-2"><label class="form-label mb-0">Alternate Number</label></div>
                        <div class="col-md-4 col-xl-4">
                            <input type="text" name="cust_alt_num[]" class="form-control" placeholder="Enter Alternate Number" value="">
                            @error('cust_alt_num')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label class="form-label mb-0">Whatsaap exist?</label>
                        </div>
                        <div class="col-md-3 col-xl-3">
                            <select name="alt_wa_exist[]" class="form-control">
                                <option value="">Select Option</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            @error('alt_wa_exist')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger remove-alt-num">Remove</button>
                        </div>
                    </div>
                `;
                $('#alt-num-container').append(newField);
                number = number+1;
            });

            // Remove the alternate number field
            $(document).on('click', '.remove-alt-num', function () {
                $(`.new_alt_num_${number}`).remove();
                $(this).closest('.row').remove();
            });

            $(document).on('click', '.del-num', function(){
                console.log($(this).closest('.row'));
                
                var alt_num_id = $(this).data("id");
                    Swal.fire({
                    title: 'Are you sure?',
                    text: "Are you sure the delete this Number?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                            url: "{{ route('alt_num.delete') }}",
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: alt_num_id
                            },
                            success: function(data) {
                                toastr.success(data.message);
                                $(`.exist_alt_num_${alt_num_id}`).remove();
                            },
                            error: function(error) {
                                toastr.error(error.responseJSON.message)
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
