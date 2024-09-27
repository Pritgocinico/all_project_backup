@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Lead Management</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('leads.store') }}" enctype="multipart/form-data" method="POST">
                        @php
                            $departmentDetail = Auth()->user()->departmentDetail;
                            $type = 0;
                            if (isset($departmentDetail) && Auth()->user()->role_id !== 1) {
                                $deptName = $departmentDetail->name;
                                if (strpos($deptName, 'Financial')) {
                                    $type = 1;
                                }
                                if (strpos($deptName, 'Insurance')) {
                                    $type = 2;
                                }
                                if (strpos($deptName, 'travel')) {
                                    $type = 3;
                                }
                            }
                        @endphp
                        @csrf
                        <div id="first_step_div">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Customer</label><span
                                        class="text-danger">*</span></div>
                                <div class="col-md-3 col-xl-3">
                                    <select name="customer_id" class="form-control" id="customer_id" onchange="customerDetai()">
                                        <option value="">Select Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}" @if ($lead->customer_id == $customer->id) {{ 'selected' }} @endif>{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                    <div id="customer_error" class="text-danger"></div>
                                    @error('customer_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-1">
                                    <a href="javascript:void(0)"
                                        class="btn btn-white mx-xs-3 mx-0 text-nowrap openAddCustomerDataForm">
                                        <i class="fa-solid fa-plus"></i></a>
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Department<span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-4 col-xl-4">
                                    <select name="department" class="form-control" id="department">
                                        <option value="">Select Department</option>
                                        @foreach ($departmentList as $depart)
                                            <option value="{{ $depart->id }}" {{ $lead->department == $depart->id ? 'selected' :"" }}>{{ $depart->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('department')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Type<span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-4 col-xl-4">
                                    <select name="invest_type" class="form-control" id="invest_type">
                                        <option value="">Select Type</option>
                                        <option value="investments" {{ $lead->invest_type == "investments" ? 'selected' :"" }}>Investments</option>
                                        <option value="general insurance" {{ $lead->invest_type == "general insurance" ? 'selected' :"" }}>General Insurance</option>
                                        <option value="travel" {{ $lead->invest_type == "travel" ? 'selected' :"" }}>Travel</option>
                                    </select>
                                    <div id="invest_type_error" class="text-danger"></div>
                                    @error('invest_type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div id="customer_detail_div" style="display: @if($lead->customer_id == null) none @endif">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                                    <div class="col-md-4 col-xl-4" id="customer_name">
                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Customer Id</label></div>
                                    <div class="col-md-4 col-xl-4" id="customer_id_div">
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Email</label></div>
                                    <div class="col-md-4 col-xl-4" id="customer_email">
                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Phone Number</label></div>
                                    <div class="col-md-4 col-xl-4" id="customer_mobile">
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6" id="customer_detail_aadhar_div">
                                    <div class="col-md-2"><label class="form-label mb-0">Aadhar Card</label></div>
                                    <div class="col-md-4 col-xl-4" id="aadhar_card_number"></div>
                                    <div class="col-md-2"><label class="form-label mb-0">Aadhar Card File</label></div>
                                    <div class="col-md-4 col-xl-4" id="cust_aadhar_card_file"></div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Pan Card</label></div>
                                    <div class="col-md-4 col-xl-4" id="pan_card_number"></div>
                                    <div class="col-md-2"><label class="form-label mb-0">Pan Card File</label></div>
                                    <div class="col-md-4 col-xl-4" id="cust_pan_card_file"></div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Address</label></div>
                                    <div class="col-md-4 col-xl-4" id="customer_address"></div>
                                    <div class="col-md-2"><label class="form-label mb-0">Status</label></div>
                                    <div class="col-md-4 col-xl-4 lead_show_status" id="customer_status"></div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Customer Reference</label></div>
                                    <div class="col-md-4 col-xl-4" id="customer_reference"></div>
                                    <div class="col-md-2 "><label class="form-label mb-0">GST Certificate</label></div>
                                    <div class="col-md-4" id="gst_certificate_file"></div>
                                </div>
                            </div>
                            <hr class="my-6">
                            <div class="row align-items-center g-3 mt-6 first_step_div">
                                <div class="col-md-8"></div>
                                <div class="col-md-4 text-end">
                                    <a href="{{ route('leads.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                                    <button type="button" id="next_first_step_button"
                                        class="btn btn-primary btn-sm">Next<i
                                            class="fa-solid fa-arrow-right"></i></button>
                                </div>
                            </div>
                        </div>
                        @include('admin.lead.partials.edit.investment_field')
                        @include('admin.lead.partials.edit.third_step')
                    </form>
                </main>
            </div>
        </main>
    </div>
    <div class="modal fade" id="depositLiquidityModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content overflow-hidden">
                <div class="modal-header pb-0 border-0">
                    <h1 class="modal-title h4" id="depositLiquidityModalLabel">Add Customer</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="vstack" method="POST" id="addForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body undefined">
                        <div class="vstack gap-1">
                            <input type="hidden" name="insurance" id="insurance_customer">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Customer ID</label></div>
                                <div class="col-md-4">
                                    {{ $customerId }}
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Name <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="name" class="form-control" placeholder="Enter Name"
                                        value="{{ old('name') }}">
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
                                        placeholder="Enter Phone Number" value="{{ old('mobile_number') }}">
                                    @error('mobile_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Email <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="email" class="form-control" placeholder="Enter Email"
                                        value="{{ old('email') }}">
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Birth Date</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="date" class="form-control" name="birth_date"
                                        max="{{ date('Y-m-d') }}" value="{{ old('birth_date') }}">
                                    @error('birth_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">GST Certificate</label></div>
                                <div class="col-md-4">
                                    <input type="file" name="gst_certificate" id="gst_certificate"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Pan Card Number</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="pan_card_number" class="form-control"
                                        id="pan_card_number" placeholder="Enter Pan Card Number"
                                        value="{{ old('pan_card_number') }}">
                                    @error('pan_card_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Pan Card File</label></div>
                                <div class="col-md-4">
                                    <input type="file" name="pan_card_file" class="form-control" id="pan_card_file">
                                    @error('pan_card_file')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Aadhar Card Number</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="aadhaar_number" class="form-control" id="aadhaar_number"
                                        placeholder="Enter Aadhar Card Number" value="{{ old('aadhaar_number') }}">
                                    @error('aadhaar_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Aadhar Card File</label></div>
                                <div class="col-md-4">
                                    <input type="file" name="aadhar_card_file" class="form-control"
                                        id="aadhar_card_file">
                                    @error('aadhar_card_file')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Passort Number</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="passport_number" class="form-control"
                                        id="passport_number" placeholder="Enter Passport Number"
                                        value="{{ old('passport_number') }}">
                                    @error('passport_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Passport File</label></div>
                                <div class="col-md-4">
                                    <input type="file" name="passport_file" class="form-control" id="passport_file">
                                    @error('passport_file')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Reference</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="reference" class="form-control" id="reference"
                                        placeholder="Enter Reference" value="{{ old('reference') }}">
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
                                            <option value="{{ $country->iso2 }}">{{ $country->name }}
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
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="submitBtn"
                            onclick="submitForm()">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(e) {
            $('#customer_id').select2();
            customerDetai();
        })
        $('.openAddCustomerDataForm').on('click', function(e) {
            $('#depositLiquidityModal').modal('show')
            // $("#country").select2({
            //     dropdownParent: $("#depositLiquidityModal")
            // })
        })

        function getState() {
            var country = $('#country').val();
            $.ajax({
                method: 'get',
                url: "{{ route('state-by-country') }}",
                data: {
                    country_code: country,
                },
                success: function(res) {
                    var html = "<option value=''>Select State</option>";
                    $.each(res.data, function(i, v) {
                        html += "<option value='" + v.id + "'>" + v.name + "</option>"
                    })
                    $('#state').html("")
                    $('#state').html(html)
                    // $("#state").select2({
                    //     dropdownParent: $("#depositLiquidityModal")
                    // });
                }
            });
        }

        function getCity() {
            var state = $('#state').val();
            var country = $('#country').val();
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
                        html += "<option value='" + v.id + "'>" + v.name + "</option>"
                    })
                    $('#city').html("")
                    $('#city').html(html)
                    // $("#city").select2({
                    //     dropdownParent: $("#depositLiquidityModal")
                    // });
                }
            });
        }

        $('#next_first_step_button').on('click', function() {
            $('#first_step_div').show()
            var type = $('#invest_type').val();
            var customer_id = $('#customer_id').val();
            var cnt = 0;
            if (type == "") {
                $('#invest_type_error').html("Please Select Department");
                cnt = 1;
            }
            if (customer_id == "") {
                $('#customer_error').html("Please Select Customer");
                cnt = 1;
            }
            if (cnt == 1) {
                return false;
            }


            if (type == 'investments') {
                $('#type_of_investments').show();
                $('#investmentRadio').show();
                $('.investement_type_field').show();
                $('#investement_field_label').show();
                $('#investement_field_form').show();
                $('#general_insurance_div').hide();
                $('#travel_div').hide();
                $('#generalRadio').hide();
                $('#pms_product_div').show();
                $('.insurance_label').show();
                $('.first_step_div').hide()
                $('#second_step_div').show()
            } else if (type == 'general insurance') {
                $('#type_of_investments').hide();
                $('#general_insurance_div').show();
                $('#travel_div').hide();
                $('#investmentRadio').hide();
                $('#pms_product_div').hide();
                $('.investement_type_field').hide();
                $('#investement_field_label').hide();
                $('#investement_field_form').hide();
                $('#generalRadio').show();
                $('#healthDiv').show();
                $('.insurance_label').show();
                $('#second_step_div').show()
                $('.first_step_div').hide()
                document.getElementById('health_insurance').checked = true;
            } else if (type == "travel") {
                $('#second_step_div').show()
                $('#travel_div').show();
                $('#general_insurance_div').hide();
                $('#type_of_investments').hide();
                $('#investmentRadio').hide();
                $('.investement_type_field').hide();
                $('#pms_product_div').hide();
                $('#investement_field_label').hide();
                $('#investement_field_form').hide();
                $('#generalRadio').hide();
                $('.insurance_label').hide();
                $('.first_step_div').hide()
            }
        });
        $('#insurance_type').on('change', function(e) {
            var type = $(this).val();
            if (type == "pms") {
                $('.investement_type_field').show();
                $('#type_of_investments').show();
                $('#mf_lumsum').hide();
                $('#mf_sip').hide();
                $('#existing_investment_detail_div').hide();
                $('#mf_installment').hide();
                $('#fd_interest').hide();
                $('#maturity_amount_interest').hide();
                $('#pms_product_div').show();
                $('.aadhar_pan_card_detail_div').show();
                $('#mf_detail_div').hide();
                $('.bond_payout_detail').hide();
            } else if (type == 'mf') {
                $('.investement_type_field').show();
                $('#type_of_investments').show();
                $('#mf_lumsum').hide();
                $('#mf_sip').hide();
                $('#mf_installment').show();
                $('#existing_investment_detail_div').hide();
                $('#fd_interest').hide();
                $('#maturity_amount_interest').hide();
                $('#pms_product_div').hide();
                $('#mf_detail_div').show();
                $('.aadhar_pan_card_detail_div').show();
                $('.bond_payout_detail').hide();
            } else if (type == "fd") {
                $('#type_of_investments').show();
                $('#mf_lumsum').hide();
                $('.investement_type_field').hide();
                $('#mf_sip').hide();
                $('#mf_installment').hide();
                $('#existing_investment_detail_div').hide();
                $('#fd_interest').show();
                $('#maturity_amount_interest').show();
                $('#pms_product_div').show();
                $('#mf_detail_div').hide();
                $('.aadhar_pan_card_detail_div').hide();
                $('.bond_payout_detail').hide();
            } else if (type == 'bond') {
                $('#type_of_investments').show();
                $('#mf_lumsum').hide();
                $('.investement_type_field').hide();
                $('#mf_sip').hide();
                $('#mf_installment').hide();
                $('#existing_investment_detail_div').hide();
                $('#fd_interest').show();
                $('#maturity_amount_interest').show();
                $('#pms_product_div').show();
                $('.bond_payout_detail').show();
                $('#mf_detail_div').hide();
                $('.aadhar_pan_card_detail_div').hide();
            }
        });
        // General Insurance
        $('#health_insurance').on('click', function() {
            $('#healthDiv').show();
            $('#motorDiv').hide();
            $('#smeDiv').hide();
            $('#select_assignee').show();
        });

        $('#motor_insurance').on('click', function() {
            $('#healthDiv').hide();
            $('#motorDiv').show();
            $('#smeDiv').hide();
            $('#select_assignee').show();
        });

        $('#sme_insurance').on('click', function() {
            $('#healthDiv').hide();
            $('#motorDiv').hide();
            $('#smeDiv').show();
            $('#select_assignee').hide();
        });

        function submitForm() {
            $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i>')
            var formData = new FormData(document.getElementById('addForm'));
            $.ajax({
                url: "{{ route('customer.add') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var html = '<option value="' + data.data.id + '" selected>' + data.data.name +
                        '</option>';
                    $('#customer_id').append(html);
                    $('#depositLiquidityModal').modal('hide')
                    toastr.success(data.message);
                    $('#submitBtn').html('Submit')
                    $('#addForm')[0].reset()
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message);
                    $('#submitBtn').html('Submit')
                }
            });
        }
        $('#previous_second_step_button').click(function(e) {
            $('#second_step_div').hide()
            $('.first_step_div').show()
        })
        $('#next_second_step_button').click(function(e) {
            $('#third_step_div').show();
            $('#assigned_to').select2()
        })
        $('#third_step_previoud').click(function(e) {
            $('#second_step_div').show()
            $('#third_step_div').hide();
        })
        $('#investment_field').on('change', function(e) {
            var val = $(this).val();
            $('#existing_investment_detail_div').hide();
            if (val == "existing") {
                $('#existing_investment_detail_div').show();
            }
        })

        $('#health_policy_type').on('change', function(e) {
            var type = $(this).val();
            $('#health_policy_renew_div').hide();
            if (type == "renewal") {
                $('#health_policy_renew_div').show();
            }
        })
        var i = 0;
        $('#add_more_button').on('click', function(e) {
            i = i + 1;
            var html = `<div class="row align-items-center g-3 mt-6" id="add_child_option_${i}">
                <div class="col-md-4">
                    <label class="form-label">Child Type</label>
                    <select class="form-select" name="child_type[]">
                        <option value="">Select</option>
                        <option value="child">Child</option>
                        <option value="Infant">Infant</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Child Name</label>
                    <input type="text" class="form-control" name="child_name[]" placeholder="Enter Name">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Child Age</label>
                    <input type="number" class="form-control" name="child_age[]" placeholder="Enter Age">
                </div>
                <div class="col-md-1">
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm " id="remove_add_child_option" onclick="removeRow(${i})" data-id="${i}"><i class="fa-solid fa-trash-can"></i></a>
                </div>
                </div>`;

            $('#travel_child_detail').append(html)
        })

        function removeRow(id) {
            $('#add_child_option_' + id).remove();
        }

        function customerDetai(){
            var custId = $('#customer_id').val();
            $.ajax({
                url: "{{ route('customer.get') }}",
                type: 'get',
                data: {
                    id: custId
                },
                success: function(data) {
                    var res = data.data;
                    $('#customer_id_div').html(res.customer_id);
                    $('#customer_email').html(res.email);
                    $('#customer_name').html(res.name);
                    var content = res.customer_department;
                    var capitalizedContent = content.charAt(0).toUpperCase() + content.slice(1);
                    $('#customer_department').html(capitalizedContent);
                    var cityName = "";
                    if (res.city_detail !== null) {
                        cityName = res.city_detail.name;
                    }
                    var stateName = "";
                    if (res.state_detail !== null) {
                        stateName = res.state_detail.name;
                    }
                    var countryName = "";
                    if (res.country_detail !== null) {
                        countryName = res.country_detail.name;
                    }
                    var html = res.address + ",<br/>" + cityName + ", " + stateName + ", <br />" +
                        countryName + " - " + res.pin_code;
                    $('#customer_address').html(html);
                    $('#customer_mobile').html(res.mobile_number);
                    var aadharFile = "-";
                    $('#aadhar_card_number').html(res.aadhar_number);
                    if (res.aadhar_card_file !== null) {
                        aadharFile =
                            `<a href="{{ asset('storage/${res.aadhar_card_file}') }}" class='btn btn-dark btn-sm' target='_blank'>View</a>`;
                    }
                    $('#cust_aadhar_card_file').html(aadharFile);
                    $('#pan_card_number').html(res.pan_card_number);
                    var panFile = "-";
                    if (res.pan_card_file !== null) {
                        panFile =
                            `<a href="{{ asset('storage/${res.pan_card_file}') }}" class='btn btn-dark btn-sm' target='_blank'>View</a>`;
                    }
                    $('#cust_pan_card_file').html(panFile);
                    var gstFile = "-";
                    if (res.gst_certificate !== null) {
                        gstFile =
                            `<a href="{{ asset('storage/${res.gst_certificate}') }}" class='btn btn-dark btn-sm' target='_blank'>View</a>`;
                    }
                    $('#gst_certificate_file').html(gstFile);
                    $('#service_preference').html(res.service_preference);
                    $('#customer_reference').html(res.reference);
                    $('#birth_date').html(res.birth_date);
                    var className = "danger";
                    var text = "Deactive";
                    if (res.status == 1) {
                        className = "success";
                        text = "Active";
                    }
                    $('#customer_status').html('<span class="badge bg-' + className + '">' + text +
                        '</span>')
                    var html1 = "";
                    if (res.lead_detail.length == 0) {
                        html1 += "<tr><td colspan='7' class='text-center'>No Data Available.</td></tr>";
                    } else {
                        $.each(res.lead_detail, function(i, v) {
                            var status = 'warning';
                            var text = 'Pending Lead';
                            if (res.lead_detaillead_status == 2) {
                                status = 'info';
                                text = 'Assigned Lead';
                            }
                            if (res.lead_detail.lead_status == 3) {
                                status = 'secondary';
                                text = 'Hold Lead';
                            }
                            if (res.lead_detail.lead_status == 4) {
                                status = 'success';
                                text = 'Complete Lead';
                            }
                            if (res.lead_detail.lead_status == 5) {
                                status = 'warning';
                                text = 'Extends Lead';
                            }
                            if (res.lead_detail.lead_status == 6) {
                                status = 'danger';
                                text = 'Cancel Lead';
                            }

                            html1 += `<tr>
                                <td>${i+1}</td>
                                <td>${v.lead_id}</td>
                                <td>${v.invest_type}</td>
                                <td>${res.name}</td>
                                <td> <span class="badge bg-${status}">${text}</span></td>
                                <td>${v.user_detail.name}</td>
                                <td>${moment(v.created_at).format('DD-MM-YYYY hh:mm:ss A')}</td>
                                </tr>`;
                        });
                    }
                    $('#lead_detail').html(html1);
                    $('#customer_detail_div').show();
                },
                error: function(error) {
                    $('#customer_detail_div').hide();
                    toastr.error(error.responseJSON.message);
                }
            });
        }
        $(".login_button_password").click(function() {
            var input = $("#password");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
                $('#password_eye_button').html('<i class="fa-solid fa-eye-slash"></i>');
            } else {
                input.attr("type", "password");
                $('#password_eye_button').html('<i class="fa-solid fa-eye"></i>');
            }
        });
        $(".confirm_button_password").click(function() {
            var input = $("#confirm_password");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
                $('#confirm_eye_button').html('<i class="fa-solid fa-eye-slash"></i>');
            } else {
                input.attr("type", "password");
                $('#confirm_eye_button').html('<i class="fa-solid fa-eye"></i>');
            }
        });
        $('.policy_type').on('click', function(e) {
            var policy_type = $(this).val();
            $('#corporate_detail_div').hide();
            if (policy_type == "corporate") {
                $('#corporate_detail_div').show();
            }
        })

        $('#sme_insurance_type').on('change', function(e) {
            var type = $(this).val();
            if (type == "fire&burglary") {
                $('#sme_insurance_fire').show();
            } else if (type == "marine") {
                $('#sme_insurance_fire').hide();
                $('#sme_insurance_marine').show();
            } else if (type == 'wc') {
                $('#sme_insurance_fire').hide();
                $('#sme_insurance_marine').hide();
                $('#sme_insurance_wc').show();
            }
        })

        function toggleDiv() {
            const checkbox1 = document.getElementById('sip_detail');
            const checkbox2 = document.getElementById('lumsum_detail');

            $('#mf_sip').hide();
            $('#mf_lumsum').hide();
            if (checkbox1.checked) {
                $('#mf_sip').show();
            }
            if (checkbox2.checked) {
                $('#mf_lumsum').show();
            }
        }
    </script>
@endsection
