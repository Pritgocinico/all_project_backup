<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0"></label>Name of Company</div>
    <div class="col-md-4">
        <input type="text" name="name_company" id="name_company" class="form-control" placeholder="Enter Company Name" value="{{ $insuranceData->pa_company_name }}">
        @error('name_company')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Policy Period</label></div>
    <div class="col-md-4">
        <select name="policy_period" id="policy_period" class="form-control">
            <option value="">Select Policy Period</option>
            @for ($i = 1; $i <= 12; $i++)
                <option value="{{$i}}" {{ $insuranceData->policy_period == $i ? 'selected'  : ""}}>{{$i}} Month</option>
            @endfor
        </select>
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2">
        <label class="form-label mb-0">Nature Of Business</label>
    </div>
    <div class="col-md-4">
        <input type="text" name="nature_of_business" id="nature_of_business" class="form-control" placeholder="Enter Nature Of Business" value="{{ $insuranceData->nature_business }}">
        @error('nature_of_business')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label mb-0">Risk Occupancy/Scope of Work</label>
    </div>
    <div class="col-md-4">
        <textarea name="risk_occupancy_scope_work" id="risk_occupancy_scope_work" class="form-control"
            placeholder="Enter Risk Occupancy/Scope of Work">{{ $insuranceData->risk_occupancy }}</textarea>
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Health Policy type</label>
    </div>
    <div class="col-md-4 col-xl-4">
        <select name="wc_health_policy_type" class="form-control health_policy_type" id="health_policy_type">
            <option value="">Select Health Policy type</option>
            <option value="new" {{ $insuranceData->health_policy_type == "new" ? 'selected'  : ""}}>New</option>
            <option value="renewal" {{ $insuranceData->health_policy_type == "renewal" ? 'selected'  : ""}}>Renewal</option>
        </select>
        @error('health_policy_type')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2 renew_policy_div d-none"><label class="form-label mb-0">Previous Policy No</label></div>
    <div class="col-md-4 renew_policy_div d-none">
        <input type="text" name="previous_policy" class="form-control" value="{{ $insuranceData->previous_policy }}"
            placeholder="Enter Previous Policy Number" >
    </div>
