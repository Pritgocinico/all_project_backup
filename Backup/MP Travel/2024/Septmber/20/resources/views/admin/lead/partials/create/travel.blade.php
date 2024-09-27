<div id="travel_div" style="display: @if ($type !== 3) none @endif;">
    <hr class="my-6">
    <h4>Travel</h4>

    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Client Name</label></div>
        <div class="col-md-4 col-xl-4">
            <input type="text" name="client_name" class="form-control" value="" placeholder="Enter Client Name">
            @error('client_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-2"><label class="form-label mb-0">Travel From Date</label></div>
        <div class="col-md-4 col-xl-4">
            <input type="date" name="travel_from_date" value="{{ old('travel_from_date', date('Y-m-d')) }}"
                class="form-control">
            @error('travel_start_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Travel To Date</label></div>
        <div class="col-md-4 col-xl-4">
            <input type="date" name="travel_to_date" value="{{ old('travel_to_date', date('Y-m-d')) }}"
                class="form-control">
            @error('travel_to_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-2"><label class="form-label mb-0">Number Of Prex</label></div>
        <div class="col-md-4 col-xl-4">
            <input type="number" name="number_of_prex" class="form-control" placeholder="Enter Number of Prex">
            @error('number_of_prex')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Travel Destination</label></div>
        <div class="col-md-4 col-xl-4">
            <select name="travel_destination" id="travel_destination" class="form-control">
                <option value="0">Domestic</option>
                <option value="1">International</option>
            </select>
        </div>

        <div class="col-md-2"><label class="form-label mb-0">Flight Preference</label></div>
        <div class="col-md-4 col-xl-4">
            <input type="text" name="flight_preference" class="form-control" placeholder="Enter Flight Preference">
            @error('flight_preference')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Hotel Preference</label></div>
        <div class="col-md-4 col-xl-4">
            <input type="text" name="hotel_preference" class="form-control" placeholder="Enter Hotel Preference">
            @error('hotel_preference')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-2"><label class="form-label mb-0">Other Services</label></div>
        <div class="col-md-4 col-xl-4">
            <select name="other_services[]" class="form-control" id="other_services" multiple>
                <option value="">Select Services</option>
                <option value="domestic_air_ticket">Domestic Air Ticket</option>
                <option value="visa">Visa</option>
                <option value="railway_ticket">Railway Ticket</option>
                <option value="hotel">Hotel</option>
                <option value="passport">Passport</option>
                <option value="rent_cab">Rent a Cab</option>
                <option value="other">Other</option>
            </select>
            @error('other_services')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Remarks</label></div>
        <div class="col-md-4 col-xl-4">
            <textarea name="itinerary_flow" class="form-control" id="description" placeholder="Enter Remark"></textarea>
            @error('itinerary_flow')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-2 other_service_div" style="display: none">Other Service</div>
        <div class="col-md-4 col-xl-4 other_service_div" style="display: none">
            <input type="text" name="other_service_charge" id="other_service_charge" class="form-control" placeholder="Enter Other Service Name">
            @error('other_service_charge')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row align-items-center g-m mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Travel Mode</label></div>
        <div class="col-md-4 col-xl-4">
            <select name="travel_mode" id="travel_mode" class="form-control">
                <option value="">Select Mode</option>
                <option value="family">Family</option>
                <option value="group">Group</option>
                <option value="individual">Individual</option>
            </select>
        </div>
        <div class="col-md-2"><label class="form-label mb-0">Other Attachment</label></div>
        <div class="col-md-4 col-xl-4">
            <input type="file" name="travel_other_attachment[]" id="travel_other_attachment" multiple class="form-control">
        </div>
    </div>
    <hr class="my-6">
    <h4>Other Detail</h4>
    <div class="row justify-content-end">
        <div class="col-md-2 child_add_more_button">
            <a href="javascript:void(0)" id="add_more_button" class="btn btn-sm btn-dark">Add
                More</a>
        </div>
    </div>
    <div id="travel_child_detail"></div>
</div>
