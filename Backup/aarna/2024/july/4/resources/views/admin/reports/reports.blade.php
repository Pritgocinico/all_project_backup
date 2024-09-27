@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
                <!-- <li class="nav-item" role="presentation">
                    <a href="#pills-business-source" class="nav-link me-2 active" id="pills-business-source-tab" data-bs-toggle="pill" data-bs-target="#pills-business-source" type="button" role="tab" aria-controls="pills-business-source" aria-selected="true">Business Source</a>
                </li> -->
                <li class="nav-item" role="presentation">
                    <a href="#pills-policy" class="nav-link me-2 active" id="pills-policy-tab" data-bs-toggle="pill" data-bs-target="#pills-policy" type="button" role="tab" aria-controls="pills-policy" aria-selected="true">Policy</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#pills-endorsement" class="nav-link me-2" id="pills-endorsement-tab" data-bs-toggle="pill" data-bs-target="#pills-endorsement" type="button" role="tab" aria-controls="pills-endorsement" aria-selected="false">Endorsement</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#pills-claim" class="nav-link me-2" id="pills-claim-tab" data-bs-toggle="pill" data-bs-target="#pills-claim" type="button" role="tab" aria-controls="pill-claim" aria-selected="false">Claim</a>
                </li>
            </ul>
            <hr>
            <div class="tab-content " id="pills-tabContent">
                <div class="tab-pane fade" id="pills-business-source" role="tabpanel" aria-labelledby="pills-business-source-tab">
                    <div class="row px-3">
                        <form action="{{route('admin.business_source_report')}}" method="post" class="row" id="businessSourceForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 form-floating mt-4">
                                    <select class="form-control" name="insurance_type" id="InsuranceType" value="{{old('insurance_type')}}" placeholder="">
                                        <option value="1">Motor Insurance</option>
                                        <option value="2">Health Insurance</option>
                                    </select>
                                    <label for="InsuranceType" class="form-label">Insurance Type *</label>
                                    @if ($errors->has('insurance_type'))
                                        <span class="text-danger">{{ $errors->first('insurance_type') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-floating mt-4 motor-insurance">
                                    <select class="form-control" name="motor_category" id="MotorCategory" value="{{old('motor_category')}}" placeholder="">
                                        <option value="0">Select Category...</option>
                                        @foreach ($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                    <label for="MotorCategory" class="form-label">Category *</label>
                                    @if ($errors->has('motor_category'))
                                        <span class="text-danger">{{ $errors->first('motor_category') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4 motor-insurance">
                                    <select class="form-control" name="motor_subcategory" id="MotorSubCategory" value="{{old('motor_subcategory')}}" placeholder="">
                                        <option value="0">Select Sub Category...</option>
                                        @foreach ($sub_categories as $sub_category)
                                            <option value="{{$sub_category->id}}">{{$sub_category->name}}</option>
                                        @endforeach
                                    </select>
                                    <label for="MotorSubCategory" class="form-label">SubCategory *</label>
                                    @if ($errors->has('motor_subcategory'))
                                        <span class="text-danger">{{ $errors->first('motor_subcategory') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <select class="form-control" name="insurance_company" id="InsuranceCompany" value="{{old('insurance_company')}}" placeholder="">
                                        <option value="0">Select Company...</option>
                                        @foreach ($companies as $company)
                                            <option value="{{$company->id}}">{{$company->name}}</option>
                                        @endforeach
                                    </select>
                                    <label for="InsuranceCompany" class="form-label">Insurance Company *</label>
                                    @if ($errors->has('insurance_company'))
                                        <span class="text-danger">{{ $errors->first('insurance_company') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <select class="form-control select2" name="customer" id="Customer" value="{{old('customer')}}" placeholder="">
                                        <option value="0">Select Customer...</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                        @endforeach
                                    </select>
                                    <label for="Customer" class="form-label">Customer *</label>
                                    @if ($errors->has('customer'))
                                        <span class="text-danger">{{ $errors->first('customer') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4 motor-insurance">
                                    <select class="form-control" name="business_type" id="BusinessType" value="{{old('business_type')}}" placeholder="">
                                        <option value="0">Select Business Type...</option>
                                        <option value="1">New</option>
                                        <option value="2">Renewal</option>
                                        <option value="3">Rollover</option>
                                        <option value="4">Used</option>
                                    </select>
                                    <label for="Customer" class="form-label">Business Type *</label>
                                    @if ($errors->has('business_type'))
                                        <span class="text-danger">{{ $errors->first('business_type') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4  motor-insurance">
                                    <input type="text" class="form-control" name="vehicle_chassis_no" id="vehicleChassisNo" value="{{old('vehicle_chassis_no')}}" placeholder="">
                                    <label for="vehicleChassisNo" class="form-label">Vehicle Chassis No *</label>
                                    @if ($errors->has('vehicle_chassis_no'))
                                        <span class="text-danger">{{ $errors->first('vehicle_chassis_no') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4 health-insurance d-none">
                                    <select class="form-control" name="health_category" id="HealthCategory" value="{{old('motor_category')}}" placeholder="">
                                        <option value="0">Select Category...</option>
                                        <option value="1">Base</option>
                                        <option value="2">Personal Accident</option>
                                        <option value="3">Super Yopup</option>
                                    </select>
                                    <label for="HealthCategory" class="form-label">Category *</label>
                                    @if ($errors->has('health_category'))
                                        <span class="text-danger">{{ $errors->first('health_category') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <select class="form-control" name="insurance_company" id="InsuranceCompany" value="{{old('insurance_company')}}" placeholder="">
                                        <option value="0">Select Company...</option>
                                        @foreach ($companies as $company)
                                            <option value="{{$company->id}}">{{$company->name}}</option>
                                        @endforeach
                                    </select>
                                    <label for="InsuranceCompany" class="form-label">Insurance Company *</label>
                                    @if ($errors->has('insurance_company'))
                                        <span class="text-danger">{{ $errors->first('health_insurance_company') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <select class="form-control" name="health_plan" id="HealthPlan" value="{{old('health_plan')}}" placeholder="">
                                        <option value="0">Select Health Plan...</option>
                                        @foreach ($plans as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    <label for="HealthPlan" class="form-label">Health Plan *</label>
                                    @if ($errors->has('health_plan'))
                                        <span class="text-danger">{{ $errors->first('health_plan') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4 health-insurance d-none">
                                    <select class="form-control" name="health_business_type" id="HealthBusinessType" value="{{old('health_business_type')}}" placeholder="">
                                        <option value="0">Select Business Type...</option>
                                        <option value="1">New</option>
                                        <option value="2">Renewal</option>
                                        <option value="3">Rollover</option>
                                        <option value="4">Used</option>
                                    </select>
                                    <label for="HealthBusinessType" class="form-label">Business Type *</label>
                                    @if ($errors->has('health_business_type'))
                                        <span class="text-danger">{{ $errors->first('health_business_type') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-floating mt-4">
                                    <input type="date" class="form-control" name="business_source_from" id="BusinessSourceFrom" value="{{old('business_source_from')}}" placeholder="" />
                                    <label for="BusinessSourceFrom" class="form-label">Business Source From</label>
                                    @if ($errors->has('business_source_from'))
                                        <span class="text-danger">{{ $errors->first('business_source_from') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <input type="date" class="form-control" name="business_source_to" id="BusinessSourceTo" value="{{old('business_source_to')}}" placeholder="" />
                                    <label for="BusinessSourceTo" class="form-label">Business Source To</label>
                                    @if ($errors->has('business_source_to'))
                                        <span class="text-danger">{{ $errors->first('business_source_to') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 text-center mt-2">
                                <input type="hidden" name="business_report_type" class="business_report_type" value="">
                                <a href="javascript:void(0);" class="btn btn-info mt-3 businessSourceReportCSV" data-type="csv">
                                    Export To CSV
                                </a>
                                <a href="javascript:void(0);" class="btn btn-secondary mt-3 businessSourceReportCSV" data-type="pdf">
                                    Export To PDF
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade show active" id="pills-policy" role="tabpanel" aria-labelledby="pills-policy-tab">
                    <div class="row px-3">
                        <form action="{{route('admin.policy_report')}}" method="post" class="row" id="policyForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 form-floating mt-4">
                                    <select class="form-control" name="insurance_type" id="InsuranceType" value="{{old('insurance_type')}}" placeholder="">
                                        <option value="1">Motor Insurance</option>
                                        <option value="2">Health Insurance</option>
                                    </select>
                                    <label for="InsuranceType" class="form-label">Insurance Type *</label>
                                    @if ($errors->has('insurance_type'))
                                        <span class="text-danger">{{ $errors->first('insurance_type') }}</span>
                                    @endif
                                </div>
                                 <div class="col-md-6 form-floating mt-4">
                                    <select class="form-control" name="agent_name" id="agent_name" value="{{old('agent_name')}}" placeholder="">
                                         <option value="0">Select Agent...</option>
                                        @foreach ($agents as $agent)
                                            <option value="{{$agent->id}}">{{$agent->name}}</option>
                                        @endforeach
                                    </select>
                                    <label for="agent_name" class="form-label">Agent Name</label>
                                    @if ($errors->has('agent_name'))
                                        <span class="text-danger">{{ $errors->first('agent_name') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <input type="date" class="form-control" name="created_date" id="created_date" value="{{old('created_date')}}" placeholder="" />
                                    <label for="created_date" class="form-label">Created Date</label>
                                    @if ($errors->has('created_date'))
                                        <span class="text-danger">{{ $errors->first('created_date') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-floating mt-4 motor-insurance">
                                    <select class="form-control" name="motor_category" id="MotorCategory" value="{{old('motor_category')}}" placeholder="">
                                        <option value="0">Select Category...</option>
                                        @foreach ($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                    <label for="MotorCategory" class="form-label">Category</label>
                                    @if ($errors->has('motor_category'))
                                        <span class="text-danger">{{ $errors->first('motor_category') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4 motor-insurance">
                                    <select class="form-control" name="motor_subcategory" id="MotorSubCategory" value="{{old('motor_subcategory')}}" placeholder="">
                                        <option value="0">Select Sub Category...</option>
                                        <!-- @foreach ($sub_categories as $sub_category)
                                            <option value="{{$sub_category->id}}">{{$sub_category->name}}</option>
                                        @endforeach -->
                                    </select>
                                    <label for="MotorSubCategory" class="form-label">SubCategory</label>
                                    @if ($errors->has('motor_subcategory'))
                                        <span class="text-danger">{{ $errors->first('motor_subcategory') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4  health-insurance d-none">
                                    <select class="form-control" name="health_category" id="HealthCategory" value="{{old('motor_category')}}" placeholder="">
                                        <option value="0">Select Category...</option>
                                        <option value="1">Base</option>
                                        <option value="2">Personal Accident</option>
                                        <option value="3">Super Yopup</option>
                                    </select>
                                    <label for="HealthCategory" class="form-label">Category</label>
                                    @if ($errors->has('health_category'))
                                        <span class="text-danger">{{ $errors->first('health_category') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <select class="form-control" name="insurance_company" id="PolicyInsuranceCompany" value="{{old('insurance_company')}}" placeholder="">
                                        <option value="0">Select Company...</option>
                                        @foreach ($companies as $company)
                                            <option value="{{$company->id}}">{{$company->name}}</option>
                                        @endforeach
                                    </select>
                                    <label for="PolicyInsuranceCompany" class="form-label">Insurance Company</label>
                                    @if ($errors->has('insurance_company'))
                                        <span class="text-danger">{{ $errors->first('insurance_company') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <input type="text" name="customer" class="form-control" id="" value="{{old('customer')}}">
                                    <label for="Customer" class="form-label">Customer</label>
                                    @if ($errors->has('customer'))
                                        <span class="text-danger">{{ $errors->first('customer') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4 motor-insurance">
                                    <select class="form-control" name="business_type" id="BusinessType" value="{{old('business_type')}}" placeholder="">
                                        <option value="0">Select Business Type...</option>
                                        <option value="1">New</option>
                                        <option value="2">Renewal</option>
                                        <option value="3">Rollover</option>
                                        <option value="4">Used</option>
                                    </select>
                                    <label for="Customer" class="form-label">Business Type</label>
                                    @if ($errors->has('business_type'))
                                        <span class="text-danger">{{ $errors->first('business_type') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4 motor-insurance">
                                    <input type="text" class="form-control" name="vehicle_chassis_no" id="vehicleChassisNo" value="{{old('vehicle_chassis_no')}}" placeholder="">
                                    <label for="vehicleChassisNo" class="form-label">Vehicle Chassis No *</label>
                                    @if ($errors->has('vehicle_chassis_no'))
                                        <span class="text-danger">{{ $errors->first('vehicle_chassis_no') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4  health-insurance d-none">
                                    <select class="form-control" name="health_plan" id="PolicyHealthPlan" value="{{old('health_plan')}}" placeholder="">
                                        <option value="0">Select Health Plan...</option>
                                        @foreach ($plans as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    <label for="PolicyHealthPlan" class="form-label">Health Plan</label>
                                    @if ($errors->has('health_plan'))
                                        <span class="text-danger">{{ $errors->first('health_plan') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4  health-insurance d-none">
                                    <select class="form-control" name="health_business_type" id="HealthBusinessType" value="{{old('health_business_type')}}" placeholder="">
                                        <option value="0">Select Business Type...</option>
                                        <option value="1">New</option>
                                        <option value="2">Renewal</option>
                                        <option value="3">Portability</option>
                                    </select>
                                    <label for="HealthBusinessType" class="form-label">Business Type</label>
                                    @if ($errors->has('health_business_type'))
                                        <span class="text-danger">{{ $errors->first('health_business_type') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <input type="date" class="form-control" name="policy_created_from" id="policyCreatedFrom" value="{{old('policy_created_from')}}" placeholder="" />
                                    <label for="policyCreatedFrom" class="form-label">Policy Created Start Date</label>
                                    @if ($errors->has('policy_created_from'))
                                        <span class="text-danger">{{ $errors->first('policy_created_from') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <input type="date" class="form-control" name="policy_created_to" id="policyCreatedTo" value="{{old('policy_created_to')}}" placeholder="" />
                                    <label for="policyCreatedTo" class="form-label">Policy Created End Date</label>
                                    @if ($errors->has('policy_created_to'))
                                        <span class="text-danger">{{ $errors->first('policy_created_to') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <input type="date" class="form-control" name="policy_expiry_from" id="policyExpiryFrom" value="{{old('policy_expiry_from')}}" placeholder="" />
                                    <label for="policyExpiryFrom" class="form-label">Policy Expiry Start Date</label>
                                    @if ($errors->has('policy_expiry_from'))
                                        <span class="text-danger">{{ $errors->first('policy_expiry_from') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <input type="date" class="form-control" name="policy_expiry_to" id="policyExpiryFrom" value="{{old('policy_expiry_to')}}" placeholder="" />
                                    <label for="policyExpiryTo" class="form-label">Policy Expiry End Date</label>
                                    @if ($errors->has('policy_expiry_to'))
                                        <span class="text-danger">{{ $errors->first('policy_expiry_to') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 text-center mt-2">
                                <input type="hidden" name="policy_report_type" class="policy_report_type" value="">
                                <a href="javascript:void(0);" class="btn btn-info mt-3 policyReportCSV" data-type="csv">
                                    Export To CSV
                                </a>
                                <a href="javascript:void(0);" class="btn btn-secondary mt-3 policyReportCSV" data-type="pdf">
                                    Export To PDF
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-endorsement" role="tabpanel" aria-labelledby="pills-endorsement-tab">
                    <div class="row p-3">
                        <form action="{{route('admin.endorsement_report')}}" method="post" class="row" id="endorsementForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 form-floating mt-4">
                                    <input type="date" class="form-control" name="endorsement_start_date" id="EndorsementStartDate" value="{{old('endorsement_start_date')}}" placeholder="">
                                    <label for="EndorsementStartDate" class="form-label">Endorsement Start Date</label>
                                    @if ($errors->has('endorsement_start_date'))
                                        <span class="text-danger">{{ $errors->first('endorsement_start_date') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <input type="date" class="form-control" name="endorsement_end_date" id="EndorsementEndDate" value="{{old('endorsement_end_date')}}" placeholder="">
                                    <label for="EndorsementEndDate" class="form-label">Endorsement End Date</label>
                                    @if ($errors->has('endorsement_end_date'))
                                        <span class="text-danger">{{ $errors->first('endorsement_end_date') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <select class="form-control" name="insurance_company" id="InsuranceCompany" value="{{old('insurance_company')}}" placeholder="">
                                        <option value="0">Select Company...</option>
                                        @foreach ($companies as $company)
                                            <option value="{{$company->id}}">{{$company->name}}</option>
                                        @endforeach
                                    </select>
                                    <label for="InsuranceCompany" class="form-label">Insurance Company</label>
                                    @if ($errors->has('insurance_company'))
                                        <span class="text-danger">{{ $errors->first('insurance_company') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <input type="text" class="form-control" name="customer_name" id="CustomerName" value="{{old('customer_name')}}" placeholder="">
                                    <label for="CustomerName" class="form-label">Customer Name</label>
                                    @if ($errors->has('customer_name'))
                                        <span class="text-danger">{{ $errors->first('customer_name') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <input type="text" class="form-control" name="endorsement_details" id="EndorsementDetails" value="{{old('endorsement_details')}}" placeholder="">
                                    <label for="EndorsementDetails" class="form-label">Details of Endorsement</label>
                                    @if ($errors->has('endorsement_details'))
                                        <span class="text-danger">{{ $errors->first('endorsement_details') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 text-center mt-2">
                                <input type="hidden" name="endorsement_report_type" class="endorsement_report_type" value="">
                                <a href="javascript:void(0);" class="btn btn-info mt-3 endorsementReportCSV" data-type="csv">
                                    Export To CSV
                                </a>
                                <a href="javascript:void(0);" class="btn btn-secondary mt-3 endorsementReportCSV" data-type="pdf">
                                    Export To PDF
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-claim" role="tabpanel" aria-labelledby="pills-claim-tab">
                    <div class="row p-3">
                        <form action="{{route('admin.claim_report')}}" method="post" class="row" id="claimForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 form-floating mt-4">
                                    <input type="date" class="form-control" name="claim_start_date" id="ClaimStartDate" value="{{old('claim_start_date')}}" placeholder="">
                                    <label for="ClaimStartDate" class="form-label">Endorsement Start Date</label>
                                    @if ($errors->has('claim_start_date'))
                                        <span class="text-danger">{{ $errors->first('claim_start_date') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <input type="date" class="form-control" name="claim_end_date" id="ClaimEndDate" value="{{old('claim_end_date')}}" placeholder="">
                                    <label for="ClaimEndDate" class="form-label">Endorsement End Date</label>
                                    @if ($errors->has('claim_end_date'))
                                        <span class="text-danger">{{ $errors->first('claim_end_date') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <select class="form-control" name="insurance_company" id="InsuranceCompany" value="{{old('insurance_company')}}" placeholder="">
                                        <option value="0">Select Company...</option>
                                        @foreach ($companies as $company)
                                            <option value="{{$company->id}}">{{$company->name}}</option>
                                        @endforeach
                                    </select>
                                    <label for="InsuranceCompany" class="form-label">Insurance Company</label>
                                    @if ($errors->has('insurance_company'))
                                        <span class="text-danger">{{ $errors->first('insurance_company') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <input type="text" class="form-control" name="customer_name" id="CustomerName" value="{{old('customer_name')}}" placeholder="">
                                    <label for="CustomerName" class="form-label">Customer Name</label>
                                    @if ($errors->has('customer_name'))
                                        <span class="text-danger">{{ $errors->first('customer_name') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <select class="form-control" name="claim_type" id="claimType" value="{{old('claim_type')}}" placeholder="">
                                        <option value="0">Select Claim Type...</option>
                                        <option value="1">OWN_DAMAGE</option>
                                        <option value="2">THIRD_PARTY</option>
                                    </select>
                                    <label for="claimType" class="form-label">Claim Type</label>
                                    @if ($errors->has('claim_type'))
                                        <span class="text-danger">{{ $errors->first('claim_type') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <select class="form-control" name="claim_status" id="claimStatus" value="{{old('claim_status')}}" placeholder="">
                                        <option value="0">Select Claim Status...</option>
                                        <option value="1">OPEN</option>
                                        <option value="2">CLOSE</option>
                                        <option value="3">REPUIDATED</option>
                                    </select>
                                    <label for="claimStatus" class="form-label">Claim Status</label>
                                    @if ($errors->has('claim_status'))
                                        <span class="text-danger">{{ $errors->first('claim_status') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 text-center mt-2">
                                <input type="hidden" name="claim_report_type" class="claim_report_type" value="">
                                <a href="javascript:void(0);" class="btn btn-info mt-3 claimReportCSV" data-type="csv">
                                    Export To CSV
                                </a>
                                <a href="javascript:void(0);" class="btn btn-secondary mt-3 claimReportCSV" data-type="pdf">
                                    Export To PDF
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-payments" role="tabpanel" aria-labelledby="pills-payments-tab">
                    <div class="row p-3">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
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
    $(document).on('change','#MotorCategory',function(){
        var cat = $(this).val();
        $.ajax({
            type: 'GET',
            url: '{{ route("get_cat_subcategory") }}',
            data: {'id': cat},
            success: function (data) {
                $('#MotorSubCategory').html('');
                $('#MotorSubCategory').append('<option value="0">Select Sub Category...</option>');
                $('#MotorSubCategory').append(data);
            },
            error: function (data) {
                // console.log(data);
            }
        });
    });
    $(document).on('change','#PolicyInsuranceCompany',function(){
        var company = $(this).val();
        $.ajax({
            type: 'GET',
            url: '{{ route("get_insurance_plan") }}',
            data: {'id': company},
            success: function (data) {
                $('#PolicyHealthPlan').html('');
                $('#PolicyHealthPlan').append('<option value="0">Select Health Plan...</option>');
                $('#PolicyHealthPlan').append(data);
            },
            error: function (data) {
                // console.log(data);
            }
        });
    });
    $(document).on('click','.businessSourceReportCSV',function(){
        var type = $(this).data('type');
        $('.business_report_type').val(type);
        $('#businessSourceForm').submit();
    });
    $(document).on('click','.endorsementReportCSV',function(){
        var type = $(this).data('type');
        $('.endorsement_report_type').val(type);
        $('#endorsementForm').submit();
    });
    $(document).on('click','.claimReportCSV',function(){
        var type = $(this).data('type');
        $('.claim_report_type').val(type);
        $('#claimForm').submit();
    });
    $(document).on('click','.policyReportCSV',function(){
        var type = $(this).data('type');
        $('.policy_report_type').val(type);
        $('#policyForm').submit();
    });
</script>
@endsection
