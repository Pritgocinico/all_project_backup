<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Destination From</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="transport_destination" class="form-control" id="transport_destination" placeholder="Enter Destination Form"
            value="{{ isset($travelData) && $travelData->travel_destination }}">
        @error('travel_destination')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Destination To</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="transport_destination_to" class="form-control" id="transport_destination_to" placeholder="Enter Flight Form"
            value="{{ isset($travelData) &&  $travelData->flight_to }}">
        @error('transport_destination_to')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Departure Date</label></div>
    <div class="col-md-4">
        <select name="transport_departure_date" id="transport_departure_date" class="form-control" onchange="getDepartureTime()">
            <option value="">Select Departure Date</option>
            <option value="flexible" {{ isset($travelData) &&  $travelData->departure_date == 'flexible' ? 'selected' : '' }}>Flexible</option>
            <option value="fixed" {{ isset($travelData) &&  $travelData->departure_date == 'fixed' ? 'selected' : '' }}>Fixed</option>
            <option value="anytime" {{ isset($travelData) &&  $travelData->departure_date == 'anytime' ? 'selected' : '' }}>AnyTime</option>
        </select>
        @error('departure_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2 flexible_date_select  {{ isset($travelData) &&  $travelData->departure_date == 'flexible' ? '': 'd-none' }}">
        <label class="form-label mb-0">Month and Year</label>
    </div>
    <div class="col-md-4 flexible_date_select {{ isset($travelData) &&  $travelData->departure_date == 'flexible' ? '': 'd-none' }}">
        <input type="text" name="transport_monthYear" class="form-control" id="monthYear" value="{{ isset($travelData) &&  $travelData->flexible_month_year }}"
            placeholder="Select Month and Year">
    </div>
    <div class="col-md-2 fixed_date_select {{ isset($travelData) &&  $travelData->departure_date == 'fixed' ? '': 'd-none' }}">
        <label class="form-label mb-0">Fixed Date</label>
    </div>
    <div class="col-md-4 fixed_date_select {{ isset($travelData) &&  $travelData->departure_date == 'fixed' ? '': 'd-none' }}">
        <input type="text" name="transport_fixed_date" class="form-control" id="transport_fixed_date" value="{{ isset($travelData) &&  $travelData->domestic_fixed_date }}"
            placeholder="Select Month and Year">
    </div>
    <div class="col-md-2 any_time_date_select {{ isset($travelData) &&  $travelData->departure_date == 'anytime' ? '': 'd-none' }}">
        <label class="form-label mb-0">Number Day</label>
    </div>
    <div class="col-md-4 any_time_date_select {{ isset($travelData) &&  $travelData->departure_date == 'anytime' ? '': 'd-none' }}">
        <input type="text" name="transport_flexible_number_days" class="form-control" id="transport_flexible_number_days" value="{{ isset($travelData) &&  $travelData->number_of_day }}"
            placeholder="Enter Number Of Days">
    </div>
</div>

<div class="row align-items-center g-3 mt-6 flexible_date_select {{isset($travelData) &&  $travelData->departure_date == 'flexible' ? '': 'd-none' }}">
    <div class="col-md-2">
        <label class="form-label mb-0">Week</label>
    </div>
    <div class="col-md-4">
        <select name="transport_week" id="transport_week" class="form-control">
            <option value="">Select Week</option>
            <option value="1" {{ isset($travelData) &&  $travelData->domestic_week == '1' ? 'selected' : '' }}>1 Week </option>
            <option value="2" {{ isset($travelData) &&  $travelData->domestic_week == '2' ? 'selected' : '' }}>2 Week</option>
            <option value="3" {{ isset($travelData) &&  $travelData->domestic_week == '3' ? 'selected' : '' }}>3 Week</option>
            <option value="4" {{ isset($travelData) &&  $travelData->domestic_week == '4' ? 'selected' : '' }}>4 Week</option>
        </select>
        @error('week')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label mb-0">Days</label>
    </div>
    <div class="col-md-4">
        <input type="number" name="transport_flexible_number_days" class="form-control"
            id="transport_flexible_number_days" placeholder="Enter Days" value="{{ isset($travelData) &&  $travelData->number_of_day }}">
        @error('transport_flexible_number_days')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Destination</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="transport_destination" class="form-control"
            value="{{ isset($travelData) &&  $travelData->travel_destination }}" placeholder="Enter Travel Destination">
        @error('transport_destination')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Pickup Date</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="date" name="pickup_date" class="form-control" id="pickup_date" value="{{ isset($travelData) &&  $travelData->pickup_date }}"
            placeholder="Enter Pickup Date">
        @error('pickup_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Drop Date</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="date" name="drop_date" class="form-control" id="drop_date" placeholder="Enter Drop Date" value="{{isset($travelData) &&  $travelData->drop_date}}">
        @error('drop_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Self Drive</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="self_drive" class="form-select" id="self_drive">
            <option value="">Select Self Drive</option>
            <option value="yes" {{ isset($travelData) &&  $travelData->self_drive == 'yes' ? 'selected' : ''}}>Yes</option>
            <option value="no" {{ isset($travelData) &&  $travelData->self_drive == 'no' ? 'selected' : ''}}>No</option>
        </select>
        @error('self_drive')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Vehicle Chauffer</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="vehicle_chauffer" class="form-control" value="{{ isset($travelData) &&  $travelData->vehicle_chauffer }}"
            placeholder="Enter Vehicle Chauffer">
        @error('vehicle_chauffer')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Vehicle Type</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="vehicle_type" class="form-control" id="vehicle_type"
            placeholder="Enter Vehicle Type" value="{{isset($travelData) &&  $travelData->vehicle_type}}">
        @error('vehicle_type')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Specific Requirement</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="specific_requirement" class="form-control" 
            value="{{ isset($travelData) &&  $travelData->specific_requirement }}" placeholder="Enter Specific Requirement">
        @error('specific_requirement')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div
    class="row align-items-center g-3 mt-6 passenger_mobile_number_div  {{isset($travelData) &&  $travelData->travel_inquiry_type == '' ? 'd-none' : '' }}">
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