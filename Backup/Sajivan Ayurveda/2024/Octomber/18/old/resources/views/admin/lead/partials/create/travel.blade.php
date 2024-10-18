<div id="travel_div" style="display:  @if (old('invest_type') !== 'travel') none @endif;">
    <h4>Travel</h4>
    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Travel Inquiry Type</label></div>
        <div class="col-md-4 col-xl-4">
            <select name="travel_inquiry_type" id="travel_inquiry_type" class="form-control">
                <option value="">Select Mode</option>
                <option value="flight">Flight</option>
                <option value="visa">Visa</option>
                <option value="domestic">Domestic</option>
                <option value="international">International</option>
                <option value="hotel">Hotel</option>
                <option value="transport">Transport</option>
            </select>
        </div>
        <div class="col-md-2"><label class="form-label mb-0">Inquiry Date</label></div>
        <div class="col-md-4">
            <input type="date" name="inquiry_date" class="form-control"
                value="{{ old('inquiry_date') ?? date('Y-m-d') }}" placeholder="Enter Previous Policy Number">
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
                        {{ old('country_document') == $country->name ? 'selected' : '' }}>
                        {{ $country->name }}</option>
                @endforeach
            </select>
            <span id="country_doc_not_file" class="error_span"></span>
            @error('country_document')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-2 document_check_list_doc d-none"><label class="form-label mb-0">Country Document</label></div>
        <div class="col-md-4 col-xl-4 document_check_list_doc d-none">
            <div id="doc_file_country"></div>
        </div>
        <input type="hidden" name="doc_check_list_id" id="doc_check_list_id">
    </div>
    <div id="flight_form_div" class="d-none">
        @include('admin.lead.partials.create.travel.flight_form')
    </div>
    <div id="visa_form_div" class="d-none">
        @include('admin.lead.partials.create.travel.visa_form')
    </div>
    <div id="domestic_form_div" class="d-none">
        @include('admin.lead.partials.create.travel.domestic_form')
    </div>
    <div id="international_form_div" class="d-none">
        @include('admin.lead.partials.create.travel.international_form')
    </div>
    <div id="hotel_form_div" class="d-none">
        @include('admin.lead.partials.create.travel.hotel_form')
    </div>
    <div id="transport_form_div" class="d-none">
        @include('admin.lead.partials.create.travel.transport_form')
    </div>
</div>
