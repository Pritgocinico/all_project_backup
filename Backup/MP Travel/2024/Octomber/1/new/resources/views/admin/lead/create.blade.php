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
                    <form enctype="multipart/form-data" method="POST" 
                        id="lead_create_form">
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
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Customer</label><span
                                        class="text-danger">*</span></div>
                                <div class="col-md-6 col-xl-7">
                                    <select name="customer_id" class="form-control" id="customer_id"
                                        onchange="customerData()">
                                        <option value="">Select Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ $customer->id == old('customer_id') || $customer->id == $customerId ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
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
                            </div>
                                <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Lead Type<span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    @if ($type == 0)
                                        <select name="invest_type" class="form-control" id="invest_type">
                                            <option value="">Select Type</option>
                                            <option value="investments"
                                                {{ 'investments' == old('invest_type') ? 'selected' : '' }}>Investments
                                            </option>
                                            <option value="general insurance"
                                                {{ 'general insurance' == old('invest_type') ? 'selected' : '' }}>General
                                                Insurance</option>
                                            <option value="travel" {{ 'travel' == old('invest_type') ? 'selected' : '' }}>
                                                Travel</option>
                                        </select>
                                    @else
                                        <select name="invest_type" class="form-control read-only" id="invest_type">
                                            <option value="">Select Type</option>
                                            <option value="investments" {{ $type == 1 ? 'selected' : '' }}>Investments
                                            </option>
                                            <option value="general insurance" {{ $type == 2 ? 'selected' : '' }}>General
                                                Insurance</option>
                                            <option value="travel" {{ $type == 3 ? 'selected' : '' }}>Travel</option>
                                        </select>
                                    @endif
                                    <div id="invest_type_error" class="text-danger"></div>
                                    @error('invest_type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            </div>
                            <div id="customer_detail_div"
                                style="display: {{ isset($customerData) || old('customer_id') ? '' : 'none' }}">
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Name</label></div>
                                    <div class="col-md-8 col-xl-6" id="customer_name">{{ $customerData->name ?? '' }}
                                    </div>
                                </div>
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Customer Id</label></div>
                                    <div class="col-md-8 col-xl-6" id="customer_id_div">
                                        {{ $customerData->customer_id ?? '' }}
                                    </div>
                                </div>
                                </div>
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Email</label></div>
                                    <div class="col-md-8 col-xl-6" id="customer_email">{{ $customerData->email ?? '' }}
                                    </div>
                                </div>
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Phone Number</label></div>
                                    <div class="col-md-8 col-xl-6" id="customer_mobile">
                                        {{ $customerData->mobile_number ?? '' }}
                                    </div>
                                </div>
                                </div>

                                <div class="row align-items-center gap-3 mt-6" id="customer_detail_aadhar_div">
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Aadhar Card</label></div>
                                    <div class="col-md-8 col-xl-6" id="aadhar_card_number">
                                        {{ $customerData->aadhar_number ?? '' }}</div>
                                    </div>
                                        <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Aadhar Card File</label></div>
                                    <div class="col-md-8 col-xl-6" id="cust_aadhar_card_file">
                                        @if (isset($customerData) && $customerData->aadhar_card_file)
                                            <a href="{{ asset('storage/' . $customerData->aadhar_card_file) }}"
                                                class="btn btn-dark" target="_blank">View</a>
                                        @endif
                                    </div>
                                </div>
                                </div>
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Pan Card</label></div>
                                    <div class="col-md-8 col-xl-6" id="pan_card_number">
                                        {{ $customerData->pan_card_number ?? '' }}</div>
                                    </div>
                                        <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Pan Card File</label></div>
                                    <div class="col-md-8 col-xl-6" id="cust_pan_card_file">
                                        @if (isset($customerData) && $customerData->pan_card_file)
                                            <a href="{{ asset('storage/' . $customerData->pan_card_file) }}"
                                                class="btn btn-dark" target="_blank">View</a>
                                        @endif
                                    </div>
                                </div>
                                </div>
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Address</label></div>
                                    <div class="col-md-8 col-xl-6" id="customer_address">
                                        {{ $customerData->address ?? '' }},<br />
                                        {{ isset($customerData->cityDetail) ? $customerData->cityDetail->name : '-' }},
                                        {{ isset($customerData->stateDetail) ? $customerData->stateDetail->name : '-' }},<br />
                                        {{ isset($customerData->countryDetail) ? $customerData->countryDetail->name : '-' }},{{ $customerData->pin_code ?? '' }}
                                    </div>
                                </div>
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4 "><label class="form-label mb-0">GST Certificate</label></div>
                                    <div class="col-md-8 col-xl-6" id="gst_certificate_file">
                                        @if (isset($customerData) && $customerData->gst_certificate)
                                            <a href="{{ asset('storage/' . $customerData->gst_certificate) }}"
                                                class="btn btn-dark" target="_blank">View</a>
                                        @endif
                                    </div>
                                </div>
                                </div>
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Customer Reference</label></div>
                                    <div class="col-md-8 col-xl-6" id="customer_reference">
                                        {{ $customerData->reference ?? '' }}</div>
                                    </div>
                                        <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4">
                                        
                                        
                                        <label class="form-label mb-0">Service Preference Tagging:-
                                        </label></div>
                                    <div class="col-md-8 col-xl-6" id="last_service_take">
                                        {{ isset($customer->lastServiceTake) ? $customer->lastServiceTake->servicePreferenceDetail->name : '-' }}
                                    </div>
                                </div>
                                </div>
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Create By:-
                                        </label></div>
                                    <div class="col-md-8 col-xl-6" id="last_service_create_by">
                                        {{ isset($customer->lastServiceTake) ? $customer->lastServiceTake->userDetail->name : '-' }}
                                    </div>
                                </div>
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Create At:-
                                        </label></div>
                                    <div class="col-md-8 col-xl-6" id="last_service_create_at">
                                        {{ isset($customer->lastServiceTake) ? Utility::convertDmyAMPMFormat($customer->lastServiceTake->created_at) : '-' }}
                                    </div>
                                </div>
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
                        @include('admin.lead.partials.create.investment_field')
                        @include('admin.lead.partials.create.third_step')
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
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Name <span
                                            class="error_span">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="text" name="name" class="form-control" placeholder="Enter Name" id="modal_customer_name"
                                        value="{{ old('name') }}">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <span id="modal_customer_name_error" class="error_span"></span>
                                </div>
                            </div>
                            </div>
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Phone Number<span
                                            class="error_span">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="number" name="mobile_number" class="form-control" min="0" id="modal_customer_phone"
                                        placeholder="Enter Phone Number" value="{{ old('mobile_number') }}">
                                    @error('mobile_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <span id="modal_customer_phone_error" class="error_span"></span>
                                </div>
                            </div>
                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Email <span
                                            class="error_span">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="text" name="email" class="form-control" placeholder="Enter Email" id="modal_customer_email"
                                        value="{{ old('email') }}">
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <span id="modal_customer_email_error" class="error_span"></span>
                                </div>
                            </div>
                            </div>
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Birth Date</label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="date" class="form-control" name="birth_date"
                                        max="{{ date('Y-m-d') }}" value="{{ old('birth_date') }}">
                                    @error('birth_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                                <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">GST Certificate</label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="file" name="gst_certificate" id="gst_certificate"
                                        class="form-control">
                                </div>
                            </div>
                            </div>
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Pan Card Number</label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="text" name="pan_card_number" class="form-control"
                                        id="pan_card_number" placeholder="Enter Pan Card Number"
                                        value="{{ old('pan_card_number') }}">
                                    @error('pan_card_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                                <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Pan Card File</label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="file" name="pan_card_file" class="form-control" id="pan_card_file">
                                    @error('pan_card_file')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Aadhar Card Number</label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="text" name="aadhaar_number" class="form-control" id="aadhaar_number"
                                        placeholder="Enter Aadhar Card Number" value="{{ old('aadhaar_number') }}">
                                    @error('aadhaar_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                                <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Aadhar Card File</label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="file" name="aadhar_card_file" class="form-control"
                                        id="aadhar_card_file">
                                    @error('aadhar_card_file')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            </div>
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Passort Number</label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="text" name="passport_number" class="form-control"
                                        id="passport_number" placeholder="Enter Passport Number"
                                        value="{{ old('passport_number') }}">
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
                                <div class="col-md-4"><label class="form-label mb-0">Reference</label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="text" name="reference" class="form-control" id="reference"
                                        placeholder="Enter Reference" value="{{ old('reference') }}">
                                    @error('reference')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
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
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Country</label></div>
                                <div class="col-md-8 col-xl-6">
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
                            </div>
                            </div>
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">State</label></div>
                                <div class="col-md-8 col-xl-6">
                                    <select name="state" class="form-control risk_location_state" id="state"
                                        onchange="getCity()">
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
                                    <select name="city" class="form-control risk_location_city" id="city">
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
    <div class="modal fade " id="leadSaveDetail" tabindex="-1" aria-labelledby="leadSaveDetailLabel"
        style="display: none;" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content overflow-hidden">
                <div class="modal-header">
                    <h1 class="modal-title h4" id="">Lead Detail</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="vstack" method="POST" id="leadFormData" enctype="multipart/form-data" action="{{ route('add_lead_follow_up') }}">
                    @csrf
                    <input type="hidden" name="lead_id" id="edit_lead_id" value="">
                    <div class="modal-body undefined">
                        <div class="row align-items-center gap-3 mt-6 mb-2">
                            <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">Follow Up Remarks<span
                                        class="error_span">*</span></label></div>
                            <div class="col-md-8 col-xl-6">
                                <textarea name="follow_up_remarks" class="form-control" id="follow_up_remarks"
                                    placeholder="Enter Follow Up Remarks"></textarea>
                                <span class="error_span" id="error_follow_up_remarks"></span>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 border-end">
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Name</label>
                                    </div>
                                    <div class="col-md-8 col-xl-6" id="lead_customer_name">

                                    </div>
                                </div>
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Customer
                                            Id</label></div>
                                    <div class="col-md-8 col-xl-6" id="lead_customer_id_div">
                                    </div>
                                </div>
                                </div>
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Email</label>
                                    </div>
                                    <div class="col-md-8 col-xl-6" style="word-wrap: break-word;"
                                        id="lead_customer_email">
                                    </div>
                                </div>
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Phone
                                            Number</label></div>
                                    <div class="col-md-8 col-xl-6" id="lead_customer_mobile">
                                    </div>
                                </div>
                                </div>

                                <div class="row align-items-center gap-3 mt-6" id="customer_detail_aadhar_div">
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Aadhar
                                            Card</label></div>
                                    <div class="col-md-8 col-xl-6" id="lead_aadhar_card_number">
                                    </div>
                                </div>
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Aadhar Card
                                            File</label></div>
                                    <div class="col-md-8 col-xl-6" id="lead_cust_aadhar_card_file">
                                    </div>
                                </div>
                                </div>
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Pan
                                            Card</label></div>
                                    <div class="col-md-8 col-xl-6" id="lead_pan_card_number">
                                    </div>
                                </div>
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Pan Card
                                            File</label></div>
                                    <div class="col-md-8 col-xl-6" id="lead_cust_pan_card_file">
                                    </div>
                                </div>
                                </div>
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Address</label>
                                    </div>
                                    <div class="col-md-8 col-xl-6" id="lead_customer_address">
                                    </div>
                                </div>
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Customer
                                            Reference</label></div>
                                    <div class="col-md-8 col-xl-6" id="lead_customer_reference">
                                    </div>
                                </div>
                                </div>
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-8 col-xl-6"><label class="form-label mb-0">GST
                                            Certificate</label></div>
                                    <div class="col-md-4" id="lead_gst_certificate_file">
                                    </div>
                                </div>
                                </div>
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-4">Service Preference Tagging :-
                                        <span id="lead_service_preference_tag"></span>
                                    </div>
                                    <div class="col-md-4">
                                        Create By:- <span id="lead_service_preference_create"></span>
                                    </div>
                                    <div class="col-md-4">
                                        Create At:-
                                        <span id="lead_service_preference_create_at"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6"><label class="form-label mb-0">Lead Type</label></div>
                                    <div class="col-md-6 col-xl-6" id="lead_invest_type">
                                    </div>
                                </div>
                                <div class="row align-items-center gap-3 mt-6 lead_insurance_type_modal">
                                    <div class="col-md-6"><label class="form-label mb-0">Insurance Type</label>
                                    </div>
                                    <div class="col-md-6 col-xl-6" id="lead_insurance_type">
                                    </div>
                                </div>

                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6"><label class="form-label mb-0">Lead Date</label></div>
                                    <div class="col-md-6 col-xl-6" id="lead_lead_date">
                                    </div>
                                </div>
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6"><label class="form-label mb-0">Lead Assign To</label>
                                    </div>
                                    <div class="col-md-6 col-xl-6" id="lead_assign_to">
                                    </div>
                                </div>
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6"><label class="form-label mb-0">Lead Amount</label>
                                    </div>
                                    <div class="col-md-6 col-xl-6" id="modal_lead_amount">
                                    </div>
                                </div>
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6"><label class="form-label mb-0">Lead Department</label>
                                    </div>
                                    <div class="col-md-6 col-xl-6" id="lead_department">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-neutral" onclick="editLeadPage()">Edit</button>
                        <button type="button" class="btn btn-neutral" onclick="listLeadPage()">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="followup_remark_button">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function editLeadPage() {
            var id = $('#edit_lead_id').val();
            window.location.href = "{{ route('leads.edit', 'id') }}".replace('id', id);
        }

        function listLeadPage() {
            window.location.href = "{{ route('leads.index') }}";
        }
        $(document).ready(function(e) {
            customerData();
            $('#customer_id').select2();
            $('#assigned_to').select2();
            $('#other_services').select2();
        })
        $('#lead_create_form').on('submit', function(e) {
            e.preventDefault();
            var customer = $('#customer_id').val();
            var type = $('#invest_type').val();
            var assign = $('#assigned_to').val();
            var department = $('#department').val();
            var cnt = 0;
            $('#customer_error').html('');
            $('#invest_type_error').html('');
            $('#assigned_to_error').html('');
            $('#department_error').html('');
            if(customer == ""){
                $('#customer_error').html('Please Select Customer.');
                cnt = 1;
            }
            if(type == ""){
                $('#invest_type_error').html('Please Select Lead Type.');
                cnt = 1;
            }
            if(assign == ""){
                $('#assigned_to_error').html('Please Select Assign.');
                cnt = 1;
            }
            if(assign == ""){
                $('#assigned_to_error').html('Please Select Assign.');
                cnt = 1;
            }
            if(department == ""){
                $('#department_error').html('Please Select Department.');
                cnt = 1;
            }
            if(cnt == 1){
                return false;
            }
            $('#lead_save_button').html('<i class="fa fa-spinner fa-spin"></i>');
            var formData = new FormData(this);
            $.ajax({
                url: "{{ route('leads.store') }}",
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    toastr.success(data.message);
                    var customer = data.data.customerData;
                    var cityName = "";
                    if (customer.city_detail !== null) {
                        cityName = customer.city_detail.name;
                    }
                    var stateName = "";
                    if (customer.state_detail !== null) {
                        stateName = customer.state_detail.name;
                    }
                    var countryName = "";
                    if (customer.country_detail !== null) {
                        countryName = customer.country_detail.name;
                    }
                    var address = customer.address + ",<br/>" + cityName + ", " + stateName +
                        ", <br />" +
                        countryName + " - " + customer.pin_code;

                    var aadharFile = "-";
                    if (customer.aadhar_card_file !== null) {
                        aadharFile =
                            `<a href="{{ asset('storage/${customer.aadhar_card_file}') }}" class='btn btn-dark btn-sm' target='_blank'>View</a>`;
                    }
                    var panFile = "-";
                    if (customer.pan_card_file !== null) {
                        panFile =
                            `<a href="{{ asset('storage/${customer.pan_card_file}') }}" class='btn btn-dark btn-sm' target='_blank'>View</a>`;
                    }
                    var gstFile = "-";
                    if (customer.gst_certificate !== null) {
                        gstFile =
                            `<a href="{{ asset('storage/${customer.gst_certificate}') }}" class='btn btn-dark btn-sm' target='_blank'>View</a>`;
                    }
                    var tagName = "-";
                    var tagCreate = "-";
                    var tagAt = "-";
                    if (customer.last_service_take !== null) {
                        if(customer.last_service_take.service_preference_detail !== null){
                            tagName = customer.last_service_take.service_preference_detail.name;
                        }
                        if(customer.last_service_take.service_preference_detail !== null){
                            tagCreate = customer.last_service_take.user_detail.name;
                        }
                        tagAt = moment(customer.last_service_take.created_at).format("DD-MM-YYYY hh:mm:ss A")
                    }
                    
                    $('#lead_service_preference_tag').html(tagName);
                    $('#lead_service_preference_create').html(tagCreate);
                    $('#lead_service_preference_create_at').html(tagAt);

                    $('#leadSaveDetail').modal('show');
                    $('#lead_save_button').html('save');
                    $('#lead_customer_name').html(customer.name);
                    $('#lead_customer_id_div').html(customer.customer_id);
                    $('#lead_customer_email').html(customer.email);
                    $('#lead_customer_mobile').html(customer.mobile_number);
                    $('#lead_aadhar_card_number').html(customer.aadhar_number);
                    $('#lead_cust_aadhar_card_file').html(aadharFile);
                    $('#lead_pan_card_number').html(customer.name);
                    $('#lead_cust_pan_card_file').html(panFile);
                    $('#lead_customer_address').html(address);
                    $('#lead_customer_reference').html(customer.name);
                    $('#lead_gst_certificate_file').html(gstFile);

                    // Lead Data
                    var lead = data.data.lead;
                    $('#edit_lead_id').val(lead.id);
                    $('#lead_invest_type').html(lead.invest_type);
                    $('.lead_insurance_type_modal').addClass('d-none');
                    if (lead.invest_type == "general insurance") {
                        $('.lead_insurance_type_modal').removeClass('d-none');
                        $('#lead_insurance_type').html(removeUnderscoresAndCapitalize(lead.insurance_type));
                    }
                    var memberName = "";
                    $.each(lead.lead_member_detail, function(i, v) {
                        if(v.user_detail !== null){
                            memberName += v.user_detail.name + ", ";
                        }
                    })
                    var departmentName = "-";
                    if (lead.department_data !== null) {
                        departmentName = lead.department_data.name;
                    }
                    $('#lead_department').html(departmentName);
                    $('#lead_lead_date').html(lead.lead_date);
                    $('#lead_assign_to').html(memberName);
                    var amount = 0;
                    if(lead.lead_amount != null){
                        amount = lead.lead_amount;
                    }
                    $('#modal_lead_amount').html(amount.toFixed(2));
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message);
                    $('#lead_save_button').html('save');
                }
            })
        });

        function removeUnderscoresAndCapitalize(str) {
            return str
                .split('_')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        }
        $('.openAddCustomerDataForm').on('click', function(e) {
            $('#depositLiquidityModal').modal('show');
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
                    $('.risk_location_state').html("")
                    $('.risk_location_state').html(html);
                    $('#risk_location_state').select2();
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
                    $('.risk_location_city').html("")
                    $('.risk_location_city').html(html)
                    $('#risk_location_city').select2();
                }
            });
        }
        $('#invest_type').on('change', function(e) {
            var type = $(this).val();
            if (type == 'investments') {
                $('#type_of_investments').show();
                $('.investement_type_field').show();
                $('#general_insurance_div').hide();
                $('#travel_div').hide();
                $('#generalRadio').hide();
                $('.insurance_label').show();
                $('#investmentRadio').show();
                $('.invest_insurance_type_span').html('Investments');
            }
            if (type == "general insurance") {
                $('#general_insurance_div').show();
                $('#type_of_investments').hide();
                $('#travel_div').hide();
                $('.investement_type_field').show();
                $('#generalRadio').show();
                $('#investmentRadio').hide();
                $('.insurance_label').show();
                $('.invest_insurance_type_span').html('Insurance');
            }
            if (type == "travel") {
                $('#type_of_investments').hide();
                $('#general_insurance_div').hide();
                $('.investement_type_field').hide();
                $('#travel_div').show();
                $('#generalRadio').hide();
                $('#investmentRadio').hide();
                $('.insurance_label').hide();
            }
        })
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
                $('.invest_insurance_type_span').html('Investments');
            } else if (type == 'general insurance') {
                $('#fire_policy_div').removeClass('d-none');
                $('#type_of_investments').hide();
                $('#general_insurance_div').show();
                $('#travel_div').hide();
                $('#investmentRadio').hide();
                $('#pms_product_div').hide();
                $('.investement_type_field').show();
                $('#investement_field_label').hide();
                $('#investement_field_form').hide();
                $('#generalRadio').show();
                $('#healthDiv').show();
                $('.insurance_label').show();
                $('#second_step_div').show()
                $('.first_step_div').hide()
                $('.invest_insurance_type_span').html('Insurance');
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
                $('#other_services').select2();
            }
        });
        $('.insurance_type').on('change', function(e) {
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
            } else if (type == "fire_policy") {
                $('#fire_policy_div').removeClass('d-none');
                $('#wc_policy_div').addClass('d-none');
                $('#health_policy_div').addClass('d-none');
                $('#gpa_policy_div').addClass('d-none');
                $('#pa_policy_div').addClass('d-none');
                $('#gms_policy_div').addClass('d-none');
                $('#marine_policy_div').addClass('d-none');
                $('#risk_location_address_div').removeClass('d-none');
            } else if (type == "wc_policy") {
                $('#wc_policy_div').removeClass('d-none');
                $('#fire_policy_div').addClass('d-none');
                $('#health_policy_div').addClass('d-none');
                $('#gpa_policy_div').addClass('d-none');
                $('#pa_policy_div').addClass('d-none');
                $('#gms_policy_div').addClass('d-none');
                $('#marine_policy_div').addClass('d-none');
                $('#risk_location_address_div').removeClass('d-none');
            } else if (type == "health_policy") {
                $('#fire_policy_div').addClass('d-none');
                $('#wc_policy_div').addClass('d-none');
                $('#health_policy_div').removeClass('d-none');
                $('#pa_policy_div').addClass('d-none');
                $('#pa_policy_div').addClass('d-none');
                $('#gms_policy_div').addClass('d-none');
                $('#marine_policy_div').addClass('d-none');
                $('#risk_location_address_div').addClass('d-none');
            } else if (type == "pa_policy") {
                $('#fire_policy_div').addClass('d-none');
                $('#wc_policy_div').addClass('d-none');
                $('#health_policy_div').addClass('d-none');
                $('#gpa_policy_div').addClass('d-none');
                $('#gms_policy_div').addClass('d-none');
                $('#pa_policy_div').removeClass('d-none');
                $('#marine_policy_div').addClass('d-none');
                $('#risk_location_address_div').addClass('d-none');
            } else if (type == "gpa_policy") {
                $('#fire_policy_div').addClass('d-none');
                $('#wc_policy_div').addClass('d-none');
                $('#health_policy_div').addClass('d-none');
                $('#gpa_policy_div').removeClass('d-none');
                $('#pa_policy_div').addClass('d-none');
                $('#gms_policy_div').addClass('d-none');
                $('#marine_policy_div').addClass('d-none');
                $('#risk_location_address_div').removeClass('d-none');
            } else if (type == "gmc_policy") {
                $('#fire_policy_div').addClass('d-none');
                $('#wc_policy_div').addClass('d-none');
                $('#health_policy_div').addClass('d-none');
                $('#gpa_policy_div').addClass('d-none');
                $('#pa_policy_div').addClass('d-none');
                $('#gms_policy_div').removeClass('d-none');
                $('#marine_policy_div').addClass('d-none');
                $('#risk_location_address_div').removeClass('d-none');
            } else if (type == "marine_policy") {
                $('#fire_policy_div').addClass('d-none');
                $('#wc_policy_div').addClass('d-none');
                $('#health_policy_div').addClass('d-none');
                $('#gpa_policy_div').addClass('d-none');
                $('#pa_policy_div').addClass('d-none');
                $('#gms_policy_div').addClass('d-none');
                $('#marine_policy_div').removeClass('d-none');
                $('#risk_location_address_div').removeClass('d-none');
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
        $('#marine_type_of_policy').on('change', function(e) {
            var type = $(this).val();
            console.log(type);

            $('.marine_policy_other_div').addClass('d-none');
            if (type == 'other') {
                $('.marine_policy_other_div').removeClass('d-none');
            }
        })

        function submitForm() {
            var name = $('#modal_customer_name').val();
            var number = $('#modal_customer_phone').val();
            var email = $('#modal_customer_email').val();
            var cnt = 0;
            $('#modal_customer_name_error').html('');
            $('#modal_customer_phone_error').html('');
            $('#modal_customer_email_error').html('');
            if (name.trim() == "") {
                $('#modal_customer_name_error').html('Please enter customer name');
                cnt = 1;
            }
            if (number.trim() == "") {
                $('#modal_customer_phone_error').html('Please enter customer phone number');
                cnt = 1;
            }
            if (email == "") {
                $('#modal_customer_email_error').html('Please enter customer email');
                cnt = 1;
            }
            if(cnt == 1){
                return false;
            }
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
            $('#wc_policy_div').addClass('d-none');
        })
        $('#next_second_step_button').click(function(e) {
            $('#third_step_div').show();
            $('.second_step_hide').hide();
            $('#assigned_to').select2()
        })
        $('#third_step_previoud').click(function(e) {
            $('#second_step_div').show()
            $('#third_step_div').hide();
            $('#wc_policy_div').removeClass('d-none');
        })
        $('#investment_field').on('change', function(e) {
            var val = $(this).val();
            $('#existing_investment_detail_div').hide();
            if (val == "existing") {
                $('#existing_investment_detail_div').show();
            }
        })

        $('.health_policy_type').on('change', function(e) {
            var type = $(this).val();
            $('.renew_policy_div').addClass('d-none');
            if (type == "renewal") {
                $('.renew_policy_div').removeClass('d-none');
            }
        })
        var i = 0;
        $('#add_more_button').on('click', function(e) {
            i = i + 1;
            var html = `<div id="add_child_option_${i}">
        <div class="row align-items-center gap-3 mt-6">
            <div class="col-md-4">
                <label class="form-label">Other Type</label>
                <select class="form-select" name="child_type[]">
                    <option value="">Select</option>
                    <option value="child">Child</option>
                    <option value="Infant">Infant</option>
                    <option value="Adult">Adult</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Other Name</label>
                <input type="text" class="form-control" name="child_name[]" placeholder="Enter Name">
            </div>
            <div class="col-md-3">
                <label class="form-label">Other Age</label>
                <input type="number" class="form-control" name="child_age[]" placeholder="Enter Age">
            </div>
            <div class="col-md-1">
                <a href="javascript:void(0)" class="btn btn-primary btn-sm remove_add_child_option" data-id="${i}">
                    <i class="fa-solid fa-trash-can"></i>
                </a>
            </div>
        </div>
        <div class="row align-items-center gap-3 mt-6">
            <div class="col-md-4">
                <label class="form-label">DOB</label>
                <input type="date" class="form-control" name="child_dob[]" id="child_dob">
            </div>
            <div class="col-md-4">
                <label class="form-label">Passport Number</label>
                <input type="text" class="form-control" name="passport_number[]" placeholder="Enter Passport Number" id="passport_number">
            </div>
            <div class="col-md-4">
                <label class="form-label">Passport File</label>
                <input type="file" class="form-control" name="passport_file[]" placeholder="Enter Age" id="passport_file">
            </div>
        </div></div>
        `;
            var type = $('#travel_mode').val();
            $('.child_add_more_button').removeClass('d-none');
            if (type == "individual") {
                $('.child_add_more_button').addClass('d-none');
            }

            $('#travel_child_detail').append(html);
        });
        $('.remove_add_child_option').on('click', function(e) {
            var id = $(this).data('id');
            $('#add_child_option_' + id).remove();
        })

        function customerData() {
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
                        console.log(html);
                        
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
        $('#other_services').on('change', function(e) {
            var type = $(this).val();
            $('.other_service_div').hide();
            if (type.includes("other")) {
                $('.other_service_div').show();
            }
        })
        $('#same_address').on('click', function(e) {
            const checkbox = document.getElementById('same_address');
            $('.risk_location_address').removeClass('d-none');
            $('#risk_location_country').select2();
            if (checkbox.checked) {
                $('.risk_location_address').addClass('d-none');
            }
        })
        $('.same_address').on('click', function(e) {
            const checkbox = document.getElementByClassName('same_address');
            $('.risk_location_address').removeClass('d-none');
            $('#risk_location_country').select2();
            if (checkbox.checked) {
                $('.risk_location_address').addClass('d-none');
            }
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
        $('.total_sum_insured').on('keyup', function(e) {
            var building_value = $('#building_value').val();
            var plant_machinary_value = $('#plant_machinary_value').val();
            var total_stock_in_process = $('#total_stock_in_process').val();
            var fff_other_ee = $('#fff_other_ee').val();
            var other_content = $('#other_content').val();
            var totalAmount = parseInt(building_value) + parseInt(plant_machinary_value) + parseInt(
                total_stock_in_process) + parseInt(fff_other_ee) + parseInt(other_content);
            $('#sum_Insured').val(totalAmount)
        })
        $('#medical_extension_limit').on('change', function(e) {
            var limit = $('#medical_extension_limit').val();
            $('#actual_amount_div').addClass('d-none')
            if (limit == "at_actual") {
                $('#actual_amount_div').removeClass('d-none')
            }
        })
        $('#plan_type').on('change', function(e) {
            var type = $(this).val();
            $('#health_family_member_div').addClass('d-none')
            $('.health_family_member_detail_more').html('');
            if (type == "floater") {
                $('#health_family_member_div').removeClass('d-none')
            }
        })
        var number = 1;
        $('#health_add_more_button').on('click', function(e) {
            number = number + 1;
            var html = `<div id="remove_add_more_div_${number}">
                <hr class="my-6" />
                <a href="javascript:void(0)" class="d-flex justify-content-end" onclick="removeDivTag(${number})"><i class="fa-solid fa-trash-can"></i></a>
                <div class="row align-items-center gap-3 mt-6">
                    <div class="col-md-4">
                        <label class="form-label">Member Name</label>
                        <input type="text" class="form-control" name="member_name[]" placeholder="Enter Name">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">DOB</label>
                        <input type="date" class="form-control" name="dob[]" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Gender</label>
                        <select class="form-control" name="gender[]">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>
                <div class="row align-items-center gap-3 mt-6">
                    <div class="col-md-4">
                        <label class="form-label">Relationship</label>
                        <input type="text" class="form-control" name="relationship[]" placholder="Enter Relationship">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Pre-Existing</label>
                        <select name="pre_existing[]" id="pre_existing" class="form-control">
                            <option value="">Select Pre-Existing</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0">Height</label>
                        <input type="text" name="height[]" id="height" class="form-control" placeholder="Enter Height">
                    </div>
                </div>
                <div class="row align-items-center gap-3 mt-6">
                    <div class="col-md-4">
                        <label class="form-label mb-0">Weight</label>
                        <input type="text" name="weight[]" id="weight" class="form-control" placeholder="Enter Weight">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0">Education</label>
                        <input type="text" name="education[]" id="education" class="form-control" placeholder="Enter Education">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0">Profession</label>
                        <select name="profession[]" id="profession" class="form-control">
                            <option value="">Select Profession</option>
                            <option value="salaried">Salaried</option>
                            <option value="business">Business</option>
                        </select>
                    </div>
                </div>
                </div>`;

            $('.health_family_member_detail_more').append(html);
        })

        function removeDivTag(number) {
            $(`#remove_add_more_div_${number}`).remove();
        }
        $('#pa_type_case').on('change', function(e) {
            var type = $(this).val();
            $('.pa_renew_policy_div').addClass('d-none')
            if (type == "renewal") {
                $('.pa_renew_policy_div').removeClass('d-none')
            }
        })
        $('#leadSaveDetail').on('submit',function(e){
            var remark = $('#follow_up_remarks').val();
            if(remark.trim() == ""){
                $('#error_follow_up_remarks').html('Please Enter Follow Up Remarks');
                e.preventDefault();
                return false;
            }
            $('#followup_remark_button').html('<i class="fa fa-spinner fa-spin"></i>');
        })
    </script>
@endsection
