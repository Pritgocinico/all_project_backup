<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0"></label>Name</div>
    <div class="col-md-4">
        <input type="text" name="health_company_name" id="health_company_name" class="form-control"
            placeholder="Enter Health Company Name" value="{{ $insuranceData->health_company_name }}">
        @error('health_company_name')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Type of Case </label></div>
    <div class="col-md-4">
        <select name="type_case" id="type_case" class="form-control">
            <option value="">Select Type Case</option>
            <option value="fresh" {{ $insuranceData->type_case == 'fresh' ? 'selected' : '' }}>Fresh</option>
            <option value="renewal" {{ $insuranceData->type_case == 'renewal' ? 'selected' : '' }}>Renewal</option>
            <option value="rollover" {{ $insuranceData->type_case == 'rollover' ? 'selected' : '' }}>Rollover</option>
        </select>
    </div>
</div>
<div class="allign-items-center row mt-6 g-3 health_renew_policy_div">
    <div class="col-md-2">
        <label class="form-label mb-0">Policy Number</label>
    </div>
    <div class="col-md-4">
        <input type="text" name="health_policy_number" id="health_policy_number" class="form-control"
            placeholder="Enter Policy Number" value="{{ $insuranceData->health_policy_number }}">
        @error('health_policy_number')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label mb-0">Policy Insurer name</label>
    </div>
    <div class="col-md-4">
        <input type="text" name="health_policy_insurer_name" id="health_policy_insurer_name" class="form-control"
            placeholder="Enter Policy Insurer name" value="{{ $insuranceData->health_policy_insurer_name }}">
        @error('health_policy_insurer_name')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2 health_renew_policy_div d-none">
        <label class="form-label mb-0">Expiry Date</label>
    </div>
    <div class="col-md-4 health_renew_policy_div d-none">
        <input type="date" name="health_policy_expiry_date" id="health_policy_expiry_date" class="form-control"
            value="{{ $insuranceData->health_policy_expiry_date }}">
        @error('health_policy_expiry_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label mb-0">Plan Type</label>
    </div>
    <div class="col-md-4">
        <select name="plan_type" id="plan_type" class="form-control">
            <option value="">Select Plan Type</option>
            <option value="individual" {{ $insuranceData->plan_type == 'individual' ? 'selected' : '' }}>Individual
            </option>
            <option value="floater" {{ $insuranceData->plan_type == 'floater' ? 'selected' : '' }}>Floater</option>
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
        <input type="date" name="health_policy_start_date" id="health_policy_start_date" class="form-control"
            value="{{ $insuranceData->policy_start_date }}">
        @error('health_policy_start_date')
            <div class="text-danger">{{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label mb-0">Policy End Date</label>
    </div>
    <div class="col-md-4">
        <input type="date" name="health_policy_end_date" id="health_policy_end_date" class="form-control"
            value="{{ $insuranceData->policy_end_date }}">
        @error('health_policy_end_date')
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
        <input type="number" name="health_sum_insured" id="health_sum_insured" class="form-control"
            value="{{ $insuranceData->health_sum_insured }}" value="0">
        @error('health_sum_insured')
            <div class="text-danger">{{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label mb-0">Claim History</label>
    </div>
    <div class="col-md-4">
        <select name="health_claim_history" id="health_claim_history" class="form-control">
            <option value="">Select Claim History</option>
            <option value="yes" {{ $insuranceData->health_claim_history == 'yes' ? 'selected' : '' }}>Yes</option>
            <option value="no" {{ $insuranceData->health_claim_history == 'no' ? 'selected' : '' }}>No</option>
        </select>
        @error('health_claim_history')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2">
        <label class="form-label mb-0">Claim History Detail</label>
    </div>
    <div class="col-md-4">
        <textarea name="health_claim_history_detail" id="health_claim_history_detail" class="form-control"
            placeholder="Enter Claim History Detail">{{ $insuranceData->health_claim_history_detail }}</textarea>
        @error('health_claim_history_detail')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label mb-0">Alcohol Consumer</label>
    </div>
    <div class="col-md-4">
        <select name="alcohol_consumer" id="alcohol_consumer" class="form-control">
            <option value="">Select Alcohol Consumer</option>
            <option value="yes" {{ $insuranceData->alcohol_consumer == 'yes' ? 'selected' : '' }}>Yes</option>
            <option value="no" {{ $insuranceData->alcohol_consumer == 'no' ? 'selected' : '' }}>No</option>
        </select>
        @error('alcohol_consumer')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2">
        <label class="form-label mb-0">Tobacco Consumer</label>
    </div>
    <div class="col-md-4">
        <select name="tobacco_consumer" id="tobacco_consumer" class="form-control">
            <option value="">Select Tobacco Consumer</option>
            <option value="yes" {{ $insuranceData->tobacco_consumer == 'yes' ? 'selected' : '' }}>Yes</option>
            <option value="no" {{ $insuranceData->tobacco_consumer == 'no' ? 'selected' : '' }}>No</option>
        </select>
        @error('tobacco_consumer')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label mb-0">Smoking</label>
    </div>
    <div class="col-md-4">
        <select name="smoking" id="smoking" class="form-control">
            <option value="">Select Smoking</option>
            <option value="yes" {{ $insuranceData->smoking == 'yes' ? 'selected' : '' }}>Yes</option>
            <option value="no" {{ $insuranceData->smoking == 'no' ? 'selected' : '' }}>No</option>
        </select>
        @error('smoking')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2">
        <label class="form-label mb-0">PED Medicines details</label>
    </div>
    <div class="col-md-4">
        <textarea name="ped_medicines_details" id="ped_medicines_details" class="form-control"
            placeholder="Enter PED Medicines details">{{ $insuranceData->ped_medical }}</textarea>
        @error('ped_medicines_details')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label mb-0">CIR</label>
    </div>
    <div class="col-md-4">
        <select name="cir" id="cir" class="form-control">
            <option value="">Select CIR</option>
            <option value="yes" {{ $insuranceData->CIR == 'yes' ? 'selected' : '' }}>Yes</option>
            <option value="no" {{ $insuranceData->CIR == 'yes' ? 'selected' : '' }}>No</option>
        </select>
        @error('cir')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2">
        <label class="form-label mb-0">Existing Policy copies</label>
    </div>
    <div class="col-md-4">
        <input type="file" name="health_existing_policy_copies[]" id="health_existing_policy_copies"
            class="form-control" multiple>
        @error('health_existing_policy_copies')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <div class="row existing_lead_div_outer">
            @foreach ($healthExistingPolicyCopy as $policyCopy)
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
    <div class="col-md-2">
        <label class="form-label mb-0">Discharge Summary in case of claim</label>
    </div>
    <div class="col-md-4">
        <input type="file" name="health_discharge_claim[]" id="health_discharge_claim" class="form-control"
            multiple>
        @error('health_discharge_claim')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <div class="row existing_lead_div_outer">
            @foreach ($dischargeSummary as $leadDischarge)
                <div class="col-md-2 existing_lead_div position-relative"
                    id="remove_discharge_summary_div{{ $leadDischarge->id }}">
                    <a href="{{ asset('storage/' . $leadDischarge->file_path) }}" target="_blank"
                        class="btn btn-dark btn-sm w-100" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $leadDischarge->file_name }}">View</a>
                    <span>{{ $leadDischarge->file_name }}</span>
                    <a class="position-absolute lead_file_remove_btn" data-bs-toggle="tooltip"
                        onclick="removeDischargeSummary({{ $leadDischarge->id }})" data-bs-placement="top"
                        title="Remove File"><i class="fa-solid fa-circle-xmark"></i></a>
                </div>
            @endforeach
        </div>
    </div>
</div>
<hr class="my-6" />
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-10">
        <h4>Family Member Detail</h4>
    </div>
    <div class="col-md-2 d-none" id="health_family_member_div">
        <a href="javascript:void(0)" id="health_add_more_button" class="btn btn-sm btn-dark">Add
            More</a>
    </div>
</div>
<div id="remove_add_more_div_1">
    @foreach ($insuranceFamilyMember as $member)
        <div id="member_detail_div_{{ $member->id }}">
            <div class="row text-end d-block">
                <a style="width: 20px" href="javascript:void(0)" onclick="removeMemberDetail({{ $member->id }})"
                    width="10px"><i class="fa-solid fa-trash-can"></i></a>
            </div>
            <div class="row align-items-center g-3 mt-6">
                <input type="hidden" name="member_id[]" value="{{ $member->id }}">
                <div class="col-md-4">
                    <label class="form-label mb-0">Member Name</label>
                    <input type="text" name="member_name[]" id="member_name" class="form-control"
                        value="{{ $member->name }}" placeholder="Enter Member Name">
                    @error('member_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label mb-0">DOB</label>
                    <input type="date" name="dob[]" id="dob" class="form-control"
                        value="{{ $member->dob }}">
                    @error('dob')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label mb-0">Gender</label>
                    <select name="gender[]" id="gender" class="form-control">
                        <option value="">Select Gender</option>
                        <option value="male" {{ $member->gender == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ $member->gender == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('gender')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-4">
                    <label class="form-label mb-0">Relationship</label>
                    <input type="text" name="relationship[]" id="relationship" class="form-control"
                        value="{{ $member->relation }}" placeholder="Enter Relationship">
                </div>
                <div class="col-md-4">
                    <label class="form-label mb-0">Pre-Existing</label>
                    <select name="pre_existing[]" id="pre_existing" class="form-control">
                        <option value="">Select Pre-Existing</option>
                        <option value="yes" {{ $member->pre_existing == 'yes' ? 'selected' : '' }}>Yes</option>
                        <option value="no" {{ $member->pre_existing == 'no' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label mb-0">Height</label>
                    <input type="text" name="height[]" id="height" class="form-control"
                        value="{{ $member->height }}" placeholder="Enter Height">
                </div>
            </div>
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-4">
                    <label class="form-label mb-0">Weight</label>
                    <input type="text" name="weight[]" id="weight" class="form-control"
                        value="{{ $member->weight }}" placeholder="Enter Weight">
                </div>
                <div class="col-md-4">
                    <label class="form-label mb-0">Education</label>
                    <input type="text" name="education[]" id="education" class="form-control"
                        value="{{ $member->education }}" placeholder="Enter Education">
                </div>
                <div class="col-md-4">
                    <label class="form-label mb-0">Profession</label>
                    <select name="profession[]" id="profession" class="form-control">
                        <option value="">Select Profession</option>
                        <option value="salaried" {{ $member->profession == 'salaried' ? 'selected' : '' }}>Salaried
                        </option>
                        <option value="business" {{ $member->profession == 'business' ? 'selected' : '' }}>Business
                        </option>
                    </select>
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="health_family_member_detail_more"></div>
