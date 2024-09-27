<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0"></label>Name of Company</div>
    <div class="col-md-4">
        <input type="text" name="name_company" id="name_company" class="form-control" placeholder="Enter Company Name">
        @error('name_company')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Policy Period</label></div>
    <div class="col-md-4">
        <select name="policy_period" id="policy_period" class="form-control">
            <option value="">Select Policy Period</option>
            @for ($i = 1; $i <= 12; $i++)
                <option value="{{$i}}">{{$i}} Month</option>
            @endfor
        </select>
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2">
        <label class="form-label mb-0">Nature Of Business</label>
    </div>
    <div class="col-md-4">
        <input type="text" name="nature_of_business" id="nature_of_business" class="form-control" placeholder="Enter Nature Of Business">
        @error('nature_of_business')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label mb-0">Risk Occupancy/Scope of Work</label>
    </div>
    <div class="col-md-4">
        <textarea name="risk_occupancy_scope_work" id="risk_occupancy_scope_work" class="form-control"
            placeholder="Enter Risk Occupancy/Scope of Work">{{ old('risk_occupancy_scope_work') }}</textarea>
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Health Policy type</label>
    </div>
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
    <div class="col-md-6">
        <input type="checkbox" name="same_address" id="same_address" value="1" checked> Same As Address For Risk
        Location Address
    </div>
    <div class="col-md-2 risk_location_address d-none">Address</div>
    <div class="col-md-4 risk_location_address d-none">
        <textarea name="risk_location_address" id="risk_location_address" class="form-control"
            placeholder="Enter Rick Location Address">{{ old('risk_location_address') }}</textarea>
    </div>
</div>
<div class="row align-items-center g-3 mt-6 risk_location_address d-none">
    <div class="col-md-2"><label class="form-label mb-0">Risk Location Country</label></div>
    <div class="col-md-4">
        <select id="risk_location_country" name="risk_location_country" class="form-control" onchange="getState()">
            <option value="">Select Country</option>
            @foreach ($countryList as $country)
                <option value="{{ $country->iso2 }}">{{ $country->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Risk Location State</label></div>
    <div class="col-md-4">
        <select id="risk_location_state" name="risk_location_state" class="form-control" onchange="getCity()">
        </select>
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Sub-Contractor Employee Coverage Required</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="sub_contract_coverage" id="sub_contract_coverage" class="form-select">
            <option value="">Select Sub-Contractor Employee Coverage Required</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Occupational Diseases Coverage Required</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="occupatioanal_diseases_covarage" id="occupatioanal_diseases_covarage" class="form-select">
            <option value="">Select Occupational Diseases Coverage Required</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
        @error('occupatioanal_diseases_covarage')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Total Number of Employees</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="total_employee" id="total_employee" class="form-control" placeholder="Enter Total Employee">
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Total Wages (Rs.)</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="total_wage" id="total_wage" class="form-control">
        @error('total_wage')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Skilled</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="skilled_employee" id="skilled_employee" class="form-control" placeholder="Enter Skilled Employee">
        @error('skilled_employee')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Unskilled</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="unskilled_employee" id="unskilled_employee" class="form-control" placeholder="Enter UnSkilled Employee">
        @error('unskilled_employee')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Commercial Traveller</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="commercial_travel" id="commercial_travel" class="form-control" placeholder="Enter Commercial Traveller">
        @error('commercial_travel')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Claim History last 3 years</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="claim_history" id="claim_history" class="form-control" placeholder="Enter Claim History last 3 years">
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
            <option value="50,000">50,000</option>
            <option value="1,00,000">1,00,000</option>
            <option value="2,00,000">2,00,000</option>
            <option value="3,00,000">3,00,000</option>
            <option value="4,00,000">4,00,000</option>
            <option value="5,00,000">5,00,000</option>
            <option value="at_actual">At Actual</option>
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
            <option value="1">One</option>
            <option value="2">Two</option>
            <option value="3">Three</option>
        </select>
        @error('number_shift')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Distance from Nearest Hospital in kms.</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="distance_near_hospital" class="form-control" id="distance_near_hospital" placeholder="Enter Distance from Nearest Hospital in kms.">
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
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>
        @error('first_aid_kit')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Fire Extinguishers, Fire hydrant system</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="fire_extinguishers" class="form-control" id="fire_extinguishers">
            <option value="">Select Fire Extinguishers, Fire hydrant system</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
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
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>
        @error('wc_security_person')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">CCTV Camera Installed</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="cctv_camera_install" class="form-control" id="cctv_camera_install">
            <option value="">Select CCTV Camera Installed</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
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
    <div class="col-md-2"><label class="form-label mb-0">Employee Data Sheet</label></div>
    <div class="col-md-4">
        <input type="file" name="employee_data_sheet[]" class="form-control" multiple id="employee_data_sheet">
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Others</label></div>
    <div class="col-md-4">
        <input type="file" name="wc_other_attachmet[]" class="form-control" multiple id="wc_other_attachmet">
    </div>
</div>
