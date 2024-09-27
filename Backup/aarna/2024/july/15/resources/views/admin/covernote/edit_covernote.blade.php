@extends('admin.layouts.app')

@section('style')
<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/dropzone.css">
<style>
    .dz-thumbnail {
        max-width: 150px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.edit.covernote.data')}}" method="post" class="g-3 " id="formDropzone" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- <div class="col-md-6 form-floating mt-4">
                        <select class="form-control" name="insurance_type" id="InsuranceType" placeholder="">
                            <option value="1" @if($policy->insurance_type == 1) selected @endif>Motor Insurance Policy</option>
                            <option value="2" @if($policy->insurance_type == 2) selected @endif>Health Insurance Policy</option>
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
                            @foreach($business_source as $source)
                                <?php $cust = DB::table('customers')->where('id',$source->customer)->first(); ?>
                                <option value="{{$source->id}}" @if($policy->business_source == $source->id) selected @endif>{{$cust->name}}</option>
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
                    <div class="col-md-6 form-floating mt-4 PolicyIndividualRate Policy motor" style="display:none;">
                        <input type="text" class="form-control" name="policy_individual_rate" id="PolicyIndividualRate" value="{{$policy->policy_individual_rate}}" placeholder="">
                        <label for="PolicyIndividualRate" class="form-label">Policy Individual Rate *</label>
                        @if ($errors->has('policy_individual_rate'))
                            <span class="text-danger">{{ $errors->first('policy_individual_rate') }}</span>
                        @endif
                        <span class="text-danger individual_rate"></span>
                    </div>
                    <div class="col-md-6 form-floating mt-4">
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
                    {{-- <div class="col-md-6 form-floating mt-4">
                        <input type="text" class="form-control" name="policy_no" id="policy_no" value="{{$policy->policy_no}}" placeholder="">
                        <label for="policy_no" class="form-label">Policy No</label>
                        @if ($errors->has('policy_no'))
                            <span class="text-danger">{{ $errors->first('policy_no') }}</span>
                        @endif
                    </div> --}}
                    <div class="col-md-6 form-floating mt-4">
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
                        <input type="text" class="form-control" name="vehicle_make" id="vehicleMake" value="{{$policy->vehicle_make}}" placeholder="">
                        <label for="vehicleMake" class="form-label">Vehicle make *</label>
                        @if ($errors->has('vehicle_make'))
                            <span class="text-danger">{{ $errors->first('vehicle_make') }}</span>
                        @endif
                        <span class="text-danger vehicle_make"></span>
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
                        <input type="number" class="form-control" name="year_of_manufacture" id="yearOfManufacture" value="{{$policy->year_of_manufacture}}" placeholder="">
                        <label for="yearOfManufacture" class="form-label"> Year Of Manufacture*</label>
                        @if ($errors->has('year_of_manufacture'))
                            <span class="text-danger">{{ $errors->first('year_of_manufacture') }}</span>
                        @endif
                        <span class="text-danger year_of_manufacture"></span>
                    </div>
                    <div class="col-md-6 form-floating mt-4 motor">
                        <input type="number" class="form-control" min="0" name="idv_amount" id="IdvAmount" value="{{$policy->idv_amount}}" placeholder="" />
                        <label for="IdvAmount" class="form-label">Idv Amount  *</label>
                        @if ($errors->has('idv_amount'))
                            <span class="text-danger">{{ $errors->first('idv_amount') }}</span>
                        @endif
                        <span class="text-danger idv_amount"></span>
                    </div>
                    <div class="params motor row">

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
                        <input type="number" class="form-control" min="0" name="net_premium_amount" id="NetPremiumAmount" value="{{$policy->net_premium_amount}}" placeholder="" />
                        <label for="NetPremiumAmount" class="form-label">Net Premium Amount *</label>
                        @if ($errors->has('net_premium_amount'))
                            <span class="text-danger">{{ $errors->first('net_premium_amount') }}</span>
                        @endif
                        <span class="text-danger net_premium_amount"></span>
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <input type="number" class="form-control" min="0" name="gross_premium_amount" id="GrossPremiumAmount" value="{{$policy->gross_premium_amount}}" placeholder="" />
                        <label for="GrossPremiumAmount" class="form-label">Gross Premium Amount *</label>
                        @if ($errors->has('gross_premium_amount'))
                            <span class="text-danger">{{ $errors->first('gross_premium_amount') }}</span>
                        @endif
                        <span class="text-danger gross_premium_amount"></span>
                    </div>
                    <div class="col-md-6 form-floating mt-4 motor">
                        <select class="form-control" name="business_type" id="BusinessType" value="{{$policy->business_type}}" placeholder="">
                            <option value="1" @if($policy->business_type == 1) selected @endif>New</option>
                            <option value="2" @if($policy->business_type == 2) selected @endif>Renewal</option>
                            <option value="3" @if($policy->business_type == 3) selected @endif>Rollover</option>
                            <option value="4" @if($policy->business_type == 4) selected @endif>Used</option>
                        </select>
                        <label for="BusinessType" class="form-label">Business Type *</label>
                        @if ($errors->has('business_type'))
                            <span class="text-danger">{{ $errors->first('business_type') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4 pyp business_type @if(old('business_type')) @if(old('business_type') == 1 || old('business_type') == 4) d-none @endif @elseif($policy->business_type == 1 || $policy->business_type == 4) d-none @endif">
                        <input type="text" name="pyp_no" id="PypNo" value="{{$policy->pyp_no}}" class="form-control">
                        <label for="PypNo" class="form-label">Pyp No *</label>
                        @if ($errors->has('pyp_no'))
                            <span class="text-danger">{{ $errors->first('pyp_no') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4 pyp business_type @if(old('business_type')) @if(old('business_type') == 1 || old('business_type') == 4) d-none @endif @elseif($policy->business_type == 1 || $policy->business_type == 4) d-none @endif">
                        <input type="text" name="pyp_insurance_company" id="PypInsuranceCompany" value="{{$policy->pyp_insurance_company}}" class="form-control">
                        <label for="PypInsuranceCompany" class="form-label">Pyp Insurance Company  *</label>
                        @if ($errors->has('pyp_insurance_company'))
                            <span class="text-danger">{{ $errors->first('pyp_insurance_company') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4 pyp business_type @if(old('business_type')) @if(old('business_type') == 1 || old('business_type') == 4) d-none @endif @elseif($policy->business_type == 1 || $policy->business_type == 4) d-none @endif">
                        <input type="date" name="pyp_expiry_date" id="PypExpiryDate" value="{{date('Y-m-d',strtotime($policy->pyp_expiry_date))}}" class="form-control">
                        <label for="PypExpiryDate" class="form-label">Pyp Expiry Date *</label>
                        @if ($errors->has('pyp_expiry_date'))
                            <span class="text-danger">{{ $errors->first('pyp_expiry_date') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4 health">
                        <select class="form-control" name="health_business_type" id="HealthBusinessType" placeholder="">
                            <option value="1" @if($policy->business_type == 1) selected @endif>New</option>
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
                     <div class="col-md-6 mt-4">
                        <label for="RiskStartDate" class="form-label form-date-label">Risk Start Date & Risk End Date </label>
                    <div class="col-md-12 form-floating">
                        <div class="custom-date-div-outer-main">
                            <div class="custom-date-div-outer justify-content-between d-flex">
                                <span class="custom-date-span w-100">
                                    <input type="date" class="form-control custom-start-date-div custom-date-div" name="risk_start_date" id="RiskStartDate" value="{{(date('Y-m-d',strtotime($policy->risk_start_date)))?date('Y-m-d',strtotime($policy->risk_start_date)):date('Y-m-d')}}" placeholder="" />
                                </span>
                                <span class="custom-date-to mt-2">To</span>
                                <span class="custom-date-span w-100">
                                    <input type="date" class="form-control custom-end-date-div custom-date-div" name="risk_end_date" id="RiskEndDate" value="{{(date('Y-m-d',strtotime($policy->risk_end_date)))?date('Y-m-d',strtotime($policy->risk_end_date)):date('Y-m-d', strtotime('+1 year -1 day'))}}" placeholder="" readonly/>
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
                    <!--    <input type="date" class="form-control" name="risk_start_date" id="RiskStartDate" value="{{date('Y-m-d',strtotime($policy->risk_start_date))}}" placeholder="" />-->
                    <!--    <label for="RiskStartDate" class="form-label">Risk Start Date *</label>-->
                    <!--    @if ($errors->has('risk_start_date'))-->
                    <!--        <span class="text-danger">{{ $errors->first('risk_start_date') }}</span>-->
                    <!--    @endif-->
                    <!--    <span class="text-danger risk_start_date"></span>-->
                    <!--</div>-->
                    <!--<div class="col-md-6 form-floating mt-4">-->
                    <!--    <input type="date" class="form-control" name="risk_end_date" id="RiskEndDate" value="{{date('Y-m-d',strtotime($policy->risk_end_date))}}" placeholder="" />-->
                    <!--    <label for="RiskEndDate" class="form-label">Risk End Date *</label>-->
                    <!--    @if ($errors->has('risk_end_date'))-->
                    <!--        <span class="text-danger">{{ $errors->first('risk_end_date') }}</span>-->
                    <!--    @endif-->
                    <!--    <span class="text-danger risk_end_date"></span>-->
                    <!--</div>-->
                    <div class="col-md-6 form-floating mt-4">
                        <select class="form-control" name="payment_type" id="PaymentType" placeholder="">
                            <option value="1" @if($policy->payment_type == 1) selected @endif>Cash</option>
                            <option value="2" @if($policy->payment_type == 2) selected @endif>Cheque</option>
                            <option value="3" @if($policy->payment_type == 3) selected @endif>Online</option>
                        </select>
                        <label for="PaymentType" class="form-label">Payment Type</label>
                        @if ($errors->has('payment_type'))
                            <span class="text-danger">{{ $errors->first('payment_type') }}</span>
                        @endif
                    </div>
                    <div class="row cheque" style="@if(old('payment_type')) @if(old('payment_type') != 2) display:none @endif @elseif($policy->payment_type != 2) display:none @endif">
                        <div class="col-md-6 form-floating mt-4">
                            <input type="text" class="form-control" name="cheque_no" id="chequeNo" value="{{$policy->cheque_no}}" placeholder="" />
                            <label for="chequeNo" class="form-label">Cheque No *</label>
                            @if ($errors->has('cheque_no'))
                                <span class="text-danger">{{ $errors->first('cheque_no') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6 form-floating mt-4">
                            <input type="date" class="form-control" name="cheque_date" id="cheque_date" value="{{date('Y-m-d',strtotime($policy->cheque_date))}}" placeholder="" />
                            <label for="cheque_date" class="form-label">Cheque Date *</label>
                            @if ($errors->has('cheque_date'))
                                <span class="text-danger">{{ $errors->first('cheque_date') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6 form-floating mt-4">
                            <input type="text" class="form-control" min="0" name="bank_name" id="BankName" value="{{$policy->bank_name}}" placeholder="" />
                            <label for="BankName" class="form-label">Bank Name *</label>
                            @if ($errors->has('bank_name'))
                                <span class="text-danger">{{ $errors->first('bank_name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row online" style="@if(old('payment_type')) @if(old('payment_type') != 3) display:none @endif @elseif($policy->payment_type != 3) display:none @endif">
                        <div class="col-md-6 form-floating mt-4">
                            <input type="text" class="form-control" name="transaction_no" id="TransactionNo" value="{{$policy->transaction_no}}" placeholder="" />
                            <label for="TransactionNo" class="form-label">Transaction No *</label>
                            @if ($errors->has('transaction_no'))
                                <span class="text-danger">{{ $errors->first('transaction_no') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group mb-4 mt-4">
                        <label class="form-label opacity-75 fw-medium" for="formImage">Covernote Attachments</label>
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
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="times"><path fill="#FFFFFF" d="M13.41,12l4.3-4.29a1,1,0,1,0-1.42-1.42L12,10.59,7.71,6.29A1,1,0,0,0,6.29,7.71L10.59,12l-4.3,4.29a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0L12,13.41l4.29,4.3a1,1,0,0,0,1.42,0,1,1,0,0,0,0-1.42Z"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="invalid-feedback fw-bold">Please upload an image.</div>
                    </div>
                </div>
                <div class="col-12">
                    <input type="hidden" name="id" value="{{$policy->id}}">
                    <button type="button" id="formSubmit" class="formSubmit btn btn-primary mt-3">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/min/dropzone.min.js"></script>
<script>
    $(document).ready(function(){
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
                url: '{{ route("get_cat_covernote_parameters") }}',
                data: {'id': sub_cat,'edit':"{{$policy->id}}"},
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
        }
        $(document).on('change','#BusinessType',function(){
            var business_type = $(this).val();
            if(business_type == 1){
                $('.pyp').css('display','none');
            }else{
                $('.pyp').css('display','block');
            }
        });
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
        $(document).on('change','#PaymentType',function(){
            var type = $(this).val();
            if(type == 2){
                $('.cheque').css('display','flex');
                $('.online').css('display','none');
            }else if(type == 3){
                $('.cheque').css('display','none');
                $('.online').css('display','flex');
            }else{
                $('.cheque').css('display','none');
                $('.online').css('display','none');
            }
        });
    });
</script>
<script>
    Dropzone.autoDiscover = false;

    var myDropzone = new Dropzone('#formDropzone',{
        previewTemplate: $('#dzPreviewContainer').html(),
        url: "{{route('admin.edit.covernote.data')}}",
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
        init: function()
        {
            // myDropzone = this;
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
                $.ajax({
                    type: 'get',
                    url: "{{route('admin.get_covernote_attachment',$policy->id)}}",
                    success: function(mocks){
                        $.each(mocks, function(key,value) {
                            var url = value.url;
                            var ext1 = value.name.split('.').pop();
                            if (ext1 == "pdf") {
                                var url = "{{url('/')}}/assets/Images/pdf.png";
                            } else if (ext1.indexOf("doc") != -1) {
                                var url = "{{url('/')}}/assets/Images/docs.png";
                            } else if (ext1.indexOf("xls") != -1) {
                                var url = "{{url('/')}}/assets/Images/docs.png";
                            }else{
                                var url = value.url;
                            }
                            var mockFile = { name: value.name, size: 1024, path:url, accepted: true, type: "application/pdf"};
                            // var mockFile = { name: value.name, size: 1024, path:value.url, accepted: true, type: "application/pdf"};
                            console.log(value.url);
                            myDropzone.files.push(mockFile);
                            myDropzone.emit("addedfile", mockFile);
                            // myDropzone.displayExistingFile(mockFile, value.url);
                            // if(mockFile.type != 'application/pdf'){
                                myDropzone.emit("thumbnail", mockFile, url);
                            // }
                            myDropzone.emit("complete", mockFile);
                        });
                    }
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
                            var policy_type = $('.policy').val();
                            var insurance_type = $('#InsuranceType').val();
                            var health_category = $('#HealthCategory').val();
                            var health_plan = $('#HealthPlan').val();
                            var i = 0;
                            var myButton = document.getElementById('formSubmit');
                            if(insurance_type == 1){
                                if(subcategory == 0){
                                    i++;
                                    $('.subcategory').html('Please select Subcategory.');
                                     hideLoader(myButton);
                                }
                                if(subcategory != 0){
                                    if(category == 1 && policy_type == 1 && individual_rate == ''){
                                        i++;
                                        $('.individual_rate').html('Individual Rate field is required.');
                                         hideLoader(myButton);
                                    }
                                }
                                if(vehicle_model == ''){
                                    i++;
                                    $('.vehicle_model').html('Vehicle Model field is required.');
                                     hideLoader(myButton);
                                }
                                if(vehicle_chassis_no == ''){
                                    i++;
                                    $('.vehicle_chassis_no').html ('Vehicle Chassis No field is required.');
                                     hideLoader(myButton);
                                }
                                if(vehicle_make == ''){
                                    i++;
                                    $('.vehicle_make').html('Vehicle Make field is required.');
                                     hideLoader(myButton);
                                }
                                if(registration_no == ''){
                                    i++;
                                    $('.vehicle_registration_no').html('Vehicle Registration No field is required.');
                                     hideLoader(myButton);
                                }
                                if(year_of_manufacture == ''){
                                    i++;
                                    $('.year_of_manufacture').html('Year Of Manufacture field is required.');
                                     hideLoader(myButton);
                                }
                                if(idv_amount == ''){
                                    i++;
                                    $('.idv_amount').html('Idv Amount field is required.');
                                     hideLoader(myButton);
                                }
                                if(net_premium == ''){
                                    i++;
                                    $('.net_premium_amount').html('Net Premium Amount field is required.');
                                     hideLoader(myButton);
                                }
                                if(gross_premium == ''){
                                    i++;
                                    $('.gross_premium_amount').html('Gross Premium Amount field is required.');
                                     hideLoader(myButton);
                                }
                            }
                            if(insurance_type == 2){
                                if(health_category == 0){
                                    i++;
                                    $('.health_category').html('Please Select health category.');
                                     hideLoader(myButton);
                                }
                                if(health_plan == 0){
                                    i++;
                                    $('.health_plan').html('Please select health plan.');
                                     hideLoader(myButton);
                                }
                            }
                            if(sourcing_agent == 0){
                                i++;
                                $('.sourcing_agent').html('Sourcing Agent field is required.');
                                 hideLoader(myButton);
                            }
                            if(risk_start_date == ''){
                                i++;
                                $('.risk_start_date').html('Risk Start Date field is required.');
                                 hideLoader(myButton);
                            }
                            if(risk_end_date == ''){
                                i++;
                                $('.risk_end_date').html('Risk End Date field is required.');
                                 hideLoader(myButton);
                            }
                            if(customer == 0){
                                i++;
                                $('.customer').html('Please select Customer.');
                                 hideLoader(myButton);
                            }
                            if(i == 0){
                                myDropzone.processQueue();
                                 showLoader(myButton);
                            }
                        }else{
                            var myButton = document.getElementById('formSubmit');
                            showLoader(myButton);
                            $('#formDropzone').submit();
                        }
                    // }
                });
                this.on("sendingmultiple", function(data, xhr, formData) {

                    var fileSelect = document.getElementById('PolicyDocument');

                    if(fileSelect != null){
                        if(fileSelect.files[0] != "undefined" && fileSelect.files[0] != null ){
                        formData.append('policy_document', fileSelect.files[0], fileSelect.files[0].name)
                        }
                    }
                });
        },
        success: function(file, response)
        {
            // hide form and show success message
            // $('#formDropzone').fadeOut(600);
            // if(response == ''){
                top.location.href="{{ route('admin.covernote') }}";
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
