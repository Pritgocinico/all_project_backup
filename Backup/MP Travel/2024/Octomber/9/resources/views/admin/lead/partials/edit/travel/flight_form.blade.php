<div class="flight_form_first_step">
    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Flight Form</label></div>
        <div class="col-md-4 col-xl-4">
            <input type="text" name="flight_form" class="form-control" id="flight_form" placeholder="Enter Flight Form"
                value="{{ $travelData->flight_form }}">
            @error('flight_form')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-2"><label class="form-label mb-0">Flight To</label></div>
        <div class="col-md-4 col-xl-4">
            <input type="text" name="flight_to" class="form-control" id="flight_to" placeholder="Enter Flight Form"
                value="{{ $travelData->flight_to }}">
            @error('flight_to')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Travel From Date</label></div>
        <div class="col-md-4 col-xl-4">
            <input type="date" name="travel_form_date" class="form-control" id="travel_form_date"
                placeholder="Enter Flight Form" value="{{ $travelData->travel_form_date }}">
            @error('travel_form_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-2"><label class="form-label mb-0">Travel To Date</label></div>
        <div class="col-md-4">
            <input type="date" name="travel_to_date" class="form-control" value="{{ $travelData->travel_to_date }}"
                placeholder="Enter Previous Policy Number">
            @error('travel_to_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Number of Passengers</label></div>
        <div class="col-md-4">
            <input type="number" name="no_of_passengers" class="form-control" placeholder="Enter Number of Passengers"
                id="no_of_passengers" value="{{ $travelData->no_of_passengers }}">
            @error('no_of_passengers')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-2"><label class="form-label mb-0">Travel Sector</label></div>
        <div class="col-md-4">
            <select name="travel_sector" class="form-select" id="travel_sector">
                <option value="">Select Travel Sector</option>
                <option value="domestic" {{ $travelData->travel_sector == 'domestic' ? 'selected' : '' }}>Domestic
                </option>
                <option value="international" {{ $travelData->travel_sector == 'international' ? 'selected' : '' }}>
                    International</option>
            </select>
            @error('travel_sector')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div
        class="row align-items-center g-3 mt-6 passenger_mobile_number_div  {{ $travelData->travel_inquiry_type == '' ? 'd-none' : '' }}">
        <h6 class="text-dark">Travel Member Detail</h6>
        @foreach ($lead->leadTravelDetail as $travel)
            <div id="travel_edit_member_{{ $travel->id }}">
                <input type="hidden" name="travel_id[]" value="{{ $travel->id }}">
                <div class="row align-items-center mt-6">
                    <div class="col-md-3">
                        <label class="form-label">Member Name</label>
                        <input type="text" class="form-control" name="travel_member_name[]" placeholder="Enter Name"
                            value="{{ $travel->child_name }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Member DOB</label>
                        <input type="date" class="form-control" name="member_dob[]" placeholder="Enter Name"
                            value="{{ $travel->dob }}" id="member_dob_${i}" onchange="calculateAge(${i})">
                        Age:- <span id="age_calculate_${i}">{{ $travel->child_age }}</span>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">MemberDocument</label>
                        <input type="file" class="form-control" name="member_doc[][${i}]" placeholder="Enter Age"
                            multiple>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Other Type</label>
                        <select class="form-select" name="child_type[]">
                            <option value="">Select</option>
                            <option value="child" {{ $travel->child_type == 'child' ? 'selected' : '' }}>Child
                            </option>
                            <option value="Infant" {{ $travel->child_type == 'Infant' ? 'selected' : '' }}>Infant
                            </option>
                            <option value="Adult" {{ $travel->child_type == 'Adult' ? 'selected' : '' }}>Adult
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row justify-content-end mt-3">
                    <div class="col-md-2">
                        <a href="javascript:void(0)" class="btn btn-sm btn-dark"
                            onclick="removeTravelChildData({{ $travel->id }})" data-id="${i}"><i
                                class="fa-solid fa-trash-can"></i></a>
                    </div>
                </div>
            </div>
        @endforeach
        <div id="travel_child_detail"></div>
        <div class="row justify-content-end mt-3">
            <div class="col-md-2">
                <a href="javascript:void(0)" id="add_more_button" class="btn btn-sm btn-dark">Add
                    More</a>
            </div>
        </div>
    </div>
    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Travelling Mode</label></div>
        <div class="col-md-4 col-xl-4">
            <select name="travel_mode" class="form-select" id="travel_mode">
                <option value="">Select Travel Mode</option>
                <option value="one_way" {{ $travelData->travel_mode == 'one_way' ? 'selected' : '' }}>One Way</option>
                <option value="return" {{ $travelData->travel_mode == 'return' ? 'selected' : '' }}>Return</option>
                <option value="multi_city" {{ $travelData->travel_mode == 'multi_city' ? 'selected' : '' }}>Multi City
                </option>
            </select>
            @error('travel_mode')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-2"><label class="form-label mb-0">All passengers are travelling back to the same
                sector?</label></div>
        <div class="col-md-4 col-xl-4">
            <select name="all_passengers_are_traveling_back" class="form-select"
                id="all_passengers_are_traveling_back">
                <option value="">Select</option>
                <option value="yes"
                    {{ $travelData->all_passengers_are_traveling_back == '"yes"' ? 'selected' : '' }}>Yes</option>
                <option value="no"
                    {{ $travelData->all_passengers_are_traveling_back == '"no"' ? 'selected' : '' }}>No</option>
            </select>
            @error('all_passengers_are_traveling_back')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Passenger Travel Other Sector</label></div>
        <div class="col-md-4 col-xl-4">
            <input type="text" name="passenger_travel_other_sector" class="form-control"
                placeholder="Enter Passenger Travel Other Sector" id="passenger_travel_other_sector"
                value="{{ $travelData->passenger_travel_other_sector }}">
            @error('passenger_travel_other_sector')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Booking Status</label></div>
        <div class="col-md-4 col-xl-4">
            <select name="booking_status" class="form-select" id="booking_status" onchange="getTravelType()">
                <option value="">Select Booking Status</option>
                <option value="pending" {{ $travelData->booking_status == 'pending' ? 'selected' : '' }}>Pending
                </option>
                <option value="hold_ticket" {{ $travelData->booking_status == 'hold_ticket' ? 'selected' : '' }}>Hold
                    Ticket</option>
                <option value="confirm" {{ $travelData->booking_status == 'confirm' ? 'selected' : '' }}>Confirm
                </option>
            </select>
            @error('booking_status')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-2 aadhar_card_number_div d-none"><label class="form-label mb-0">Aadhar Card</label></div>
        <div class="col-md-4 col-xl-4 aadhar_card_number_div d-none">
            <input type="file" name="aadhar_card_number_travel" class="form-control"
                id="aadhar_card_number_travel">
        </div>
        <div class="col-md-2 passport_number_div d-none"><label class="form-label mb-0">Passport</label></div>
        <div class="col-md-4 col-xl-4 passport_number_div d-none">
            <input type="file" name="passport_number_travel" class="form-control" id="passport_number_travel">
        </div>
    </div>
    <div class="row align-items-center g-3 mt-6 pending_booking_div_status {{ $travelData->booking_status == 'pending' ? '' : "d-none" }}">
        <div class="col-md-2"><label class="form-label mb-0">Followup date</label></div>
        <div class="col-md-4 col-xl-4">
            <input type="date" name="followup_date" class="form-control" id="followup_date"
                value="{{ $travelData->followup_date }}">
            @error('followup_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-2 "><label class="form-label mb-0">Remarks</label></div>
        <div class="col-md-4 col-xl-4">
            <textarea name="pending_remarks" class="form-control" id="pending_remarks" class="form-control">{{ $travelData->pending_remarks }}</textarea>
        </div>
    </div>
</div>
