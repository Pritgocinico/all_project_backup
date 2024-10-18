<div id="travel_div" style="display:  @if ($lead->invest_type !== 'travel') none @endif;">
    @php $travelData = $lead->travelLeadData;@endphp
    <h4>Travel</h4>
    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Travel Inquiry Type</label></div>
        <div class="col-md-4 col-xl-4">
            <select name="travel_inquiry_type" id="travel_inquiry_type" class="form-control">
                <option value="">Select Mode</option>
                <option value="flight" {{ isset($travelData) &&$travelData->travel_inquiry_type == 'flight' ? 'selected' : '' }}>Flight
                </option>
                <option value="visa" {{ isset($travelData) && $travelData->travel_inquiry_type == 'visa' ? 'selected' : '' }}>Visa</option>
                <option value="domestic" {{ isset($travelData) && $travelData->travel_inquiry_type == 'domestic' ? 'selected' : '' }}>Domestic
                </option>
                <option value="international"
                    {{ isset($travelData) && $travelData->travel_inquiry_type == 'international' ? 'selected' : '' }}>
                    International</option>
                <option value="hotel" {{ isset($travelData) && $travelData->travel_inquiry_type == 'hotel' ? 'selected' : '' }}>Hotel
                </option>
                <option value="transport" {{ isset($travelData) && $travelData->travel_inquiry_type == 'transport' ? 'selected' : '' }}>
                    Transport
                </option>
            </select>
        </div>
        
        <div class="col-md-2"><label class="form-label mb-0">Inquiry Date</label></div>
        <div class="col-md-4">
            <input type="date" name="inquiry_date" class="form-control"
                value="{{ isset($travelData) && $travelData->inquiry_date }}" placeholder="Enter Previous Policy Number">
            @error('inquiry_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Country List</label></div>
        <div class="col-md-4 col-xl-4">
            <select name="country_document" id="country_document" class="form-control" onchange="getCountryDocument()">
                <option value="">Select Country</option>
                @foreach ($countryList as $country)
                    <option value="{{ $country->name }}"
                        {{ isset($travelData) &&$travelData->document_country == $country->name ? 'selected' : '' }}>
                        {{ $country->name }}</option>
                @endforeach
            </select>
            <span id="country_doc_not_file" class="error_span"></span>
            @error('country_document')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-2"><label class="form-label mb-0">Country Document</label></div>
        <div class="col-md-4 col-xl-4">
            <div id="doc_file_country">
            @if (isset($documentCheckList) && $documentCheckList->document_file != null)
                <a href="{{ asset('storage/'.$documentCheckList->document_file) }}" target="_blank" class="btn btn-dark btn-sm">View</a>
            @endif
            </div>
        </div>
    </div>
    <input type="hidden" name="doc_check_list_id" id="doc_check_list_id" value="{{ isset($documentCheckList)?$documentCheckList->id : "" }}">
    <div id="flight_form_div" class="{{ isset($travelData) && $travelData->travel_inquiry_type == 'flight' ? '' : 'd-none' }}">
        @include('admin.lead.partials.edit.travel.flight_form')
    </div>
    <div id="visa_form_div" class="{{ isset($travelData) && $travelData->travel_inquiry_type == 'visa' ? '' : 'd-none' }}">
        @include('admin.lead.partials.edit.travel.visa_form')
    </div>
    <div id="domestic_form_div" class="{{ isset($travelData) && $travelData->travel_inquiry_type == 'domestic' ? '' : 'd-none' }}">
        @include('admin.lead.partials.edit.travel.domestic_form')
    </div>
    <div id="international_form_div" class="{{ isset($travelData) && $travelData->travel_inquiry_type == 'international' ? '' : 'd-none' }}">
        @include('admin.lead.partials.edit.travel.international_form')
    </div>
    <div id="hotel_form_div" class="{{ isset($travelData) && $travelData->travel_inquiry_type == 'hotel' ? '' : 'd-none' }}">
        @include('admin.lead.partials.edit.travel.hotel_form')
    </div>
    <div id="transport_form_div" class="{{ isset($travelData) && $travelData->travel_inquiry_type == 'transport' ? '' : 'd-none' }}">
        @include('admin.lead.partials.edit.travel.transport_form')
    </div>
</div>
