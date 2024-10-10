<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Travel Date</label></div>
    <div class="col-md-4">
        <input type="date" name="visa_travel_date" class="form-control"
            value="{{ old('visa_travel_date') ?? date('Y-m-d') }}" placeholder="Enter Previous Policy Number">
        @error('visa_travel_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Duration of their stay</label></div>
    <div class="col-md-4">
        <input type="text" name="duration_of_stay" class="form-control"
            value="{{ old('duration_of_stay') }}" placeholder="Enter Duration of their stay">
        @error('duration_of_stay')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Number of Customers</label></div>
    <div class="col-md-4">
        <input type="number" name="number_of_customers" class="form-control"
            value="{{ old('number_of_customers') }}" placeholder="Enter Number of Customers">
        @error('number_of_customers')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Country</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="travel_country" class="form-select" id="travel_country">
            <option value="">Select Country</option>
            @foreach ($countryList as $country)
                <option value="{{ $country->iso2 }}">{{ $country->name }}</option>
            @endforeach
        </select>
        @error('travel_country')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Purpose of Travel</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="purpose_of_travel" class="form-control"
            value="{{ old('purpose_of_travel') }}" placeholder="Enter Purpose of Travel">
        @error('purpose_of_travel')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Visa Type</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="visa_type" class="form-control"
            id="visa_type" value="{{ old('visa_type') }}" placeholder="Enter Visa Type">
        @error('visa_type')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Expense bearer	</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="expense_bearer" class="form-select" id="expense_bearer">
            <option value="">Select Expense bearer</option>
            <option value="self_sponsored">Self Sponsored</option>
            <option value="company_sponsored">Company Sponsored</option>
        </select>
        @error('expense_bearer')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2">First Time Traveller or not?</div>
    <div class="col-md-4 col-xl-4">
        <select name="first_time_traveler"  class="form-select" id="first_time_traveler" onchange="showTravelHistory()">
            <option value="">Select First Time Traveller</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
        @error('first_time_traveler')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2 show_travel_history_div d-none"><label class="form-label mb-0">Travel History</label></div>
    <div class="col-md-4 col-xl-4 show_travel_history_div d-none">
        <textarea class="form-control" name="travel_history" placeholder="Enter Travel History">{{ old('travel_history') }}</textarea></textarea>
        @error('travel_history')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Visa Rejection</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="visa_rejection" class="form-select" id="visa_rejection" onchange="showVisaRejectionDetail()">
            <option value="">Select Visa Rejection</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
        @error('visa_rejection')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Other Services</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="travel_other_services" class="form-select" id="travel_other_services" onchange="showTravelOtherServicesDetail()">
            <option value="">Select Country</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
        @error('travel_other_services')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6 visa_rejection_detail_div d-none">
    <div class="col-md-2"><label class="form-label mb-0">Reject Country Name</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="visa_rejection_country" class="form-select" id="visa_rejection_country">
            <option value="">Select Country</option>
            @foreach ($countryList as $country)
                <option value="{{ $country->iso2 }}">{{ $country->name }}</option>
            @endforeach
        </select>
        @error('visa_rejection_country')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Visa Rejection Reason</label></div>
    <div class="col-md-4 col-xl-4">
        <textarea class="form-control" name="visa_rejection_reason" placeholder="Enter Visa Rejection Reason">{{ old('visa_rejection_reason') }}</textarea></textarea>
        @error('visa_rejection_reason')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6 visa_other_service_document_div d-none">
    <div class="col-md-2"><label class="form-label mb-0">Upload Document</label></div>
    <div class="col-md-4 col-xl-4 ">
        <input type="file" class="form-control" name="other_service_document[]" placeholder="Enter Visa Rejection Reason" multiple></input></textarea>
    </div>
</div>
<hr class="my-6">
<div class="row align-items-center g-3 mt-6 passenger_mobile_number_div d-none">
    <h6 class="text-dark">Occupation of Passenger</h6>
    <div id="visa_member_detail"></div>
    <div class="row justify-content-end mt-3">
        <div class="col-md-2">
            <a href="javascript:void(0)" onclick="addVisaChildData()" class="btn btn-sm btn-dark">Add
                More</a>
        </div>
    </div>
</div>