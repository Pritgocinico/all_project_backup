<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0"></label>Name</div>
    <div class="col-md-4">
        <input type="text" name="pa_company_name" id="pa_company_name" class="form-control"
            placeholder="Enter PA Company Name" value="{{ $insuranceData->pa_company_name }}">
        @error('pa_company_name')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Occupation(Nature Of Dutied)</label></div>
    <div class="col-md-4">
        <input type="text" name="occupation" id="occupation" class="form-control" placeholder="Enter Occuopation" value="{{ $insuranceData->occupation }}">
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Type of Case </label></div>
    <div class="col-md-4">
        <select name="pa_type_case" id="pa_type_case" class="form-control">
            <option value="">Select Type Case</option>
            <option value="fresh" {{ $insuranceData->type_case == "fresh" ? 'selected'  : ""}}>Fresh</option>
            <option value="renewal" {{ $insuranceData->type_case == "renewal" ? 'selected'  : ""}}>Renewal</option>
            <option value="rollover" {{ $insuranceData->type_case == "rollover" ? 'selected'  : ""}}>Rollover</option>
        </select>
    </div>
</div>
<div class="allign-items-center row mt-6 g-3 pa_renew_policy_div d-none">
    <div class="col-md-2">
        <label class="form-label mb-0">Policy Number</label>
    </div>
    <div class="col-md-4">
        <input type="text" name="policy_number" id="policy_number" class="form-control"
            placeholder="Enter Policy Number" value="{{ $insuranceData->policy_number }}">
        @error('policy_number')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label mb-0">Policy Insurer name</label>
    </div>
    <div class="col-md-4">
        <input type="text" name="policy_insurer_name" id="policy_insurer_name" class="form-control"
            placeholder="Enter Policy Insurer name" value="{{ $insuranceData->policy_insurer_name }}">
        @error('policy_insurer_name')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2 pa_renew_policy_div d-none">
        <label class="form-label mb-0">Expiry Date</label>
    </div>
    <div class="col-md-4 pa_renew_policy_div d-none">
        <input type="date" name="health_policy_expiry_date" id="health_policy_expiry_date" class="form-control"
            value="{{ $insuranceData->expiry_date }}">
        @error('health_policy_expiry_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label mb-0">Plan Type</label>
    </div>
    <div class="col-md-4">
        <select name="plan_type" id="plan_type" class="form-control" disabled>
            <option value="">Select Plan Type</option>
            <option value="individual" {{ $insuranceData->plan_type == "individual" ? 'selected'  : ""}}>Individual</option>
            <option value="floater" {{ $insuranceData->plan_type == "floater" ? 'selected'  : ""}}>Floater</option>
        </select>
        @error('plan_type')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2">
        <label class="form-label mb-0">Policy Start Date</label>
    </div>
    <div class="col-md-4">
        <input type="date" name="pa_policy_start_date" id="pa_policy_start_date" class="form-control"
            value="{{ $insuranceData->policy_start_date }}">
        @error('pa_policy_start_date')
            <div class="text-danger">{{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label mb-0">Policy End Date</label>
    </div>
    <div class="col-md-4">
        <input type="date" name="pa_policy_end_date" id="pa_policy_end_date" class="form-control"
            value="{{ $insuranceData->policy_end_date }}">
        @error('pa_policy_end_date')
            <div class="text-danger">{{ $message }}
            </div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2">
        <label class="form-label mb-0">Sum Insured</label>
    </div>
    <div class="col-md-4">
        <input type="number" name="pa_sum_insured" id="pa_sum_insured" class="form-control"
            value="{{ $insuranceData->total_sum_insured }}">
        @error('pa_sum_insured')
            <div class="text-danger">{{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label mb-0">Physical disability/defect if any</label>
    </div>
    <div class="col-md-4">
        <textarea name="physical_disable" id="physical_disable" class="form-control">{{ $insuranceData->physical_disable }}</textarea>
        @error('physical_disable')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label for="" class="form-label mb-0">Gross Monthly Salary Amount</label></div>
    <div class="col-md-4">
        <input type="number" name="gross_monthly_salary" id="gross_monthly_salary" class="form-control"
            placeholder="Enter Gross Monthly Salary Amount" value="{{ $insuranceData->monthly_salary }}">
        @error('gross_monthly_salary')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Accidental Dealth Coverage</label></div>
    <div class="col-md-4">
        <select name="accidental_death" id="accidental_death" class="form-control">
            <option value="">Select Accidental Death Coverage</option>
            <option value="yes" {{ $insuranceData->accident_coverage == "yes" ? 'selected'  : ""}}>Yes</option>
            <option value="no" {{ $insuranceData->accident_coverage == "no" ? 'selected'  : ""}}>No</option>
        </select>
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Permanent Partial Disability+Permanent Total Disability</label></div>
    <div class="col-md-4">
        <select name="permanent_disability" id="permanent_disability" class="form-control">
            <option value="">Select Permanent Partial Disability+Permanent Total Disability</option>
            <option value="yes" {{ $insuranceData->permanent_disability == "yes" ? 'selected'  : ""}}>Yes</option>
            <option value="no" {{ $insuranceData->permanent_disability == "no" ? 'selected'  : ""}}>No</option>
        </select>
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Loss of Income Benefit/TTD</label></div>
    <div class="col-md-4">
        <select name="loss_of_income" id="loss_of_income" class="form-control">
            <option value="">Select Loss of Income Benefit/TTD</option>
            <option value="yes" {{ $insuranceData->loss_income_benefit == "yes" ? 'selected'  : ""}}>Yes</option>
            <option value="no" {{ $insuranceData->loss_income_benefit == "no" ? 'selected'  : ""}}>No</option>
        </select>
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Fracture Care</label></div>
    <div class="col-md-4">
        <select name="fracture_care" id="fracture_care" class="form-control">
            <option value="">Select Fracture Care</option>
            <option value="yes" {{ $insuranceData->fracture_care == "yes" ? 'selected'  : ""}}>Yes</option>
            <option value="no" {{ $insuranceData->fracture_care == "no" ? 'selected'  : ""}}>No</option>
        </select>
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Hospital Cash Benefit</label></div>
    <div class="col-md-4">
        <select name="hospital_cash_benefit" id="hospital_cash_benefit" class="form-control">
            <option value="">Select Hospital Cash Benefit</option>
            <option value="yes" {{ $insuranceData->hospital_road_ambulance_covercash_benefit == "yes" ? 'selected'  : ""}}>Yes</option>
            <option value="no" {{ $insuranceData->hospital_cash_benefit == "no" ? 'selected'  : ""}}>No</option>
        </select>
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Road Ambulance Cover</label></div>
    <div class="col-md-4">
        <select name="road_ambulance_cover" id="road_ambulance_cover" class="form-control">
            <option value="">Select Road Ambulance Cover</option>
            <option value="yes" {{ $insuranceData->road_ambulance_cover == "yes" ? 'selected'  : ""}}>Yes</option>
            <option value="no" {{ $insuranceData->road_ambulance_cover == "no" ? 'selected'  : ""}}>No</option>
        </select>
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Travel Expenses benefit</label></div>
    <div class="col-md-4">
        <select name="travel_expenses_benefit" id="travel_expenses_benefit" class="form-control">
            <option value="">Select Travel expenses benefit</option>
            <option value="yes" {{ $insuranceData->travel_expense_benefit == "yes" ? 'selected'  : ""}}>Yes</option>
            <option value="no" {{ $insuranceData->travel_expense_benefit == "no" ? 'selected'  : ""}}>No</option>
        </select>
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Accidental Hospitalization Expenses</label></div>
    <div class="col-md-4">
        <select name="accidental_hospitalization_expenses" id="accidental_hospitalization_expenses"
            class="form-control">
            <option value="">Select Accidental Hospitalization expenses</option>
            <option value="yes" {{ $insuranceData->accidental_hospitalization_expenses == "yes" ? 'selected'  : ""}}>Yes</option>
            <option value="no" {{ $insuranceData->accidental_hospitalization_expenses == "no" ? 'selected'  : ""}}>No</option>
        </select>
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Adventure Sports Benefit</label></div>
    <div class="col-md-4">
        <select name="adventure_sports_benefit" id="adventure_sports_benefit" class="form-control">
            <option value="">Select Adventure Sports Benefit</option>
            <option value="yes" {{ $insuranceData->adventure_sports_benefit == "yes" ? 'selected'  : ""}}>Yes</option>
            <option value="no" {{ $insuranceData->adventure_sports_benefit == "no" ? 'selected'  : ""}}>No</option>
        </select>
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Air Ambulance Cover</label></div>
    <div class="col-md-4">
        <select name="air_ambulance_cover" id="air_ambulance_cover" class="form-control">
            <option value="">Select Air Ambulance Cover</option>
            <option value="yes" {{ $insuranceData->air_ambulance_cover == "yes" ? 'selected'  : ""}}>Yes</option>
            <option value="no" {{ $insuranceData->air_ambulance_cover == "no" ? 'selected'  : ""}}>No</option>
        </select>
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Children's Education Benefit</label></div>
    <div class="col-md-4">
        <select name="childrens_education_benefit" id="childrens_education_benefit" class="form-control">
            <option value="">Select Children's Education Benefit</option>
            <option value="yes" {{ $insuranceData->child_education_benefit == "yes" ? 'selected'  : ""}}>Yes</option>
            <option value="no" {{ $insuranceData->child_education_benefit == "no" ? 'selected'  : ""}}>No</option>
        </select>
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Coma Due to Accidental Bodily Injury</label></div>
    <div class="col-md-4">
        <select name="coma_due_to_accidental_bodily_care" id="coma_due_to_accidental_bodily_care"
            class="form-control">
            <option value="">Select coma due to Accidental Bodily Care</option>
            <option value="yes" {{ $insuranceData->comma_due_accident == "yes" ? 'selected'  : ""}}>Yes</option>
            <option value="no" {{ $insuranceData->comma_due_accident == "no" ? 'selected'  : ""}}>No</option>
        </select>
    </div>
    <div class="col-md-2"><label class="form-label mb-0">EMI Payment Cover</label></div>
    <div class="col-md-4">
        <select name="emi_payment_cover" id="emi_payment_cover" class="form-control">
            <option value="">Select EMI Payment Cover</option>
            <option value="yes" {{ $insuranceData->emi_payment_cover == "yes" ? 'selected'  : ""}}>Yes</option>
            <option value="no" {{ $insuranceData->emi_payment_cover == "no" ? 'selected'  : ""}}>No</option>
        </select>
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">PA Policy Attachement</label></div>
    <div class="col-md-4">
        <input type="file" name="pa_policy_attachement[]" id="pa_policy_attachement" class="form-control" multiple>
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Other Attachment</label></div>
    <div class="col-md-4">
        <input type="file" name="pa_other_attachment[]" id="pa_other_attachment" class="form-control" multiple>
    </div>
</div>