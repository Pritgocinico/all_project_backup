<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Type of Policy</label></div>
    <div class="col-md-4">
        <select name="marine_type_of_policy" id="marine_type_of_policy" class="form-control">
            <option value="">Select Type of Policy</option>
            <option value="single_transit" {{ $insuranceData->policy_type == 'single_transit' ? 'selected' : '' }}>
                Single Transit</option>
            <option value="other" {{ $insuranceData->policy_type == 'other' ? 'selected' : '' }}>Other</option>
        </select>
        @error('marine_type_of_policy')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div
        class="col-md-2 marine_policy_other_div {{ $insuranceData->reasonable_charge == 'single_transit' ? 'd-none' : '' }}">
        <label class="form-label mb-0">Other Marine Policy</label>
    </div>
    <div class="col-md-4 marine_policy_other_div d-none">
        <input type="text" value="{{ $insuranceData->other_marine_policy }}" name="other_marine_policy"
            id="other_marine_policy" placeholder="Enter Other Marine Policy" class="form-control">
        @error('other_marine_policy')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Hyothecation</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text"value="{{ $insuranceData->hyphenation }}" name="hyphenation"
            class="form-control"id=" hyphenation" placeholder="Enter Hyothecation">
        @error('hyphenation')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Previous Policy Number</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" value="{{ $insuranceData->previous_policy }}" name="previous_policy_number"
            class="form-control" id=" previous_policy_number" placeholder="Enter Previous Policy Number">
        @error('previous_policy_number')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Policy Period Start Date</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="date" name="marine_policy_period_start_date" class="form-control"
            value="{{ $insuranceData->policy_start_date }}" id="marine_policy_period_start_date"
            min="{{ date('Y-m-d') }}">
        @error('marine_policy_period_start_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Policy Period End Date</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="date" name="marine_policy_period_end_date" class="form-control"
            id="marine_policy_period_end_date" value="{{ $insuranceData->policy_end_date }}"
            min="{{ date('Y-m-d') }}">
        @error('marine_policy_period_end_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Commodity Description</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" value="{{ $insuranceData->commodity_description }}" name="commodity_description"
            class="form-control" id="commodity_description" placeholder="Enter Commodity Description">
        @error('commodity_description')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Mode of Transit</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" value="{{ $insuranceData->transit_mode }}" name="transit_mode" class="form-control"
            id="transit_mode" placeholder="Enter Mode of Transit">
        @error('transit_mode')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Voyage Type</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" value="{{ $insuranceData->voyage_type }}" name="voyage_type" class="form-control"
            placeholder="Enter Voyage Type" id="voyage_type">
        @error('voyage_type')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Voyage Details</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" value="{{ $insuranceData->voyage_detail }}" name="voyage_detail" class="form-control"
            id="voyage_detail" placeholder="Enter Voyage Details">
        @error('voyage_detail')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Packaging</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" value="{{ $insuranceData->packaging }}" name="packaging" class="form-control"
            id="packaging" placeholder="Enter Packaging">
        @error('Packaging')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Sum Insured</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" value="{{ $insuranceData->total_sum_insured }}" name="marine_sum_insured"
            class="form-control" id="marine_sum_insured" placeholder="Enter Sum Insured">
        @error('marine_sum_insured')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Per Bottom Limit</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" value="{{ $insuranceData->per_bottom_limit }}" name="per_bottom_limit"
            id="per_bottom_limit" class="form-control" placeholder="Enter Per Bottom Limit">
        @error('per_bottom_limit')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Per Location limit</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" value="{{ $insuranceData->per_location_limit }}" name="per_location_limit"
            id="per_location_limit" class="form-control" placeholder="Enter Per Location limit">
        @error('per_location_limit')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Type of Vehicle</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" value="{{ $insuranceData->vehicle_type }}" name="vehicle_type" id="vehicle_type"
            placeholder="Enter Type of Vehicle" class="form-control">
        @error('vehicle_type')
            <div class="text-danger">{{ $message }}</div>
        @enderror

    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Existing/Previous Policy copies if any</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="file" name="marine_exist_policy_copy[]" class="form-control" id="marine_exist_policy_copy"
            multiple>
        @error('marine_exist_policy_copy')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <div class="row existing_lead_div_outer">
            @foreach ($marineExistingPolicyCopy as $marinePolicy)
                <div class="col-md-2 existing_lead_div position-relative"
                    id="remove_exist_policy_div{{ $marinePolicy->id }}">
                    <a href="{{ asset('storage/' . $marinePolicy->file_path) }}" target="_blank" btn=""
                        class="btn btn-dark btn-sm w-100" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $marinePolicy->file_name }}">View</a>
                    <span>{{ $marinePolicy->file_name }}</span>
                    <a class="position-absolute lead_file_remove_btn" data-bs-toggle="tooltip"
                        onclick="removeExistPolicy({{ $marinePolicy->id }})" data-bs-placement="top"
                        title="Remove File"><i class="fa-solid fa-circle-xmark"></i></a>
                </div>
            @endforeach
        </div>
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Claim History of last 1 year</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="file" name="marine_claim_one_year[]" class="form-control" id="marine_claim_one_year"
            multiple>
        @error('marine_claim_one_year')
            <div class="text-danger">{{ $message }}</div>
        @enderror
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
    <div class="col-md-2"><label class="form-label mb-0">Invoice copy </label></div>
    <div class="col-md-4">
        <input type="file" name="invoice_copy[]" class="form-control" multiple id="invoice_copy">
    </div>
    <div class="col-md-6">
        <div class="row existing_lead_div_outer">
            @foreach ($invoiceCopy as $invoice)
                <div class="col-md-2 existing_lead_div position-relative"
                    id="remove_invoice_copy_div{{ $invoice->id }}">
                    <a href="{{ asset('storage/' . $invoice->file_path) }}" target="_blank" btn=""
                        class="btn btn-dark btn-sm w-100" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $invoice->file_name }}">View</a>
                    <span>{{ $invoice->file_name }}</span>
                    <a class="position-absolute lead_file_remove_btn" data-bs-toggle="tooltip"
                        onclick="removeInvoiceCopy({{ $invoice->id }})" data-bs-placement="top"
                        title="Remove File"><i class="fa-solid fa-circle-xmark"></i></a>
                </div>
            @endforeach
        </div>
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">ODC Cargo - Survey Report</label></div>
    <div class="col-md-4">
        <input type="file" name="odc_cargo[]" class="form-control" multiple id="odc_cargo">
    </div>
    <div class="col-md-6">
        <div class="row existing_lead_div_outer">
            @foreach ($surveyReport as $report)
                <div class="col-md-2 existing_lead_div position-relative"
                    id="remove_survey_report_div{{ $report->id }}">
                    <a href="{{ asset('storage/' . $report->file_path) }}" target="_blank" btn=""
                        class="btn btn-dark btn-sm w-100" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $report->file_name }}">View</a>
                    <span>{{ $report->file_name }}</span>
                    <a class="position-absolute lead_file_remove_btn" data-bs-toggle="tooltip"
                        onclick="removeSurveyReport({{ $report->id }})" data-bs-placement="top"
                        title="Remove File"><i class="fa-solid fa-circle-xmark"></i></a>
                </div>
            @endforeach
        </div>
    </div>
</div>
