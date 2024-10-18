@extends('admin.partials.header', ['active' => 'user'])
@section('content')
<div
    class="flex-fill scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
    <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
        <div class="mb-6 mb-xl-10">
            <div class="row g-3 align-items-center">
                <div class="col">
                    <h1 class="ls-tight">Update Customer - {{ $customer->customer_id }}</h1>
                </div>
            </div>
        </div>
        <div
            class="flex-fill scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
            <main class="container-fluid px-6 pb-10">
                <form action="{{ route('customer.update', $customer->id) }}" enctype="multipart/form-data" method="POST">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="id" value="{{ $customer->id }}">
                    <input type="hidden" name="customer_id" value="{{ $customer->customer_id }}">
                    <div class="row align-items-center gap-3 mt-6">
                        <div class="col-md-6 row align-items-center g-3 ">
                            <div class="col-md-4"><label class="form-label mb-0">Name <span
                                        class="text-danger">*</span></label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="text" name="name" class="form-control capitalize_letter" placeholder="Enter Name"
                                    value="{{ $customer->name }}">
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
                                <input type="text" name="mobile_number" class="form-control" minlength="10" maxlength="10" onkeypress="return isNumberKey(event)"
                                    placeholder="Enter Phone Number" value="{{ $customer->mobile_number }}">
                                @error('mobile_number')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 row align-items-center g-3 ">

                            <div class="col-md-4"><label class="form-label mb-0">Age </label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="number" name="cust_age" class="form-control" placeholder="Enter Age"
                                    min="0" value="{{ $customer->cust_age }}">
                                @error('cust_age')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>




                    <div class="row align-items-center gap-3 mt-6">
                        <div class="col-md-6 row align-items-center g-3 ">
                            <div class="col-md-4"><label class="form-label mb-0">Height</label></div>
                            <div class="col-md-1 col-xl-1 height_unit_outer">
                                <select class="form-control height_feet" name="height_unit">
                                    <option value="foot" {{ $customer->height_unit == 'foot' ? 'selected' : '' }}>Ft
                                    </option>
                                    <option value="cm" {{ $customer->height_unit == 'cm' ? 'selected' : '' }}>CM
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3 col-xl-3 height_unit_input">
                                <input type="number" class="form-control" name="cust_height" placeholder="Enter Height"
                                    min="0" step="0.01" value="{{ $customer->cust_height }}">
                                @error('cust_height')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 row align-items-center g-3 ">
                            <div class="col-md-4"><label class="form-label mb-0">Weight (Kg.)</label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="number" class="form-control" name="cust_weight" placeholder="Enter Weight"
                                    min="0" step="0.01" value="{{ $customer->cust_weight }}">
                                @error('cust_weight')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center gap-3 mt-6">

                        <div class="col-md-6 row align-items-center g-3 ">
                            <div class="col-md-4"><label class="form-label mb-0">WhatsApp Exist?</label></div>
                            <div class="col-md-8 col-xl-6">
                                <select name="wa_exist" class="form-control">
                                    <option value="">Select Option</option>
                                    <option value="1" {{ $customer->wa_exist == 1 ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="0" {{ $customer->wa_exist == 0 ? 'selected' : '' }}>No</option>
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
                                        @if ($disease->id == $customer->cust_disease) {{ 'selected' }} @endif>
                                        {{ $disease->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('cust_disease')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @foreach ($alternateNumbers as $index => $number)
                    <div class="row align-items-center gap-3 mt-3 exist_alt_num_{{ $number->id }}">

                        <div class="col-md-6 row align-items-center g-3 ">
                            <div class="col-md-4">
                                <label class="form-label mb-0">Alternate Number</label>
                            </div>
                            <div class="col-md-8 col-xl-6">
                                <input type="hidden" name="alt_num_ids[]" value="{{ $number->id }}">
                                <input type="text" name="cust_alt_num[]" class="form-control" minlength="10" maxlength="10" onkeypress="return isNumberKey(event)"
                                    placeholder="Enter Alternate Number"
                                    value="{{ old('cust_alt_num.' . $index, $number->cust_alt_num) }}">
                                @error('cust_alt_num.' . $index)
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="col-md-6 row align-items-center g-3">

                            <div class="col-md-4">
                                <label class="form-label mb-0">WhatsApp exist?</label>
                            </div>

                            <div class="col-md-3 col-xl-6">
                                <select name="alt_wa_exist[]" class="form-control">
                                    <option value="">Select Option</option>
                                    <option value="1"
                                        {{ old('alt_wa_exist.' . $index, $number->alt_wa_exist) == 1 ? 'selected' : '' }}>
                                        Yes</option>
                                    <option value="0"
                                        {{ old('alt_wa_exist.' . $index, $number->alt_wa_exist) == 0 ? 'selected' : '' }}>
                                        No</option>
                                </select>
                                @error('alt_wa_exist.' . $index)
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-1">
                                <button type="button" data-id="{{ $number->id }}"
                                    class="btn btn-danger del-num">Remove</button>
                            </div>

                        </div>
                    </div>
                    @endforeach

                    <!-- Button to add more fields -->
                    <div class="row align-items-center gap-3 mt-6">
                        <div class="col-md-6 row align-items-center g-3  ">

                            <div class="col-md-4">
                                <label class="form-label mb-0">Alternate Number</label>
                            </div>
                            <div class="col-md-8 col-xl-6">
                                <input type="text" name="cust_alt_num[]" class="form-control" minlength="10" maxlength="10" onkeypress="return isNumberKey(event)"
                                    placeholder="Enter Alternate Number">

                            </div>
                        </div>
                        <div class="col-md-6 row align-items-center g-3  ">
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

                    <div id="alt-num-container"></div>

                    <hr class="my-6">
                    <h4>Address Detail</h4>

                    <div id="address-containers">
                        @foreach ($custAddresses as $index => $address)
                        <div class="address-block" data-index="{{ $index }}">
                            <hr class="my-6">
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Address Type </label>
                                    </div>
                                    <div class="col-md-8 col-xl-6">
                                        <select name="add_type[]" class="form-control">
                                            <option value="">Select Type</option>
                                            <option value="office_add"
                                                {{ $address->add_type == 'office_add' ? 'selected' : '' }}>Office
                                                Address
                                            </option>
                                            <option value="shop_add"
                                                {{ $address->add_type == 'shop_add' ? 'selected' : '' }}>
                                                Shop Address</option>
                                            <option value="home_add"
                                                {{ $address->add_type == 'home_add' ? 'selected' : '' }}>
                                                Home Address</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Pin Code </label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="number" name="pin_code[]" class="form-control pin-code"
                                            min="0" placeholder="Enter Pin Code" value="{{ $address->pin_code }}"
                                            data-index="{{ $index }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Address </label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <textarea name="address[]" class="form-control capitalize_letter" placeholder="Enter Address">{{ $address->address }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Village </label></div>
                                    <div class="col-md-7 col-xl-5">
                                        <select name="village[]" class="form-control village"
                                        onchange="addOtherOptions({{ $index }},'village')"
                                            id="village_id_{{ $index }}">
                                            <option value="{{ $address->village }}">{{ $address->village }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <a href="javascript:void(0)" class="btn btn-primary copy-detail"
                                            onclick="copyVillage({{ $index }},'village')"
                                            data-index="{{ $index }}"><i class="fa-solid fa-copy"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Post Office(B.O/S.O/H.O) </label></div>
                                    <div class="col-md-7 col-xl-5">
                                        <select name="office_name[]" class="form-control office"
                                        onchange="addOtherOptions({{ $index }},'office')"
                                            id="office_id_{{ $index }}">
                                            <option value="{{ $address->office_name }}">
                                                {{ $address->office_name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <a href="javascript:void(0)" class="btn btn-primary copy-detail"
                                            onclick="copyVillage({{ $index }},'office')"
                                            data-index="{{ $index }}"><i class="fa-solid fa-copy"></i></a>
                                    </div>
                                </div>
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Taluka /Tehsil</label></div>
                                    <div class="col-md-7 col-xl-7">
                                        <input type="text" name="taluka_tehsil[]" id="taluka_tehsil_{{$index}}"
                                            value="{{ old('taluka_tehsil.' . $index) }}" class="form-control">
                                        @error('taluka_tehsil.0')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-1">
                                        <a href="javascript:void(0)" class="btn btn-primary copy-detail"
                                            onclick="copyVillage({{ $index }},'taluka')" data-index=""><i
                                                class="fa-solid fa-copy"></i></a>
                                    </div>
                                </div>

                            </div>

                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">City </label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="dist_city[]" class="form-control city"
                                            value="{{ $address->dist_city }}"
                                            id="dist_city_{{ $index }}">
                                    </div>
                                </div>
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">State </label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="dist_state[]" class="form-control state"
                                            value="{{ $address->dist_state }}"
                                            id="dist_state_{{ $index }}">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="address_id[]" value="{{ $address->id }}">

                            <div class="col-md-12 text-end mt-3">
                                <button type="button" class="btn btn-danger delete_address"
                                    data-id="{{ $address->id }}">Remove</button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div id="address-container"></div>

                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-primary add-address">Add More Address</button>
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
<div class="modal fade" id="addOtherOptionModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
    data-bs-backdrop="static" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content overflow-hidden">
            <div class="modal-header pb-0 border-0">
                <h1 class="modal-title h4">Edit Disease</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="vstack" method="POST" id="updateForm">
                @csrf
                <input type="hidden" name="disease_id" id="disease_id">
                <div class="modal-body undefined">
                    <div class="vstack gap-1">
                        <div class="row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0 other_field_text"> <span
                                        class="text_danger_require">*</span></label></div>
                            <div class="col-md-8 col-xl-8">
                                <input type="text" name="name" class="form-control" id="other_detail_name"
                                    placeholder="Enter Other Name">
                                <span style="color: red;" id="other_detail_name_error"></span>
                            </div>
                            <input type="hidden" name="field_type" id="field_type">
                            <input type="hidden" name="index_id" id="index_id">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-dark" id="update_button_disease"
                        onclick="addOtherData()">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    // $('#cust_disease').select2();

    function isNumberKey(evt) {
        if (event.which != 8 && isNaN(String.fromCharCode(event.which))) {
            event.preventDefault();
        }
    }

    $('#cust_disease').select2(); // Add class to body when select2 opens $('#cust_disease').on('select2:open', function () { $('body').addClass('select2-open'); }); // Remove class from body when select2 closes $('#cust_disease').on('select2:close', function () { $('body').removeClass('select2-open'); });

    $(document).ready(function() {
        var addressIndex = "{{count($custAddresses) - 1}}"; // Start with the number of existing addresses

        // Function to dynamically add new address blocks
        $('.add-address').on('click', function() {
            addressIndex++; // Increment the index for each new block

            var newField = `
                <div class="address-block" data-index="${addressIndex}">
                    <input type="hidden" name="address_id[]" value="">
                    <hr class="my-6">
                    <div class="row align-items-center gap-3 mt-6">
                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">Address Type </label></div>
                            <div class="col-md-8 col-xl-6">
                                <select name="add_type[]" class="form-control">
                                    <option value="">Select Type</option>
                                    <option value="office_add">Office Address</option>
                                    <option value="shop_add">Shop Address</option>
                                    <option value="home_add">Home Address</option>
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
                            <div class="col-md-7 col-xl-5">
                                <select name="village[]" class="form-control village" id="village_id_${addressIndex}" onchange="addOtherOptions(${addressIndex},'village')">
                                    <option value="">Select Village</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <a href="javascript:void(0)" class="btn btn-primary" onclick="copyVillage(${addressIndex},'village')" data-index="${addressIndex}"><i class="fa-solid fa-copy "></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center gap-3 mt-6">
                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">Post Office(B.O/S.O/H.O) </label></div>
                            <div class="col-md-7 col-xl-5">
                                <select name="office_name[]" class="form-control office" id="office_id_${addressIndex}" 
                                onchange="addOtherOptions(${addressIndex},'office')">
                                    <option value="">Select Office</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                        <a href="javascript:void(0)" class="btn btn-primary copy-detail"
                                            onclick="copyVillage(${addressIndex},'office')"
                                            data-index=""><i class="fa-solid fa-copy"></i></a>
                                    </div>
                        </div>
<div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">Taluka /Tehsil</label></div>
                            <div class="col-md-7 col-xl-7">
                                <input type="text" name="taluka_tehsil[]" class="form-control"
                                    id="taluka_tehsil_${addressIndex}" value="">
                            </div>
                            <div class="col-md-1">
                                        <a href="javascript:void(0)" class="btn btn-primary copy-detail"
                                            onclick="copyVillage(${addressIndex},'taluka')"
                                            data-index=""><i class="fa-solid fa-copy"></i></a>
                                    </div>
                        </div>
                        
                    </div>

                    <div class="row align-items-center gap-3 mt-6">
                    <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">City </label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="text" name="dist_city[]" class="form-control city" id="dist_city_${addressIndex}">
                            </div>
                        </div>
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
                </div>
                `;
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
                        var officeHtml = "<option value=''>Select Office</option>";

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
                        villageHtml += "<option value='other'>Other</option>";
                        officeHtml += "<option value='other'>Other</option>";
                        $('#village_id_' + index).html(villageHtml);
                        $('#office_id_' + index).html(officeHtml);
                        $('#dist_state_' + index).val(data[0].state_name);
                        $('#dist_city_' + index).val(data[0].district_name);
                        $('#taluka_tehsil_' + index).val(data[0].sub_distname);
                        $('#village_id_' + index).select2();
                        $('#office_id_' + index).select2();
                    },
                });
            }
        });

        // Handle Remove Address
        $(document).on('click', '.remove-address', function() {
            $(this).closest('.address-block').remove();
        });
    });

    $(document).on('click', '.delete_address', function() {
        var address_id = $(this).data("id"); // Get the address ID from the button

        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure you want to delete this Address?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('address-delete') }}", // Correctly route to delete action
                    type: 'GET', // Use appropriate method
                    dataType: 'json',
                    data: {
                        _token: "{{ csrf_token() }}", // Pass the CSRF token
                        id: address_id // Pass the ID of the address to be deleted
                    },
                    success: function(data) {
                        toastr.success(data.message);

                        // Remove the address block from the DOM by using the correct selector
                        $(this).closest('.address-block')
                            .remove(); // Correctly target the parent address-block
                    }.bind(this), // Bind `this` to ensure the correct context
                    error: function(error) {
                        toastr.error(error.responseJSON.message);
                    }
                });
            }
        });
    });



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
    $(document).ready(function() {
        $('.add-alt-num').on('click', function() {
            var newField = `
                    <div class="row align-items-center g-3 mt-3 new_alt_num_${number}">
                        <div class="col-md-2"><label class="form-label mb-0">Alternate Number</label></div>
                        <div class="col-md-4 col-xl-4">
                            <input type="text" name="cust_alt_num[]" minlength="10" maxlength="10" onkeypress="return isNumberKey(event)" class="form-control" placeholder="Enter Alternate Number" value="">
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
            number = number + 1;
        });

        // Remove the alternate number field
        $(document).on('click', '.remove-alt-num', function() {
            $(`.new_alt_num_${number}`).remove();
            $(this).closest('.row').remove();
        });

        $(document).on('click', '.del-num', function() {
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
    function copyVillage(index, type) {
        if (type == "village") {
            var input = document.getElementById('village_id');
            if (index !== "") {
                input = document.getElementById(`village_id_${index}`);
            }
        } else {
            var input = document.getElementById('office_id');
            if (index !== "") {
                input = document.getElementById(`office_id_${index}`);
            }
        }
        if (type == "taluka") {
            var taluka = document.getElementById('taluka_tehsil');
            if (index !== "") {
                taluka = document.getElementById(`taluka_tehsil_${index}`);
            }
            taluka.select();
            taluka.setSelectionRange(0, 99999);
            document.execCommand('copy');
        } else {
            var selectedValue = input.options[input.selectedIndex].value;

            var tempInput = document.createElement("input");
            tempInput.value = selectedValue;
            document.body.appendChild(tempInput);
            tempInput.select();
            tempInput.setSelectionRange(0, 99999);
            document.execCommand('copy');
            document.body.removeChild(tempInput);
        }
    }
    function addOtherOptions(index, type) {
        if (type == 'village') {
            var input = $('#village_id');
            if (index !== "") {
                input = $(`#village_id_${index}`);
            }
            if (input.val() == "other") {
                $('#other_village').val('yes')
                if (index !== "") {
                    $(`#other_village_${index}`).val('yes')
                }
            }
        } else if (type == "office") {
            var input = $('#office_id');
            if (index !== "") {
                input = $(`#office_id_${index}`);
            }
            if (input.val() == "other") {
                $('#other_office').val('yes')
                if (index !== "") {
                    $(`#other_office_${index}`).val('yes')
                }
            }
        }

        if (input.val() == "other") {
            $('#index_id').val(index);
            $('#field_type').val(type);
            $('.other_field_text').html(`Other ${type} <span class="text_danger_require">*</span>`);
            $('#other_detail_name').attr('placeholder', `Enter Other ${type}`);
            $('.modal-title').html(`Add Other ${type}`);
            if (type == "office") {
                $('.other_field_text').html(`Other Post ${type} <span class="text_danger_require">*</span>`);
                $('.modal-title').html(`Add Other Post ${type}`);
                $('#other_detail_name').attr('placeholder', `Enter Other Post ${type}`);
            }
            $('#addOtherOptionModal').modal('show')
        }
    }
    function addOtherData() {
        $('#other_detail_name_error').html('');
        var other_detail_name = $('#other_detail_name').val();
        if (other_detail_name == "") {
            $('#other_detail_name_error').html(`Please Enter Other ${$('#field_type').val()}`);
            return false;
        }
        var index_id = $('#index_id').val();
        var field_type = $('#field_type').val();
        if (field_type == 'village') {
            if (index_id == "") {
                $('#village_id').html(`<option value="${other_detail_name}" selected>${other_detail_name}</option>`);
            } else {
                $(`#village_id_${index_id}`).html(
                    `<option value="${other_detail_name}" selected>${other_detail_name}</option>`);

            }
        } else {
            if (index_id == "") {
                $('#office_id').html(`<option value="${other_detail_name}" selected>${other_detail_name}</option>`);
            } else {
                $(`#office_id_${index_id}`).html(
                    `<option value="${other_detail_name}" selected>${other_detail_name}</option>`);

            }
        }
        $('#other_detail_name_error').html('');
        $('#other_detail_name').val('');
        $('#addOtherOptionModal').modal('hide')
    }
</script>
@endsection