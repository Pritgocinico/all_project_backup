@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.add.source.data')}}" method="post" class="row g-3">
                @csrf
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
                <div class="row motor-insurance">
                    <div class="col-md-6 form-floating mt-4">
                        <select class="form-control" name="motor_category" id="MotorCategory" value="{{old('motor_category')}}" placeholder="">
                            @foreach ($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                        <label for="MotorCategory" class="form-label">Category *</label>
                        @if ($errors->has('motor_category'))
                            <span class="text-danger">{{ $errors->first('motor_category') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <select class="form-control" name="motor_subcategory" id="MotorSubCategory" value="{{old('motor_subcategory')}}" placeholder="">
                            @foreach ($sub_categories as $sub_category)
                                <option value="{{$sub_category->id}}">{{$sub_category->name}}</option>
                            @endforeach
                        </select>
                        <label for="MotorSubCategory" class="form-label">SubCategory *</label>
                        @if ($errors->has('motor_subcategory'))
                            <span class="text-danger">{{ $errors->first('motor_subcategory') }}</span>
                        @endif
                    </div>
                    <div class="col-md-12 form-floating mt-4">
                        <select class="form-control" name="insurance_company" id="InsuranceCompany" value="{{old('insurance_company')}}" placeholder="">
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
                    <div class="col-md-6 form-floating mt-4">
                        <select class="form-control" name="business_type" id="BusinessType" value="{{old('business_type')}}" placeholder="">
                            <option value="1" @if(old('business_type') == 1) selected @endif>New</option>
                            <option value="2" @if(old('business_type') == 2) selected @endif>Renewal</option>
                            <option value="3" @if(old('business_type') == 3) selected @endif>Rollover</option>
                            <option value="4" @if(old('business_type') == 4) selected @endif>Used</option>
                        </select>
                        <label for="Customer" class="form-label">Business Type *</label>
                        @if ($errors->has('business_type'))
                            <span class="text-danger">{{ $errors->first('business_type') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4 business_type @if(old('business_type')) @if(old('business_type') == 1 || old('business_type') == 4) d-none @endif @else d-none @endif">
                        <input type="text" class="form-control" name="motor_pyp_no" id="PypNo" value="{{old('motor_pyp_no')}}" placeholder="" />
                        <label for="PypNo" class="form-label">Pyp No *</label>
                        @if ($errors->has('motor_pyp_no'))
                            <span class="text-danger">The Pyp No field is required.</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4 business_type @if(old('business_type')) @if(old('business_type') == 1 || old('business_type') == 4) d-none @endif @else d-none @endif">
                        <input type="text" class="form-control" name="motor_pyp_insurance_company" id="PypInsuranceCompany" value="{{old('motor_pyp_insurance_company')}}" placeholder="" />
                        <label for="PypInsuranceCompany" class="form-label">Pyp Insurance Company *</label>
                        @if ($errors->has('motor_pyp_insurance_company'))
                            <span class="text-danger">The Pyp Insurance Company field is required.</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4 business_type @if(old('business_type')) @if(old('business_type') == 1 || old('business_type') == 4) d-none @endif @else d-none @endif">
                        <input type="date" class="form-control" name="motor_pyp_expiry_date" id="PypExpiryDate" value="{{old('motor_pyp_expiry_date')}}" placeholder="" />
                        <label for="PypExpiryDate" class="form-label">Pyp Expiry Date *</label>
                        @if ($errors->has('motor_pyp_expiry_date'))
                            <span class="text-danger">The Pyp Expiry Date field is required.</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <input type="text" class="form-control" name="vehicle_chassis_no" id="vehicleChassisNo" value="{{old('vehicle_chassis_no')}}" placeholder="">
                        <label for="vehicleChassisNo" class="form-label">Vehicle Chassis No *</label>
                        @if ($errors->has('vehicle_chassis_no'))
                            <span class="text-danger">{{ $errors->first('vehicle_chassis_no') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <input type="date" class="form-control" name="risk_start_date" id="RiskStartDate" value="{{old('risk_start_date')}}" placeholder="" />
                        <label for="RiskStartDate" class="form-label">Risk Start Date *</label>
                        @if ($errors->has('risk_start_date'))
                            <span class="text-danger">{{ $errors->first('risk_start_date') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <input type="number" class="form-control" min="0" name="gross_premium_amount" id="GrossPremiumAmount" value="{{old('gross_premium_amount')}}" placeholder="" />
                        <label for="GrossPremiumAmount" class="form-label">Gross Premium Amount *</label>
                        @if ($errors->has('gross_premium_amount'))
                            <span class="text-danger">{{ $errors->first('gross_premium_amount') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <input type="number" class="form-control" min="0" name="net_premium_amount" id="NetPremiumAmount" value="{{old('net_premium_amount')}}" placeholder="" />
                        <label for="NetPremiumAmount" class="form-label">Net Premium Amount *</label>
                        @if ($errors->has('net_premium_amount'))
                            <span class="text-danger">{{ $errors->first('net_premium_amount') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row health-insurance d-none">
                    <div class="col-md-6 form-floating mt-4">
                        <select class="form-control" name="health_category" id="HealthCategory" value="{{old('motor_category')}}" placeholder="">
                            <option value="">Select Health Category</option>
                            @foreach ($healthCategory as $health)
                                <option value="{{$health->id}}" {{ old('health_category') == $health->id ? "checked" :"" }}>{{$health->name}}</option>
                            @endforeach
                        </select>
                        <label for="HealthCategory" class="form-label">Category *</label>
                        @if ($errors->has('health_category'))
                            <span class="text-danger">{{ $errors->first('health_category') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <select class="form-control" name="health_insurance_company" id="HealthInsuranceCompany" value="{{old('health_insurance_company')}}" placeholder="">
                            @foreach ($companies as $company)
                                <option value="{{$company->id}}">{{$company->name}}</option>
                            @endforeach
                        </select>
                        <label for="HealthInsuranceCompany" class="form-label">Insurance Company *</label>
                        @if ($errors->has('health_insurance_company'))
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
                    <div class="col-md-6 form-floating mt-4">
                        <select class="form-control select2" name="health_customer" id="HealthCustomer" value="{{old('health_customer')}}" placeholder="">
                            <option value="0">Select Customer...</option>
                            @foreach ($customers as $customer)
                                <option value="{{$customer->id}}">{{$customer->name}}</option>
                            @endforeach
                        </select>
                        <label for="HealthCustomer" class="form-label">Customer *</label>
                        @if ($errors->has('customer'))
                            <span class="text-danger">{{ $errors->first('customer') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <select class="form-control" name="health_business_type" id="HealthBusinessType" value="{{old('health_business_type')}}" placeholder="">
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
                    <div class="col-md-6 form-floating mt-4 business_type @if(old('business_type')) @if(old('business_type') == 1 || old('business_type') == 4) d-none @endif @else d-none @endif">
                        <input type="text" class="form-control" name="pyp_no" id="PypNo" value="{{old('pyp_no')}}" placeholder="" />
                        <label for="PypNo" class="form-label">Pyp No *</label>
                        @if ($errors->has('pyp_no'))
                            <span class="text-danger">The Pyp No field is required.</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4 business_type @if(old('business_type')) @if(old('business_type') == 1 || old('business_type') == 4) d-none @endif @else d-none @endif">
                        <input type="text" class="form-control" name="pyp_insurance_company" id="PypInsuranceCompany" value="{{old('pyp_insurance_company')}}" placeholder="" />
                        <label for="PypInsuranceCompany" class="form-label">Pyp Insurance Company *</label>
                        @if ($errors->has('pyp_insurance_company'))
                            <span class="text-danger">The Pyp Insurance Company field is required.</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4 business_type @if(old('business_type')) @if(old('business_type') == 1 || old('business_type') == 4) d-none @endif @else d-none @endif">
                        <input type="date" class="form-control" name="pyp_expiry_date" id="PypExpiryDate" value="{{old('pyp_expiry_date')}}" placeholder="" />
                        <label for="PypExpiryDate" class="form-label">Pyp Expiry Date *</label>
                        @if ($errors->has('pyp_expiry_date'))
                            <span class="text-danger">The Pyp Expiry Date field is required.</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <input type="date" class="form-control" name="health_risk_start_date" id="HealthRiskStartDate" value="{{old('health_risk_start_date')}}" placeholder="" />
                        <label for="HealthRiskStartDate" class="form-label">Risk Start Date *</label>
                        @if ($errors->has('health_risk_start_date'))
                            <span class="text-danger">{{ $errors->first('health_risk_start_date') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <input type="number" class="form-control" min="0" name="health_gross_premium_amount" id="HealthGrossPremiumAmount" value="{{old('health_gross_premium_amount')}}" placeholder="" />
                        <label for="HealthGrossPremiumAmount" class="form-label">Gross Premium Amount *</label>
                        @if ($errors->has('health_gross_premium_amount'))
                            <span class="text-danger">{{ $errors->first('health_gross_premium_amount') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <input type="number" class="form-control" min="0" name="health_net_premium_amount" id="HealthNetPremiumAmount" value="{{old('health_net_premium_amount')}}" placeholder="" />
                        <label for="HealthNetPremiumAmount" class="form-label">Net Premium Amount *</label>
                        @if ($errors->has('health_net_premium_amount'))
                            <span class="text-danger">{{ $errors->first('health_net_premium_amount') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function(){
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
        $(document).on('change','#BusinessType',function(){
            var business_type = $(this).val();
            if(business_type == 2 || business_type == 3){
                $('.business_type').removeClass('d-none');
            }else{
                $('.business_type').addClass('d-none');
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
                    $('#MotorSubCategory').append(data);
                },
                error: function (data) {
                    // console.log(data);
                }
            });
        });
        $(document).on('change','#HealthInsuranceCompany',function(){
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
    });
</script>
@endsection
