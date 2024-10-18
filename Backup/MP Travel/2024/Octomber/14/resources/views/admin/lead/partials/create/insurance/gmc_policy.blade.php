<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">COMPANY NAME:</label></div>
    <div class="col-md-4">
        <input type="text" name="gmc_policy_name" id="gmc_policy_name" placeholder="Enter Company Name" class="form-control">
        @error('gmc_policy_name')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Previous Policy if any</label></div>
    <div class="col-md-4">
        <input type="text" name="gmc_previous_policy" id="gmc_previous_policy" placeholder="Enter Previous Policy" class="form-control">
        @error('gmc_previous_policy')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Policy Period Start Date</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="date" name="gmc_policy_period_start_date" class="form-control" value="{{ date('Y-m-d') }}"
            id="gmc_policy_period_start_date" min="{{ date('Y-m-d') }}">
        @error('gmc_policy_period_start_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Policy Period End Date</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="date" name="gmc_policy_period_end_date" class="form-control" id="gmc_policy_period_end_date"
            value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
        @error('gmc_policy_period_end_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Total Lives Insured</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="gmc_sum_insured" class="form-control" id="gmc_sum_insured">
        @error('gmc_sum_insured')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Pre-Exzisting Diseases</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="exist_diseases" class="form-control" id="exist_diseases">
        @error('exist_diseases')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">9 Month waiting period waiver</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="nine_month_period"class="form-control"
            placeholder="Enter 9 Month waiting period waiver" id="nine_month_period">
        @error('nine_month_period')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">1st year waiting period</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="one_year_waiting" class="form-control" id="one_year_waiting"
            placeholder="Enter 1st year waiting period">
        @error('one_year_waiting')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Room Rent Capping</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="room_rent_capping" class="form-control"
            id="room_rent_capping" placeholder="Enter Room Rent Capping">
        @error('room_rent_capping')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Maternity Benefits</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="maternity_benefit" class="form-control" id="maternity_benefit"
            placeholder="Enter Maternity Benefits">
        @error('maternity_benefit')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Pre and Post Hospitalization</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="pre_post_hospital" id="pre_post_hospital" class="form-control">
        @error('pre_post_hospital')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Ambulance Charges per Hospitalization</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="ambulance_charge" id="ambulance_charge" class="form-control"
            placeholder="Enter Ambulance Charges per Hospitalization">
        @error('ambulance_charge')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Day Care procedures</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="day_care_procedures" id="day_care_procedures" class="form-control"
            placeholder="Enter Day Care procedures">
        @error('day_care_procedures')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Terrorism</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="terrorism" id="terrorism" class="form-control"
            placeholder="Enter terrorism">
        @error('terrorism')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Organ Donor Medical Exp.</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="organ_donor" id="organ_donor" class="form-control"
            placeholder="Enter Organ Donor Medical Exp.">
        @error('organ_donor')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Air Ambulance</label></div>
    <div class="col-md-4 col-xl-4">
        <textarea name="air_ambulance" id="air_ambulance" class="form-control"
            placeholder="Enter Air Ambulance"></textarea>
        @error('air_ambulance')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Internal/Ezternal Congenital disease</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="internal_external_disease" id="internal_external_disease"
            placeholder="Enter Internal/Ezternal Congenital disease" class="form-control">
        @error('internal_external_disease')
            <div class="text-danger">{{ $message }}</div>
        @enderror

    </div>
    <div class="col-md-2"><label class="form-label mb-0">Lucentis</label></div>
    <div class="col-md-4 col-xl-4">
        <textarea name="lucentis" id="lucentis" class="form-control"
            placeholder="Enter Lucentis"></textarea>
        @error('lucentis')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Attendant Charges</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="attendant_charge" id="attendant_charge"
            placeholder="Enter Attendant Charges" class="form-control">
        @error('attendant_charge')
            <div class="text-danger">{{ $message }}</div>
        @enderror

    </div>
    <div class="col-md-2"><label class="form-label mb-0">Reasonable and Customary Charges</label></div>
    <div class="col-md-4 col-xl-4">
       <select class="form-control" id="reasonable_charge" name="reasonable_charge">
        <option value="">Select Reasonable and Customary Charges</option>
        <option value="Yes">Yes</option>
        <option value="No">No</option>
       </select>
        @error('reasonable_charge')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Dental Treatment due to Accident only</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="dental_treatment_accident" id="dental_treatment_accident"
            placeholder="Enter Dental Treatment due to Accident only" class="form-control">
        @error('dental_treatment_accident')
            <div class="text-danger">{{ $message }}</div>
        @enderror

    </div>
    <div class="col-md-2"><label class="form-label mb-0">Automatic Sum Insured Reinstatement</label></div>
    <div class="col-md-4 col-xl-4">
        <textarea name="automatic_sum_insured" id="automatic_sum_insured" class="form-control"
            placeholder="Enter Automatic Sum Insured Reinstatement"></textarea>
        @error('automatic_sum_insured')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">All Modern treatment</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="modern_treatment" id="modern_treatment"
            placeholder="Enter All Modern treatment" class="form-control">
        @error('modern_treatment')
            <div class="text-danger">{{ $message }}</div>
        @enderror

    </div>
    <div class="col-md-2"><label class="form-label mb-0">Domiciliary Hospitalization</label></div>
    <div class="col-md-4 col-xl-4">
        <textarea name="domiciliary_hospital" id="domiciliary_hospital" class="form-control"
            placeholder="Enter Domiciliary Hospitalization"></textarea>
        @error('domiciliary_hospital')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">AYUSH Treatment</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="ayush_treatment" id="ayush_treatment"
            placeholder="Enter AYUSH Treatment" class="form-control">
        @error('ayush_treatment')
            <div class="text-danger">{{ $message }}</div>
        @enderror

    </div>
    <div class="col-md-2"><label class="form-label mb-0">Existing Policy copies if any</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="file" name="gmc_exist_policy_copy[]" class="form-control" id="gmc_exist_policy_copy" multiple>
        @error('gmc_exist_policy_copy')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Employee data sheet</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="file" name="gmc_emp_data_sheet[]" class="form-control" id="gmc_emp_data_sheet" multiple>
        @error('gmc_emp_data_sheet')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Last 3 years claim history</label></div>
    <div class="col-md-4">
        <input type="file" name="gmc_three_year_claim_history[]" class="form-control" multiple
        id="gmc_three_year_claim_history">
        @error('gmc_three_year_claim_history')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    
    <div class="col-md-2"><label class="form-label mb-0">Claim Dump with MIS</label></div>
    <div class="col-md-4">
        <input type="file" name="gmc_claim_mis[]" class="form-control" multiple id="gmc_claim_mis">
        @error('gmc_claim_mis')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
