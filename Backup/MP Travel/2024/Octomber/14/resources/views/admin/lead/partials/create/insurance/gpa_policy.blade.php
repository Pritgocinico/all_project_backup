<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Policy Period Start Date</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="date" name="gpa_policy_period_start_date" class="form-control" value="{{ date('Y-m-d') }}"
            id="gpa_policy_period_start_date" min="{{ date('Y-m-d') }}">
        @error('gpa_policy_period_start_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Policy Period End Date</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="date" name="gpa_policy_period_end_date" class="form-control" id="gpa_policy_period_end_date"
            value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
        @error('gpa_policy_period_end_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Sum Insured</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="gpa_sum_insured" class="form-control" 
            id="gpa_sum_insured">
        @error('gpa_sum_insured')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Type of Business/Occupancy</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="type_business" class="form-control" 
            id="type_business">
        @error('type_business')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Accidental Death Cover</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="accident_death_cover"class="form-control" placeholder="Enter Accidental Death Cover"
            id="accident_death_cover">
        @error('accident_death_cover')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Permanent Total Disability ( PTD )</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="permanent_total_disability"  class="form-control"
            id="permanent_total_disability" placeholder="Enter Permanent Total Disability ( PTD )">
        @error('permanent_total_disability')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Permanent Partial Disability ( PPD )</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="permanent_partial_disability" class="form-control"
            id="permanent_partial_disability" placeholder="Enter Permanent Partial Disability ( PPD )">
        @error('permanent_partial_disability')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Loss of Income Benefit/TTD</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="loss_income" class="form-control"
            id="loss_income" placeholder="Enter Loss of Income Benefit/TTD">
        @error('loss_income')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Ambulance Cover</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="ambulance_cover" id="ambulance_cover"
            class="form-control">
        @error('ambulance_cover')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Accidental Hospitalization Cover</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="accidental_hospital_cover" id="accidental_hospital_cover"
            class="form-control" placeholder="Enter Accidental Hospitalization Cover">
        @error('sum_Insured')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Cashless Facility during Hospitalization</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="cashless_facility_hospital" id="cashless_facility_hospital"
            class="form-control" placeholder="Enter Cashless Facility during Hospitalization">
        @error('cashless_facility_hospital')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Burn Expenses</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="burn_expense" id="burn_expense" class="form-control" placeholder="Enter Burn Expenses">
        @error('burn_expense')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Broken Bone Cover</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="broken_bone_cover" id="broken_bone_cover" class="form-control" placeholder="Enter Broken Bone Cover">
        @error('broken_bone_cover')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Repatriation of mortal remains</label></div>
    <div class="col-md-4 col-xl-4">
        <textarea name="report_mortal_remain" id="report_mortal_remain" class="form-control"
            placeholder="Enter Repatriation of mortal remains"></textarea>
        @error('report_mortal_remain')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Daily cash allowances</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="daily_cash_allowance" id="daily_cash_allowance" class="form-control" placeholder="Enter Daily cash allowances">
        @error('daily_cash_allowance')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Carriage of dead body</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="carriage_dead_body" id="carriage_dead_body" placeholder="Enter Carriage of dead body" class="form-control">
        @error('carriage_dead_body')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    
    </div>
    <div class="col-md-2"><label class="form-label mb-0">children education grant</label></div>
    <div class="col-md-4 col-xl-4">
        <textarea name="children_education_grant" id="children_education_grant" class="form-control"
            placeholder="Enter children education grant"></textarea>
        @error('children_education_grant')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Existing Policy copies if any</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="file" name="gpa_exist_policy_copy[]" class="form-control" id="gpa_exist_policy_copy" multiple>
        @error('gpa_exist_policy_copy')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Employee data sheet</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="file" name="gpa_emp_data_sheet[]" class="form-control" id="gpa_emp_data_sheet" multiple>
        @error('gpa_emp_data_sheet')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Last 3 years claim history</label></div>
    <div class="col-md-4">
        <input type="file" name="gpa_claim_history[]" class="form-control" multiple id="gpa_claim_history">
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Claim Dump with MIS</label></div>
    <div class="col-md-4">
        <input type="file" name="claim_mis[]" class="form-control" multiple id="claim_mis">
    </div>
</div>