</div>
<div class="row align-items-center g-3 mt-6 renew_policy_div d-none">
    <div class="col-md-2"><label class="form-label mb-0">Insurer Name</label></div>
    <div class="col-md-4">
        <input type="text" name="sum_insurance" class="form-control" placeholder="Enter Insurer Name" value="{{ $insuranceData->sum_insurance }}">
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Expiry Date</label></div>
    <div class="col-md-4">
        <input type="date" name="expiry_date" class="form-control" value="{{ $insuranceData->expiry_date }}">
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Total Number of Employees</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="total_employee" id="total_employee" class="form-control" placeholder="Enter Total Employee" value="{{ $insuranceData->total_employees }}">
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Total Wages (Rs.)</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="total_wage" id="total_wage" class="form-control" value="{{ $insuranceData->total_wage }}">
        @error('total_wage')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Skilled</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="skilled_employee" id="skilled_employee" class="form-control" placeholder="Enter Skilled Employee" value="{{ $insuranceData->skilled }}">
        @error('skilled_employee')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Unskilled</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="unskilled_employee" id="unskilled_employee" class="form-control" placeholder="Enter UnSkilled Employee" value="{{ $insuranceData->un_skilled }}">
        @error('unskilled_employee')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Commercial Traveller</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="commercial_travel" id="commercial_travel" class="form-control" placeholder="Enter Commercial Traveller" value="{{ $insuranceData->commercial_travel }}">
        @error('commercial_travel')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Claim History last 3 years</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="claim_history" id="claim_history" class="form-control" placeholder="Enter Claim History last 3 years" value="{{ $insuranceData->three_year_claim_history }}">
        @error('claim_history')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Medical Extension Limit per person</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="medical_extension_limit" class="form-control" id="medical_extension_limit">
            <option value="">Select Medical Extension Limit per person</option>
            <option value="50,000" {{ $insuranceData->medical_extension == "50,000" ? 'selected'  : ""}}>50,000</option>
            <option value="1,00,000" {{ $insuranceData->medical_extension == "1,00,000" ? 'selected'  : ""}}>1,00,000</option>
            <option value="2,00,000" {{ $insuranceData->medical_extension == "2,00,000" ? 'selected'  : ""}}>2,00,000</option>
            <option value="3,00,000" {{ $insuranceData->medical_extension == "3,00,000" ? 'selected'  : ""}}>3,00,000</option>
            <option value="4,00,000" {{ $insuranceData->medical_extension == "4,00,000" ? 'selected'  : ""}}>4,00,000</option>
            <option value="5,00,000" {{ $insuranceData->medical_extension == "5,00,000" ? 'selected'  : ""}}>5,00,000</option>
            <option value="at_actual" {{ $insuranceData->medical_extension == "at_actual" ? 'selected'  : ""}}>At Actual</option>
        </select>
        @error('medical_extension_limit')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2 actual_amount_div d-none"><label class="form-label mb-0">Actual Amount</label></div>
    <div class="col-md-4 col-xl-4 actual_amount_div d-none">
        <input type="number" name="actual_amount" id="actual_amount" placeholder="Enter Actual Amount" class="form-control">
        @error('basement')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Number of Shifts</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="number_shift" id="number_shift" class="form-control">
            <option value="">Select Number of Shifts</option>
            <option value="1" {{ $insuranceData->number_shift == "1" ? 'selected'  : ""}}>One</option>
            <option value="2" {{ $insuranceData->number_shift == "2" ? 'selected'  : ""}}>Two</option>
            <option value="3" {{ $insuranceData->number_shift == "3" ? 'selected'  : ""}}>Three</option>
        </select>
        @error('number_shift')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Distance from Nearest Hospital in kms.</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="distance_near_hospital" class="form-control" id="distance_near_hospital" placeholder="Enter Distance from Nearest Hospital in kms." value="{{ $insuranceData->distance_near_hospital }}">
        @error('distance_near_hospital')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">First Aid Kit Available at work site</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="first_aid_kit" class="form-control" id="first_aid_kit">
            <option value="">Select First Aid Kit Available at work site</option>
            <option value="Yes" {{ $insuranceData->first_aid_kit == "yes" ? 'selected'  : ""}}>Yes</option>
            <option value="No" {{ $insuranceData->first_aid_kit == "no" ? 'selected'  : ""}}>No</option>
        </select>
        @error('first_aid_kit')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Fire Extinguishers, Fire hydrant system</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="fire_extinguishers" class="form-control" id="fire_extinguishers">
            <option value="">Select Fire Extinguishers, Fire hydrant system</option>
            <option value="Yes" {{ $insuranceData->first_extinguishers == "yes" ? 'selected'  : ""}}>Yes</option>
            <option value="No" {{ $insuranceData->first_extinguishers == "no" ? 'selected'  : ""}}>No</option>
        </select>
        @error('fire_extinguishers')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">24x7 Security person in Premise</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="wc_security_person" class="form-control" id="wc_security_person">
            <option value="">Select 24x7 Security person in Premise</option>
            <option value="Yes" {{ $insuranceData->security_cctv == "yes" ? 'selected'  : ""}}>Yes</option>
            <option value="No" {{ $insuranceData->security_cctv == "no" ? 'selected'  : ""}}>No</option>
        </select>
        @error('wc_security_person')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">CCTV Camera Installed</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="cctv_camera_install" class="form-control" id="cctv_camera_install">
            <option value="">Select CCTV Camera Installed</option>
            <option value="Yes" {{ $insuranceData->cctv_camera_install == "yes" ? 'selected'  : ""}}>Yes</option>
            <option value="No" {{ $insuranceData->cctv_camera_install == "no" ? 'selected'  : ""}}>No</option>
        </select>
        @error('cctv_camera_install')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Existing Policy copies</label></div>
    <div class="col-md-4">
        <input type="file" name="wc_exist_policy[]" class="form-control" multiple id="wc_exist_policy">
    </div>
    <div class="col-md-6">
        <div class="row existing_lead_div_outer">
            @foreach ($wcExistingPolicyCopy as $policyCopy)
                <div class="col-md-2 existing_lead_div position-relative"
                    id="remove_exist_policy_div{{ $policyCopy->id }}">
                    <a href="{{ asset('storage/' . $policyCopy->file_path) }}" target="_blank" btn=""
                        class="btn btn-dark btn-sm w-100" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $policyCopy->file_name }}">View</a>
                    <span>{{ $policyCopy->file_name }}</span>
                    <a class="position-absolute lead_file_remove_btn" data-bs-toggle="tooltip"
                        onclick="removeExistPolicy({{ $policyCopy->id }})" data-bs-placement="top"
                        title="Remove File"><i class="fa-solid fa-circle-xmark"></i></a>
                </div>
            @endforeach
        </div>
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Employee Data Sheet</label></div>
    <div class="col-md-4">
        <input type="file" name="employee_data_sheet[]" class="form-control" multiple id="employee_data_sheet">
    </div>
    <div class="col-md-6">
        <div class="row existing_lead_div_outer">
            @foreach ($employeeDataSheet as $empDataSheet)
                <div class="col-md-2 existing_lead_div position-relative"
                    id="remove_emp_sheet_div{{ $empDataSheet->id }}">
                    <a href="{{ asset('storage/' . $empDataSheet->file_path) }}" target="_blank"
                        class="btn btn-dark btn-sm w-100" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $empDataSheet->file_name }}">View</a>
                    <span>{{ $empDataSheet->file_name }}</span>
                    <a class="position-absolute lead_file_remove_btn" data-bs-toggle="tooltip"
                        onclick="removeEmpDataSheet({{ $empDataSheet->id }})" data-bs-placement="top"
                        title="Remove File"><i class="fa-solid fa-circle-xmark"></i></a>
                </div>
            @endforeach
        </div>
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Others</label></div>
    <div class="col-md-4">
        <input type="file" name="wc_other_attachmet[]" class="form-control" multiple id="wc_other_attachmet">
    </div>
    <div class="col-md-6">
        <div class="row existing_lead_div_outer">
            @foreach ($wcOtherAttachment as $otherAttac)
                <div class="col-md-2 existing_lead_div position-relative"
                    id="remove_fire_other_div{{ $otherAttac->id }}">
                    <a href="{{ asset('storage/' . $otherAttac->file_path) }}" target="_blank"
                        class="btn btn-dark btn-sm w-100" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $otherAttac->file_name }}">View</a>
                    <span>{{ $otherAttac->file_name }}</span>
                    <a class="position-absolute lead_file_remove_btn" data-bs-toggle="tooltip"
                        onclick="removeFireOther({{ $otherAttac->id }})" data-bs-placement="top"
                        title="Remove File"><i class="fa-solid fa-circle-xmark"></i></a>
                </div>
            @endforeach
        </div>
    </div>
</div>
