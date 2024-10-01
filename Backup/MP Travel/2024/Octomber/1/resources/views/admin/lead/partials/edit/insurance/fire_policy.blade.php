<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Health Policy type</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="health_policy_type" class="form-control health_policy_type" id="health_policy_type">
            <option value="">Select Health Policy type</option>
            <option value="new" {{ $insuranceData->health_policy_type == 'new' ? 'selected' : '' }}>New</option>
            <option value="renewal" {{ $insuranceData->health_policy_type == 'renewal' ? 'selected' : '' }}>Renewal
            </option>
        </select>
        @error('health_policy_type')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2 renew_policy_div d-none"><label class="form-label mb-0">Previous Policy No</label></div>
    <div class="col-md-4 renew_policy_div d-none">
        <input type="text" name="previous_policy" class="form-control" value="{{ $insuranceData->previous_policy }}"
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
        <input type="date" name="expiry_date" class="form-control" value="{{ $insuranceData->expiry_date }}">
    </div>
</div>

<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Policy Period Start Date</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="date" name="policy_period_start_date" class="form-control"
            value="{{ $insuranceData->policy_start_date }}" id="policy_period_start_date" min="{{ date('Y-m-d') }}">
        @error('policy_period_start_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Policy Period End Date</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="date" name="policy_period_end_date" class="form-control" id="policy_period_end_date"
            value="{{ $insuranceData->policy_end_date }}" min="{{ date('Y-m-d') }}">
        @error('policy_period_end_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Financier Interest or Hypothecation</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="financier_interest_hypo" class="form-control"
            value="{{ $insuranceData->financier_interest_hypo }}" id="financier_interest_hypo">
        @error('financier_interest_hypo')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Building Value(Rs.)</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="building_value" value="{{ $insuranceData->building_value }}"
            class="form-control total_sum_insured" id="building_value">
        @error('building_value')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Plant & Machinary Value(Rs.)</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="plant_machinary_value" value="{{ $insuranceData->plant_machinery }}"
            class="form-control total_sum_insured" id="plant_machinary_value">
        @error('plant_machinary_value')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Total Stock IN PROCESS / RAW / FINISHED(Rs.)</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="total_stock_in_process" value="{{ $insuranceData->total_stock_in_process }}"
            class="form-control total_sum_insured" id="total_stock_in_process">
        @error('total_stock_in_process')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">FFF & Other EE Value(Rs.)</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="fff_other_ee" class="form-control total_sum_insured"
            value="{{ $insuranceData->fff_other_ee }}" id="fff_other_ee">
        @error('fff_other_ee')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Other Content(Rs.)</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="other_content" value="0" id="other_content"
            value="{{ $insuranceData->other_content }}" class="form-control total_sum_insured">
        @error('other_content')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Total Sum Insured(Rs.)</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="sum_Insured" value="{{ $insuranceData->total_sum_insured }}" id="sum_Insured"
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
            <option value="normal" {{ $insuranceData->type_of_policy == 'normal' ? 'selected' : '' }}>Normal</option>
            <option value="floter" {{ $insuranceData->type_of_policy == 'floter' ? 'selected' : '' }}>Floter</option>
        </select>
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Operational Fire Hydrant/Sprinkler/water Spray System/Fire
            alarm/smoke detectors</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="operational_fire" id="operational_fire" class="form-select">
            <option value="">Select Operational Fire Hydrant</option>
            <option value="yes" {{ $insuranceData->operational_fire == 'yes' ? 'selected' : '' }}>Yes</option>
            <option value="no" {{ $insuranceData->operational_fire == 'no' ? 'selected' : '' }}>No</option>
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
            <option value="yes" {{ $insuranceData->burglary_coverage == 'yes' ? 'selected' : '' }}>Yes</option>
            <option value="no" {{ $insuranceData->burglary_coverage == 'no' ? 'selected' : '' }}>No</option>
        </select>
        @error('burglary_coverage')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Burglary Sum Insured (Rs.)</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" value="{{ $insuranceData->burglary_sum_insured }}" name="burglary_sum_insured"
            id="burglary_sum_insured" placeholder="Enter Burglary Sum Insured (Rs.)" class="form-control">
        @error('burglary_sum_insured')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">First Loss Percentage</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" value="{{ $insuranceData->first_loss_percentage }}" name="first_loss_percentage"
            id="first_loss_percentage" placeholder="Enter First Loss Percentage" class="form-control">
        @error('first_loss_percentage')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Theft Extension</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="theft_extension" id="theft_extension" class="form-select">
            <option value="">Select Operational Fire Hydrant</option>
            <option value="yes" {{ $insuranceData->theft_extension == 'yes' ? 'selected' : '' }}>Yes</option>
            <option value="no" {{ $insuranceData->theft_extension == 'no' ? 'selected' : '' }}>No</option>
        </select>
        @error('theft_extension')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Well Maintained Electrical Standard equipments
            Installations</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="maintain_electrical" id="maintain_electrical" class="form-select">
            <option value="">Select Well Maintained Electrical</option>
            <option value="yes" {{ $insuranceData->maintain_electric == 'yes' ? 'selected' : '' }}>Yes</option>
            <option value="no" {{ $insuranceData->maintain_electric == 'no' ? 'selected' : '' }}>No</option>
        </select>
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Provision of Storm water drainage system and building with
            plinth Level at least 1.5 ft above ground</label></div>
    <div class="col-md-4 col-xl-4">
        <textarea name="provision_storm_water_drainage" id="provision_storm_water_drainage" class="form-control"
            placeholder="Enter Provision Of Storm Water drainage">{{ $insuranceData->water_drainage }}</textarea>
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
            <option value="yes" {{ $insuranceData->security_cctv == 'yes' ? 'selected' : '' }}>Yes</option>
            <option value="no" {{ $insuranceData->security_cctv == 'no' ? 'selected' : '' }}>No</option>
        </select>
    </div>
    <div class="col-md-2"><label class="form-label mb-0">PAST 3 YEAR CLAIM history</label></div>
    <div class="col-md-4 col-xl-4">
        <textarea name="past_year_claim_history" id="past_year_claim_history" class="form-control"
            placeholder="Enter PAST 3 YEAR CLAIM history">{{ $insuranceData->past_year_claim_history }}</textarea>
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
            <option value="yes" {{ $insuranceData->basement == 'yes' ? 'selected' : '' }}>Yes</option>
            <option value="no" {{ $insuranceData->basement == 'no' ? 'selected' : '' }}>No</option>
        </select>
        @error('basement')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Basement in the building used for operations/Storage/Plant
            and Machinery installed there in</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="use_basement_other_operation" class="form-control" id="use_basement_other_operation">
            <option value="">Select Basement in the building used for operations/Storage/Plant and Machinery
                installed there in</option>
            <option value="yes" {{ $insuranceData->use_basement == 'yes' ? 'selected' : '' }}>Yes</option>
            <option value="no" {{ $insuranceData->use_basement == 'no' ? 'selected' : '' }}>No</option>
        </select>
        @error('basement')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Insured premises located within 1 KM distance of water
            body</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="insure_premises" class="form-control" id="insure_premises">
            <option value="">Select Insured premises located within 1 KM distance of water body</option>
            <option value="yes" {{ $insuranceData->insure_premises == 'yes' ? 'selected' : '' }}>Yes</option>
            <option value="no" {{ $insuranceData->insure_premises == 'no' ? 'selected' : '' }}>No</option>
        </select>
        @error('basement')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Risk is located in a thickly populated area with No access to
            fire brigade vehicle</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="rick_located_area_access" class="form-control" id="rick_located_area_access">
            <option value="">Select Risk is located in a thickly populated area with No access to fire brigade
                vehicle</option>
            <option value="yes" {{ $insuranceData->risk_locate == 'yes' ? 'selected' : '' }}>Yes</option>
            <option value="no" {{ $insuranceData->risk_locate == 'no' ? 'selected' : '' }}>No</option>
        </select>
        @error('basement')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Age Of Building</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="age_building" id="age_building" value="{{ $insuranceData->age_building }}"
            class="form-control" placeholder="Enter Age Of Building">
        @error('age_building')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Existing Policy Copy</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="file" name="existing_policy_copy[]" class="form-control" id="existing_policy_copy" multiple>
        @error('existing_policy_copy')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <div class="row existing_lead_div_outer">
            @foreach ($existingPolicyCopy as $policyCopy)
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
    <div class="col-md-2"><label class="form-label mb-0">Photographs</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="file" name="photopgaphs[]" class="form-control" id="photopgaphs" multiple>
        @error('photopgaphs')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <div class="row existing_lead_div_outer">
            @foreach ($leadPhoto as $photo)
                <div class="col-md-2 existing_lead_div position-relative" id="remove_photo_div{{ $photo->id }}">
                    <a href="{{ asset('storage/' . $photo->file_path) }}" target="_blank" btn=""
                        class="btn btn-dark btn-sm w-100" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $photo->file_name }}">View</a>
                    <span>{{ $photo->file_name }}</span>
                    <a class="position-absolute lead_file_remove_btn" data-bs-toggle="tooltip"
                        onclick="removePhotoPolicy({{ $photo->id }})" data-bs-placement="top"
                        title="Remove File"><i class="fa-solid fa-circle-xmark"></i></a>
                </div>
            @endforeach
        </div>
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Investigation Report</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="file" name="invetigation_report[]" class="form-control" id="invetigation_report" multiple>
        @error('invetigation_report')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <div class="row existing_lead_div_outer">
            @foreach ($investigationReport as $report)
                <div class="col-md-2 existing_lead_div position-relative" id="remove_investigation_div{{ $report->id }}">
                    <a href="{{ asset('storage/' . $report->file_path) }}" target="_blank" btn=""
                        class="btn btn-dark btn-sm w-100" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $report->file_name }}">View</a>
                    <span>{{ $report->file_name }}</span>
                    <a class="position-absolute lead_file_remove_btn" data-bs-toggle="tooltip"
                        onclick="removeInvestigation({{ $report->id }})" data-bs-placement="top"
                        title="Remove File"><i class="fa-solid fa-circle-xmark"></i></a>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Others</label></div>
    <div class="col-md-4">
        <input type="file" name="other_attachment[]" class="form-control" multiple id="other_attachment">
    </div>
    <div class="col-md-6">
        <div class="row existing_lead_div_outer">
            @foreach ($fireOtherAttachment as $fireOther)
                <div class="col-md-2 existing_lead_div position-relative" id="remove_fire_other_div{{ $fireOther->id }}">
                    <a href="{{ asset('storage/' . $fireOther->file_path) }}" target="_blank" btn=""
                        class="btn btn-dark btn-sm w-100" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $fireOther->file_name }}">View</a>
                    <span>{{ $fireOther->file_name }}</span>
                    <a class="position-absolute lead_file_remove_btn" data-bs-toggle="tooltip"
                        onclick="removeFireOther({{ $fireOther->id }})" data-bs-placement="top"
                        title="Remove File"><i class="fa-solid fa-circle-xmark"></i></a>
                </div>
            @endforeach
        </div>
    </div>
</div>
