@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/css/vendors/dropzone.css">
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets2/select2/css/select2.min.css">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.add.covernote.data') }}" method="post" class="g-3 " id="formDropzone"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="table-responsive custom-scrollbar">
                            @if (!isset($policy))
                                <div class="col-md-6 form-floating mt-4">
                                    <select class="form-control select2" name="policies_number" id="policies_number"
                                        value="{{ old('policies_number') }}" placeholder="">
                                        <option value="">Select Policy Number...</option>
                                    </select>
                                    <label for="SubCategory" class="form-label">Policy Number *</label>
                                    @if ($errors->has('policies_number'))
                                        <span class="text-danger">{{ $errors->first('policies_number') }}</span>
                                    @endif
                                </div>
                            @endif
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <td>Covernote No</td>
                                        <td id="policy_covernote_number">{{ $policy->covernote_no ?? '' }}</td>
                                        <td>Policy No</td>
                                        <td id="policy_no">{{ $policy->policy_no ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Customer</td>

                                        <td id="policy_customer_name">
                                            @foreach ($customers as $customer)
                                                @if (isset($policy->customer) && $customer->id == $policy->customer)
                                                    {{ $customer->name }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>Insurance Company</td>
                                        <td id="insurane_company_name">
                                            @if (!blank($companies))
                                                @foreach ($companies as $company)
                                                    @if (isset($policy->company) && $company->id == $policy->company)
                                                        {{ $company->name }}
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Category</td>
                                        <td id="category_name">
                                            @if (!blank($categories))
                                                @foreach ($categories as $category)
                                                    @if (isset($policy->category) && $category->id == $policy->category)
                                                        {{ $category->name }}
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>Sub Category</td>
                                        <td id="sub_category_name">
                                            @if (!blank($sub_categories))
                                                @foreach ($sub_categories as $sub_cat)
                                                    @if (isset($policy->sub_category) && $sub_cat->id == $policy->sub_category)
                                                        {{ $sub_cat->name }}
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Is Individual?</td>
                                        <td id="policy_is_individual">
                                            @if ($policy->policy_type ?? '' == 1)
                                                Yes
                                            @endif
                                        </td>
                                        <td>Policy Individual Rate</td>
                                        <td id="policy_individual_rate">
                                            {{ $policy->policy_individual_rate ?? '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>IDV Amount ( <i class="fa fa-inr"></i> )</td>
                                        <td id="idv_amount">{{ $policy->idv_amount ?? 0 }}</td>
                                    </tr>

                                    <tr>
                                        <td>Gross Premium Amount ( <i class="fa fa-inr"></i> )</td>
                                        <td id="gross_premium_amount">{{ $policy->gross_premium_amount ?? 0 }}</td>
                                        <td>OD Premium Amount ( <i class="fa fa-inr"></i> )</td>
                                        <td id="od_amount">{{ $policy->od ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Net Premium Amount ( <i class="fa fa-inr"></i> )</td>
                                        <td id="net_premium_amount">{{ $policy->net_premium_amount ?? 0 }}</td>
                                        <td>Risk Start Date</td>
                                        <td id="risk_start_date">{{ $policy->risk_start_date ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Risk End Date</td>
                                        <td id="risk_end_date">{{ $policy->risk_end_date ?? '' }}</td>
                                        <td>Business Type</td>
                                        <td id="business_type_div">
                                            @if (isset($policy->insurance_type) && $policy->insurance_type == 1)
                                                @if ($policy->business_type == 1)
                                                    New
                                                @elseif ($policy->business_type == 2)
                                                    Renewal
                                                @elseif ($policy->business_type == 3)
                                                    Rollover
                                                @elseif ($policy->business_type == 4)
                                                    Used
                                                @endif
                                            @else
                                                @if (isset($policy->insurance_type) && $policy->business_type == 1)
                                                    New
                                                @elseif (isset($policy->insurance_type) && $policy->business_type == 2)
                                                    Renewal
                                                @elseif (isset($policy->insurance_type) && $policy->business_type == 3)
                                                    Portability
                                                @endif

                                            @endif

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Sourcing Agent</td>
                                        <td id="sourcing_agent_div">
                                            @if (!blank($sourcing_agents))
                                                @foreach ($sourcing_agents as $agent)
                                                    @if (isset($policy->agent) && $agent->id == $policy->agent)
                                                        {{ $agent->name }}
                                                    @endif
                                                @endforeach
                                            @endif

                                        </td>
                                    </tr>
                                    <tr class="online_payment_tr @if (isset($policy->payment_type) && $policy->payment_type == 3) '' @else d-none @endif">
                                        <td>Transaction No</td>
                                        <td id="transaction_no_td">
                                            {{ $policy->transaction_no ?? '' }}
                                        </td>
                                    </tr>
                                    <tr class="cheque_payment_tr @if (isset($policy->payment_type) && $policy->payment_type == 2) '' @else d-none @endif">
                                        <td>Cheque No</td>
                                        <td id="cheque_no_td">
                                            {{ $policy->cheque_no ?? '' }}
                                        </td>
                                    </tr>
                                    <tr class="cheque_payment_tr @if (isset($policy->payment_type) && $policy->payment_type == 2) '' @else d-none @endif">
                                        <td>Cheque Date</td>
                                        <td id="cheque_date_td">
                                            {{ $policy->cheque_date ?? '' }}
                                        </td>
                                    </tr>
                                    <tr class="cheque_payment_tr @if (isset($policy->payment_type) && $policy->payment_type == 2) '' @else d-none @endif">
                                        <td>Bank Name</td>
                                        <td id="bank_name_td">
                                            {{ $policy->bank_name ?? '' }}
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        {{-- <div class="col-md-6 form-floating mt-4">
                        <select class="form-control" name="insurance_type" id="InsuranceType" placeholder="">
                            <option value="1">Motor Insurance Policy</option>
                            <option value="2">Health Insurance Policy</option>
                        </select>
                        <label for="InsuranceType" class="form-label">Insurance Type *</label>
                        @if ($errors->has('insurance_type'))
                        <span class="text-danger">{{ $errors->first('insurance_type') }}</span>
                        @endif
                    </div> --}}
                        <input type="hidden" name="insurance_type" value="1">
                        {{-- <div class="col-md-6 form-floating mt-4">
                        <select class="form-control" name="business_source" id="BusinessSource" placeholder="">
                            <option value="0">Select Business Source...</option>
                            @foreach ($business_source as $source)
                            <?php $cust = DB::table('customers')
                                ->where('id', $source->customer)
                                ->first(); ?>
                            <option value="{{$source->id}}" @if (old(''))>{{$cust->name}}</option>
                            @endforeach
                        </select>
                        <label for="BusinessSource" class="form-label">Business Source</label>
                        @if ($errors->has('business_source'))
                        <span class="text-danger">{{ $errors->first('business_source') }}</span>
                        @endif
                    </div> --}}
                        <div class="col-md-6 form-floating mt-4 motor">
                            <select class="form-control" name="category" id="Category" placeholder="">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        @if (isset($policy->category)) @if ($policy->category == $category->id) selected @endif
                                        @endif>{{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="Category" class="form-label">Category *</label>
                            @if ($errors->has('category'))
                                <span class="text-danger">{{ $errors->first('category') }}</span>
                            @endif

                        </div>
                        <div class="col-md-6 form-floating mt-4 motor">
                            <select class="form-control" name="subcategory" id="SubCategory"
                                value="{{ old('subcategory') }}" placeholder="">
                                <option value="0">Select Sub Category...</option>
                                @foreach ($sub_categories as $sub_category)
                                    <option value="{{ $sub_category->id }}"
                                        @if ($policy->sub_category ?? '' == $sub_category->id) selected @endif>{{ $sub_category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="SubCategory" class="form-label">SubCategory *</label>
                            @if ($errors->has('subcategory'))
                                <span class="text-danger">{{ $errors->first('subcategory') }}</span>
                            @endif

                            <span class="text-danger subcategory">{{ $errors->first('subcategory') }}</span>
                        </div>
                        <div class="col-md-6 mt-4 PolicyType Policy motor" style="display:none;">
                            <label for="PolicyType" class="form-label me-3">Policy Type *</label>
                            <input type="radio" name="policy_type" value="1" id="individual"
                                class="policy_type policy" @if (isset($policy->policy_type) && $policy->policy_type == 1) checked @endif><label
                                class="mx-2" for="individual"> Individual
                            </label>
                            <input type="radio" name="policy_type" value="2" id="corporate"
                                class="policy_type policy" @if (isset($policy->policy_type) && $policy->policy_type == 2) checked @endif><label
                                class="mx-2" for="corporate"> Corporate
                            </label>
                            @if ($errors->has('policy_type'))
                                <span class="text-danger">{{ $errors->first('policy_type') }}</span>
                            @endif

                            <span class="text-danger policy_type"></span>
                        </div>
                        <div class="col-md-6 form-floating mt-4 PolicyIndividualRate Policy motor" style="display:none;">
                            <input type="text" class="form-control" name="policy_individual_rate"
                                id="PolicyIndividualRate" value="{{ $policy->policy_individual_rate ?? '' }}"
                                placeholder="">
                            <label for="PolicyIndividualRate" class="form-label">Policy Individual Rate *</label>
                            @if ($errors->has('policy_individual_rate'))
                                <span class="text-danger">{{ $errors->first('policy_individual_rate') }}</span>
                            @endif

                            <span class="text-danger individual_rate"></span>
                        </div>
                        <div class="col-md-6 form-floating mt-4">
                            <select class="form-control" name="insurance_company" id="InsuranceCompany"
                                value="{{ old('insurance_company') }}" placeholder="">
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}"
                                        @if (isset($policy->company) && $company->id == $policy->company) selected @endif>
                                        {{ $policy->insurance_company ?? '' }}{{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="InsuranceCompany" class="form-label">Insurance Company *</label>
                            @if ($errors->has('insurance_company'))
                                <span class="text-danger">{{ $errors->first('insurance_company') }}</span>
                            @endif

                        </div>
                        <div class="col-md-6 form-floating mt-4 health" style="display:none;">
                            <select class="form-control" name="health_category" id="HealthCategory" placeholder="">
                                <option value="1" @if (old('health_category') == 1) selected @endif>Base</option>
                                <option value="2" @if (old('health_category') == 2) selected @endif>Personal
                                    Accident
                                </option>
                                <option value="3" @if (old('health_category') == 3) selected @endif>Super Topup
                                </option>
                            </select>
                            <label for="HealthCategory" class="form-label">Category *</label>
                            @if ($errors->has('health_category'))
                                <span class="text-danger">{{ $errors->first('health_category') }}</span>
                            @endif

                            <span class="text-danger health_category"></span>
                        </div>
                        <div class="col-md-6 form-floating mt-4 health" style="display:none;">
                            <select class="form-control" name="health_plan" id="HealthPlan" placeholder="">
                                @if (!blank($plans))
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}"
                                            @if (old('health_plan') == $plan->id) selected @endif>
                                            {{ $plan->name }}
                                        </option>
                                    @endforeach
                                @endif

                            </select>
                            <label for="HealthPlan" class="form-label">Health Plan *</label>
                            @if ($errors->has('health_plan'))
                                <span class="text-danger">{{ $errors->first('health_plan') }}</span>
                            @endif

                            <span class="text-danger health_plan"></span>
                        </div>
                        <div class="col-md-6 form-floating mt-4 motor">
                            <input type="text" class="form-control" name="covernote_no" id="covernote_no"
                                value="{{ old('covernote_no') }}" placeholder="">
                            <label for="covernote_no" class="form-label">Covernote No</label>
                            @if ($errors->has('covernote_no'))
                                <span class="text-danger">{{ $errors->first('covernote_no') }}</span>
                            @endif

                        </div>
                        <div class="col-md-6 mt-4">
                            <div class="form-row row">
                                <div class="form-group col-md-4 w-100">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="w-100 form-floating">
                                            <select class="form-control select2" name="customer" id="Customer"
                                                value="{{ old('customer') }}" placeholder="">
                                                <option value="0">Select Customer...</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        @if (isset($policy->customer)) @if ($policy->customer == $customer->id) selected @endif
                                                        @endif>
                                                        {{ $customer->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="Customer" class="form-label">Customer *</label>
                                            @if ($errors->has('customer'))
                                                <span class="text-danger">{{ $errors->first('customer') }}</span>
                                            @endif

                                            <span class="text-danger customer"></span>
                                        </div>
                                        <div class="h-100">
                                            <button type="button" class="btn gc_btn" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdrop_business"
                                                style="padding: 4px 17px; font-size: 30px;">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-6 form-floating mt-4">
                                                        <select class="form-control select2" name="customer" id="Customer" value="{{ old('customer') }}" placeholder="">
                                                            <option value="0">Select Customer...</option>
                                                            @foreach ($customers as $customer)
    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
    @endforeach
                                                        </select>
                                                        <label for="Customer" class="form-label">Customer *</label>
                                                        @if ($errors->has('customer'))
                                                            <span class="text-danger">{{ $errors->first('customer') }}</span>
                                                        @endif
                                                        <span class="text-danger customer"></span>
                                                    </div> -->
                        <div class="col-md-6 form-floating mt-4 motor">
                            <input type="text" class="form-control" name="vehicle_model" id="vehicleModel"
                                value="{{ $policy->vehicle_model ?? '' }}" placeholder="">
                            <label for="vehicleModel" class="form-label">Vehicle Model *</label>
                            @if ($errors->has('vehicle_model'))
                                <span class="text-danger">{{ $errors->first('vehicle_model') }}</span>
                            @endif

                            <span class="text-danger vehicle_model"></span>
                        </div>
                        <div class="col-md-6 form-floating mt-4 motor">
                            <input type="text" class="form-control" name="vehicle_chassis_no" id="vehicleChassisNo"
                                value="{{ $policy->vehicle_chassis_no ?? '' }}" placeholder="">
                            <label for="vehicleChassisNo" class="form-label">Vehicle Chassis No *</label>
                            @if ($errors->has('vehicle_chassis_no'))
                                <span class="text-danger">{{ $errors->first('vehicle_chassis_no') }}</span>
                            @endif

                            <span class="text-danger vehicle_chassis_no"></span>
                        </div>
                        <div class="col-md-6 form-floating mt-4 motor">
                            <input type="text" class="form-control" name="vehicle_make" id="vehicleMake"
                                value="{{ $policy->vehicle_make ?? '' }}" placeholder="">
                            <label for="vehicleMake" class="form-label">Vehicle make *</label>
                            @if ($errors->has('vehicle_make'))
                                <span class="text-danger">{{ $errors->first('vehicle_make') }}</span>
                            @endif

                            <span class="text-danger vehicle_make"></span>
                        </div>
                        <div class="col-md-6 form-floating mt-4 motor">
                            <input type="text" class="form-control" name="vehicle_registration_no"
                                id="vehicleRegistrationNo" value="{{ $policy->vehicle_registration_no ?? '' }}"
                                placeholder="">
                            <label for="vehicleRegistrationNo" class="form-label">Vehicle Registration No </label>
                            @if ($errors->has('vehicle_registration_no'))
                                <span class="text-danger">{{ $errors->first('vehicle_registration_no') }}</span>
                            @endif

                            <span class="text-danger vehicle_registration_no"></span>
                        </div>
                        <div class="col-md-6 form-floating mt-4 motor">
                            <input type="number" class="form-control" name="year_of_manufacture" id="yearOfManufacture"
                                value="{{ $policy->year_of_manufacture ?? '' }}" placeholder="">
                            <label for="yearOfManufacture" class="form-label"> Year Of Manufacture*</label>
                            @if ($errors->has('year_of_manufacture'))
                                <span class="text-danger">{{ $errors->first('year_of_manufacture') }}</span>
                            @endif

                            <span class="text-danger year_of_manufacture"></span>
                        </div>
                        <div class="col-md-6 form-floating mt-4 motor">
                            <input type="number" class="form-control" min="0" name="idv_amount" id="IdvAmount"
                                value="{{ old('idv_amount') }}" placeholder="" />
                            <label for="IdvAmount" class="form-label">Idv Amount *</label>
                            @if ($errors->has('idv_amount'))
                                <span class="text-danger">{{ $errors->first('idv_amount') }}</span>
                            @endif

                            <span class="text-danger idv_amount"></span>
                        </div>
                        <div class="params motor row">

                        </div>
                        <div class="col-md-6 form-floating mt-4 health" style="display:none;">
                            <input type="number" class="form-control" min="0" name="sum_insured_amount"
                                id="SumInsuredAmount" value="{{ old('sum_insured_amount') }}" placeholder="" />
                            <label for="SumInsuredAmount" class="form-label">Sum Insured Amount *</label>
                            @if ($errors->has('sum_insured_amount'))
                                <span class="text-danger">{{ $errors->first('sum_insured_amount') }}</span>
                            @endif

                            <span class="text-danger sum_insured_amount"></span>
                        </div>
                        <div class="col-md-6 form-floating mt-4">
                            <input type="number" class="form-control" min="0" name="net_premium_amount"
                                id="NetPremiumAmount" value="{{ old('net_premium_amount') }}" placeholder="" />
                            <label for="NetPremiumAmount" class="form-label">Net Premium Amount *</label>
                            @if ($errors->has('net_premium_amount'))
                                <span class="text-danger">{{ $errors->first('net_premium_amount') }}</span>
                            @endif

                            <span class="text-danger net_premium_amount"></span>
                        </div>
                        <div class="col-md-6 form-floating mt-4">
                            <input type="number" class="form-control" min="0" name="gross_premium_amount"
                                id="GrossPremiumAmount" value="{{ old('gross_premium_amount') }}" placeholder="" />
                            <label for="GrossPremiumAmount" class="form-label">Gross Premium Amount *</label>
                            @if ($errors->has('gross_premium_amount'))
                                <span class="text-danger">{{ $errors->first('gross_premium_amount') }}</span>
                            @endif

                            <span class="text-danger gross_premium_amount"></span>
                        </div>
                        <div class="col-md-6 form-floating mt-4 motor">
                            <select class="form-control" name="business_type" id="BusinessType"
                                value="{{ old('business_type') }}" placeholder="">
                                <option value="1">New</option>
                                <option value="2" selected>Renewal</option>
                                <option value="3">Rollover</option>
                                <option value="4">Used</option>
                            </select>
                            <label for="BusinessType" class="form-label">Business Type *</label>
                            @if ($errors->has('business_type'))
                                <span class="text-danger">{{ $errors->first('business_type') }}</span>
                            @endif

                        </div>
                        <div
                            class="col-md-6 form-floating mt-4 motor business_type @if (old('business_type') == 1 || old('business_type') == 4) d-none @endif ">
                            <input type="text" class="form-control" name="pyp_no" id="PypNo"
                                value="{{ $policy->policy_no ?? old('pyp_no') }}" placeholder="" />
                            <label for="PypNo" class="form-label">Pyp No *</label>
                            @if ($errors->has('pyp_no'))
                                <span class="text-danger">The Pyp No field is required.</span>
                            @endif

                        </div>
                        <div
                            class="col-md-6 form-floating mt-4 motor business_type @if (old('business_type') == 1 || old('business_type') == 4) d-none @endif">
                            <input type="text" class="form-control" name="pyp_insurance_company"
                                id="PypInsuranceCompany"
                                value="{{ $policy->pyp_insurance_company ?? old('pyp_insurance_company') }}"
                                placeholder="" />
                            <label for="PypInsuranceCompany" class="form-label">Pyp Insurance Company *</label>
                            @if ($errors->has('pyp_insurance_company'))
                                <span class="text-danger">The Pyp Insurance Company field is required.</span>
                            @endif

                        </div>
                        <div
                            class="col-md-6 form-floating mt-4 motor business_type @if (old('business_type')) @if (old('business_type') == 1 || old('business_type') == 4) d-none @endif
@else
d-none @endif">
                            <input type="date" class="form-control" name="pyp_expiry_date" id="PypExpiryDate"
                                value="{{ old('pyp_expiry_date') }}" placeholder="" />
                            <label for="PypExpiryDate" class="form-label">Pyp Expiry Date *</label>
                            @if ($errors->has('pyp_expiry_date'))
                                <span class="text-danger">The Pyp Expiry Date field is required.</span>
                            @endif

                        </div>
                        <div class="col-md-6 form-floating mt-4">
                            <select class="form-control" name="sourcing_agent" id="SourcingAgent" placeholder="">
                                <option value="0">Select Sourcing Agent...</option>
                                @foreach ($sourcing_agents as $agent)
                                    <option value="{{ $agent->id }}"
                                        @if (isset($policy->agent)) @if ($policy->agent == $agent->id)
                                selected @endif
                                        @endif>{{ $agent->name }}</option>
                                @endforeach
                            </select>
                            <label for="SourcingAgent" class="form-label">Sourcing Agent *</label>
                            @if ($errors->has('sourcing_agent'))
                                <span class="text-danger">{{ $errors->first('sourcing_agent') }}</span>
                            @endif

                            <span class="text-danger sourcing_agent"></span>
                        </div>
                        <div class="col-md-12">
                            <label for="RiskStartDate" class="form-label form-date-label">Risk Start Date & Risk End Date
                            </label>
                            <div class="col-md-12 form-floating">
                                <div class="custom-date-div-outer-main">
                                    <div class="custom-date-div-outer justify-content-between d-flex">
                                        <span class="custom-date-span w-100">
                                            <input type="date"
                                                class="form-control custom-start-date-div custom-date-div"
                                                name="risk_start_date" id="RiskStartDate"
                                                value="{{ old('risk_start_date') ? old('risk_start_date') : date('Y-m-d') }}"
                                                placeholder="" />
                                        </span>
                                        <span class="custom-date-to mt-2">To</span>
                                        <span class="custom-date-span w-100">
                                            <input type="date" class="form-control custom-end-date-div custom-date-div"
                                                name="risk_end_date" id="RiskEndDate"
                                                value="{{ old('risk_end_date') ? old('risk_end_date') : date('Y-m-d', strtotime('+1 year -1 day')) }}"
                                                placeholder="" readonly />
                                        </span>
                                    </div>
                                </div>
                                @if ($errors->has('risk_start_date'))
                                    <span class="text-danger">{{ $errors->first('risk_start_date') }}</span>
                                @endif

                                <span class="text-danger risk_start_date"></span>
                            </div>
                        </div>
                        <!--<div class="col-md-6 form-floating mt-4">-->
                        <!--    <input type="date" class="form-control" name="risk_start_date" id="RiskStartDate" value="{{ old('risk_start_date') }}" placeholder="" />-->
                        <!--    <label for="RiskStartDate" class="form-label">Risk Start Date *</label>-->
                        <!--    @if ($errors->has('risk_start_date'))-->
                        <!--        <span class="text-danger">{{ $errors->first('risk_start_date') }}</span>-->
                        <!--    @endif-->
                        <!--    <span class="text-danger risk_start_date"></span>-->
                        <!--</div>-->
                        <!--<div class="col-md-6 form-floating mt-4">-->
                        <!--    <input type="date" class="form-control" name="risk_end_date" id="RiskEndDate" value="{{ old('risk_end_date') }}" placeholder="" />-->
                        <!--    <label for="RiskEndDate" class="form-label">Risk End Date *</label>-->
                        <!--    @if ($errors->has('risk_end_date'))-->
                        <!--        <span class="text-danger">{{ $errors->first('risk_end_date') }}</span>-->
                        <!--    @endif-->
                        <!--    <span class="text-danger risk_end_date"></span>-->
                        <!--</div>-->
                        <div class="col-md-6 form-floating mt-4">
                            <select class="form-control" name="payment_type" id="PaymentType" placeholder="">
                                <option value="0">Select Payment Option</option>
                                <option value="1" @if (old('payment_type') == 1) selected @endif>Cash</option>
                                <option value="2" @if (old('payment_type') == 2) selected @endif>Cheque</option>
                                <option value="3" @if (old('payment_type') == 3) selected @endif>Online</option>
                            </select>
                            <label for="PaymentType" class="form-label">Payment Type</label>
                            @if ($errors->has('payment_type'))
                                <span class="text-danger">{{ $errors->first('payment_type') }}</span>
                            @endif

                        </div>
                        <div class="row cheque" @if (old('payment_type') == 2) @else style="display:none;" @endif>
                            <div class="col-md-6 form-floating mt-4">
                                <input type="text" class="form-control" name="cheque_no" id="chequeNo"
                                    value="{{ old('cheque_no') }}" placeholder="" />
                                <label for="chequeNo" class="form-label">Cheque No *</label>
                                @if ($errors->has('cheque_no'))
                                    <span class="text-danger">{{ $errors->first('cheque_no') }}</span>
                                @endif

                            </div>
                            <div class="col-md-6 form-floating mt-4">
                                <input type="date" class="form-control" name="cheque_date" id="cheque_date"
                                    value="{{ old('cheque_date') }}" placeholder="" />
                                <label for="cheque_date" class="form-label">Cheque Date *</label>
                                @if ($errors->has('cheque_date'))
                                    <span class="text-danger">{{ $errors->first('cheque_date') }}</span>
                                @endif

                            </div>
                            <div class="col-md-6 form-floating mt-4">
                                <input type="text" class="form-control" min="0" name="bank_name"
                                    id="BankName" value="{{ old('bank_name') }}" placeholder="" />
                                <label for="BankName" class="form-label">Bank Name *</label>
                                @if ($errors->has('bank_name'))
                                    <span class="text-danger">{{ $errors->first('bank_name') }}</span>
                                @endif

                            </div>
                        </div>
                        <div class="row online" @if (old('payment_type') == 3) @else style="display:none;" @endif>
                            <div class="col-md-6 form-floating mt-4">
                                <input type="text" class="form-control" name="transaction_no" id="TransactionNo"
                                    value="{{ old('transaction_no') }}" placeholder="" />
                                <label for="TransactionNo" class="form-label">Transaction No *</label>
                                @if ($errors->has('transaction_no'))
                                    <span class="text-danger">{{ $errors->first('transaction_no') }}</span>
                                @endif

                            </div>
                        </div>
                        <div class="form-group mb-4 mt-4">
                            <label class="form-label opacity-75 fw-medium" for="formImage">Policy Attachments</label>
                            <div class="dropzone-drag-area dropzone dropzone-secondary" id="previews">
                                <div class="dz-message needsclick text-muted opacity-50" data-dz-message>
                                    <i class="icon-cloud-up"></i><br>
                                    <span>Drag file here to upload</span>
                                </div>
                                <div class="d-flex" id="previews1"></div>
                                <div class="d-none" id="dzPreviewContainer">
                                    <div class="dz-file-preview">
                                        <div class="dz-photo">
                                            <img class="dz-thumbnail" data-dz-thumbnail>
                                        </div>
                                        <div class="dz-name"></div>
                                        <button class="dz-delete border-0 p-0" type="button" data-dz-remove>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="times">
                                                <path fill="#FFFFFF"
                                                    d="M13.41,12l4.3-4.29a1,1,0,1,0-1.42-1.42L12,10.59,7.71,6.29A1,1,0,0,0,6.29,7.71L10.59,12l-4.3,4.29a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0L12,13.41l4.29,4.3a1,1,0,0,0,1.42,0,1,1,0,0,0,0-1.42Z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback fw-bold">Please upload an image.</div>
                        </div>
                        <div class="col-md-12 form-floating mt-4">
                            <textarea name="remarks" class="form-control" id="Remarks" placeholder="">{{ old('remarks') }}</textarea>
                            <label for="Remarks" class="form-label">Remarks</label>
                            @if ($errors->has('remarks'))
                                <span class="text-danger">{{ $errors->first('remarks') }}</span>
                            @endif

                        </div>
                    </div>
                    <div class="col-12">
                        <button type="button" id="formSubmit" class="formSubmit btn btn-primary mt-3">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="staticBackdrop_business" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered  modal-lg">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="staticBackdropLabel">Add New Customer</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12">

                            <form id="customerForm" name="customerForm" action="" method="POST">

                                @csrf

                                <div class="row">

                                    <div class="col-md-12 form-floating mt-4">

                                        <input type="text" class="form-control" name="name" id="customerName"
                                            value="{{ old('customer_name') }}" placeholder="" required>

                                        <label for="customerName" class="form-label">Customer Name *</label>

                                        @if ($errors->has('customer_name'))

                                            <span class="text-danger">{{ $errors->first('customer_name') }}</span>

                                        @endif


                                        <span class="text-danger customer_nameError"></span>

                                    </div>

                                    <div class="col-md-12 form-floating mt-4">

                                        <input type="email" class="form-control" name="email" id="customerEmail"
                                            value="{{ old('customer_email') }}" placeholder="">

                                        <label for="customerEmail" class="form-label">Customer Email</label>

                                        @if ($errors->has('customer_email'))

                                            <span class="text-danger">{{ $errors->first('customer_email') }}</span>

                                        @endif


                                        <span class="text-danger customer_emailError"></span>

                                    </div>

                                    <div class="col-md-12 form-floating mt-4">

                                        <input type="text" class="form-control" name="phone" id="customerPhone"
                                            value="{{ old('customer_phone') }}" placeholder="" required>

                                        <label for="customerPhone" class="form-label">Customer Phone Number *</label>

                                        @if ($errors->has('customer_phone'))

                                            <span class="text-danger">{{ $errors->first('customer_phone') }}</span>

                                        @endif


                                        <span class="text-danger customer_phoneError"></span>

                                    </div>

                                </div>

                                <button type="submit" class="btn btn-primary mt-3" id="saveCustomerBtn">Submit</button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/min/dropzone.min.js"></script>
    <script src="{{ url('/') }}/assets2/select2/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#Customer').select2();
            $('#policies_number').select2({
                placeholder: 'Search for a Policy Number',
                allowClear: true,
                ajax: {
                    url: '{{ route('search.policy.number') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term,
                        }
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(item => ({
                                id: item.id,
                                text: item.policy_no
                            }))
                        };
                    },
                    cache: true
                },
            });
            $(document).on('click', '#saveCustomerBtn', function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.add.customer.data') }}",
                    data: $('#customerForm').serialize() + "&modal=1",
                    success: function(data) {
                        var newOption = new Option(data.name, data.id, true, true);
                        $('#Customer').append(newOption).trigger('change');
                        $('#staticBackdrop_business').modal('hide');
                    },
                    error: function(data) {}
                });
            });
            $(document).on('change', '#InsuranceType', function() {
                var insurance_type = $(this).val();
                if (insurance_type == 2) {
                    $('.motor').css('display', 'none');
                    $('.health').css('display', 'inline-block');
                } else {
                    $('.health').css('display', 'none');
                    $('.motor').css('display', 'inline-block');
                }
            });
            $(document).on('change', '#GcvType', function() {
                var gcv_type = $(this).val();
                var cat_id = $(this).data('id');
                $.ajax({
                    type: 'GET',
                    url: '{{ route('get_gcv_parameters') }}',
                    data: {
                        'id': cat_id,
                        'gcv_type': gcv_type
                    },
                    success: function(data) {
                        if (data == '') {
                            $('.GCVCarrier').css('display', 'none');
                        } else {
                            $('.GCVCarrier').css('display', 'block');
                            $('.GCVCarrier').html(data);
                        }
                    },
                    error: function(data) {
                        // console.log(data);
                    }
                });
            });
            $(document).on('change', '#PcvType', function() {
                var gcv_type = $(this).val();
                var cat_id = $(this).data('id');
                $.ajax({
                    type: 'GET',
                    url: '{{ route('get_pcv_parameters') }}',
                    data: {
                        'id': cat_id,
                        'pcv_type': gcv_type
                    },
                    success: function(data) {
                        if (data == '') {
                            $('.PCVCarrier').css('display', 'none');
                        } else {
                            $('.PCVCarrier').css('display', 'contents');
                            $('.PCVCarrier').html(data);
                        }
                    },
                    error: function(data) {
                        // console.log(data);
                    }
                });
            });
            $(document).on('change', '#InsuranceType', function() {
                var insurance_type = $(this).val();
                if (insurance_type == 1) {
                    $('.health-insurance').addClass('d-none');
                    $('.motor-insurance').removeClass('d-none');
                } else {
                    $('.motor-insurance').addClass('d-none');
                    $('.health-insurance').removeClass('d-none');
                }
            });
            $(document).on('change', '#Category', function() {
                var cat = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('get_cat_subcategory') }}',
                    data: {
                        'id': cat
                    },
                    success: function(data) {
                        $('#SubCategory').html('');
                        $('#SubCategory').append(data);
                    },
                    error: function(data) {
                        // console.log(data);
                    }
                });
            });
            $(document).on('change', '#InsuranceCompany', function() {
                var company = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('get_insurance_plan') }}',
                    data: {
                        'id': company
                    },
                    success: function(data) {
                        $('#HealthPlan').html('');
                        $('#HealthPlan').append(data);
                    },
                    error: function(data) {
                        // console.log(data);
                    }
                });
            });
            $(document).on('change', '.policy_type', function() {
                var val = $(this).val();
                if (val == 1) {
                    $('.PolicyIndividualRate').css('display', 'block');
                } else {
                    $('.PolicyIndividualRate').css('display', 'none');
                }
            });
            $(document).on('change', '#Category', function() {
                if ($(this).val() == 2) {
                    $('.Policy').css('display', 'none');
                } else {
                    $('.Policy').css('display', 'block');
                }
            });
            $(document).on('change', '#SubCategory', function() {
                var sub_cat = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('get_cat_parameters') }}',
                    data: {
                        'id': sub_cat
                    },
                    success: function(data) {
                        if ($('#Category').val() == 2) {
                            $('.Policy').css('display', 'none');
                        } else {
                            $('.Policy').css('display', 'block');
                        }
                        $('.params').html(data);
                    },
                    error: function(data) {
                        // console.log(data);
                    }
                });
            });
            $(document).on('change', '#PaymentType', function() {
                var type = $(this).val();
                if (type == 2) {
                    $('.cheque').css('display', 'flex');
                    $('.online').css('display', 'none');
                } else if (type == 3) {
                    $('.cheque').css('display', 'none');
                    $('.online').css('display', 'flex');
                } else {
                    $('.cheque').css('display', 'none');
                    $('.online').css('display', 'none');
                }
            });
        });
    </script>
    <script>
        $("#GrossPremiumAmount").change(function() {
            $("#NetPremiumAmount").val(Math.round(((($("#GrossPremiumAmount").val() * 100) / 118) * 100) / 100));
        });
        Dropzone.autoDiscover = false;

        var myDropzone = new Dropzone('#formDropzone', {
            previewTemplate: $('#dzPreviewContainer').html(),
            url: "{{ route('admin.add.covernote.data') }}",
            addRemoveLinks: true,
            dictRemoveFileConfirmation: "Are you sure?",
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 10,
            maxFiles: 10,
            acceptedFiles: "image/*,application/pdf",
            thumbnailWidth: 200,
            thumbnailHeight: 200,
            previewsContainer: "#previews1",
            timeout: 0,
            init: function() {
                // myDropzone = this;
                // when file is dragged in
                this.on('addedfile', function(file) {
                    var ext = file.name.split('.').pop();
                    $(file.previewElement).find(".dz-name").html(file.name);
                    if (ext == "pdf") {
                        $(file.previewElement).find(".dz-photo img").attr("src",
                            "{{ url('/') }}/assets/Images/pdf.png");
                    } else if (ext.indexOf("doc") != -1) {
                        $(file.previewElement).find(".dz-photo img").attr("src",
                            "{{ url('/') }}/assets/Images/docs.png");
                    } else if (ext.indexOf("xls") != -1) {
                        $(file.previewElement).find(".dz-photo img").attr("src",
                            "{{ url('/') }}/assets/Images/docs.png");
                    }
                    $('.dropzone-drag-area').removeClass('is-invalid').next('.invalid-feedback').hide();
                });
                this.on("sendingmultiple", function(data, xhr, formData) {
                    var i = 0;
                    $(".documents").each(function() {
                        formData.append('policy_document[' + i + ']', $(this)[0].files[0]);
                        i++;
                    });
                });
                $('#formSubmit').on('click', function(event) {
                    event.preventDefault();
                    var $this = $(this);

                    // show submit button spinner
                    $this.children('.spinner-border').removeClass('d-none');

                    // validate form & submit if valid
                    if ($('#formDropzone')[0].checkValidity() === false) {

                    } else {
                        if (myDropzone.getQueuedFiles().length > 0) {
                            event.preventDefault();
                            event.stopPropagation();
                            // var dr = document.querySelector("#formDropzone").dropzone;
                            // console.log(dr);

                            // if everything is ok, submit the form
                            var category = $('#Category').val();
                            var subcategory = $('#SubCategory').val();
                            var customer = $('#Customer').val();
                            var vehicle_model = $('#vehicleModel').val();
                            var vehicle_chassis_no = $('#vehicleChassisNo').val();
                            var vehicle_make = $('#vehicleMake').val();
                            var registration_no = $('#vehicleRegistrationNo').val();
                            var year_of_manufacture = $('#yearOfManufacture').val();
                            var idv_amount = $('#IdvAmount').val();
                            var net_premium = $('#NetPremiumAmount').val();
                            var gross_premium = $('#GrossPremiumAmount').val();
                            var sourcing_agent = $('#SourcingAgent').val();
                            var risk_start_date = $('#RiskStartDate').val();
                            var risk_end_date = $('#RiskEndDate').val();
                            var individual_rate = $('#PolicyIndividualRate').val();
                            var policy_type = $("input[name=policy_type]:checked").val();
                            var insurance_type = $('#InsuranceType').val();
                            var health_category = $('#HealthCategory').val();
                            var health_plan = $('#HealthPlan').val();
                            var i = 0;
                            var myButton = document.getElementById('formSubmit');
                            if (insurance_type == 1) {
                                if (subcategory == 0) {
                                    i++;
                                    $('.subcategory').html('Please select Subcategory.');
                                    hideLoader(myButton);
                                }
                                if (subcategory != 0) {
                                    if (category == 1 && policy_type == 1 && individual_rate == '') {
                                        i++;
                                        $('.individual_rate').html(
                                            'Individual Rate field is required.');
                                        hideLoader(myButton);
                                    }
                                }
                                if (vehicle_model == '') {
                                    i++;
                                    $('.vehicle_model').html('Vehicle Model field is required.');
                                    hideLoader(myButton);
                                }
                                if (vehicle_chassis_no == '') {
                                    i++;
                                    $('.vehicle_chassis_no').html(
                                        'Vehicle Chassis No field is required.');
                                    hideLoader(myButton);
                                }
                                if (vehicle_make == '') {
                                    i++;
                                    $('.vehicle_make').html('Vehicle Make field is required.');
                                    hideLoader(myButton);
                                }
                                if (registration_no == '') {
                                    i++;
                                    $('.vehicle_registration_no').html(
                                        'Vehicle Registration No field is required.');
                                    hideLoader(myButton);
                                }
                                if (year_of_manufacture == '') {
                                    i++;
                                    $('.year_of_manufacture').html(
                                        'Year Of Manufacture field is required.');
                                    hideLoader(myButton);
                                }
                                if (idv_amount == '') {
                                    i++;
                                    $('.idv_amount').html('Idv Amount field is required.');
                                    hideLoader(myButton);
                                }
                                if (net_premium == '') {
                                    i++;
                                    $('.net_premium_amount').html(
                                        'Net Premium Amount field is required.');
                                    hideLoader(myButton);
                                }
                                if (gross_premium == '') {
                                    i++;
                                    $('.gross_premium_amount').html(
                                        'Gross Premium Amount field is required.');
                                    hideLoader(myButton);
                                }
                            }
                            if (insurance_type == 2) {
                                if (health_category == 0) {
                                    i++;
                                    $('.health_category').html('Please Select health category.');
                                    hideLoader(myButton);
                                }
                                if (health_plan == 0) {
                                    i++;
                                    $('.health_plan').html('Please select health plan.');
                                    hideLoader(myButton);
                                }
                            }
                            if (sourcing_agent == 0) {
                                i++;
                                $('.sourcing_agent').html('Sourcing Agent field is required.');
                                hideLoader(myButton);
                            }
                            if (risk_start_date == '') {
                                i++;
                                $('.risk_start_date').html('Risk Start Date field is required.');
                                hideLoader(myButton);
                            }
                            if (risk_end_date == '') {
                                i++;
                                $('.risk_end_date').html('Risk End Date field is required.');
                                hideLoader(myButton);
                            }
                            if (customer == 0) {
                                i++;
                                $('.customer').html('Please select Customer.');
                                hideLoader(myButton);
                            }
                            // alert(i);
                            if (i == 0) {
                                myDropzone.processQueue();
                                showLoader(myButton);
                            }
                        } else {
                            var myButton = document.getElementById('formSubmit');
                            showLoader(myButton);
                            $('#formDropzone').submit();
                        }
                    }
                });
            },
            success: function(file, response) {
                // if(response == ''){
                top.location.href = "{{ route('admin.covernote') }}";
                // }
            }
        });
        $(document).on('change', '#BusinessType', function() {

            var business_type = $(this).val();

            if (business_type == 2 || business_type == 3) {

                $('.business_type').removeClass('d-none');

            } else {

                $('.business_type').addClass('d-none');

            }

        });
    </script>
    <script>
        $(document).ready(function() {
            $('#RiskStartDate').on('change', function() {
                var startDate = new Date($(this).val());
                var endDate = new Date(startDate.setFullYear(startDate.getFullYear() + 1));
                endDate.setDate(endDate.getDate() - 1);
                var formattedEndDate = endDate.toISOString().split('T')[0];
                $('#RiskEndDate').val(formattedEndDate);
            });
        });
        $('#policies_number').on('change', function(e) {
            var policies_number = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('get_policies_details') }}',
                data: {
                    'policies_number': policies_number
                },
                success: function(data) {
                    console.log(data);
                    $('#policy_covernote_number').html(data.covernote_no)
                    $('#policy_no').html(data.policy_no)
                    $('#policy_customer_name').html(data.customers.name)
                    $('#insurane_company_name').html(data.company.name)
                    $('#sub_category_name').html(data.category.name)
                    $('#category_name').html(data.category_detail.name)
                    if (data.insurance_type == 2) {
                        $('#sub_category_name').html(data.health_sub_category_detail.name)
                        $('#category_name').html(data.health_category_detail.name)
                    }
                    var indvidual = "";
                    if (data.policy_type == 1) {
                        indvidual = "Yes";
                    }
                    $('#policy_is_individual').html(indvidual)
                    $('#policy_individual_rate').html(data.policy_individual_rate)
                    $('#idv_amount').html(data.idv_amount)
                    $('#gross_premium_amount').html(data.gross_premium_amount)
                    $('#od_amount').html(data.od)
                    $('#net_premium_amount').html(data.net_premium_amount)
                    $('#risk_start_date').html(data.risk_start_date)
                    $('#risk_end_date').html(data.risk_end_date)
                    var businessType = "";
                    if (data.insurance_type == 1) {
                        if (data.business_type == 1) {
                            businessType = "New";
                        } else if (data.business_type == 2) {
                            businessType = "Renewal";
                        } else if (data.business_type == 3) {
                            businessType = "Rollover";
                        } else if (data.business_type == 4) {
                            businessType = "Used";
                        }
                    } else {
                        if (data.business_type == 1) {
                            businessType = "New";
                        } else if (data.business_type == 2) {
                            businessType = "Renewal";
                        } else if (data.business_type == 3) {
                            businessType = "Portability";
                        }
                    }
                    $('#business_type_div').html(businessType)
                    $('#sourcing_agent_div').html(data.agent.name)
                    $('.online_payment_tr').addClass('d-none')
                    if(data.payment_type == 3){
                        $('.online_payment_tr').removeClass('d-none')
                        $('#transaction_no_td').html(data.transaction_no)
                    }
                    $('.cheque_payment_tr').addClass('d-none')
                    if(data.payment_type == 2){
                        $('.cheque_payment_tr').removeClass('d-none')
                        $('#cheque_no_td').html(data.cheque_no)
                        $('#cheque_date_td').html(data.cheque_date)
                        $('#bank_name_td').html(data.bank_name)
                    }
                },
            });

        })
    </script>
@endsection
