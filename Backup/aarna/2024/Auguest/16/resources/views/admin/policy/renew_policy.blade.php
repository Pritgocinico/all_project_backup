@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/dropzone.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets2/select2/css/select2.min.css">
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.renew.policy.data')}}" method="post" class="g-3" id="formDropzone" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 form-floating mt-4">
                        <select class="form-control" name="insurance_type" id="InsuranceType" placeholder="">
                            <option value="1" @if($policy->insurance_type == 1) selected @endif>Motor Insurance Policy</option>
                            <option value="2" @if($policy->insurance_type == 2) selected @endif>Health Insurance Policy</option>
                        </select>
                        <label for="InsuranceType" class="form-label">Insurance Type *</label>
                        @if ($errors->has('insurance_type'))
                            <span class="text-danger">{{ $errors->first('insurance_type') }}</span>
                        @endif
                    </div>
                     <div class="col-md-6 form-floating mt-4">
                       <input type="date" class="form-control" name="created_date" id="created_date" value="{{ old('created_date') ?? now()->toDateString() }}" placeholder="" />
                        <label for="created_date" class="form-label">Created Date *</label>
                        @if ($errors->has('created_date'))
                            <span class="text-danger">{{ $errors->first('created_date') }}</span>
                        @endif
                    </div>
                    <!-- <div class="col-md-6 form-floating mt-4">
                        <select class="form-control" name="business_source" id="BusinessSource" placeholder="">
                            <option value="0">Select Business Source...</option>
                            @foreach($business_source as $source)
                                <?php $cust = DB::table('customers')->where('id',$source->customer)->first(); ?>
                                <option value="{{$source->id}}" @if($policy->business_source == $source->id) selected @endif>{{$cust->name}}</option>
                            @endforeach
                        </select>
                        <label for="BusinessSource" class="form-label">Business Source</label>
                        @if ($errors->has('business_source'))
                            <span class="text-danger">{{ $errors->first('business_source') }}</span>
                        @endif
                    </div> -->
                    <div class="col-md-6 form-floating mt-4 motor">
                        <select class="form-control" name="category" id="Category" placeholder="">
                            @foreach ($categories as $category)
                                <option value="{{$category->id}}" @if($policy->category == $category->id) selected @endif>{{$category->name}}</option>
                            @endforeach
                        </select>
                        <label for="Category" class="form-label">Category *</label>
                        @if ($errors->has('category'))
                            <span class="text-danger">{{ $errors->first('category') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4 motor">
                        <select class="form-control" name="subcategory" id="SubCategory" value="{{old('subcategory')}}" placeholder="">
                            <option value="0">Select Sub Category...</option>
                            @foreach ($sub_categories as $sub_category)
                                <option value="{{$sub_category->id}}" @if($policy->sub_category == $sub_category->id) selected @endif>{{$sub_category->name}}</option>
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
                        <input type="radio" name="policy_type" value="1" id="individual" class="policy_type policy" @if($policy->policy_type == 1 || $policy->policy_type == NULL) checked @endif><label class="mx-2" for="individual"> Individual </label>
                        <input type="radio" name="policy_type" value="2" id="corporate" class="policy_type policy" @if($policy->policy_type == 2) checked @endif><label class="mx-2" for="corporate"> Corporate </label>
                        @if ($errors->has('policy_type'))
                            <span class="text-danger">{{ $errors->first('policy_type') }}</span>
                        @endif
                        <span class="text-danger policy_type"></span>
                    </div>
                    <div class="col-md-6 form-floating mt-4 PolicyIndividualRate Policy motor">
                        <input type="text" class="form-control" name="policy_individual_rate" id="PolicyIndividualRate" value="{{$policy->policy_individual_rate}}" placeholder="">
                        <label for="PolicyIndividualRate" class="form-label">CPA Premium *</label>
                        @if ($errors->has('policy_individual_rate'))
                            <span class="text-danger">{{ $errors->first('policy_individual_rate') }}</span>
                        @endif
                        <span class="text-danger individual_rate"></span>
                    </div>
                    <div class="col-md-12 form-floating mt-4">
                        <select class="form-control" name="insurance_company" id="InsuranceCompany" value="{{$policy->company}}" placeholder="">
                            @foreach ($companies as $company)
                                <option value="{{$company->id}}" @if($policy->company == $company->id) selected @endif>{{$company->name}}</option>
                            @endforeach
                        </select>
                        <label for="InsuranceCompany" class="form-label">Insurance Company *</label>
                        @if ($errors->has('insurance_company'))
                            <span class="text-danger">{{ $errors->first('insurance_company') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4 health" style="display:none;">
                        <select class="form-control" name="health_category" id="HealthCategory" placeholder="">
                            <option value="1" @if($policy->health_category == 1) selected @endif>Base</option>
                            <option value="2" @if($policy->health_category == 2) selected @endif>Personal Accident</option>
                            <option value="3" @if($policy->health_category == 3) selected @endif>Super Topup</option>
                        </select>
                        <label for="HealthCategory" class="form-label">Category *</label>
                        @if ($errors->has('health_category'))
                            <span class="text-danger">{{ $errors->first('health_category') }}</span>
                        @endif
                        <span class="text-danger health_category"></span>
                    </div>
                    <div class="col-md-6 form-floating mt-4 health" style="display:none;">
                        <select class="form-control" name="health_plan" id="HealthPlan" placeholder="">
                            @if(!blank($plans))
                                @foreach ($plans as $plan)
                                    <option value="{{$plan->id}}" @if($policy->health_plan == $plan->id) selected @endif>{{$plan->name}}</option>
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
                        <input type="text" class="form-control" name="covernote_no" id="covernote_no" value="{{$policy->covernote_no}}" placeholder="">
                        <label for="covernote_no" class="form-label">Covernote No</label>
                        @if ($errors->has('covernote_no'))
                            <span class="text-danger">{{ $errors->first('covernote_no') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <input type="text" class="form-control" name="policy_no" id="policy_no" value="{{ old('policy_no') }}" placeholder="">
                        <label for="policy_no" class="form-label">Policy No</label>
                        @if ($errors->has('policy_no'))
                            <span class="text-danger">{{ $errors->first('policy_no') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="w-100 form-floating">
                                <select class="form-control select2" name="customer" id="Customer" placeholder="">
                                    <option value="0">Select Customer...</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{$customer->id}}" @if($customer->id == $policy->customer) selected @endif>{{$customer->name}}</option>
                                    @endforeach
                                </select>
                                <label for="Customer" class="form-label">Customer *</label>
                                @if ($errors->has('customer'))
                                <span class="text-danger">{{ $errors->first('customer') }}</span>
                                @endif
                                <span class="text-danger customer"></span>
                            </div>
                            <div class="h-100 mrg-tp">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop_business" style="padding: 4px 17px; font-size: 30px;">
                                +
                                </button>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-md-6 form-floating mt-4 motor">
                        <input type="text" class="form-control" name="vehicle_registration_no" id="vehicleRegistrationNo" value="{{$policy->vehicle_registration_no}}" placeholder="">
                        <label for="vehicleRegistrationNo" class="form-label">Vehicle Registration No </label>
                        @if ($errors->has('vehicle_registration_no'))
                            <span class="text-danger">{{ $errors->first('vehicle_registration_no') }}</span>
                        @endif
                        <span class="text-danger vehicle_registration_no"></span>
                    </div>
                    <div class="col-md-6 form-floating mt-4 motor">
                        <input type="text" class="form-control" name="vehicle_make" id="vehicleMake" value="{{$policy->vehicle_make}}" placeholder="">
                        <label for="vehicleMake" class="form-label">Vehicle make *</label>
                        @if ($errors->has('vehicle_make'))
                            <span class="text-danger">{{ $errors->first('vehicle_make') }}</span>
                        @endif
                        <span class="text-danger vehicle_make"></span>
                    </div>
                    <div class="col-md-6 form-floating mt-4 motor">
                        <input type="text" class="form-control" name="vehicle_model" id="vehicleModel" value="{{$policy->vehicle_model}}" placeholder="">
                        <label for="vehicleModel" class="form-label">Vehicle Model *</label>
                        @if ($errors->has('vehicle_model'))
                            <span class="text-danger">{{ $errors->first('vehicle_model') }}</span>
                        @endif
                        <span class="text-danger vehicle_model"></span>
                    </div>
                    <div class="col-md-6 form-floating mt-4 motor">
                        <input type="text" class="form-control" name="vehicle_chassis_no" id="vehicleChassisNo" value="{{$policy->vehicle_chassis_no}}" placeholder="">
                        <label for="vehicleChassisNo" class="form-label">Vehicle Chassis No *</label>
                        @if ($errors->has('vehicle_chassis_no'))
                            <span class="text-danger">{{ $errors->first('vehicle_chassis_no') }}</span>
                        @endif
                        <span class="text-danger vehicle_chassis_no"></span>
                    </div>
                    <div class="col-md-6 form-floating mt-4 motor">
                        <input type="text" class="form-control" name="vehicle_engine" id="vehicleEngine" value="{{$policy->vehicle_engine}}" placeholder="">
                        <label for="vehicleEngine" class="form-label">Vehicle Engine *</label>
                        <div>
                            @if ($errors->has('vehicle_engine'))
                                <span class="text-danger">{{ $errors->first('vehicle_engine') }}</span>
                            @endif
                        </div>
                        <span class="text-danger vehicle_chassis_no"></span>
                    </div>
                    <div class="col-md-6 form-floating mt-4 motor">
                        <input type="number" class="form-control" min="0" name="idv_amount" id="IdvAmount" value="{{$policy->idv_amount}}" placeholder="" />
                        <label for="IdvAmount" class="form-label">Idv Amount  *</label>
                        @if ($errors->has('idv_amount'))
                            <span class="text-danger">{{ $errors->first('idv_amount') }}</span>
                        @endif
                        <span class="text-danger idv_amount"></span>
                    </div>
                    <div class="col-md-6 form-floating mt-4 motor">
                        <input type="number" class="form-control" name="year_of_manufacture" id="yearOfManufacture" value="{{$policy->year_of_manufacture}}" placeholder="">
                        <label for="yearOfManufacture" class="form-label"> Year Of Manufacture*</label>
                        @if ($errors->has('year_of_manufacture'))
                            <span class="text-danger">{{ $errors->first('year_of_manufacture') }}</span>
                        @endif
                        <span class="text-danger year_of_manufacture"></span>
                    </div>
                    <div class="params motor row w-100">
                    </div>
                    <div class="col-md-6 form-floating mt-4 health" style="display:none;">
                        <input type="number" class="form-control" min="0" name="sum_insured_amount" id="SumInsuredAmount" value="{{$policy->sum_insured_amount}}" placeholder="" />
                        <label for="SumInsuredAmount" class="form-label">Sum Insured Amount *</label>
                        @if ($errors->has('sum_insured_amount'))
                            <span class="text-danger">{{ $errors->first('sum_insured_amount') }}</span>
                        @endif
                        <span class="text-danger sum_insured_amount"></span>
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <input type="number" class="form-control" min="0" step="0.01" name="gross_premium_amount" id="GrossPremiumAmount" value="{{$policy->gross_premium_amount}}" placeholder="" />
                        <label for="GrossPremiumAmount" class="form-label">Gross Premium Amount *</label>
                        @if ($errors->has('gross_premium_amount'))
                            <span class="text-danger">{{ $errors->first('gross_premium_amount') }}</span>
                        @endif
                        <span class="text-danger gross_premium_amount"></span>
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <input type="number" class="form-control" min="0" step="0.01" name="net_premium_amount" id="NetPremiumAmount" value="{{$policy->net_premium_amount}}" placeholder="" />
                        <label for="NetPremiumAmount" class="form-label">Net Premium Amount *</label>
                        @if ($errors->has('net_premium_amount'))
                            <span class="text-danger">{{ $errors->first('net_premium_amount') }}</span>
                        @endif
                        <span class="text-danger net_premium_amount"></span>
                    </div>
                    
                    <div class="col-md-6 form-floating mt-4">
                        <input type="number" name="tp" min="0" step="0.01" id="TP" class="form-control" value="{{$policy->tp}}" placeholder="">
                        <label for="TP" class="form-label">Third Party Premium (TP) </label>
                        @if ($errors->has('tp'))
                            <span class="text-danger">{{ $errors->first('tp') }}</span>
                        @endif
                        <span class="text-danger tp"></span>
                    </div>

                    <div class="col-md-6 form-floating mt-4">
                        <input type="number" name="od" min="0" step="0.01" id="OD" class="form-control" value="{{$policy->od}}" placeholder="">
                        <label for="OD" class="form-label">Own Damage (OD) </label>
                        @if ($errors->has('od'))
                            <span class="text-danger">{{ $errors->first('od') }}</span>
                        @endif
                        <span class="text-danger od"></span>
                    </div>
                    <div class="col-md-6 form-floating mt-4 motor">
                        <select class="form-control" name="business_type" id="BusinessType" value="{{$policy->business_type}}" placeholder="">
                            <option value="2" selected>Renewal</option>
                            <option value="3">Rollover</option>
                        </select>
                        <label for="BusinessType" class="form-label">Business Type *</label>
                        @if ($errors->has('business_type'))
                            <span class="text-danger">{{ $errors->first('business_type') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4 pyp business_type">
                        <input type="text" name="pyp_no" id="PypNo" value="{{$policy->policy_no}}" class="form-control">
                        <label for="PypNo" class="form-label">Pyp No *</label>
                        @if ($errors->has('pyp_no'))
                            <span class="text-danger">{{ $errors->first('pyp_no') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4 pyp business_type">
                        @if(!blank($companies))
                            @foreach ($companies as $company)
                                @if($company->id == $policy->company)
                                    <?php $comp = $company->name; ?>
                                @endif
                            @endforeach
                        @endif
                        <input type="text" name="pyp_insurance_company" id="PypInsuranceCompany" value="{{$comp}}" class="form-control">
                        <label for="PypInsuranceCompany" class="form-label">Pyp Insurance Company *</label>
                        @if ($errors->has('pyp_insurance_company'))
                            <span class="text-danger">{{ $errors->first('pyp_insurance_company') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4 pyp business_type">
                        <input type="date" name="pyp_expiry_date" id="PypExpiryDate" value="{{date('Y-m-d',strtotime($policy->risk_end_date))}}" class="form-control">
                        <label for="PypExpiryDate" class="form-label">Pyp Expiry Date *</label>
                        @if ($errors->has('pyp_expiry_date'))
                            <span class="text-danger">{{ $errors->first('pyp_expiry_date') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4 health">
                        <select class="form-control" name="health_business_type" id="HealthBusinessType" placeholder="">
                            <option value="2" @if($policy->business_type == 2) selected @endif>Renewal</option>
                            <option value="3" @if($policy->business_type == 3) selected @endif>Portability</option>
                        </select>
                        <label for="HealthBusinessType" class="form-label">Business Type *</label>
                        @if ($errors->has('health_business_type'))
                            <span class="text-danger">{{ $errors->first('health_business_type') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <select class="form-control" name="sourcing_agent" id="SourcingAgent" placeholder="">
                            <option value="0">Select Sourcing Agent...</option>
                            @foreach($sourcing_agents as $agent)
                                <option value="{{$agent->id}}" @if($policy->agent == $agent->id) selected @endif>{{$agent->name}}</option>
                            @endforeach
                        </select>
                        <label for="SourcingAgent" class="form-label">Sourcing Agent *</label>
                        @if ($errors->has('sourcing_agent'))
                            <span class="text-danger">{{ $errors->first('sourcing_agent') }}</span>
                        @endif
                        <span class="text-danger sourcing_agent"></span>
                    </div>
                     <!--<div class="col-md-12 form-floating  d-none">-->
                     <!--               <input type="date" class="form-control" name="risk_start_date" id="RiskStartDate1" value="{{(old('risk_start_date'))?old('risk_start_date'):date('Y-m-d')}}" placeholder="" />-->
                     <!--               <label for="RiskStartDate" class="form-label">Risk Start Date *</label>-->
                     <!--               @if ($errors->has('risk_start_date'))-->
                     <!--               <span class="text-danger">{{ $errors->first('risk_start_date') }}</span>-->
                     <!--               @endif-->
                     <!--               <span class="text-danger risk_start_date"></span>-->
                     <!--           </div>-->
                                <div class="col-md-6  mt-4">                                    
                                    <label for="RiskStartDate" class="form-label form-date-label">Risk Start Date & Risk End Date </label>
                                <div class="col-md-12 form-floating">
                                    <div class="custom-date-div-outer-main">
                                        <div class="custom-date-div-outer justify-content-between d-flex">
                                            <span class="custom-date-span  w-100">
                                                <input type="date" class="form-control custom-start-date-div custom-date-div" name="risk_start_date" id="RiskStartDate" value="{{$policy->risk_end_date ? date('Y-m-d', strtotime($policy->risk_end_date . ' +1 day')) : old('risk_start_date')}}" placeholder="" />
                                            </span>
                                            <span class="custom-date-to mt-2">To</span>
                                            <span class="custom-date-span w-100">
                                                <input type="date" class="form-control custom-end-date-div custom-date-div" name="risk_end_date" id="RiskEndDate" value="{{$policy->risk_end_date ? date('Y-m-d', strtotime($policy->risk_end_date . ' +1 year')) : old('risk_start_date')}}" placeholder="" />
                                            </span>
                                        </div>
                                    </div>
                                    @if ($errors->has('risk_start_date'))
                                        <span class="text-danger">{{ $errors->first('risk_start_date') }}</span>
                                    @endif
                                    <span class="text-danger risk_start_date"></span>
                                </div>                                
                                </div>
                    <div class="col-md-6 form-floating mt-4">
                        <select name="ncb" class="form-control" id="NCB">
                            <option value="0" @if($policy->ncb == 0) selected @endif>0%</option>
                            <option value="20" @if($policy->ncb == 20) selected @endif>20%</option>
                            <option value="25" @if($policy->ncb == 25) selected @endif>25%</option>
                            <option value="35" @if($policy->ncb == 35) selected @endif>35%</option>
                            <option value="45" @if($policy->ncb == 45) selected @endif>45%</option>
                            <option value="50" @if($policy->ncb == 50) selected @endif>50%</option>
                        </select>
                        <label for="NCB" class="form-label">Current Policy NCB (%) *</label>
                        @if ($errors->has('ncb'))
                            <span class="text-danger">{{ $errors->first('ncb') }}</span>
                        @endif
                        <span class="text-danger ncb"></span>
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <select name="team_lead" class="form-control" id="teamLead">
                            <option value="0">Select Team Lead...</option>
                            @if(!blank($team_lead))
                                @foreach($team_lead as $lead)
                                    <option value="{{$lead->id}}" @if($policy->team_lead == $lead->id) selected @endif>{{$lead->name}}</option>
                                @endforeach
                            @endif
                        </select>
                        <label for="teamLead" class="form-label">Team Lead *</label>
                        @if ($errors->has('team_lead'))
                            <span class="text-danger">{{ $errors->first('team_lead') }}</span>
                        @endif
                        <span class="text-danger team_lead"></span>
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <select name="managed_by" class="form-control" id="managedBy">
                            <option value="0">Select Staff...</option>
                            @if(!blank($managed_by))
                                @foreach($managed_by as $manage)
                                    <option value="{{$manage->id}}" @if($policy->managed_by == $manage->id || Auth()->user()->id == $manage->id) selected @endif>{{$manage->name}}</option>
                                @endforeach
                            @endif
                        </select>
                        <label for="managedBy" class="form-label">Managed By *</label>
                        @if ($errors->has('managed_by'))
                            <span class="text-danger">{{ $errors->first('managed_by') }}</span>
                        @endif
                        <span class="text-danger managed_by"></span>
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <input type="text" name="rto_name" id="RtoName" class="form-control" value="{{old('rto_name')}}" placeholder="">
                        <label for="RtoName" class="form-label">RTO Name </label>
                        @if ($errors->has('rto_name'))
                            <span class="text-danger">{{ $errors->first('rto_name') }}</span>
                        @endif
                        <span class="text-danger rto_name"></span>
                    </div>
                    <div class="row">
                        <div class="payments">
                            @if(old('payment_type'))
                            @foreach(old('payment_type') as $key => $paymentType)
                                <div class="row payment-{{ $key }}">
                                    <div class="col-md-6 form-floating mt-4">
                                        <select class="form-control paymentType" name="payment_type[{{ $key }}]" id="PaymentType{{ $key }}" data-id="{{ $key }}" placeholder="">
                                            <option value="0" {{ $paymentType == 0 ? 'selected' : '' }}>Select Payment Option</option>
                                            <option value="1" {{ $paymentType == 1 ? 'selected' : '' }}>Cash</option>
                                            <option value="2" {{ $paymentType == 2 ? 'selected' : '' }}>Cheque</option>
                                            <option value="3" {{ $paymentType == 3 ? 'selected' : '' }}>Online</option>
                                        </select>
                                        <label for="PaymentType{{ $key }}" class="form-label">Payment Type</label>
                                    </div>
                                    <div class="col-md-6 form-floating mt-4">
                                        <input type="date" name="payment_date[{{ $key }}]" value="{{ old('payment_date.'.$key) }}" id="paymentDate-{{ $key }}" class="form-control">
                                        <label for="paymentDate-{{ $key }}">Payment Date</label>
                                    </div>
                                    <div class="col-md-6 form-floating mt-4">
                                        <select name="payment_made_by[{{ $key }}]" id="paymentMadeBy-{{ $key }}" class="form-control">
                                            <option value="AGENT" {{ old('payment_made_by.'.$key) == 'AGENT' ? 'selected' : '' }}>AGENT</option>
                                            <option value="CUSTOMER" {{ old('payment_made_by.'.$key) == 'CUSTOMER' ? 'selected' : '' }}>CUSTOMER</option>
                                        </select>
                                        <label for="paymentMadeBy-{{ $key }}">Payment Made By</label>
                                    </div>
                                    
                                        <div class="row cheque cheque-{{ $key }}" @if($paymentType != 2)  style="display:none;" @endif >
                                            <div class="col-md-6 form-floating mt-4">
                                                <input type="text" class="form-control" name="cheque_no[{{ $key }}]" id="chequeNo-{{ $key }}" value="{{ old('cheque_no.'.$key) }}" placeholder="">
                                                <label for="chequeNo-{{ $key }}" class="form-label">Cheque No *</label>
                                            </div>
                                            <div class="col-md-6 form-floating mt-4">
                                                <input type="date" class="form-control" name="cheque_date[{{ $key }}]" id="cheque_date-{{ $key }}" value="{{ old('cheque_date.'.$key) }}" placeholder="">
                                                <label for="cheque_date-{{ $key }}" class="form-label">Cheque Date *</label>
                                            </div>
                                            <div class="col-md-6 form-floating mt-4">
                                                <input type="text" class="form-control" min="0" name="bank_name[{{ $key }}]" id="BankName-{{ $key }}" value="{{ old('bank_name.'.$key) }}" placeholder="">
                                                <label for="BankName-{{ $key }}" class="form-label">Bank Name *</label>
                                            </div>
                                        </div>
                                    
                                        <div class="col-md-6 online online-{{ $key }}"  @if($paymentType != 3)  style="display:none;" @endif>
                                            <div class="col-md-12 form-floating mt-4">
                                                <input type="text" class="form-control" name="transaction_no[{{ $key }}]" id="TransactionNo-{{ $key }}" value="{{ old('transaction_no.'.$key) }}" placeholder="">
                                                <label for="TransactionNo-{{ $key }}" class="form-label">Transaction No *</label>
                                            </div>
                                        </div>
                                    
                                    <div class="col-md-12 mt-3">
                                        <a href="javascript:void(0);" class="btn btn-danger delete-payment" data-id="{{ $key }}">Remove</a>
                                    </div>
                                </div>
                            @endforeach
                        @endif   
                        </div>
                        <div class="col-md-6">
                            <hr>
                            <button type="button" class="btn btn-primary AddPayment">Add Payment</button>
                        </div>
                    </div>
                    <div class="form-group mb-4 mt-4">
                        <label class="form-label opacity-75 fw-medium" for="formImage">Policy Attachments</label>
                        <div class="dropzone-drag-area dropzone dropzone-secondary" id="previews">
                            <div class="dz-message needsclick text-muted opacity-50" data-dz-message>
                                <i class="icon-cloud-up"></i><br>
                                <span>Drag file here to upload</span>
                            </div>
                            <div class="d-flex gap-3 overflow-auto" id="previews1"></div>
                            <div class="d-none" id="dzPreviewContainer">
                                <div class="dz-file-preview">
                                    <div class="dz-photo">
                                        <img class="dz-thumbnail" data-dz-thumbnail>
                                    </div>
                                    <div class="dz-name"></div>
                                    <button class="dz-delete border-0 p-0" type="button" data-dz-remove>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="times"><path fill="#FFFFFF" d="M13.41,12l4.3-4.29a1,1,0,1,0-1.42-1.42L12,10.59,7.71,6.29A1,1,0,0,0,6.29,7.71L10.59,12l-4.3,4.29a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0L12,13.41l4.29,4.3a1,1,0,0,0,1.42,0,1,1,0,0,0,0-1.42Z"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="invalid-feedback fw-bold">Please upload an image.</div>
                    </div>
                    <!-- <div class="col-md-12 form-floating mt-4">
                        <input type="file" name="policy_document[]" multiple class="form-control" id="PolicyDocument">
                        <label for="PolicyDocument" class="form-label">Policy Document</label>
                        @if ($errors->has('policy_document'))
                            <span class="text-danger">{{ $errors->first('policy_document') }}</span>
                        @endif
                    </div> -->
                    <div class="col-md-12 mb-2">
                        <label for="">Policy Documents</label>
                    </div>
                    <div class="row">
                        <div class="PolicyDocument">
                          
                        </div>
                    </div>
                    <div class="col-md-12 my-3">
                        <a href="javascript:void(0);" class="btn btn-primary addDocument" role="button">Add Document</a>
                    </div>
                </div>
                <div class="col-md-12 form-floating mt-4">
                    <textarea name="remarks" class="form-control" id="Remarks" placeholder="">{{old('remarks')}}</textarea>
                    <label for="Remarks" class="form-label">Remarks</label>
                    @if ($errors->has('remarks'))
                        <span class="text-danger">{{ $errors->first('remarks') }}</span>
                    @endif
                </div>
                <div class="col-12">
                    <input type="hidden" name="id" value="{{$policy->id}}">
                    <button type="button" id="formSubmit" class="formSubmit btn btn-primary mt-3"  onclick="showLoader(this)">
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
                                    <input type="text" class="form-control" name="name" id="customerName" value="{{old('customer_name')}}" placeholder="" required>
                                    <label for="customerName" class="form-label">Customer Name *</label>
                                    @if ($errors->has('customer_name'))
                                    <span class="text-danger">{{ $errors->first('customer_name') }}</span>
                                    @endif
                                    <span class="text-danger customer_nameError"></span>
                                </div>
                                <div class="col-md-12 form-floating mt-4">
                                    <input type="email" class="form-control" name="email" id="customerEmail" value="{{old('customer_email')}}" placeholder="">
                                    <label for="customerEmail" class="form-label">Customer Email</label>
                                    @if ($errors->has('customer_email'))
                                    <span class="text-danger">{{ $errors->first('customer_email') }}</span>
                                    @endif
                                    <span class="text-danger customer_emailError"></span>
                                </div>
                                <div class="col-md-12 form-floating mt-4">
                                    <input type="text" class="form-control" name="phone" id="customerPhone" value="{{old('customer_phone')}}" placeholder="" required>
                                    <label for="customerPhone" class="form-label">Customer Phone Number *</label>
                                    @if ($errors->has('customer_phone'))
                                    <span class="text-danger">{{ $errors->first('customer_phone') }}</span>
                                    @endif
                                    <span class="text-danger customer_phoneError"></span>
                                </div>
                            </div>
                            <input type="hidden" name="sub_category_gst" class="sub_category_gst" value="18">
                            <button type="submit" class="btn btn-primary mt-3" id="saveCustomerBtn" onclick="hideLoader(this)">Submit</button>
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
<script src="{{url('/')}}/assets2/select2/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        $('#Customer').select2();
        var ins_type = "{{$policy->insurance_type}}";
        if(ins_type == 2){
            $('.motor').css('display','none');
            $('.health').css('display','flex');
        }else{
            $('.health').css('display','none');
            $('.motor').css('display','flex');
            var sub_cat = '{{$policy->sub_category}}';
            $.ajax({
                type: 'GET',
                url: '{{ route("get_cat_parameters") }}',
                data: {'id': sub_cat,'edit':"{{$policy->id}}"},
                success: function (data) {
                    if($('#Category').val() == 2){
                        $('.Policy').css('display','none');
                    }else{
                        $('.Policy').css('display','block');
                    }
                    $('.params').html(data);
                    var policy_type = $('input[name="policy_type"]:checked').val();
                    if(policy_type == 1 && $('#Category').val() == 1){
                        $('.PolicyIndividualRate').css('display','block');
                    }else{
                        $('.PolicyIndividualRate').css('display','none');
                    }
                },
                error: function (data) {
                    // console.log(data);
                }
            });
        }
        // var p_type = "{{$policy->payment_type}}";
        // if(p_type == 2){
        //     $('.cheque').css('display','flex');
        //     $('.online').css('display','none');
        // }else if(p_type == 3){
        //     $('.cheque').css('display','none');
        //     $('.online').css('display','flex');
        // }else{
        //     $('.cheque').css('display','none');
        //     $('.online').css('display','none');
        // }
        $(document).on('click','.remove-document',function(){
            $('.document-preview').css('display','none');
        });
        $(document).on('change','#InsuranceType',function(){
            var insurance_type = $(this).val();
            if(insurance_type == 2){
                $('.motor').css('display','none');
                $('.health').css('display','flex');
            }else{
                $('.health').css('display','none');
                $('.motor').css('display','flex');
            }
        });
        $(document).on('change','#GcvType',function(){
            var gcv_type = $(this).val();
            var cat_id = $(this).data('id');
            $.ajax({
                type: 'GET',
                url: '{{ route("get_gcv_parameters") }}',
                data: {'id': cat_id,'gcv_type':gcv_type,'edit':"{{$policy->id}}"},
                success: function (data) {
                    if(data == ''){
                        $('.GCVCarrier').css('display','none');
                    }else{
                        $('.GCVCarrier').css('display','block');
                        $('.GCVCarrier').html(data);
                    }
                },
                error: function (data) {
                    // console.log(data);
                }
            });
        });
        $(document).on('change','#PcvType',function(){
            var gcv_type = $(this).val();
            var cat_id = $(this).data('id');
            $.ajax({
                type: 'GET',
                url: '{{ route("get_pcv_parameters") }}',
                data: {'id': cat_id,'pcv_type':gcv_type,'edit':"{{$policy->id}}"},
                success: function (data) {
                    if(data == ''){
                        $('.PCVCarrier').css('display','none');
                    }else{
                        $('.PCVCarrier').css('display','contents');
                        $('.PCVCarrier').html(data);
                    }
                },
                error: function (data) {
                    // console.log(data);
                }
            });
        });
        $(document).on('change','#InsuranceType',function(){
            var insurance_type = $(this).val();
            if(insurance_type == 1){
                $('.health-insurance').addClass('d-none');
                $('.motor-insurance').removeClass('d-none');
            }else{
                $('.motor-insurance').addClass('d-none');
                $('.health-insurance').removeClass('d-none');
            }
        });
        $(document).on('change','#Category',function(){
            var cat = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route("get_cat_subcategory") }}',
                data: {'id': cat},
                success: function (data) {
                    $('#SubCategory').html('');
                    $('#SubCategory').append(data);
                },
                error: function (data) {
                    // console.log(data);
                }
            });
        });
        $(document).on('change','#InsuranceCompany',function(){
            var company = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route("get_insurance_plan") }}',
                data: {'id': company},
                success: function (data) {
                    $('#HealthPlan').html('');
                    $('#HealthPlan').append(data);
                },
                error: function (data) {
                    // console.log(data);
                }
            });
        });
        $(document).on('change','.policy_type',function(){
            var val = $(this).val();
            if(val == 1){
                $('.PolicyIndividualRate').css('display','block');
            }else{
                $('.PolicyIndividualRate').css('display','none');
            }
        });
        $(document).on('change','#Category',function(){
            if($(this).val() == 2){
                $('.Policy').css('display','none');
            }else{
                $('.Policy').css('display','block');
            }
        });
        $(document).on('change','#SubCategory',function(){
            var sub_cat = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route("get_cat_parameters") }}',
                data: {'id': sub_cat},
                success: function (data) {
                    if($('#Category').val() == 2){
                        $('.Policy').css('display','none');
                    }else{
                        $('.Policy').css('display','block');
                    }
                    $('.params').html(data);
                },
                error: function (data) {
                    // console.log(data);
                }
            });
        });
        $(document).on('change','.paymentType',function(){
            var type = $(this).val();
            var dataid = $(this).data('id');
            if(type == 2){
                $('.cheque-'+dataid).css('display','flex');
                $('.online-'+dataid).css('display','none');
            }else if(type == 3){
                $('.cheque-'+dataid).css('display','none');
                $('.online-'+dataid).css('display','flex');
            }else{
                $('.cheque-'+dataid).css('display','none');
                $('.online-'+dataid).css('display','none');
            }
        });
        @php    $paymentTypeCount = count(old('payment_type', []));  @endphp
        
        var i = {{ $paymentTypeCount }};
        
    $(document).on('click','.AddPayment',function(){
        i++;
        var html = '';
        var d = new Date();
        var today = d.getFullYear() + "-" + String(d.getMonth() + 1).padStart(2, '0') + "-" + String(d.getDate()).padStart(2, '0');
        html += '<div class="row payment-'+i+'">';
        html += '<div class="col-md-6 form-floating mt-4">';
        html += '<select class="form-control paymentType" name="payment_type['+i+']" id="PaymentType'+i+'" data-id="'+i+'" placeholder="">';
        html += '<option value="0">Select Payment Option</option>';
        html += '<option value="1">Cash</option>';
        html += '<option value="2">Cheque</option>';
        html += '<option value="3">Online</option>';
        html += '</select>';
        html += '<label for="PaymentType" class="form-label">Payment Type</label>';
        html += '</div>';
        html += '<div class="col-md-6 form-floating mt-4">';
        html += '<input type="date" name="payment_date['+i+']" id="paymentDate-'+i+'" value="'+today+'" class="form-control">';
        html += '<label for="paymentDate-'+i+'">Payment Date</label>';
        html += '</div>';
        html += '<div class="col-md-6 form-floating mt-4">';
        html += '<select name="payment_made_by['+i+']" id="paymentMadeBy-'+i+'" class="form-control">';
        html += '<option value="AGENT">AGENT</option>';
        html += '<option value="CUSTOMER">CUSTOMER</option>';
        html += '</select>';
        html += '<label for="paymentMadeBy-'+i+'">Payment Made By</label>';
        html += '</div>';
        html += '<div class="row cheque cheque-'+i+'" style="display:none;">';
        html += '<div class="col-md-6 form-floating mt-4">';
        html += '<input type="text" class="form-control" name="cheque_no['+i+']" id="chequeNo" placeholder="" />';
        html += '<label for="chequeNo" class="form-label">Cheque No *</label>';
        html += '</div>';
        html += '<div class="col-md-6 form-floating mt-4">';
        html += '<input type="date" class="form-control" name="cheque_date['+i+']" id="cheque_date" placeholder="" />';
        html += '<label for="cheque_date" class="form-label">Cheque Date *</label>';
        html += '</div>';
        html += '<div class="col-md-6 form-floating mt-4">';
        html += '<input type="text" class="form-control" min="0" name="bank_name['+i+']" id="BankName" placeholder="" />';
        html += '<label for="BankName" class="form-label">Bank Name *</label>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-6 online online-'+i+'" style="display:none;">';
        html += '<div class="col-md-12 form-floating mt-4">';
        html += '<input type="text" class="form-control" name="transaction_no['+i+']" id="TransactionNo" placeholder="" />';
        html += '<label for="TransactionNo" class="form-label">Transaction No *</label>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-12 mt-3">';
        html += '<a href="javascript:void(0);" class="btn btn-danger delete-payment" data-id="'+i+'">Remove</a>';
        html += '</div>';
        html += '</div>';
        $('.payments').append(html);
    });
    $(document).on('click','.delete-payment',function(){
        var data_id = $(this).data('id');
        $('.payment-'+data_id).remove();
    });
    $(document).on('click','.addDocument',function(){
        i++;
        var html = '';
        html += '<div class="row col-md-12 mt-3 doc-'+i+'">';
        html += '<div class="col-md-6">';
        html += '<input type="file" name="policy_document[]" id="documents" class="form-control documents">';
        html += '</div>';
        html += '<div class="col-md-6 d-flex align-items-center">';
        html += '<a href="javascript:void(0);" role="button" class="text-danger delete-doc delete-doc-'+i+'" data-id="'+i+'">Delete</a>';
        html += '</div>';
        html += '</div>';
        $('.PolicyDocument').append(html);
    });
    $(document).on('click','.delete-doc',function(){
        var data_id = $(this).data('id');
        $('.doc-'+data_id).remove();
    });
    $(document).on('click','.delete-policy-doc',function(){
        var doc_id = $(this).attr('data-id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url : "{{route('delete.policy.document', '')}}"+"/"+doc_id,
                    type : 'GET',
                    dataType:'json',
                    success : function(data) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: "Policy Document has been deleted.",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                $('.policy-doc-'+doc_id).remove();
                            }
                        });
                    }
                });
            }
        });
    });
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

        error: function(data) {

        }

    });

});
</script>
<script>
    $("#GrossPremiumAmount").change(function(){
		$("#NetPremiumAmount").val(Math.round(((($("#GrossPremiumAmount").val() * 100)/118) * 100) /100));
	});
    Dropzone.autoDiscover = false;

    var myDropzone = new Dropzone('#formDropzone',{
        previewTemplate: $('#dzPreviewContainer').html(),
        url: "{{route('admin.renew.policy.data')}}",
        addRemoveLinks: true,
        dictRemoveFileConfirmation: "Are you sure?",
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 10,
        maxFiles: 10,
        acceptedFiles: "image/*,application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        thumbnailWidth: 200,
        thumbnailHeight: 200,
        previewsContainer: "#previews1",
        timeout: 0,
        init: function()
        {
                // when file is dragged in
                this.on('addedfile', function(file) {
                    var ext = file.name.split('.').pop();
                    $(file.previewElement).find(".dz-name").html(file.name);
                    if (ext == "pdf") {
                        $(file.previewElement).find(".dz-photo img").attr("src", "{{url('/')}}/assets/Images/pdf.png");
                    } else if (ext.indexOf("doc") != -1) {
                        $(file.previewElement).find(".dz-photo img").attr("src", "{{url('/')}}/assets/Images/docs.png");
                    } else if (ext.indexOf("xls") != -1) {
                        $(file.previewElement).find(".dz-photo img").attr("src", "{{url('/')}}/assets/Images/docs.png");
                    }
                    $('.dropzone-drag-area').removeClass('is-invalid').next('.invalid-feedback').hide();
                });
                $('#formSubmit').on('click', function(event) {
                    event.preventDefault();
                    var $this = $(this);

                    // show submit button spinner
                    // $this.children('.spinner-border').removeClass('d-none');

                    // // validate form & submit if valid
                    // if ($('#formDropzone')[0].checkValidity() === false) {
                    //     event.stopPropagation();
                    //     // show error messages & hide button spinner
                    //     $('#formDropzone').addClass('was-validated');
                    //     $this.children('.spinner-border').addClass('d-none');

                    //     // if dropzone is empty show error message
                    //     if (!myDropzone.getQueuedFiles().length > 0) {
                    //         $('.dropzone-drag-area').addClass('is-invalid').next('.invalid-feedback').show();
                    //     }
                    // } else {
                        if (myDropzone.getQueuedFiles().length > 0) {
                            event.preventDefault();
                            event.stopPropagation();
                            // var dr = document.querySelector("#formDropzone").dropzone;
                            // console.log(dr);

                            // if everything is ok, submit the form
                            var category = $('#Category').val();
                            var subcategory = $('#SubCategory').val();
                            var customer    = $('#Customer').val();
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
                            var team_lead = $('#teamLead').val();
                            var managed_by = $('#managedBy').val();
                            var i = 0;
                            if(insurance_type == 1){
                                if(subcategory == 0){
                                    i++;
                                    $('.subcategory').html('Please select Subcategory.');
                                }
                                if(subcategory != 0){
                                    if(category == 1 && policy_type == 1 && individual_rate == ''){
                                        i++;
                                        $('.individual_rate').html('Individual Rate field is required.');
                                    }
                                }
                                if(team_lead == 0){
                                    i++;
                                    console.log('Team Lead');
                                    $('.team_lead').html('Please select team lead.');
                                }
                                if(managed_by == 0){
                                    i++;
                                    console.log('Managed By');
                                    $('.managed_by').html('Please select managed by.');
                                }
                                if(vehicle_model == ''){
                                    i++;
                                    $('.vehicle_model').html('Vehicle Model field is required.');
                                }
                                if(vehicle_chassis_no == ''){
                                    i++;
                                    $('.vehicle_chassis_no').html ('Vehicle Chassis No field is required.');
                                }
                                if(vehicle_make == ''){
                                    i++;
                                    $('.vehicle_make').html('Vehicle Make field is required.');
                                }
                                if(registration_no == ''){
                                    i++;
                                    $('.vehicle_registration_no').html('Vehicle Registration No field is required.');
                                }
                                if(year_of_manufacture == ''){
                                    i++;
                                    $('.year_of_manufacture').html('Year Of Manufacture field is required.');
                                }
                                if(idv_amount == ''){
                                    i++;
                                    $('.idv_amount').html('Idv Amount field is required.');
                                }
                                if(net_premium == ''){
                                    i++;
                                    $('.net_premium_amount').html('Net Premium Amount field is required.');
                                }
                                if(gross_premium == ''){
                                    i++;
                                    $('.gross_premium_amount').html('Gross Premium Amount field is required.');
                                }
                            }
                            if(insurance_type == 2){
                                if(health_category == 0){
                                    i++;
                                    $('.health_category').html('Please Select health category.');
                                }
                                if(health_plan == 0){
                                    i++;
                                    $('.health_plan').html('Please select health plan.');
                                }
                            }
                            if(sourcing_agent == 0){
                                i++;
                                $('.sourcing_agent').html('Sourcing Agent field is required.');
                            }
                            if(risk_start_date == ''){
                                i++;
                                $('.risk_start_date').html('Risk Start Date field is required.');
                            }
                            if(risk_end_date == ''){
                                i++;
                                $('.risk_end_date').html('Risk End Date field is required.')
                            }
                            if(customer == 0){
                                i++;
                                $('.customer').html('Please select Customer.');
                            }
                            if(i == 0){
                                myDropzone.processQueue();
                            }
                        }else{
                            $('#formDropzone').submit();
                        }
                    // }
                });
                this.on("sendingmultiple", function(data, xhr, formData) {
                    var i = 0;
                    $(".documents").each(function(){
                        formData.append('policy_document['+i+']',$(this)[0].files[0]);
                        i++;
                    });
                    // var fileSelect = document.getElementById('PolicyDocument');

                    // if(fileSelect != null){
                    //     if(fileSelect.files[0] != "undefined" && fileSelect.files[0] != null ){
                    //     formData.append('policy_document', fileSelect.files[0], fileSelect.files[0].name)
                    //     }
                    // }
                });
        },
        success: function(file, response)
        {
            // hide form and show success message
            // $('#formDropzone').fadeOut(600);
            // if(response == ''){
               top.location.href="{{ route('admin.policies') }}";
            // }
            // setTimeout(function() {
            //     $('#successMessage').removeClass('d-none');
            // }, 600);
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
</script>
@endsection
