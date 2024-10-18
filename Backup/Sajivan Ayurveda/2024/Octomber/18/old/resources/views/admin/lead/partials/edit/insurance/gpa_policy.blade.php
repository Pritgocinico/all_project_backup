<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Policy Period Start Date</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="date" name="gpa_policy_period_start_date" class="form-control"
            value="{{ $insuranceData->policy_start_date }}" id="gpa_policy_period_start_date" min="{{ date('Y-m-d') }}">
        @error('gpa_policy_period_start_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Policy Period End Date</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="date" name="gpa_policy_period_end_date" class="form-control" id="gpa_policy_period_end_date"
            value="{{ $insuranceData->policy_end_date }}" min="{{ date('Y-m-d') }}">
        @error('gpa_policy_period_end_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Sum Insured</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="gpa_sum_insured" class="form-control"
            value="{{ $insuranceData->total_sum_insured }}" id="gpa_sum_insured">
        @error('gpa_sum_insured')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Type of Business/Occupancy</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="type_business" class="form-control" value="{{ $insuranceData->business_type }}"
            id="type_business">
        @error('type_business')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Accidental Death Cover</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" value="{{ $insuranceData->accident_coverage }}"
            name="accident_death_cover"class="form-control" placeholder="Enter Accidental Death Cover"
            id="accident_death_cover">
        @error('accident_death_cover')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Permanent Total Disability ( PTD )</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" value="{{ $insuranceData->permanent_total_disability }}"
            name="permanent_total_disability" class="form-control" id="permanent_total_disability"
            placeholder="Enter Permanent Total Disability ( PTD )">
        @error('permanent_total_disability')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Permanent Partial Disability ( PPD )</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" value="{{ $insuranceData->permanent_partial_disability }}"
            name="permanent_partial_disability" class="form-control" id="permanent_partial_disability"
            placeholder="Enter Permanent Partial Disability ( PPD )">
        @error('permanent_partial_disability')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Loss of Income Benefit/TTD</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" value="{{ $insuranceData->loss_income_benefit }}" name="loss_income" class="form-control"
            id="loss_income" placeholder="Enter Loss of Income Benefit/TTD">
        @error('loss_income')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Ambulance Cover</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" value="{{ $insuranceData->road_ambulance_cover }}" name="ambulance_cover"
            id="ambulance_cover" class="form-control">
        @error('ambulance_cover')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Accidental Hospitalization Cover</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="accidental_hospital_cover" id="accidental_hospital_cover"
            value="{{ $insuranceData->accidental_hospital_cover }}" class="form-control"
            placeholder="Enter Accidental Hospitalization Cover">
        @error('sum_Insured')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Cashless Facility during Hospitalization</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" value="{{ $insuranceData->cashless_facility_hospital }}"
            name="cashless_facility_hospital" id="cashless_facility_hospital" class="form-control"
            placeholder="Enter Cashless Facility during Hospitalization">
        @error('cashless_facility_hospital')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Burn Expenses</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" value="{{ $insuranceData->burn_expense }}" name="burn_expense" id="burn_expense"
            class="form-control" placeholder="Enter Burn Expenses">
        @error('burn_expense')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Broken Bone Cover</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" value="{{ $insuranceData->broken_bone_cover }}" name="broken_bone_cover"
            id="broken_bone_cover" class="form-control" placeholder="Enter Broken Bone Cover">
        @error('broken_bone_cover')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Repatriation of mortal remains</label></div>
    <div class="col-md-4 col-xl-4">
        <textarea name="report_mortal_remain" id="report_mortal_remain" class="form-control"
            placeholder="Enter Repatriation of mortal remains">{{ $insuranceData->report_mortal_remain }}</textarea>
        @error('report_mortal_remain')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Carriage of dead body</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" value="{{ $insuranceData->carriage_dead_body }}" name="carriage_dead_body"
            id="carriage_dead_body" placeholder="Enter Carriage of dead body" class="form-control">
        @error('carriage_dead_body')
            <div class="text-danger">{{ $message }}</div>
        @enderror

    </div>
    <div class="col-md-2"><label class="form-label mb-0">children education grant</label></div>
    <div class="col-md-4 col-xl-4">
        <textarea name="children_education_grant" id="children_education_grant" class="form-control"
            placeholder="Enter children education grant">{{ $insuranceData->child_education_benefit }}</textarea>
        @error('children_education_grant')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Daily cash allowances</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="daily_cash_allowance" value="{{ $insuranceData->daily_cash_allowance }}"
            id="daily_cash_allowance" class="form-control" placeholder="Enter Daily cash allowances">
        @error('daily_cash_allowance')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Existing Policy copies if any</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="file" name="gpa_exist_policy_copy[]" class="form-control" id="gpa_exist_policy_copy"
            multiple>
        @error('gpa_exist_policy_copy')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <div class="row existing_lead_div_outer">
            @foreach ($gpaExistingPolicyCopy as $gpaPolicy)
                <div class="col-md-2 existing_lead_div position-relative"
                    id="remove_exist_policy_div{{ $gpaPolicy->id }}">
                    <a href="{{ asset('storage/' . $gpaPolicy->file_path) }}" target="_blank" btn=""
                        class="btn btn-dark btn-sm w-100" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $gpaPolicy->file_name }}">View</a>
                    <span>{{ $gpaPolicy->file_name }}</span>
                    <a class="position-absolute lead_file_remove_btn" data-bs-toggle="tooltip"
                        onclick="removeExistPolicy({{ $gpaPolicy->id }})" data-bs-placement="top"
                        title="Remove File"><i class="fa-solid fa-circle-xmark"></i></a>
                </div>
            @endforeach
        </div>
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Employee data sheet</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="file" name="gpa_emp_data_sheet[]" class="form-control" id="gpa_emp_data_sheet" multiple>
        @error('gpa_emp_data_sheet')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <div class="row existing_lead_div_outer">
            @foreach ($employeeDataSheet as $empData)
                <div class="col-md-2 existing_lead_div position-relative"
                    id="remove_emp_sheet_div{{ $empData->id }}">
                    <a href="{{ asset('storage/' . $empData->file_path) }}" target="_blank" btn=""
                        class="btn btn-dark btn-sm w-100" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $empData->file_name }}">View</a>
                    <span>{{ $empData->file_name }}</span>
                    <a class="position-absolute lead_file_remove_btn" data-bs-toggle="tooltip"
                        onclick="removeEmpDataSheet({{ $empData->id }})" data-bs-placement="top"
                        title="Remove File"><i class="fa-solid fa-circle-xmark"></i></a>
                </div>
            @endforeach
        </div>
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Last 3 years claim history</label></div>
    <div class="col-md-4">
        <input type="file" name="gpa_claim_history[]" class="form-control" multiple id="gpa_claim_history">
    </div>
    <div class="col-md-6">
        <div class="row existing_lead_div_outer">
            @foreach ($gpaClaimHistory as $claimHistory)
                <div class="col-md-2 existing_lead_div position-relative"
                    id="remove_claim_history_div{{ $claimHistory->id }}">
                    <a href="{{ asset('storage/' . $claimHistory->file_path) }}" target="_blank" btn=""
                        class="btn btn-dark btn-sm w-100" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $claimHistory->file_name }}">View</a>
                    <span>{{ $claimHistory->file_name }}</span>
                    <a class="position-absolute lead_file_remove_btn" data-bs-toggle="tooltip"
                        onclick="removeClaimHistory({{ $claimHistory->id }})" data-bs-placement="top"
                        title="Remove File"><i class="fa-solid fa-circle-xmark"></i></a>
                </div>
            @endforeach
        </div>
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Claim Dump with MIS</label></div>
    <div class="col-md-4">
        <input type="file" name="claim_mis[]" class="form-control" multiple id="claim_mis">
    </div>
    <div class="col-md-6">
        <div class="row existing_lead_div_outer">
            @foreach ($gpaDumpData as $gpaDump)
                <div class="col-md-2 existing_lead_div position-relative"
                    id="remove_gpa_dump_div{{ $gpaDump->id }}">
                    <a href="{{ asset('storage/' . $gpaDump->file_path) }}" target="_blank" btn=""
                        class="btn btn-dark btn-sm w-100" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $gpaDump->file_name }}">View</a>
                    <span>{{ $gpaDump->file_name }}</span>
                    <a class="position-absolute lead_file_remove_btn" data-bs-toggle="tooltip"
                        onclick="removeGpaDump({{ $gpaDump->id }})" data-bs-placement="top"
                        title="Remove File"><i class="fa-solid fa-circle-xmark"></i></a>
                </div>
            @endforeach
        </div>
    </div>
</div>
