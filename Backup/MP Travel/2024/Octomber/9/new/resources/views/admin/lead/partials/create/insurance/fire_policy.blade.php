<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Health Policy type</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="health_policy_type" class="form-control health_policy_type" id="health_policy_type">
            <option value="">Select Health Policy type</option>
            <option value="new" selected>New</option>
            <option value="renewal">Renewal</option>
        </select>
        @error('health_policy_type')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2 renew_policy_div d-none"><label class="form-label mb-0">Previous Policy No</label></div>
    <div class="col-md-4 renew_policy_div d-none">
        <input type="text" name="previous_policy" class="form-control" value=""
            placeholder="Enter Previous Policy Number">
    </div>
</div>
<div class="row align-items-center g-3 mt-6 renew_policy_div d-none">
    <div class="col-md-2"><label class="form-label mb-0">Insurer Name</label></div>
    <div class="col-md-4">
        <input type="text" name="sum_insurance" class="form-control" placeholder="Enter Insurer Name">
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Expiry Date</label></div>
    <div class="col-md-4">
        <input type="date" name="expiry_date" class="form-control">
    </div>
</div>

<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Policy Period Start Date</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="date" name="policy_period_start_date" class="form-control" value="{{ date('Y-m-d') }}"
            id="policy_period_start_date" min="{{ date('Y-m-d') }}">
        @error('policy_period_start_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Policy Period End Date</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="date" name="policy_period_end_date" class="form-control" id="policy_period_end_date"
            value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
        @error('policy_period_end_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Financier Interest or Hypothecation</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="financier_interest_hypo" class="form-control" 
            id="financier_interest_hypo">
        @error('financier_interest_hypo')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Building Value(Rs.)</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="building_value" value="0" class="form-control total_sum_insured"
            id="building_value">
        @error('building_value')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Plant & Machinary Value(Rs.)</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="plant_machinary_value" value="0" class="form-control total_sum_insured"
            id="plant_machinary_value">
        @error('plant_machinary_value')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Total Stock IN PROCESS / RAW / FINISHED(Rs.)</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="total_stock_in_process" value="0" class="form-control total_sum_insured"
            id="total_stock_in_process">
        @error('total_stock_in_process')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">FFF & Other EE Value(Rs.)</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="fff_other_ee" class="form-control total_sum_insured" value="0"
            id="fff_other_ee">
        @error('fff_other_ee')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Other Content(Rs.)</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="other_content" value="0" id="other_content"
            class="form-control total_sum_insured">
        @error('other_content')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Total Sum Insured(Rs.)</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="sum_Insured" value="0" id="sum_Insured"
            class="form-control total_sum_insured">
        @error('sum_Insured')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Type Of Policies</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="type_of_policy" id="type_of_policy" class="form-select">
            <option value="">Select Type Of Policies</option>
            <option value="normal">Normal</option>
            <option value="floter">Floter</option>
        </select>
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Operational Fire Hydrant/Sprinkler/water</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="operational_fire" id="operational_fire" class="form-select">
            <option value="">Select Operational Fire Hydrant</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
        @error('operational_fire')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Burglary coverage required</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="burglary_coverage" id="burglary_coverage" class="form-select">
            <option value="">Select Burglary coverage required</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
        @error('burglary_coverage')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Burglary Sum Insured (Rs.)</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="burglary_sum_insured" id="burglary_sum_insured" placeholder="Enter Burglary Sum Insured (Rs.)" class="form-control">
        @error('burglary_sum_insured')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">First Loss Percentage</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="first_loss_percentage" id="first_loss_percentage" placeholder="Enter First Loss Percentage" class="form-control">
        @error('first_loss_percentage')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Theft Extension</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="theft_extension" id="theft_extension" class="form-select">
            <option value="">Select Operational Fire Hydrant</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
        @error('theft_extension')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Well Maintained Electrical</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="maintain_electrical" id="maintain_electrical" class="form-select">
            <option value="">Select Well Maintained Electrical</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Provision of Storm water drainage system</label></div>
    <div class="col-md-4 col-xl-4">
        <textarea name="provision_storm_water_drainage" id="provision_storm_water_drainage" class="form-control"
            placeholder="Enter Provision Of Storm Water drainage"></textarea>
        @error('provision_storm_water_drainage')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">24x7 Security and CCTV</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="security_and_cctv" id="security_and_cctv" class="form-select">
            <option value="">Select 24x7 Security and CCTV</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
    </div>
    <div class="col-md-2"><label class="form-label mb-0">PAST 3 YEAR CLAIM history</label></div>
    <div class="col-md-4 col-xl-4">
        <textarea name="past_year_claim_history" id="past_year_claim_history" class="form-control"
            placeholder="Enter PAST 3 YEAR CLAIM history"></textarea>
        @error('past_year_claim_history')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">BASEMENT</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="basement" class="form-control" id="basement">
            <option value="">Select Basement</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
        @error('basement')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Basement in the building used for operations</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="use_basement_other_operation" class="form-control" id="use_basement_other_operation">
            <option value="">Select Basement in the building used for operations</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
        @error('basement')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Insured premises located within 1 KM</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="insure_premises" class="form-control" id="insure_premises">
            <option value="">Select Insured premises located within 1 KM</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
        @error('basement')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Risk is located in a thickly populated area with</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="rick_located_area_access" class="form-control" id="rick_located_area_access">
            <option value="">Select Risk is located in a thickly populated area with</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
        @error('basement')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Age Of Building</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="age_building" id="age_building" value="{{old('age_building')}}" class="form-control" placeholder="Enter Age Of Building">
        @error('age_building')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Existing Policy Copy</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="file" name="existing_policy_copy[]" class="form-control" id="existing_policy_copy" multiple>
        @error('existing_policy_copy')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Photographs</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="file" name="photopgaphs[]" class="form-control" id="photopgaphs" multiple>
        @error('photopgaphs')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Investigation Report</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="file" name="invetigation_report[]" class="form-control" id="invetigation_report" multiple>
        @error('invetigation_report')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Others</label></div>
    <div class="col-md-4">
        <input type="file" name="other_attachmet[]" class="form-control" multiple id="other_attachmet">
    </div>
</div>
