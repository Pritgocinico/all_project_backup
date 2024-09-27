<div id="second_step_div" style="display: {{old('invest_type') == '' ? 'none' : ''}};">
    <div class="row align-items-center g-3 mt-6">
        {{-- @dd(old('invest_type')) --}}
        <div class="col-md-2 insurance_label"
            style="display: @if ($type !== 0 || old('invest_type') == 'travel' || old('invest_type') == '') none @endif;"><label
                class="form-label mb-0">Investment Type</label></div>
        <div class="col-md-4 col-xl-4" id="investmentRadio"
            style="display: @if ($type == 1 || old('invest_type') != 'investments') none @endif;">
            <select name="invest_insurance_type" id="invest_insurance_type" class="form-control insurance_type">
                <option value="pms">PMS</option>
                <option value="mf">MF</option>
                <option value="fd">FD</option>
                <option value="bond">Bond</option>
            </select>
        </div>
        <div class="col-md-4 col-xl-4" id="generalRadio"
            style="display: @if ($type == 2 || old('invest_type') != 'general insurance') none @endif;">
            <select name="insurance_type" id="insurance_type" class="form-control insurance_type">
                <option value="">Select Insurance Type</option>
                <option value="fire_policy" {{ old('insurance_type') == "fire_policy"? 'selected' :"selected" }}>FIRE POLICY</option>
                <option value="wc_policy" {{ old('insurance_type') == "wc_policy"? 'selected' :"" }}>WC POLICY</option>
                <option value="health_policy" {{ old('insurance_type') == "health"? 'selected' :"" }}>Health Policy</option>
                <option value="pa_policy" {{ old('insurance_type') == "pa_policy"? 'selected' :"" }}>PA POLICY</option>
                <option value="gpa_policy" {{ old('insurance_type') == "gpa_policy"? 'selected' :"" }}>GPA POLICY</option>
                <option value="gmc_policy" {{ old('insurance_type') == "gmc_policy"? 'selected' :"" }}>GMC POLICY</option>
                <option value="marine_policy" {{ old('insurance_type') == "marine_policy"? 'selected' :"" }}>Marine POLICY</option>
            </select>
        </div>
        <div class="col-md-2 investement_field_label investement_type_field" style="display: @if ($type == 2 || old('invest_type')== 'travel') none @endif;">
            <label class="form-label mb-0">Investment Field</label>
        </div>
        <div class="col-md-4 investement_field_form investement_type_field" style="display: @if ($type == 2 || old('invest_type')== 'travel') none @endif;">
            <select name="investment_field" id="investment_field" class="form-control">
                <option value="new">New</option>
                <option value="existing">Existing</option>
            </select>
        </div>
    </div>
    <div id="type_of_investments" style="display:  @if (old('invest_type') != 'investments') none @endif;">
        <div class="align-items-center row g-3 mt-6" id="existing_investment_detail_div"
            style="display: none">
            <div class="col-md-2">Existing Investment Details</div>
            <div class="col-md-4">
                <input type="text" name="investment_code" id="investment_code" placeholder="Enter Existing Investment Detail"
                    class="form-control">
            </div>
            <div class="col-md-2">Existing Investment Remarks</div>
            <div class="col-md-4">
                <textarea name="investment_remark" id="investment_remark" class="form-control" placeholder="Enter Existing Investment Remarks"></textarea>
            </div>
        </div>
        <div class="row align-items-center g-3 mt-6">
            <div class="col-md-2 aadhar_pan_card_detail_div"><label class="form-label mb-0">KYC Status</label></div>
            <div class="col-md-4 aadhar_pan_card_detail_div">
                <select name="kyc_status" id="kyc_status" class="form-control">
                    <option value="">Select KYC Status</option>
                    <option value="pending">Pending</option>
                    <option value="registered">Registered</option>
                    <option value="validated">Validated</option>
                </select>
            </div>
            <div class="col-md-2"><label class="form-label mb-0">Investment Date</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="date" name="investment_date" id="investment_date"
                    value="{{ old('investment_date', date('Y-m-d')) }}" class="form-control">
                @error('investment_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row align-items-center g-3 mt-6" id="pms_product_div">
            <div class="col-md-2"><label class="form-label mb-0">Product Name</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="text" name="product_name" id="product_name" class="form-control"
                    placeholder="Enter Product Name" value="">
                @error('product_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2"><label class="form-label mb-0">Amount Of Investment</label>
            </div>
            <div class="col-md-4 col-xl-4">
                <input type="number" name="amount_of_investment" id="amount_of_investment"
                    class="form-control" placeholder="Amount Of Investment">
                @error('amount_of_investment')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row align-items-center g-3 mt-6" id="mf_detail_div" style="display: none">
            <div class="col-md-2">Cancel Cheque</div>
            <div class="col-md-4">
                <input type="file" name="cancel_cheque" id="cancel_cheque" class="form-control">
            </div>
            <div class="col -md-2">Mutual Fund Type</div>
            <div class="col-md-4">
                <input type="checkbox" name="mf_type_amount" id="sip_detail" class="form-check-inline" value="SIP" onclick="toggleDiv()"> SIP
                <input type="checkbox" name="mf_type_amount" id="lumsum_detail" class="form-check-inline" value="Lumsum" onclick="toggleDiv()"> Lumsum
            </div>
        </div>
        <div class="row align-items-center g-3 mt-6" id="mf_sip" style="display: none;">
            <div class="col-md-2"><label class="form-label mb-0">SIP Amount</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="number" name="sip_amount" id="sip_amount" class="form-control" placeholder="Enter SIP Amount"
                    value="">
                @error('sip_amount')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-2"><label class="form-label mb-0">SIP Start Date</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="date" name="sip_date" id="sip_date"
                    value="{{ old('sip_date', date('Y-m-d')) }}" class="form-control">
                @error('sip_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row align-items-center g-3 mt-6" id="mf_lumsum" style="display: none;">
            <div class="col-md-2"><label class="form-label mb-0">Lumsum Amount</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="number" name="lumsum_amount" id="lumsum_amount" placeholder="Enter Lumsum Amount"
                    class="form-control">
                @error('lumsum_amount')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
        </div>

        <div class="row align-items-center g-3 mt-6" id="fd_interest" style="display: none;">
            <div class="col-md-2"><label class="form-label mb-0">Rate of Interest</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="number" name="interest_rate" id="interest_rate" placeholder="Enter Rate Of Interest"
                    class="form-control" value="">
                @error('interest_rate')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2"><label class="form-label mb-0">Maturity Date</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="date" name="maturity_date" id="maturity_date"
                    value="{{ old('maturity_date', date('Y-m-d')) }}" class="form-control">
                @error('maturity_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

        </div>
        <div class="row align-items-center g-3 mt-6" id="maturity_amount_interest"
            style="display: none;">
            <div class="col-md-2"><label class="form-label mb-0">Maturity Amount</label></div>
            <div class="col-md-4">
                <input type="number" name="maturity_amount" id="maturity_amount"
                    class="form-control" placeholder="Enter Maturity Amount">
                @error('maturity_amount')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-2">Interest Mode</div>
            <div class="col-md-4">
                <select name="interest_mode" id="interest_mode" class="form-control">
                    <option value="">Select Interest Mode</option>
                    <option value="Cumulative">Cumulative</option>
                    <option value="Non Cumulative">Non Cumulative</option>
                </select>
            </div>
        </div>
        <div class="row align-items-center g-3 mt-6">
            <div class="col-md-2"><label class="form-label mb-0">Tenure</label></div>
            <div class="col-md-4"><input type="text" name="tenure" id="tenure" class="form-control" placeholder="Enter Tenure"></div>
            <div class="col-md-2 bond_payout_detail"><label class="form-label mb-0">Interest Payout </label></div>
            <div class="col-md-4 bond_payout_detail">
                <select name="interest_payout" id="interest_payout" class="form-control">
                    <option value="">Select Interest Payout</option>
                    <option value="Monthly">Monthly</option>
                    <option value="Quarterly">Quarterly</option>
                    <option value="half_year">Half Year</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
        </div>

        <div class="row align-items-center g-3 mt-6">

            <div class="col-md-2"><label class="form-label mb-0">Lead Date</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="date" name="lead_date"
                    value="{{ old('lead_date', date('Y-m-d')) }}" class="form-control">
                @error('lead_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    @include('admin.lead.partials.create.general_insurance')
    @include('admin.lead.partials.create.travel')
    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-8"></div>
        <div class="col-md-4 text-end second_step_hide">
            <button type="button" id="previous_second_step_button"
                class="btn btn-light btn-sm"><i class="fa-solid fa-arrow-left"></i>Previous</button>
            <a href="{{ route('leads.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
            <button type="button" id="next_second_step_button"
                class="btn btn-primary btn-sm">Next<i class="fa-solid fa-arrow-right"></i></button>
        </div>
    </div>
</div>