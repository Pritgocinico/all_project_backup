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
                    <form action="{{ route('leads.update',$lead->id) }}" enctype="multipart/form-data" method="POST">
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
                            <div class="row align-items-center g-3 mt-6 first_step_div">
                                <div class="col-md-2"><label class="form-label mb-0">Department</label></div>
                                <div class="col-md-4 col-xl-4">
                                    @if (Auth()->user()->role_id == 1)
                                        <select name="invest_type" class="form-control" id="invest_type" onchange="getCustomer()">
                                            <option value="">Select Department</option>
                                            <option value="investments"
                                                @if ($lead->invest_type == 'investments') {{ 'selected' }} @endif>Investments
                                            </option>
                                            <option value="general insurance"
                                                @if ($lead->invest_type == 'general insurance') {{ 'selected' }} @endif>General
                                                Insurance</option>
                                            <option value="travel"
                                                @if ($lead->invest_type == 'travel') {{ 'selected' }} @endif>Travel</option>
                                        </select>
                                    @else
                                        <select name="invest_type" class="form-control read-only" id="invest_type_data" onchange="getCustomer()">
                                            <option value="">Select Insurance</option>
                                            <option value="investments" {{ $type == 1 ? 'selected' : '' }}>
                                                Investments</option>
                                            <option value="general insurance" {{ $type == 2 ? 'selected' : '' }}>General
                                                Insurance</option>
                                            <option value="travel" {{ $type == 3 ? 'selected' : '' }}>Travel
                                            </option>
                                        </select>
                                    @endif
                                    <div id="invest_type_error" class="text-danger"></div>
                                    @error('invest_type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Customer</label></div>
                                <div class="col-md-3 col-xl-3">
                                    <select name="customer_id" class="form-control" id="customer_id" onchange="getCustomerDetail()">
                                        <option value="">Select Customer</option>
                                    </select>
                                    <div id="customer_error" class="text-danger"></div>
                                    @error('customer_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-1">
                                    <a href="javascript:void(0)"
                                        class="btn btn-white mx-xs-3 mx-0 text-nowrap openAddCustomerDataForm">
                                        <i class="bi bi-plus-square-fill"></i></a>
                                </div>
                            </div>
                            <div id="customer_detail_div" style="display: none">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2">Customer Name:-</div>
                                    <div class="col-md-4" id="customer_name"></div>
                                    <div class="col-md-2">Customer Email:-</div>
                                    <div class="col-md-4" id="customer_email"></div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2">Customer Code:-</div>
                                    <div class="col-md-4" id="customer_code"></div>
                                    <div class="col-md-2">Address:-</div>
                                    <div class="col-md-4" id="customer_address"></div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2">Customer Mobile Number:-</div>
                                    <div class="col-md-4" id="customer_mobile"></div>
                                    <div class="col-md-2">Gender:-</div>
                                    <div class="col-md-4" id="customer_gender"></div>
                                </div>
                            </div>
                            <hr class="my-6">
                            <div class="row align-items-center g-3 mt-6 first_step_div">
                                <div class="col-md-8"></div>
                                <div class="col-md-4 text-end">
                                    <a href="{{ route('leads.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                                    <button type="button" id="next_first_step_button" class="btn btn-primary">Next<i
                                            class="bi bi-arrow-right-short"></i></button>
                                </div>
                            </div>
                        </div>

                        <div id="second_step_div" style="display: none;">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2 insurance_label"
                                    style="display: @if ($type == 0) none @endif;"><label
                                        class="form-label mb-0">Investment Type</label></div>
                                <div class="col-md-4 col-xl-4" id="investmentRadio"
                                    style="display: @if ($type !== 1) none @endif;">
                                    <input class="form-check-input" type="radio" name="insurance_type" id="pms_type"
                                        value="pms" @if($lead->insurance_type == "pms") checked @endif>
                                    <label class="form-check-label" for="disabled">
                                        PMS
                                    </label>

                                    <input class="form-check-input" type="radio" name="insurance_type" id="mf_type"
                                        value="mf" @if($lead->insurance_type == "mf") checked @endif>
                                    <label class="form-check-label" for="view">
                                        MF
                                    </label>

                                    <input class="form-check-input" type="radio" name="insurance_type" id="fd_type"
                                        value="fd" @if($lead->insurance_type == "fd") checked @endif>
                                    <label class="form-check-label" for="corporate">
                                        FD
                                    </label>

                                    <input class="form-check-input" type="radio" name="insurance_type" id="bond_type"
                                        value="bond" @if($lead->insurance_type == "bond") checked @endif>
                                    <label class="form-check-label" for="corporate">
                                        Bond
                                    </label>
                                </div>
                                <div class="col-md-4 col-xl-4" id="generalRadio"
                                    style="display: @if ($type !== 2) none @endif;">
                                    <input class="form-check-input" type="radio" name="insurance_type"
                                        id="health_insurance" value="health" @if($lead->insurance_type == "health") checked @endif>
                                    <label class="form-check-label" for="disabled">
                                        Health
                                    </label>

                                    <input class="form-check-input" type="radio" name="insurance_type"
                                        id="motor_insurance" value="motor" @if($lead->insurance_type == "motor") checked @endif>
                                    <label class="form-check-label" for="view">
                                        Motor
                                    </label>

                                    <input class="form-check-input" type="radio" name="insurance_type"
                                        id="sme_insurance" value="sme" @if($lead->insurance_type == "sme") checked @endif>
                                    <label class="form-check-label" for="corporate">
                                        SME
                                    </label>
                                </div>
                                <div class="col-md-2 investement_field_label"
                                    style="display: @if ($type !== 1) none @endif;">
                                    <label class="form-label mb-0">Investment Field</label>
                                </div>
                                <div class="col-md-4 investement_field_form"
                                    style="display: @if ($type !== 1) none @endif;">
                                    <input class="form-check-input investment_field" type="radio"
                                        name="investment_field" id="investment_field" value="new">
                                    <label class="form-check-label" for="corporate">
                                        New
                                    </label>
                                    <input class="form-check-input investment_field" type="radio"
                                        name="investment_field" id="investment_field" value="existing">
                                    <label class="form-check-label" for="corporate">
                                        Existing
                                    </label>
                                </div>
                            </div>
                            <div id="type_of_investments" style="display: none;">
                                <div class="align-items-center row g-3 mt-6" id="existing_investment_detail_div"
                                    style="display: none">
                                    <div class="col-md-2">Investment Code</div>
                                    <div class="col-md-4">
                                        <input type="text" name="investment_code" id="investment_code"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-2">Investment Remarks</div>
                                    <div class="col-md-4">
                                        <textarea name="investment_remark" id="investment_remark" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Product Name</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="product_name" id="product_name" class="form-control"
                                            placeholder="Enter Product Name" value="">
                                        @error('product_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Amount Of Investment</label>
                                    </div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="number" name="amount_of_investment" id="amount_of_investment"
                                            class="form-control" placeholder="Amount Of Investment">
                                        @error('amount_of_investment')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Investment Date</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="date" name="investment_date" id="investment_date"
                                            value="{{ old('investment_date', date('Y-m-d')) }}" class="form-control">
                                        @error('investment_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6" id="mf_lumsum" style="display: none;">
                                    <div class="col-md-2"><label class="form-label mb-0">SIP</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="sip" id="sip" class="form-control"
                                            value="">
                                        @error('sip')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Lumsum Amount</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="number" name="lumsum_amount" id="lumsum_amount"
                                            class="form-control">
                                        @error('lumsum_amount')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6" id="mf_sip" style="display: none;">
                                    <div class="col-md-2"><label class="form-label mb-0">SIP Amount</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="number" name="sip_amount" id="sip_amount" class="form-control"
                                            value="">
                                        @error('sip_amount')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">SIP Start Date</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="date" name="sip_date" id="sip_date"
                                            value="{{ old('sip_date', date('Y-m-d')) }}" class="form-control">
                                        @error('sip_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6" id="mf_installment" style="display: none;">
                                    <div class="col-md-2"><label class="form-label mb-0">No. of Installment</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="number" name="installment_no" id="installment_no"
                                            class="form-control" value="">
                                        @error('installment_no')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6" id="fd_interest" style="display: none;">
                                    <div class="col-md-2"><label class="form-label mb-0">Rate of Interest</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="number" name="interest_rate" id="interest_rate"
                                            class="form-control" value="">
                                        @error('interest_rate')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Maturity Date</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="date" name="maturity_date" id="maturity_date"
                                            value="{{ old('maturity_date', date('Y-m-d')) }}" class="form-control">
                                        @error('maturity_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Managed By</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="managed_by" class="form-control" value="">
                                        @error('managed_by')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Lead Date</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="date" name="lead_date"
                                            value="{{ old('lead_date', date('Y-m-d')) }}" class="form-control">
                                        @error('lead_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id="general_insurance_div"
                                style="display: @if ($type !== 2) none @endif;">
                                <hr class="my-6">
                                <h4>General Insurance</h4>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Policy No</label></div>
                                    <div class="col-md-4">
                                        <input type="text" name="policy_no" class="form-control" value=""
                                            placeholder="Enter Policy No" value="{{$lead->policy_no}}">
                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Policy Type </label></div>
                                    <div class="col-md-4">
                                        <input class="form-check-input policy_type" type="radio" name="policy_type"
                                            id="policy_type" value="individual" @if($lead->policy_type == "individual") checked @endif>
                                        <label Individual="form-check-label" for="disabled">
                                            Individual
                                        </label>

                                        <input class="form-check-input policy_type" type="radio" name="policy_type"
                                            id="policy_type" value="corporate" @if($lead->policy_type == "corporate") checked @endif>
                                        <label class="form-check-label" for="view">
                                            Corporate
                                        </label>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6" id="corporate_detail_div" style="display: @if($lead->policy_type == null || $lead->policy_type == "individual") none @endif">
                                    <div class="col-md-2"><label class="form-label mb-0">GST Certificate</label></div>
                                    <div class="col-md-4">
                                        <input type="file" name="gst_certificate" class="form-control" value=""
                                            placeholder="Enter KYC Number">
                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">No Of Business</label></div>
                                    <div class="col-md-4">
                                        <input type="text" name="no_of_business" class="form-control"
                                            placeholder="Enter No Of Business">
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Previous Policy</label></div>
                                    <div class="col-md-4">
                                        <input type="text" name="previous_policy" class="form-control" value=""
                                            placeholder="Enter Previous Policy Number" value="{{$lead->previous_policy}}">
                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Sum Insurance</label></div>
                                    <div class="col-md-4">
                                        <input type="text" name="sum_insurance" class="form-control" value="{{$lead->sum_insurance}}"
                                            placeholder="Enter Sum Insurance">
                                    </div>
                                </div>
                                <div id="healthDiv" style="display: @if ($type !== 2) none @endif;">
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Health Policy type</label>
                                        </div>
                                        <div class="col-md-4 col-xl-4">
                                            <select name="health_policy_type" class="form-control"
                                                id="health_policy_type">
                                                <option value="">Select Health Policy type</option>
                                                <option value="new" @if($lead->health_policy_type == "new") {{"selected"}} @endif>New</option>
                                                <option value="renewal" @if($lead->health_policy_type == "renewal") {{"selected"}} @endif>Renewal</option>
                                            </select>
                                            @error('health_policy_type')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Insurer</label></div>
                                        <div class="col-md-4 col-xl-4">
                                            <input type="text" name="insurer" class="form-control" value="{{$lead->insurer}}">
                                            @error('insurer')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-2"><label class="form-label mb-0">Insured</label></div>
                                        <div class="col-md-4 col-xl-4">
                                            <input type="text" name="insured" class="form-control" value="{{$lead->insured}}">
                                            @error('insured')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Product</label></div>
                                        <div class="col-md-4 col-xl-4">
                                            <select name="product" class="form-control">
                                                <option value="">Select Product</option>
                                            </select>
                                            @error('product')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-2"><label class="form-label mb-0">Sub Product</label></div>
                                        <div class="col-md-4 col-xl-4">
                                            <select name="sub_product" class="form-control">
                                                <option value="">Select Sub Product</option>
                                            </select>
                                            @error('sub_product')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Received Date</label></div>
                                        <div class="col-md-4 col-xl-4">
                                            <input type="date" name="received_date"
                                                value="{{$lead->received_date}}" class="form-control">
                                            @error('received_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-2"><label class="form-label mb-0">Sum Insurance</label></div>
                                        <div class="col-md-4 col-xl-4">
                                            <input type="number" name="sum_insurance" class="form-control" value="{{$lead->sum_insurance}}">
                                            @error('sum_insurance')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row align-item-center g-3 mt-6" id="health_policy_renew_div"
                                        style="display: @if($lead->health_policy_type == "new") none @endif ">
                                        <div class="col-md-2"><label class="form-label mb-0">Old Policy Attachment</label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="file" name="old_policy_attachment[]" class="form-control"
                                                multiple>
                                        </div>
                                        <div class="col-md-2"><label class="form-label mb-0">Claim History</label></div>
                                        <div class="col-md-4">
                                            <input type="file" name="claim_history[]" class="form-control" multiple>
                                        </div>
                                    </div>
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Insurer DOB</label></div>
                                        <div class="col-md-4 col-xl-4">
                                            <input type="date" name="insurer_dob"
                                            value="{{$lead->insurer_dob}}" class="form-control">
                                            @error('insurer_dob')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div id="motorDiv" style="display: none;">
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Vehicle</label></div>
                                        <div class="col-md-4 col-xl-4">
                                            <select name="vehicle" class="form-control">
                                                <option value="">Select Vehicle</option>
                                                <option value="Two Wheeler">Two Wheeler</option>
                                                <option value="Four Wheeler">Four Wheeler</option>
                                                <option value="Commercial Vehicle">Commercial Vehicle</option>
                                                <option value="TP Policy Only">TP Policy Only</option>
                                            </select>
                                            @error('vehicle')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-2"><label class="form-label mb-0">Client</label></div>
                                        <div class="col-md-4 col-xl-4">
                                            <select name="client" class="form-control">
                                                <option value="">Client</option>
                                            </select>
                                            @error('client')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Received Date</label></div>
                                        <div class="col-md-4 col-xl-4">
                                            <input type="date" name="received_date"
                                                value="{{ old('received_date', date('Y-m-d')) }}" class="form-control">
                                            @error('received_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-2"><label class="form-label mb-0">Vehicle Make</label></div>
                                        <div class="col-md-4 col-xl-4">
                                            <input type="text" name="vehicle_make" class="form-control">
                                            @error('vehicle_make')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Vehicle Model</label></div>
                                        <div class="col-md-4 col-xl-4">
                                            <input type="text" name="vehicle_model" class="form-control">
                                            @error('vehicle_model')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-2"><label class="form-label mb-0">RC Copy</label></div>
                                        <div class="col-md-4 col-xl-4">
                                            <input type="file" name="rc_copy" class="form-control"
                                                placeholder="Upload RC Copy">
                                            @error('rc_copy')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div id="smeDiv" style="display: none;">
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">SME Insurance</label></div>
                                        <div class="col-md-4 col-xl-4">
                                            <select name="sme_insurance" class="form-control" id="sme_insurance_type">
                                                <option value="">Select</option>
                                                <option value="fire&burglary" @if($lead->sme_insurance == "fire&burglary") {{"selected"}} @endif>Fire & Burglary</option>
                                                <option value="marine" @if($lead->sme_insurance == "marine") {{"selected"}} @endif>Marine</option>
                                                <option value="wc" @if($lead->sme_insurance == "wc") {{"selected"}} @endif>WC</option>
                                            </select>
                                            @error('sme_insurance')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        {{-- <div class="col-md-2"><label class="form-label mb-0">Fire & Burglary</label></div>
                                        <div class="col-md-4 col-xl-4">
                                            <input type="text" name="fire_burglary" class="form-control"
                                                value="">
                                            @error('fire_burglary')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div> --}}

                                        {{-- <div class="col-md-2"><label class="form-label mb-0">Marine</label></div>
                                        <div class="col-md-4 col-xl-4">
                                            <input type="text" name="marine" class="form-control">
                                            @error('marine')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div> --}}
                                    </div>

                                    <div id="sme_insurance_fire" style="display: none;">
                                        <div class="row align-items-center g-3 mt-6">
                                            <div class="col-md-2"><label class="form-label mb-0">Insurance Cover</label></div>
                                            <div class="col-md-4 col-xl-4">
                                                <input type="text" name="insurance_cover" class="form-control">
                                                @error('insurance_cover')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
    
                                            <div class="col-md-2"><label class="form-label mb-0">Insurance
                                                    Value</label>
                                            </div>
                                            <div class="col-md-4 col-xl-4">
                                                <input type="text" name="insurance_value" class="form-control">
                                                @error('insurance_value')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row align-items-center g-3 mt-6">
                                            <div class="col-md-2"><label class="form-label mb-0">Hypothication</label></div>
                                            <div class="col-md-4 col-xl-4">
                                                <input type="text" name="hypothication" class="form-control">
                                                @error('hypothication')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
    
                                            <div class="col-md-2"><label class="form-label mb-0">Nature Of Business</label>
                                            </div>
                                            <div class="col-md-4 col-xl-4">
                                                <input type="text" name="nature_of_business" class="form-control">
                                                @error('nature_of_business')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row align-items-center g-3 mt-6">
                                            <div class="col-md-2"><label class="form-label mb-0">Claim History</label></div>
                                            <div class="col-md-4 col-xl-4">
                                                <input type="text" name="fire_claim_history" class="form-control">
                                                @error('fire_claim_history')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div id="sme_insurance_marine" style="display: none;">
                                        <div class="row align-items-center g-3 mt-6">
                                            <div class="col-md-2"><label class="form-label mb-0">Nature Of Business</label>
                                            </div>
                                            <div class="col-md-4 col-xl-4">
                                                <input type="text" name="nature_of_business" class="form-control">
                                                @error('nature_of_business')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
    
                                            <div class="col-md-2"><label class="form-label mb-0">Good Description</label>
                                            </div>
                                            <div class="col-md-4 col-xl-4">
                                                <textarea name="good_description" class="form-control" placeholder="Enter Description"></textarea>
                                                @error('good_description')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row align-items-center g-3 mt-6">
                                            <div class="col-md-2"><label class="form-label mb-0">Invoice Copy</label></div>
                                            <div class="col-md-4">
                                                <input type="file" name="invoice_copy" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div id="sme_insurance_wc" style="display: none;">
                                        <div class="row align-items-center g-3 mt-6">
                                            <div class="col-md-2"><label class="form-label mb-0">No. Of Workers</label>
                                            </div>
                                            <div class="col-md-4 col-xl-4">
                                                <input type="number" name="workers_number" class="form-control">
                                                @error('workers_number')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
    
                                            <div class="col-md-2"><label class="form-label mb-0">Salary Range</label>
                                            </div>
                                            <div class="col-md-4 col-xl-4">
                                                <input type="number" name="salary_range" class="form-control">
                                                @error('salary_range')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row align-items-center g-3 mt-6">
                                            <div class="col-md-2"><label class="form-label mb-0">Designation</label></div>
                                            <div class="col-md-4">
                                                <textarea name="designation" class="form-control" placeholder="Enter Designation"></textarea>
                                                @error('designation')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row align-items-center g-3 mt-6">
                                        {{-- <div class="col-md-2"><label class="form-label mb-0">WC</label></div>
                                        <div class="col-md-4 col-xl-4">
                                            <input type="text" name="wc" class="form-control">
                                            @error('wc')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div> --}}

                                        <div class="col-md-2"><label class="form-label mb-0">GMC</label></div>
                                        <div class="col-md-4 col-xl-4">
                                            <input type="text" name="gmc" class="form-control">
                                            @error('gmc')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">GPA</label></div>
                                        <div class="col-md-4 col-xl-4">
                                            <input type="text" name="gpa" class="form-control">
                                            @error('wc')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-2"><label class="form-label mb-0">Professional
                                                Indemnity</label>
                                        </div>
                                        <div class="col-md-4 col-xl-4">
                                            <input type="text" name="professional_indemnity" class="form-control">
                                            @error('gmc')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Other Insurance</label></div>
                                        <div class="col-md-4 col-xl-4">
                                            <input type="text" name="other_insurance" class="form-control">
                                            @error('other_insurance')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Lead Attachment</label></div>
                                    <div class="col-md-4">
                                        <input type="file" name="policy_attachment[]" class="form-control" multiple>
                                    </div>
                                </div>
                            </div>
                            <div id="travel_div" style="display: @if ($type !== 3) none @endif;">
                                <hr class="my-6">
                                <h4>Travel</h4>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Client Name</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="client_name" class="form-control" value="">
                                        @error('client_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Travel From Date</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="date" name="travel_from_date"
                                            value="{{ old('travel_from_date', date('Y-m-d')) }}" class="form-control">
                                        @error('travel_start_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Travel To Date</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="date" name="travel_to_date"
                                            value="{{ old('travel_to_date', date('Y-m-d')) }}" class="form-control"
                                            value="">
                                        @error('travel_to_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Number Of Prex</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="number" name="number_of_prex" class="form-control">
                                        @error('number_of_prex')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Travel Destination</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input class="form-check-input" type="radio" name="travel_destination"
                                            id="health_insurance" value="0" required checked>
                                        <label class="form-check-label" for="disabled">
                                            Domestic
                                        </label>

                                        <input class="form-check-input" type="radio" name="travel_destination"
                                            id="motor_insurance" value="1">
                                        <label class="form-check-label" for="view">
                                            International
                                        </label>
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Flight Preference</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="flight_preference" class="form-control">
                                        @error('flight_preference')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Hotel Preference</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="hotel_preference" class="form-control">
                                        @error('hotel_preference')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Other Services</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <select name="other_services" class="form-control">
                                            <option value="">Select Services</option>
                                            <option value="domestic_air_ticket">Domestic Air Ticket</option>
                                            <option value="visa">Visa</option>
                                            <option value="railway_ticket">Railway Ticket</option>
                                            <option value="hotel">Hotel</option>
                                            <option value="passport">Passport</option>
                                            <option value="rent_cab">Rent a Cab</option>
                                            <option value="other">Other</option>
                                        </select>
                                        @error('other_services')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Itinerary Flow</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <textarea name="itinerary_flow" class="form-control" id="description" placeholder="Enter Flow"></textarea>
                                        @error('itinerary_flow')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <hr class="my-6">
                                <h4>Child Detail</h4>
                                <div class="row justify-content-end">
                                    <div class="col-md-2">
                                        <a href="javascript:void(0)" id="add_more_button"
                                            class="btn btn-sm btn-dark">Add More</a>
                                    </div>
                                </div>
                                <div id="travel_child_detail"></div>
                            </div>

                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-8"></div>
                                <div class="col-md-4 text-end">
                                    <button type="button" id="previous_second_step_button" class="btn btn-light"><i
                                            class="bi bi-arrow-left-short"></i>Previous</button>
                                    <a href="{{ route('leads.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                                    <button type="button" id="next_second_step_button" class="btn btn-primary">Next<i
                                            class="bi bi-arrow-right-short"></i></button>
                                </div>
                            </div>
                        </div>
                        <div id="third_step_div" style="display: none">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Assigned To</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <select name="assigned_to[]" class="form-control js-example-basic-single"
                                        id="assigned_to" multiple>
                                        <option value="">Select Assignee</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ in_array($user->id, $leadMemberDetail) ? 'selected' : '' }}>
                                                {{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('assigned_to')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Lead Amount (Optional)</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="number" name="lead_amount" class="form-control" id="lead_amount"
                                        placeholder="Enter Lead Amount (Optional)" value="{{ $lead->lead_amount }}">
                                    @error('lead_amount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-item-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Description</label></div>
                                <div class="col-md-10">
                                    <textarea name="description" class="form-control" id="description" placeholder="Enter Description">{{ $lead->description }}</textarea>
                                </div>
                            </div>
                            <hr class="my-6">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="javascript:void(0)" class="btn btn-sm btn-light" id="third_step_previoud"><i
                                        class="bi bi-arrow-left-short"></i>Previous</a>
                                <a href="{{ route('leads.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                                <button type="submit" class="btn btn-sm btn-dark">Save</button>
                            </div>
                        </div>
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
                <form class="vstack" method="POST" id="addForm">
                    @csrf
                    <div class="modal-body undefined">
                        <div class="vstack gap-1">
                            <input type="hidden" name="insurance" id="insurance_customer">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="name" class="form-control" placeholder="Enter Name"
                                        value="{{ old('name', $customer->name ?? '') }}">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Email</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="email" class="form-control" placeholder="Enter Email"
                                        value="{{ old('email', $customer->email ?? '') }}">
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Password</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <div class="input-group">
                                        <input type="password" name="password" class="form-control" id="password"
                                            placeholder="Enter Password" value="{{ old('password') }}">
                                        <div class="input-group-append login_button_password">
                                            <span class="input-group-text" id="password_eye_button"><i
                                                    class="bi bi-eye"></i></span>
                                        </div>
                                    </div>
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Confirm Password</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <div class="input-group">
                                        <input type="password" name="confirm_password" class="form-control"
                                            placeholder="Enter Confirm Password" id="confirm_password"
                                            value="{{ old('confirm_password') }}">
                                        <div class="input-group-append confirm_button_password">
                                            <span class="input-group-text" id="confirm_eye_button"><i
                                                    class="bi bi-eye"></i></span>
                                        </div>
                                    </div>
                                    @error('confirm_password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Phone Number</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="mobile_number" class="form-control"
                                        placeholder="Enter Phone Number"
                                        value="{{ old('mobile_number', $customer->mobile_number ?? '') }}">
                                    @error('mobile_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Gender</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <select name="gender" class="form-control" id="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" @if (old('gender', $customer->gender ?? '') == 'male') selected @endif>Male
                                        </option>
                                        <option value="female" @if (old('gender', $customer->gender ?? '') == 'female') selected @endif>Female
                                        </option>
                                    </select>
                                    @error('gender')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Birth Date</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="date" class="form-control" name="birth_date"
                                        max="{{ date('Y-m-d') }}"
                                        value="{{ old('birth_date', $customer->birth_date ?? '') }}">
                                    @error('birth_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Pan Card Number</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="pan_card_number" class="form-control"
                                        id="pan_card_number" placeholder="Enter Pan Card Number"
                                        value="{{ old('pan_card_number', $customer->pan_card_number ?? '') }}">
                                    @error('pan_card_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Aadhar Card Number</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="aadhaar_number" class="form-control" id="aadhaar_number"
                                        placeholder="Enter Aadhar Card Number"
                                        value="{{ old('aadhaar_number', $customer->aadhaar_number ?? '') }}">
                                    @error('aadhaar_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Service Preference</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="service_preference" class="form-control"
                                        id="service_preference" placeholder="Enter Service Preference"
                                        value="{{ old('service_preference', $customer->service_preference ?? '') }}">
                                    @error('service_preference')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Reference</label></div>
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
                                <div class="col-md-2"><label class="form-label mb-0">Address</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <textarea name="address" class="form-control" id="address" placeholder="Enter Address">{{ old('address', $customer->address ?? '') }}</textarea>
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
                                        placeholder="Enter Pin Code"
                                        value="{{ old('pin_code', $customer->pin_code ?? '') }}">
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

            // $("#country").select2({
            //     dropdownParent: $("#depositLiquidityModal")
            // });
            getCustomer();
            getCustomerDetail();
        })
        $('.openAddCustomerDataForm').on('click', function(e) {
            $('#depositLiquidityModal').modal('show')
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
        function getCustomer() {
            var type = $('#invest_type').val();
            var customer = "{{$lead->customer_id}}";
            $.ajax({
                method: 'get',
                url: "{{ route('get-department-customer') }}",
                data: {
                    type: type,
                },
                success: function(res) {
                    html = "<option value=''>Select Customer</option>"
                    $.each(res.data, function(i, v) {
                        var select = "";
                        if(customer == v.id){
                            select = "selected";
                        }
                        html += "<option value=" + v.id +" " +select+">" + v.name + "</option>";
                    })
                    $('#customer_id').html("")
                    $('#customer_id').html(html)
                    $('#customer_id').select2();
                    $('#insurance_customer').val(type);
                }
            })

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
                $('#investement_field_label').show();
                $('#investement_field_form').show();
                $('#general_insurance_div').hide();
                $('#travel_div').hide();
                $('#generalRadio').hide();
                $('.insurance_label').show();
                $('.first_step_div').hide()
                $('#second_step_div').show()
                document.getElementById('pms_type').checked = true;
            } else if (type == 'general insurance') {
                $('#type_of_investments').hide();
                $('#general_insurance_div').show();
                $('#travel_div').hide();
                $('#investmentRadio').hide();
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
                $('#investement_field_label').hide();
                $('#investement_field_form').hide();
                $('#generalRadio').hide();
                $('.insurance_label').hide();
                $('.first_step_div').hide()
            }
        });

        $('#pms_type').on('click', function() {
            $('#type_of_investments').show();
            $('#mf_lumsum').hide();
            $('#mf_sip').hide();
            $('#mf_installment').hide();
            $('#fd_interest').hide();
        });

        $('#mf_type').on('click', function() {
            $('#type_of_investments').show();
            $('#mf_lumsum').show();
            $('#mf_sip').show();
            $('#mf_installment').show();
            $('#fd_interest').hide();
        });

        $('#fd_type').on('click', function() {
            $('#type_of_investments').show();
            $('#mf_lumsum').hide();
            $('#mf_sip').hide();
            $('#mf_installment').hide();
            $('#fd_interest').show();
        });

        $('#bond_type').on('click', function() {
            $('#type_of_investments').show();
            $('#mf_lumsum').hide();
            $('#mf_sip').hide();
            $('#mf_installment').hide();
            $('#fd_interest').show();
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
            $.ajax({
                url: "{{ route('customer.add') }}",
                type: 'POST',
                data: $('#addForm').serialize(),
                success: function(data) {
                    var html = '<option value="' + data.data.id + '" selected>"' + data.data.name +
                    '"</option>';
                    $('#customer_id').append(html);
                    $('#depositLiquidityModal').modal('hide')
                    $('#addForm').reset()
                    toastr.success(data.message);
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message);
                }
            });
        }
        $('#previous_second_step_button').click(function(e) {
            $('#second_step_div').hide()
            $('.first_step_div').show()
        })
        $('#next_second_step_button').click(function(e) {
            $('#second_step_div').hide()
            $('#third_step_div').show();
            $('#assigned_to').select2()
        })
        $('#third_step_previoud').click(function(e) {
            $('#second_step_div').show()
            $('#third_step_div').hide();
        })
        $('.investment_field').on('click', function(e) {
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
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm " id="remove_add_child_option" onclick="removeRow(${i})" data-id="${i}"><i class="bi bi-trash"></i></a>
                </div>
                </div>`;

            $('#travel_child_detail').append(html)
        })

        function removeRow(id) {
            $('#add_child_option_' + id).remove();
        }

        function getCustomerDetail(){
            var custId = $('#customer_id').val();
            if(custId == ""){
                custId = "{{$lead->customer_id}}";
            }
            $.ajax({
                url: "{{ route('customer.get') }}",
                type: 'get',
                data: {
                    id: custId,
                },
                success: function(data) {
                    var res = data.data;
                    $('#customer_email').html(res.email);
                    $('#customer_name').html(res.name);
                    $('#customer_code').html(res.customer_id);
                    var html = res.address + ",<br/>" + res.city_detail.name + ", " + res.state_detail
                        .name + ", <br />" + res.country_detail.name + " - " + res.pin_code;
                    $('#customer_address').html(html);
                    $('#customer_mobile').html(res.mobile_number);
                    $('#customer_gender').html(res.gender);

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
                $('#password_eye_button').html('<i class="bi bi-eye-slash"></i>');
            } else {
                input.attr("type", "password");
                $('#password_eye_button').html('<i class="bi bi-eye"></i>');
            }
        });
        $(".confirm_button_password").click(function() {
            var input = $("#confirm_password");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
                $('#confirm_eye_button').html('<i class="bi bi-eye-slash"></i>');
            } else {
                input.attr("type", "password");
                $('#confirm_eye_button').html('<i class="bi bi-eye"></i>');
            }
        });
        $('.policy_type').on('click',function(e){
            var policy_type = $(this).val();
            $('#corporate_detail_div').hide();
            if(policy_type == "corporate"){
                $('#corporate_detail_div').show();
            } 
        })
    </script>
@endsection
