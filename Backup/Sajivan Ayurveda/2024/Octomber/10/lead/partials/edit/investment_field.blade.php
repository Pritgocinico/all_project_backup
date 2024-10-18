<div id="second_step_div" style="display: none;">
    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2 insurance_label"
            style="display: @if ($type == 0) none @endif;"><label
                class="form-label mb-0">Investment Type</label></div>
        <div class="col-md-4 col-xl-4" id="investmentRadio"
            style="display: @if ($type !== 1) none @endif;">
            <select name="insurance_type" id="insurance_type" class="form-control">
                <option value="pms" {{ $lead->insurance_type == "pms"? 'selected' :"" }}>PMS</option>
                <option value="mf" {{ $lead->insurance_type == "mf"? 'selected' :"" }}>MF</option>
                <option value="fd" {{ $lead->insurance_type == "fd"? 'selected' :"" }}>FD</option>
                <option value="bond" {{ $lead->insurance_type == "bond"? 'selected' :"" }}>Bond</option>
            </select>
        </div>
        <div class="col-md-4 col-xl-4" id="generalRadio"
            style="display: @if ($type !== 2) none @endif;">
            <input class="form-check-input" type="radio" name="insurance_type"
                id="health_insurance" value="health" {{ $lead->insurance_type == "health_insurance"? 'checked' :"" }}>
            <label class="form-check-label" for="disabled">
                Health
            </label>

            <input class="form-check-input" type="radio" name="insurance_type"
                id="motor_insurance" value="motor" {{ $lead->insurance_type == "motor"? 'checked' :"" }}>
            <label class="form-check-label" for="view">
                Motor
            </label>

            <input class="form-check-input" type="radio" name="insurance_type"
                id="sme_insurance" value="sme" {{ $lead->insurance_type == "sme"? 'checked' :"" }}>
            <label class="form-check-label" for="corporate">
                SME
            </label>
        </div>
        <div class="col-md-2 investement_field_label investement_type_field">
            <label class="form-label mb-0">Investment Field</label>
        </div>
        <div class="col-md-4 investement_field_form investement_type_field">
            <select name="investment_field" id="investment_field" class="form-control">
                <option value="new" {{ $lead->investment_field == "new"? 'selected' :"" }}>New</option>
                <option value="existing" {{ $lead->investment_field == "existing"? 'selected' :"" }}>Existing</option>
            </select>
        </div>
    </div>
    <div id="type_of_investments" style="display: {{ $lead->invest_type !== "investments" ? "none" : ""}};">
        <div class="align-items-center row g-3 mt-6" id="existing_investment_detail_div"
            style="display: none">
            <div class="col-md-2">Existing Investment Details</div>
            <div class="col-md-4">
                <input type="text" name="investment_code" id="investment_code" placeholder="Enter Existing Investment Detail" value="{{$lead->investment_code}}"
                    class="form-control">
            </div>
            <div class="col-md-2">Existing Investment Remarks</div>
            <div class="col-md-4">
                <textarea name="investment_remark" id="investment_remark" class="form-control" placeholder="Enter Existing Investment Remarks">{{$lead->investment_remark}}</textarea>
            </div>
        </div>
        <div class="row align-items-center g-3 mt-6">
            <div class="col-md-2 aadhar_pan_card_detail_div" style="display: {{ $lead->insurance_type !== "pms" ? 'none':''}}"><label class="form-label mb-0">KYC Status</label></div>
            <div class="col-md-4 aadhar_pan_card_detail_div" style="display: {{ $lead->insurance_type !== "pms" ? 'none':''}}">
                <select name="kyc_status" id="kyc_status" class="form-control">
                    <option value="">Select KYC Status</option>
                    <option value="pending" {{ $lead->kyc_status == "pending"? 'selected' :"" }}>Pending</option>
                    <option value="registered" {{ $lead->kyc_status == "registered"? 'selected' :"" }}>Registered</option>
                    <option value="validated" {{ $lead->kyc_status == "validated"? 'selected' :"" }}>Validated</option>
                </select>
            </div>
            <div class="col-md-2"><label class="form-label mb-0">Investment Date</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="date" name="investment_date" id="investment_date"
                    value="{{$lead->investment_date ?? date('Y-m-d')}}" class="form-control">
                @error('investment_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row align-items-center g-3 mt-6 " id="pms_product_div" style="display: {{ $lead->invest_type == "mf"? 'none' :''}}">
            <div class="col-md-2"><label class="form-label mb-0">Product Name</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="text" name="product_name" id="product_name" class="form-control"
                    placeholder="Enter Product Name" value="{{$lead->product_name}}">
                @error('product_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2"><label class="form-label mb-0">Amount Of Investment</label>
            </div>
            <div class="col-md-4 col-xl-4">
                <input type="number" name="amount_of_investment" id="amount_of_investment"
                    class="form-control" placeholder="Amount Of Investment" {{$lead->amount_of_investment}}>
                @error('amount_of_investment')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row align-items-center g-3 mt-6" id="mf_detail_div" style="display: {{ $lead->invest_type !== "mf"? 'none' :''}}">
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
                    value="{{$lead->sip_amount}}">
                @error('sip_amount')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-2"><label class="form-label mb-0">SIP Start Date</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="date" name="sip_date" id="sip_date"
                    value="{{$lead->sip_date ?? date('Y-m-d') }}" class="form-control">
                @error('sip_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row align-items-center g-3 mt-6" id="mf_lumsum" style="display: none;">
            <div class="col-md-2"><label class="form-label mb-0">Lumsum Amount</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="number" name="lumsum_amount" id="lumsum_amount" placeholder="Enter Lumsum Amount" value="{{$lead->lumsum_amount}}"
                    class="form-control">
                @error('lumsum_amount')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
        </div>

        <div class="row align-items-center g-3 mt-6" id="fd_interest" style="display: {{ $lead->invest_type !== "fd" || $lead->invest_type !== "bond"? 'none' :''}};">
            <div class="col-md-2"><label class="form-label mb-0">Rate of Interest</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="number" name="interest_rate" id="interest_rate" placeholder="Enter Rate Of Interest"
                    class="form-control" value="{{$lead->interest_rate}}">
                @error('interest_rate')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2"><label class="form-label mb-0">Maturity Date</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="date" name="maturity_date" id="maturity_date"
                    value="{{$lead->maturity_date ?? date('Y-m-d') }}" class="form-control">
                @error('maturity_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

        </div>
        <div class="row align-items-center g-3 mt-6" id="maturity_amount_interest"
            style="display: {{ $lead->invest_type !== "fd" || $lead->invest_type !== "bond"? 'none' :''}};">
            <div class="col-md-2"><label class="form-label mb-0">Maturity Amount</label></div>
            <div class="col-md-4">
                <input type="number" name="maturity_amount" id="maturity_amount"
                    class="form-control" placeholder="Enter Maturity Amount" value="{{$lead->maturity_amount}}">
                @error('maturity_amount')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-2">Interest Mode</div>
            <div class="col-md-4">
                <select name="interest_mode" id="interest_mode" class="form-control">
                    <option value="">Select Interest Mode</option>
                    <option value="Cumulative" {{ $lead->interest_mode == "Cumulative"? 'selected' :"" }}>Cumulative</option>
                    <option value="Non Cumulative" {{ $lead->interest_mode == "Non Cumulative"? 'selected' :"" }}>Non Cumulative</option>
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
                    <option value="Monthly" {{ $lead->interest_payout == "Monthly"? 'selected' :"" }}>Monthly</option>
                    <option value="Quarterly" {{ $lead->interest_payout == "Quarterly"? 'selected' :"" }}>Quarterly</option>
                    <option value="half_year" {{ $lead->interest_payout == "half_year"? 'selected' :"" }}>Half Year</option>
                    <option value="yearly" {{ $lead->interest_payout == "yearly"? 'selected' :"" }}>Yearly</option>
                </select>
            </div>
        </div>

        <div class="row align-items-center g-3 mt-6">

            <div class="col-md-2"><label class="form-label mb-0">Lead Date</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="date" name="lead_date"
                    value="{{$lead->lead_date ?? date('Y-m-d') }}" class="form-control">
                @error('lead_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    @include('admin.lead.partials.edit.general_insurance')
    @include('admin.lead.partials.edit.travel')
    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-8"></div>
        <div class="col-md-4 text-end">
            <button type="button" id="previous_second_step_button"
                class="btn btn-light btn-sm"><i class="fa-solid fa-arrow-left"></i>Previous</button>
            <a href="{{ route('leads.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
            <button type="button" id="next_second_step_button"
                class="btn btn-primary btn-sm">Next<i class="fa-solid fa-arrow-right"></i></button>
        </div>
    </div>
</div>