<div id="general_insurance_div" style="display: @if (old('invest_type') !== 'general insurance') none @endif;">
    <hr class="my-6">
    <h4>General Insurance</h4>
    <div id="risk_location_address_div">
        <div class="row align-items-center g-3 mt-6">
            <div class="col-md-6">
                <input type="checkbox" name="same_address" id="same_address" value="1" checked> Same As Address For
                Risk
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
                <select id="risk_location_country" name="risk_location_country" class="form-control"
                    onchange="getState()">
                    <option value="">Select Country</option>
                    @foreach ($countryList as $country)
                        <option value="{{ $country->iso2 }}">{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><label class="form-label mb-0">Risk Location State</label></div>
            <div class="col-md-4">
                <select id="risk_location_state" name="risk_location_state" class="form-control risk_location_state"
                    onchange="getCity()">
                </select>
            </div>
        </div>
        <div class="row align-items-center g-3 mt-6 risk_location_address d-none">
            <div class="col-md-2"><label class="form-label mb-0">Risk Location City</label></div>
            <div class="col-md-4">
                <select id="risk_location_city" name="risk_location_city" class="form-control risk_location_city">

                </select>
            </div>
            <div class="col-md-2"><label class="form-label mb-0">Risk Location Pin Code</label></div>
            <div class="col-md-4">
                <input type="text" name="risk_location_pin_code" id="risk_location_pin_code" class="form-control"
                    placeholder="Enter Pincode">
            </div>
        </div>
    </div>
    <div id="fire_policy_div" class="{{ old('insurance_type') !== "fire_policy"? 'd-none' :"" }}">
        @include('admin.lead.partials.create.insurance.fire_policy')
    </div>
    <div id="wc_policy_div" class="{{ old('insurance_type') !== "wc_policy"? 'd-none' :"" }}">
        @include('admin.lead.partials.create.insurance.wc_policy')
    </div>
    <div id="health_policy_div" class="{{ old('insurance_type') !== "health"? 'd-none' :"" }}">
        @include('admin.lead.partials.create.insurance.health_policy')
    </div>
    <div id="pa_policy_div" class="{{ old('insurance_type') !== "pa_policy"? 'd-none' :"" }}">
        @include('admin.lead.partials.create.insurance.pa_policy')
    </div>
    <div id="gpa_policy_div" class="{{ old('insurance_type') !== "gpa_policy"? 'd-none' :"" }}">
        @include('admin.lead.partials.create.insurance.gpa_policy')
    </div>
    <div id="gms_policy_div" class="{{ old('insurance_type') !== "gmc_policy"? 'd-none' :"" }}">
        @include('admin.lead.partials.create.insurance.gmc_policy')
    </div>
    <div id="marine_policy_div" class="{{ old('insurance_type') !== "marine_policy"? 'd-none' :"" }}">
        @include('admin.lead.partials.create.insurance.marine_policy')
    </div>
</div>
