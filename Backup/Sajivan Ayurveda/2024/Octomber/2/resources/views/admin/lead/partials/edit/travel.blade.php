<div id="travel_div" style="display: @if ($type !== 3) none @endif;">
    <hr class="my-6">
    <h4>Travel</h4>

    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Client Name</label></div>
        <div class="col-md-4 col-xl-4">
            <input type="text" name="client_name" class="form-control" value="{{$lead->client_name}}" placeholder="Enter Client Name">
            @error('client_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-2"><label class="form-label mb-0">Travel From Date</label></div>
        <div class="col-md-4 col-xl-4">
            <input type="date" name="travel_from_date" value="{{$lead->travel_from_date ?? date('Y-m-d') }}}"
                class="form-control">
            @error('travel_start_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Travel To Date</label></div>
        <div class="col-md-4 col-xl-4">
            <input type="date" name="travel_to_date" value="{{$lead->travel_to_date ?? date('Y-m-d') }}"
                class="form-control">
            @error('travel_to_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-2"><label class="form-label mb-0">Number Of Prex</label></div>
        <div class="col-md-4 col-xl-4">
            <input type="number" name="number_of_prex" class="form-control" placeholder="Enter Number of Prex" value="{{$lead->number_of_prex}}">
            @error('number_of_prex')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Travel Destination</label></div>
        <div class="col-md-4 col-xl-4">
            <select name="travel_destination" id="travel_destination" class="form-control">
                <option value="0" {{ $lead->travel_destination == "0"? 'selected' :"" }}>Domestic</option>
                <option value="1" {{ $lead->travel_destination == "1"? 'selected' :"" }}>International</option>
            </select>
        </div>

        <div class="col-md-2"><label class="form-label mb-0">Flight Preference</label></div>
        <div class="col-md-4 col-xl-4">
            <input type="text" name="flight_preference" class="form-control" placeholder="Enter Flight Preference" value="{{$lead->flight_preference}}">
            @error('flight_preference')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Hotel Preference</label></div>
        <div class="col-md-4 col-xl-4">
            <input type="text" name="hotel_preference" class="form-control" placeholder="Enter Hotel Preference" value="{{$lead->hotel_preference}}">
            @error('hotel_preference')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-2"><label class="form-label mb-0">Other Services</label></div>
        <div class="col-md-4 col-xl-4">
            <select name="other_services" class="form-control">
                <option value="">Select Services</option>
                <option value="domestic_air_ticket" {{ $lead->other_services == "domestic_air_ticket"? 'selected' :"" }}>Domestic Air Ticket</option>
                <option value="visa" {{ $lead->other_services == "visa"? 'selected' :"" }}>Visa</option>
                <option value="railway_ticket" {{ $lead->other_services == "railway_ticket"? 'selected' :"" }}>Railway Ticket</option>
                <option value="hotel" {{ $lead->other_services == "hotel"? 'selected' :"" }}>Hotel</option>
                <option value="passport" {{ $lead->other_services == "passport"? 'selected' :"" }}>Passport</option>
                <option value="rent_cab" {{ $lead->other_services == "rent_cab"? 'selected' :"" }}>Rent a Cab</option>
                <option value="other" {{ $lead->other_services == "other"? 'selected' :"" }}>Other</option>
            </select>
            @error('other_services')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Itinerary Flow</label></div>
        <div class="col-md-4 col-xl-4">
            <textarea name="itinerary_flow" class="form-control" id="description" placeholder="Enter Flow" >{{$lead->description}}</textarea>
            @error('itinerary_flow')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <hr class="my-6">
    <h4>Child Detail</h4>
    <div class="row justify-content-end">
        <div class="col-md-2">
            <a href="javascript:void(0)" id="add_more_button" class="btn btn-sm btn-dark">Add
                More</a>
        </div>
    </div>
    <div id="travel_child_detail"></div>
</div>
