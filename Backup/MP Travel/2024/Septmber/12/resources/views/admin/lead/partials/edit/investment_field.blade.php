<div id="second_step_div" style="display: none;">
    @if (isset($lead->investmentLeadData))
        <div class="row align-items-center g-3 mt-6">
            @php $investmentData= $lead->investmentLeadData; @endphp
            <input type="hidden" name="investment_id" value="">
            <div class="col-md-2 insurance_label" style="display: @if ($type == 0) none @endif;"><label
                    class="form-label mb-0">Investment Type</label></div>
            <div class="col-md-4 col-xl-4" id="investmentRadio"
                style="display: @if ($type !== 1) none @endif;">
                <select name="invest_insurance_type" id="invest_insurance_type" class="form-control insurance_type">
                    <option value="pms"{{ $lead->insurance_type == 'pms' ? 'selected' : '' }}>PMS</option>
                    <option value="mf" {{ $lead->insurance_type == 'mf' ? 'selected' : '' }}>MF</option>
                    <option value="fd" {{ $lead->insurance_type == 'fd' ? 'selected' : '' }}>FD</option>
                    <option value="bond" {{ $lead->insurance_type == 'bond' ? 'selected' : '' }}>Bond</option>
                    <option value="gpa_policy" {{ $lead->insurance_type == "gpa_policy"? 'selected' :"" }}>GPA POLICY</option>
                    <option value="gmc_policy" {{ $lead->insurance_type == "gmc_policy"? 'selected' :"" }}>GMC POLICY</option>
                    <option value="marine_policy" {{ $lead->insurance_type == "marine_policy"? 'selected' :"" }}>Marine POLICY</option>
                </select>
            </div>
            <div class="col-md-4 col-xl-4" id="generalRadio"
                style="display: @if ($type !== 2) none @endif;">
                <select name="insurance_type" id="insurance_type" class="form-control insurance_type">
                    <option value="">Select Insurance Type</option>
                    <option value="fire_policy" {{ $lead->insurance_type == 'fire_policy' ? 'selected' : '' }}>FIRE
                        POLICY
                    </option>
                    <option value="wc_policy" {{ $lead->insurance_type == 'wc_policy' ? 'selected' : '' }}>WC POLICY
                    </option>
                    <option value="health_policy" {{ $lead->insurance_type == 'health' ? 'selected' : '' }}>Health
                        Policy
                    </option>
                    <option value="pa_policy" {{ $lead->insurance_type == 'pa_policy' ? 'selected' : '' }}>PA POLICY
                    </option>
                </select>
            </div>
            <div class="col-md-2 investement_field_label investement_type_field">
                <label class="form-label mb-0">Investment Field</label>
            </div>
            <div class="col-md-4 investement_field_form investement_type_field">
                <select name="investment_field" id="investment_field" class="form-control">
                    <option value="new" {{ $investmentData->investment_field == 'new' ? selected : '' }}>New</option>
                    <option value="existing" {{ $investmentData->investment_field == 'existing' ? selected : '' }}>
                        Existing</option>
                </select>
            </div>
        </div>
        <div id="type_of_investments" style="display: {{ $investmentData->investment_field == 'new' ? 'none' : '' }}">
            <div class="align-items-center row g-3 mt-6" id="existing_investment_detail_div" style="display: none">
                <div class="col-md-2">Existing Investment Details</div>
                <div class="col-md-4">
                    <input type="text" name="investment_code" id="investment_code"
                        placeholder="Enter Existing Investment Detail" class="form-control"
                        value="{{ $investmentData->investment_code }}">
                </div>
                <div class="col-md-2">Existing Investment Remarks</div>
                <div class="col-md-4">
                    <textarea name="investment_remark" id="investment_remark" class="form-control"
                        placeholder="Enter Existing Investment Remarks">{{ $investmentData->investment_remark }}</textarea>
                </div>
            </div>
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2 aadhar_pan_card_detail_div"><label class="form-label mb-0">KYC Status</label></div>
                <div class="col-md-4 aadhar_pan_card_detail_div">
                    <select name="kyc_status" id="kyc_status" class="form-control">
                        <option value="">Select KYC Status</option>
                        <option value="pending" {{ $investmentData->kyc_status == 'pending' ? 'selected' : '-' }}>
                            Pending
                        </option>
                        <option value="registered" {{ $investmentData->kyc_status == 'registered' ? selected : '-' }}>
                            Registered</option>
                        <option value="validated" {{ $investmentData->kyc_status == 'validated' ? selected : '-' }}>
                            Validated</option>
                    </select>
                </div>
                <div class="col-md-2"><label class="form-label mb-0">Investment Date</label></div>
                <div class="col-md-4 col-xl-4">
                    <input type="date" name="investment_date" id="investment_date"
                        value="{{ $investmentData->investment_date }}" class="form-control">
                    @error('investment_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row align-items-center g-3 mt-6" id="pms_product_div">
                <div class="col-md-2"><label class="form-label mb-0">Product Name</label></div>
                <div class="col-md-4 col-xl-4">
                    <input type="text" name="product_name" id="product_name" class="form-control"
                        placeholder="Enter Product Name" value="{{ $investmentData->product_name }}">
                    @error('product_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2"><label class="form-label mb-0">Amount Of Investment</label>
                </div>
                <div class="col-md-4 col-xl-4">
                    <input type="number" name="amount_of_investment" id="amount_of_investment" class="form-control"
                        placeholder="Amount Of Investment" value="{{ $investmentData->amount_of_investment }}">
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
                    <input type="checkbox" name="mf_type_amount" id="sip_detail" class="form-check-inline"
                        value="SIP" onclick="toggleDiv()"> SIP
                    <input type="checkbox" name="mf_type_amount" id="lumsum_detail" class="form-check-inline"
                        value="Lumsum" onclick="toggleDiv()"> Lumsum
                </div>
            </div>
            <div class="row align-items-center g-3 mt-6" id="mf_sip" style="display: none;">
                <div class="col-md-2"><label class="form-label mb-0">SIP Amount</label></div>
                <div class="col-md-4 col-xl-4">
                    <input type="number" name="sip_amount" id="sip_amount" class="form-control"
                        placeholder="Enter SIP Amount" value="{{ $investmentData->sip_amount }}">
                    @error('sip_amount')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2"><label class="form-label mb-0">SIP Start Date</label></div>
                <div class="col-md-4 col-xl-4">
                    <input type="date" name="sip_date" id="sip_date" value="{{ $investmentData->sip_date }}"
                        class="form-control">
                    @error('sip_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row align-items-center g-3 mt-6" id="mf_lumsum" style="display: none;">
                <div class="col-md-2"><label class="form-label mb-0">Lumsum Amount</label></div>
                <div class="col-md-4 col-xl-4">
                    <input type="number" name="lumsum_amount" id="lumsum_amount" placeholder="Enter Lumsum Amount"
                        class="form-control" value="{{ $investmentData->lumsum_amount }}">
                    @error('lumsum_amount')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="row align-items-center g-3 mt-6" id="fd_interest" style="display: none;">
                <div class="col-md-2"><label class="form-label mb-0">Rate of Interest</label></div>
                <div class="col-md-4 col-xl-4">
                    <input type="number" name="interest_rate" id="interest_rate"
                        placeholder="Enter Rate Of Interest" class="form-control"
                        value="{{ $investmentData->interest_rate }}">
                    @error('interest_rate')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2"><label class="form-label mb-0">Maturity Date</label></div>
                <div class="col-md-4 col-xl-4">
                    <input type="date" name="maturity_date" id="maturity_date"
                        value="{{ $investmentData->maturity_date }}" class="form-control">
                    @error('maturity_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

            </div>
            <div class="row align-items-center g-3 mt-6" id="maturity_amount_interest" style="display: none;">
                <div class="col-md-2"><label class="form-label mb-0">Maturity Amount</label></div>
                <div class="col-md-4">
                    <input type="number" name="maturity_amount" id="maturity_amount" class="form-control"
                        placeholder="Enter Maturity Amount" value="{{ $investmentData->maturity_amount }}">
                    @error('maturity_amount')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2">Interest Mode</div>
                <div class="col-md-4">
                    <select name="interest_mode" id="interest_mode" class="form-control">
                        <option value="">Select Interest Mode</option>
                        <option value="Cumulative"
                            {{ $investmentData->interest_mode == 'Cumulative' ? selected : '' }}>
                            Cumulative</option>
                        <option value="Non Cumulative"
                            {{ $investmentData->interest_mode == 'Non Cumulative' ? selected : '' }}>Non Cumulative
                        </option>
                    </select>
                </div>
            </div>
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Tenure</label></div>
                <div class="col-md-4"><input type="text" name="tenure" id="tenure" class="form-control"
                        placeholder="Enter Tenure"></div>
                <div class="col-md-2 bond_payout_detail"><label class="form-label mb-0">Interest Payout </label></div>
                <div class="col-md-4 bond_payout_detail">
                    <select name="interest_payout" id="interest_payout" class="form-control">
                        <option value="">Select Interest Payout</option>
                        <option value="Monthly" {{ $investmentData->interest_payout == 'Monthly' ? selected : '' }}>
                            Monthly
                        </option>
                        <option value="Quarterly"
                            {{ $investmentData->interest_payout == 'Quarterly' ? selected : '' }}>
                            Quarterly</option>
                        <option value="half_year"
                            {{ $investmentData->interest_payout == 'half_year' ? selected : '' }}>
                            Half Year</option>
                        <option value="yearly" {{ $investmentData->interest_payout == 'yearly' ? selected : '' }}>
                            Yearly
                        </option>
                    </select>
                </div>
            </div>

            <div class="row align-items-center g-3 mt-6">

                <div class="col-md-2"><label class="form-label mb-0">Lead Date</label></div>
                <div class="col-md-4 col-xl-4">
                    <input type="date" name="lead_date" value="{{ $lead->lead_date }}" class="form-control">
                    @error('lead_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    @endif
    @include('admin.lead.partials.edit.general_insurance')
    @include('admin.lead.partials.edit.travel')
    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-8"></div>
        <div class="col-md-4 text-end">
            <button type="button" id="previous_second_step_button" class="btn btn-light btn-sm"><i
                    class="fa-solid fa-arrow-left"></i>Previous</button>
            <a href="{{ route('leads.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
            <button type="button" id="next_second_step_button" class="btn btn-primary btn-sm">Next<i
                    class="fa-solid fa-arrow-right"></i></button>
        </div>
    </div>
</div>
