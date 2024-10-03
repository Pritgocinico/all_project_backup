@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg ">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Create Customer - {{ $customerId }}</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('customer.store') }}" enctype="multipart/form-data" method="POST">
                        @method('POST')
                        @csrf
                        <div class="row align-items-center gap-3 mt-6">
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Name <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="text" name="name" class="form-control capitalize_letter"
                                        placeholder="Enter Name" value="{{ old('name') }}">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center gap-3 mt-6">
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Phone Number <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="text" name="mobile_number" id="mobile_number" class="form-control"
                                        min="0" placeholder="Enter Phone Number" value="{{ old('mobile_number') }}"
                                        minlength="10" maxlength="10" onkeypress="return isNumberKey(event)">
                                    @error('mobile_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Age</label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="number" name="cust_age" class="form-control" placeholder="Enter Age"
                                        min="0" value="{{ old('cust_age') }}">
                                    @error('cust_age')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center gap-3 mt-6">
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Height</label></div>
                                <!-- Height Dropdown -->
                                <div class="col-md-1 col-xl-1 height_unit_outer">
                                    <select class="form-control height_feet" name="height_unit">
                                        <option value="foot" {{ old('height_unit') == 'foot' ? 'selected' : '' }}>Ft
                                        </option>
                                        <option value="cm" {{ old('height_unit') == 'cm' ? 'selected' : '' }}>CM
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-xl-3 height_unit_input">
                                    <input type="number" class="form-control" name="cust_height" placeholder="Enter Height"
                                        min="0" step="0.01" value="{{ old('cust_height') }}">
                                    @error('cust_height')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Weight(Kg.)</label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="number" class="form-control" name="cust_weight" placeholder="Enter Weight"
                                        min="0" step="0.01" value="{{ old('cust_weight') }}">
                                    @error('cust_weight')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center  gap-3 mt-6" id="aadhar_card_div">
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">WhatsApp Exist?</label></div>
                                <div class="col-md-8 col-xl-6">
                                    <select name="wa_exist" class="form-control">
                                        <option value="">Select Option</option>
                                        <option value="1" {{ old('wa_exist') == '1' ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ old('wa_exist') == '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                    @error('wa_exist')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Disease <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <select name="cust_disease" class="form-control" id="cust_disease">
                                        <option value="">Select Disease</option>
                                        @foreach ($diseases as $disease)
                                            <option value="{{ $disease->id }}"
                                                @if ($disease->id == old('cust_disease')) {{ 'selected' }} @endif>
                                                {{ $disease->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('cust_disease')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @if (old('cust_alt_num'))
                            @php $cnt = count(old('cust_alt_num')); @endphp
                            @for ($i = 0; $i < $cnt; $i++)
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3 ">
                                        <div class="col-md-4">
                                            <label class="form-label mb-0">Alternate Number</label>
                                        </div>
                                        <div class="col-md-8 col-xl-6">
                                            <input type="text" name="cust_alt_num[]" class="form-control"
                                                pattern="\d{10}" minlength="10" maxlength="10"
                                                onkeypress="return isNumberKey(event)"
                                                placeholder="Enter Alternate Number"
                                                value="{{ old('cust_alt_num.' . $i) }}">
                                            @error('cust_alt_num.' . $i)
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 row align-items-center g-3 ">
                                        <div class="col-md-4">
                                            <label class="form-label mb-0">WhatsApp exist?</label>
                                        </div>
                                        <div class="col-md-3 col-xl-6">
                                            <select name="alt_wa_exist[]" class="form-control">
                                                <option value="">Select Option</option>
                                                <option value="1"
                                                    @if ('1' == old('alt_wa_exist.' . $i)) {{ 'selected' }} @endif>Yes
                                                </option>
                                                <option value="0"
                                                    @if ('0' == old('alt_wa_exist.' . $i)) {{ 'selected' }} @endif>No
                                                </option>
                                            </select>
                                            @error('alt_wa_exist.' . $i)
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-primary add-alt-num">Add</button>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        @else
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Alternate Number</label>
                                    </div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="cust_alt_num[]" class="form-control"
                                            pattern="\d{10}" minlength="10" maxlength="10"
                                            onkeypress="return isNumberKey(event)" placeholder="Enter Alternate Number"
                                            value="">
                                        @error('cust_alt_num.*')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">WhatsApp exist?</label>
                                    </div>
                                    <div class="col-md-3 col-xl-6">
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
                            </div>
                        @endif

                        <div id="alt-num-container"></div>

                        <hr class="my-6">
                        <h4>Address Detail</h4>
                        @if (old('pin_code'))
                            @php $cnt = count(old('pin_code')); @endphp
                            @for ($i = 0; $i < $cnt; $i++)
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                        <div class="col-md-4"><label class="form-label mb-0">Address Type </label></div>
                                        <div class="col-md-8 col-xl-6">
                                            <select name="add_type[]" class="form-control">
                                                <option value="">Select Type</option>
                                                <option value="office_add"
                                                    @if ('office_add' == old('add_type.' . $i)) {{ 'selected' }} @endif>Office
                                                    Address</option>
                                                <option value="shop_add"
                                                    @if ('shop_add' == old('add_type.' . $i)) {{ 'selected' }} @endif>Shop
                                                    Address</option>
                                                <option value="home_add"
                                                    @if ('home_add' == old('add_type.' . $i)) {{ 'selected' }} @endif selected>
                                                    Home
                                                    Address</option>
                                            </select>
                                            @error('add_type.' . $i)
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 row align-items-center g-3">
                                        <div class="col-md-4"><label class="form-label mb-0">Pin Code </label></div>
                                        <div class="col-md-8 col-xl-6">
                                            <input type="number" name="pin_code[]" min="0" class="form-control"
                                                placeholder="Enter Pin Code" value="{{ old('pin_code.' . $i) }}"
                                                id="pin_code">
                                            @error('pin_code.' . $i)
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                        <div class="col-md-4"><label class="form-label mb-0">Address </label></div>
                                        <div class="col-md-8 col-xl-6">
                                            <textarea name="address[]" class="form-control capitalize_letter" placeholder="Enter Address">{{ old('address.' . $i) }}</textarea>
                                            @error('address.0')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 row align-items-center g-3">
                                        <div class="col-md-4"><label class="form-label mb-0">Village </label></div>
                                        <div class="col-md-8 col-xl-6">
                                            <select name="village[]" class="form-control" id="village_id">
                                                <option value="">Select Village</option>
                                            </select>
                                            @error('village.0')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                        <div class="col-md-4"><label class="form-label mb-0">Post Office(B.O/S.O/H.O)
                                            </label></div>
                                        <div class="col-md-8 col-xl-6">
                                            <select name="office_name[]" class="form-control" id="office_id">
                                                <option value="">Select Post Office</option>
                                            </select>
                                            @error('office_name.0')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 row align-items-center g-3">
                                        <div class="col-md-4"><label class="form-label mb-0">City </label></div>
                                        <div class="col-md-8 col-xl-6">
                                            <input type="text" name="dist_city[]" class="form-control" id="dist_city"
                                                value="{{ old('dist_city.' . $i) }}">
                                            @error('dist_city.0')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                        <div class="col-md-4"><label class="form-label mb-0">State </label></div>
                                        <div class="col-md-8 col-xl-6">
                                            <input type="text" name="dist_state[]" class="form-control"
                                                id="dist_state" value="{{ old('dist_state.' . $i) }}">
                                            @error('dist_state.0')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        @else
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Address Type </label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <select name="add_type[]" class="form-control">
                                            <option value="">Select Type</option>
                                            <option value="office_add"
                                                {{ old('add_type.0') == 'office_add' ? 'selected' : '' }}>Office Address
                                            </option>
                                            <option value="shop_add"
                                                {{ old('add_type.0') == 'shop_add' ? 'selected' : '' }}>Shop Address
                                            </option>
                                            <option value="home_add"
                                                {{ old('add_type.0') == 'home_add' ? 'selected' : '' }} selected>Home
                                                Address
                                            </option>
                                        </select>
                                        @error('add_type.0')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Pin Code </label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="number" name="pin_code[]" min="0" class="form-control"
                                            placeholder="Enter Pin Code" value="{{ old('pin_code.0') }}" id="pin_code">
                                        @error('pin_code.0')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Address </label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <textarea name="address[]" class="form-control capitalize_letter" placeholder="Enter Address">{{ old('address.0') }}</textarea>
                                        @error('address.0')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Village </label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <select name="village[]" class="form-control" id="village_id">
                                            <option value="">Select Village</option>
                                        </select>
                                        @error('village.0')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Post Office(B.O/S.O/H.O) </label>
                                    </div>
                                    <div class="col-md-8 col-xl-6">
                                        <select name="office_name[]" class="form-control" id="office_id">
                                            <option value="">Select Post Office</option>
                                        </select>
                                        @error('office_name.0')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">City </label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="dist_city[]" class="form-control" id="dist_city"
                                            value="{{ old('dist_city.0') }}">
                                        @error('dist_city.0')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">State </label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="dist_state[]" class="form-control" id="dist_state"
                                            value="{{ old('dist_state.0') }}">
                                        @error('dist_state.0')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div id="address-container"></div>

                        <div class="row mt-3">
                            <div class="col-md-12 text-end">
                                <button type="button" class="btn btn-primary add-address">Add More Address</button>
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
        function isNumberKey(evt) {
            if (event.which != 8 && isNaN(String.fromCharCode(event.which))) {
                event.preventDefault();
            }
        }
        $('#cust_disease').select2();
        $('#pin_code').bind('keyup', function(e) {
            var pincode = $(this).val();

            if (pincode.length === 6) {
                $.ajax({
                    method: 'get',
                    url: "{{ route('pin-code-ajax') }}",
                    data: {
                        pincode: pincode,
                    },
                    success: function(result) {
                        var data = result.data;
                        var html = '';
                        var html2 = '';

                        if (data.length > 0) {
                            var html = "<option value=''>Select Village</option>";
                            var html2 = "<option value=''>Select Post Office</option>";

                            var officeNamesSet = new Set(); // To keep track of unique office names

                            $.each(data, function(i, v) {
                                // For village dropdown
                                html += "<option value='" + v.village + "'>" + v.village +
                                    "</option>";

                                // For office dropdown, check for uniqueness
                                if (!officeNamesSet.has(v.office_name)) {
                                    html2 += "<option value='" + v.office_name + "'>" + v
                                        .office_name + "</option>";
                                    officeNamesSet.add(v
                                        .office_name); // Add office name to the Set
                                }
                            });

                            $('#village_id').html("");
                            $('#village_id').html(html);
                            $('#village_id').select2();

                            $('#office_id').html("");
                            $('#office_id').html(html2);
                            $('#office_id').select2();
                        }

                        $('#dist_state').val(data[0].state_name);
                        $('#dist_city').val(data[0].district_name);
                    },
                });
            }
        });


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

        $(document).ready(function() {
            $('.add-alt-num').on('click', function() {
                var newField = `
                    <div class="row align-items-center g-3 mt-3">
                        <div class="col-md-2"><label class="form-label mb-0">Alternate Number</label></div>
                        <div class="col-md-4 col-xl-4">
                            <input type="text" name="cust_alt_num[]" minlength="10" maxlength="10" onkeypress="return isNumberKey(event)" class="form-control" placeholder="Enter Alternate Number" >
                            @error('cust_alt_num')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label class="form-label mb-0">WhatsApp exist?</label>
                        </div>
                        <div class="col-md-3 col-xl-6">
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
            });

            // Remove the alternate number field
            $(document).on('click', '.remove-alt-num', function() {
                $(this).closest('.row').remove();
            });
        });

        $(document).ready(function() {
            var addressIndex = 0; // Initialize index for unique ID management

            $('.add-address').on('click', function() {
                addressIndex++; // Increment the index for each new address block

                var newField = `
                <div class="address-block" data-index="${addressIndex}">
                    <hr class="my-6">
                    <div class="row align-items-center gap-3 mt-6">
                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">Address Type </label></div>
                            <div class="col-md-8 col-xl-6">
                                <select name="add_type[]" class="form-control">
                                    <option value="">Select Type</option>
                                    <option value="office_add">Office Address</option>
                                    <option value="shop_add">Shop Address</option>
                                    <option value="home_add" selected>Home Address</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">Pin Code </label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="number" name="pin_code[]" min="0" class="form-control pin-code" placeholder="Enter Pin Code" data-index="${addressIndex}">
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center gap-3 mt-6">
                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">Address </label></div>
                            <div class="col-md-8 col-xl-6">
                                <textarea name="address[]" class="form-control capitalize_letter" placeholder="Enter Address"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">Village </label></div>
                            <div class="col-md-8 col-xl-6">
                                <select name="village[]" class="form-control village" id="village_id_${addressIndex}">
                                    <option value="">Select Village</option>
                                </select>
                            </div>
                        </div> 
                    </div>

                    <div class="row align-items-center gap-3 mt-6">
                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">Post Office(B.O/S.O/H.O) </label></div>
                            <div class="col-md-8 col-xl-6">
                                <select name="office_name[]" class="form-control office" id="office_id_${addressIndex}">
                                    <option value="">Select Office</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">City </label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="text" name="dist_city[]" class="form-control city" id="dist_city_${addressIndex}">
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center gap-3 mt-6">
                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">State </label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="text" name="dist_state[]" class="form-control state" id="dist_state_${addressIndex}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-end mt-3">
                        <button type="button" class="btn btn-danger remove-address">Remove</button>
                    </div>
                </div>`;

                $('#address-container').append(newField);
            });

            // Handle Pin Code change for each block
            $(document).on('keyup', '.pin-code', function() {
                var index = $(this).data('index'); // Get the unique index for this block
                var pincode = $(this).val();

                if (pincode.length === 6) {
                    $.ajax({
                        method: 'get',
                        url: "{{ route('pin-code-ajax') }}",
                        data: {
                            pincode: pincode,
                        },
                        success: function(result) {
                            var data = result.data;
                            var villageHtml = "<option value=''>Select Village</option>";
                            var officeHtml = "<option value=''>Select Post Office</option>";
                            var officeNamesSet = new Set();

                            $.each(data, function(i, v) {
                                villageHtml += "<option value='" + v.village + "'>" + v
                                    .village + "</option>";
                                if (!officeNamesSet.has(v.office_name)) {
                                    officeHtml += "<option value='" + v.office_name +
                                        "'>" + v.office_name + "</option>";
                                    officeNamesSet.add(v.office_name);
                                }
                            });

                            $('#village_id_' + index).html(villageHtml);
                            $('#village_id_' + index).select2();

                            $('#office_id_' + index).html(officeHtml);
                            $('#office_id_' + index).select2();

                            $('#dist_state_' + index).val(data[0].state_name);
                            $('#dist_city_' + index).val(data[0].district_name);
                        },
                    });
                }
            });

            // Remove an address block
            $(document).on('click', '.remove-address', function() {
                $(this).closest('.address-block').remove();
            });
            $('#lead_source').on('change', function() {
                if ($(this).val() == "Other") {
                    $('#other_lead_source_div').removeClass('d-none');
                } else {
                    $('#other_lead_source_div').addClass('d-none');
                }
            });
        });
    </script>
@endsection
