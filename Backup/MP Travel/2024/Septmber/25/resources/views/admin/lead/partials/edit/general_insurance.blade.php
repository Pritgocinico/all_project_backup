@if (isset($lead->insuranceLeadData))
    @php $insuranceData = $lead->insuranceLeadData @endphp
    <div id="general_insurance_div" style="display: @if ($type !== 2) none @endif;">
        <hr class="my-6">
        <h4>General Insurance</h4>
        <div id="risk_location_address_div">
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-6">
                    <input type="checkbox" name="same_address" id="same_address" value="1" {{ $insuranceData->same_address == 1 ? "checked" : ""}}> Same As Address For
                    Risk
                    Location Address
                </div>
                <div class="col-md-2 risk_location_address {{ $insuranceData->same_address !== 1 ? "d-none" : ""}}">Address</div>
                <div class="col-md-4 risk_location_address {{ $insuranceData->same_address !== 1 ? "d-none" : ""}}">
                    <textarea name="risk_location_address" id="risk_location_address" class="form-control"
                        placeholder="Enter Rick Location Address">{{ $insuranceData->risk_location_address }}</textarea>
                </div>
            </div>
            <div class="row align-items-center g-3 mt-6 risk_location_address {{ $insuranceData->same_address !== 1 ? "d-none" : ""}}">
                <div class="col-md-2"><label class="form-label mb-0">Risk Location Country</label></div>
                <div class="col-md-4">
                    <select id="risk_location_country" name="risk_location_country" class="form-control"
                        onchange="getState()">
                        <option value="">Select Country</option>
                        @foreach ($countryList as $country)
                            <option value="{{ $country->iso2 }}" {{$insuranceData->risk_location_country == $country->iso2 ? 'selected' :""}}>{{ $country->name }}</option>
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
            <div class="row align-items-center g-3 mt-6 risk_location_address {{ $insuranceData->same_address !== 1 ? "d-none" : ""}}">
                <div class="col-md-2"><label class="form-label mb-0">Risk Location City</label></div>
                <div class="col-md-4">
                    <select id="risk_location_city" name="risk_location_city" class="form-control risk_location_city">

                    </select>
                </div>
                <div class="col-md-2"><label class="form-label mb-0">Risk Location Pin Code</label></div>
                <div class="col-md-4">
                    <input type="text" name="risk_location_pin_code" id="risk_location_pin_code" class="form-control"
                        placeholder="Enter Pincode" value="{{ $insuranceData->risk_location_pin_code }}">
                </div>
            </div>
        </div>
        <div id="fire_policy_div" class="d-none">
            @include('admin.lead.partials.edit.insurance.fire_policy')
        </div>
        <div id="wc_policy_div" class="d-none">
            @include('admin.lead.partials.edit.insurance.wc_policy')
        </div>
        <div id="health_policy_div" class="d-none">
            @include('admin.lead.partials.edit.insurance.health_policy')
        </div>
        <div id="pa_policy_div" class="d-none">
            @include('admin.lead.partials.edit.insurance.pa_policy')
        </div>
        <div id="gpa_policy_div" class="d-none">
            @include('admin.lead.partials.create.insurance.gpa_policy')
        </div>
        <div id="gms_policy_div" class="d-none">
            @include('admin.lead.partials.create.insurance.gmc_policy')
        </div>
        <div id="marine_policy_div" class="d-none">
            @include('admin.lead.partials.create.insurance.marine_policy')
        </div>
    </div>
@endif
