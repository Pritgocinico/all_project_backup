@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Lead</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('leads.store') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="row align-items-center gap-3 mt-6">
                            <input type="hidden" name="customer_id" id="customer_id" value="">
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Customer Mobile Number <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="text" name="mobile_number" class="form-control" id="customer_number"
                                        pattern="\d*" minlength="10" maxlength="10" onkeypress="return isNumberKey(event)"
                                        placeholder="Enter Customer Mobile Number" value="{{ old('mobile_number') }}"
                                        onkeyup="customerDetail()">

                                    <span id="number_exist_error" class="text-danger"></span>
                                    @error('mobile_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Customer Name <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="text" name="name" class="form-control capitalize_letter"
                                        placeholder="Enter Customer Name" value="{{ old('name') }}">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Lead Source <span
                                                class="text-danger">*</span></label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <select name="lead_source" class="form-control" id="lead_source">
                                            <option value="">Select Lead Source</option>
                                            <option value="Whatsapp"
                                                {{ old('lead_source') == 'Whatsapp' ? 'selected' : '' }}>
                                                Whatsapp
                                            </option>
                                            <option value="Facebook"
                                                {{ old('lead_source') == 'Facebook' ? 'selected' : '' }}>
                                                Facebook
                                            </option>
                                            <option value="Instagram"
                                                {{ old('lead_source') == 'Instagram' ? 'selected' : '' }}>
                                                Instagram
                                            </option>
                                            <option value="Other" {{ old('lead_source') == 'Other' ? 'selected' : '' }}>
                                                Other
                                            </option>
                                        </select>
                                        @error('lead_source')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 row align-items-center g-3">
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
                                            <option value="other" @if ("other" == old('cust_disease')) {{ 'selected' }} @endif>Other</option>
                                        </select>
                                        @error('cust_disease')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3 {{ old('lead_source') == 'Other' ? '' : 'd-none' }}"
                                    id="other_lead_source_div">
                                    <div class="col-md-4"><label class="form-label mb-0">Other Lead Source <span
                                                class="text-danger">*</span></label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="other_lead_source" class="form-control"
                                            placeholder="Enter Other Lead Source" value="{{ old('other_lead_source') }}">
                                        @error('other_lead_source')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 row align-items-center g-3 {{ old('cust_disease') == 'Other' ? '' : 'd-none' }}"
                                    id="other_disease_div">
                                    <div class="col-md-4"><label class="form-label mb-0">Other Customer Disease <span
                                                class="text-danger">*</span></label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="other_cust_disease" class="form-control"
                                            placeholder="Enter Other Customer Disease" value="{{ old('other_cust_disease') }}">
                                        @error('other_cust_disease')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center gap-3 mt-6">

                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Assign <span
                                                class="text-danger">*</span></label></div>
                                    <div class="col-md-8 col-xl-6">
                                        @if (Auth()->user()->role_id == 1)
                                            <select name="assigned_to" class="form-control" id="employee_id">
                                                <option value="">Select Employee</option>
                                                @foreach ($employees as $emp)
                                                    <option value="{{ $emp->id }}"
                                                        {{ old('assigned_to') == $emp->id ? 'selected' : '' }}>
                                                        {{ $emp->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select name="assigned_to" class="form-control read-only" id="user_employee_id">
                                                <option value="">Select Employee</option>
                                                @foreach ($employees as $emp)
                                                    <option value="{{ $emp->id }}"
                                                        {{ Auth()->user()->id == $emp->id ? 'selected' : '' }}>
                                                        {{ $emp->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                        @error('assigned_to')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>

                        <hr class="my-6">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('leads.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-dark" id="save_button_lead">Save</button>
                        </div>
                    </form>
                </main>
            </div>
        </main>
    </div>
    </div>
@endsection
@section('script')
    <script>
        function isNumberKey(evt) {
            if (event.which != 8 && isNaN(String.fromCharCode(event.which))) {
                event.preventDefault();
            }
        }

        $('#lead_source').on('change', function() {
            if ($(this).val() == "Other") {
                $('#other_lead_source_div').removeClass('d-none');
            } else {
                $('#other_lead_source_div').addClass('d-none');
            }
        });

        $(document).ready(function(e) {
            $('#cust_disease').select2();
            $('#employee_id').select2();
            $('#product_id').select2();
            var customerExists = "{{ old('mobile_number') ? true : false }}";

            if (customerExists) {
                customerDetail();
            }

        })
        $('#cust_disease').on('change', function() {
            $('#other_disease_div').addClass('d-none');
            if ($(this).val() == 'other') {
                $('#other_disease_div').removeClass('d-none');
            }
        })

        $('#lead_type').on('change', function() {
            if ($(this).val() == 'Resale Lead') {
                $('#new_customer_div').addClass('d-none')
                $('#lead_field_div').removeClass('d-none')
                $('#customer_detail_div').removeClass('d-none')
                $('#reference_div').addClass('d-none')
            } else if ($(this).val() == 'New Lead') {
                $('#customer_detail_div').addClass('d-none')
                $('#new_customer_div').removeClass('d-none')
                $('#lead_field_div').removeClass('d-none')
                $('#reference_div').addClass('d-none')
            } else if ($(this).val() == 'Referance Lead') {
                $('#reference_div').removeClass('d-none')
                $('#customer_detail_div').addClass('d-none')
                $('#new_customer_div').removeClass('d-none')
                $('#lead_field_div').removeClass('d-none')
            }
        })

        function customerDetail() {
            var number = $('#customer_number').val();

            if (number.length === 10) {
                $.ajax({
                    url: "{{ route('check.number.lead') }}",
                    type: 'GET',
                    data: {
                        alt_num: number
                    },
                    success: function(data) {
                        $('#save_button_lead').attr('disabled', true);
                        $('#number_exist_error').html('This Number is already registered');
                    },
                    error: function(error) {
                        $('#save_button_lead').attr('disabled', false);
                        $('#number_exist_error').html('');
                    }
                });
            } else {
                $('#customer_detail_div').addClass('d-none');
            }
        }

        // New Customer
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

        $(document).ready(function() {
            $('.add-alt-num').on('click', function() {
                var newField = `
                    <div class="row align-items-center g-3 mt-3">
                        <div class="col-md-2"><label class="form-label mb-0">Alternate Number</label></div>
                        <div class="col-md-4 col-xl-4">
                            <input type="number" name="cust_alt_num[]" class="form-control" placeholder="Enter Alternate Number" >
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

            // Product Detail
            $('.add-product-detail').on('click', function() {
                var newField = `
                    <div class="row align-items-center product_row gap-3 mt-6">
                        <!-- Product Field -->
                        <div class="col-md-4 row align-items-center g-3">
                            <div class="col-md-6">
                                <label class="form-label mb-0">Product</label>
                            </div>
                            <div class="col-md-6 col-xl-6">
                                <select name="product[]" class="form-control" id="product_id">
                                    <option value="">Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Quantity Field -->
                        <div class="col-md-3 row align-items-center g-3">
                            <div class="col-md-6">
                                <label class="form-label mb-0">Quantity</label>
                            </div>
                            <div class="col-md-6 col-xl-6">
                                <input type="number" name="quantity[]" id="product_qty" class="form-control" min="0">
                                @error('quantity')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Price Field -->
                        <div class="col-md-3 row align-items-center g-3">
                            <div class="col-md-6">
                                <label class="form-label mb-0">Price</label>
                            </div>
                            <div class="col-md-6 col-xl-6">
                                <input type="number" name="product_price[]" id="product_price" class="form-control" min="0">
                                @error('product_price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Remove Button -->
                        <div class="col-md-2 row align-items-center g-3">
                            <div class="col-md-6"></div>
                            <div class="col-md-6 col-xl-6">
                                <button type="button" class="btn btn-danger remove-product-detail">Remove</button>
                            </div>
                        </div>
                    </div>
                `;
                $('#product-container').append(newField);
            });

            // Remove the product detail row
            $(document).on('click', '.remove-product-detail', function() {
                $(this).closest('.row.product_row').remove(); // Adjust this to remove the entire row
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
                            <div class="col-md-4"><label class="form-label mb-0">Post Office </label></div>
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
        });
    </script>
@endsection
