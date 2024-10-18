<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Duration of Stay</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="hotel_duration_stay" class="form-control" value="{{ isset($travelData) && $travelData->duration_of_stay }}"
            placeholder="Enter Duration of their stay">
        @error('hotel_duration_stay')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Flight To</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="flight_to" class="form-control" id="flight_to" placeholder="Enter Flight Form"
            value="{{ isset($travelData) && $travelData->flight_to }}">
        @error('flight_to')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Departure Date</label></div>
    <div class="col-md-4">
        <select name="hotel_departure_date" id="hotel_departure_date" class="form-control" onchange="getDepartureTime()">
            <option value="">Select Departure Date</option>
            <option value="flexible" {{ isset($travelData) && $travelData->departure_date == 'flexible' ? 'selected' : '' }}>Flexible</option>
            <option value="fixed" {{ isset($travelData) && $travelData->departure_date == 'fixed' ? 'selected' : '' }}>Fixed</option>
            <option value="anytime" {{ isset($travelData) && $travelData->departure_date == 'anytime' ? 'selected' : '' }}>AnyTime</option>
        </select>
        @error('departure_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2 flexible_date_select  {{ isset($travelData) && $travelData->departure_date == 'flexible' ? '': 'd-none' }}">
        <label class="form-label mb-0">Month and Year</label>
    </div>
    <div class="col-md-4 flexible_date_select {{ isset($travelData) && $travelData->departure_date == 'flexible' ? '': 'd-none' }}">
        <input type="text" name="hotel_monthYear" class="form-control" id="monthYear" value="{{ isset($travelData) && $travelData->flexible_month_year }}"
            placeholder="Select Month and Year">
    </div>
    <div class="col-md-2 fixed_date_select {{ isset($travelData) && $travelData->departure_date == 'fixed' ? '': 'd-none' }}">
        <label class="form-label mb-0">Fixed Date</label>
    </div>
    <div class="col-md-4 fixed_date_select {{ isset($travelData) && $travelData->departure_date == 'fixed' ? '': 'd-none' }}">
        <input type="text" name="hotel_fixed_date" class="form-control" id="hotel_fixed_date" value="{{ isset($travelData) && $travelData->domestic_fixed_date }}"
            placeholder="Select Month and Year">
    </div>
    <div class="col-md-2 any_time_date_select {{ isset($travelData) && $travelData->departure_date == 'anytime' ? '': 'd-none' }}">
        <label class="form-label mb-0">Number Day</label>
    </div>
    <div class="col-md-4 any_time_date_select {{ isset($travelData) && $travelData->departure_date == 'anytime' ? '': 'd-none' }}">
        <input type="text" name="hotel_days" class="form-control" id="hotel_days" value="{{ isset($travelData) && $travelData->number_of_day }}"
            placeholder="Enter Number Of Days">
    </div>
</div>

<div class="row align-items-center g-3 mt-6 flexible_date_select {{ isset($travelData) && $travelData->departure_date == 'flexible' ? '': 'd-none' }}">
    <div class="col-md-2">
        <label class="form-label mb-0">Week</label>
    </div>
    <div class="col-md-4">
        <select name="hotel_week" id="hotel_week" class="form-control">
            <option value="">Select Week</option>
            <option value="1" {{ isset($travelData) && $travelData->domestic_week == '1' ? 'selected' : '' }}>1 Week </option>
            <option value="2" {{ isset($travelData) && $travelData->domestic_week == '2' ? 'selected' : '' }}>2 Week</option>
            <option value="3" {{ isset($travelData) && $travelData->domestic_week == '3' ? 'selected' : '' }}>3 Week</option>
            <option value="4" {{ isset($travelData) && $travelData->domestic_week == '4' ? 'selected' : '' }}>4 Week</option>
        </select>
        @error('week')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label mb-0">Days</label>
    </div>
    <div class="col-md-4">
        <input type="number" name="hotel_flexible_number_days" class="form-control"
            id="hotel_flexible_number_days" placeholder="Enter Days" value="{{ isset($travelData) && $travelData->number_of_day }}">
        @error('hotel_flexible_number_days')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Number of Customers</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="hotel_number_of_customers" class="form-control"
            value="{{ isset($travelData) && $travelData->number_of_customers }}" placeholder="Enter Number of Customers">
        @error('hotel_number_of_customers')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Travel Destination</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="hotel_travel_destination" class="form-control" id="hotel_travel_destination"
            value="{{ isset($travelData) && $travelData->travel_destination }}" placeholder="Enter Travel Destination">
        @error('hotel_travel_destination')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div
    class="row align-items-center g-3 mt-6 passenger_mobile_number_div  {{ isset($travelData) && $travelData->travel_inquiry_type == '' ? 'd-none' : '' }}">
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

    <div class="col-md-2"><label class="form-label mb-0">Preferable Area for stay</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="area_stay" class="form-control" id="area_stay"
            placeholder="Enter Preferable Area for stay" value="{{isset($travelData) && $travelData->inquiry_date}}">
        @error('area_stay')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Hotel Category</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" class="form-control" name="hotel_hotel_category" placeholder="Enter Hotel Category"
            value="{{ isset($travelData) && $travelData->hotel_category }}"></textarea>
        @error('hotel_hotel_category')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">

    <div class="col-md-2"><label class="form-label mb-0">Meal Plan</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="hotel_meal_plan" class="form-control" value="{{ isset($travelData) && $travelData->meal_plan_preference }}"
            placeholder="Enter Meal Plan">
        @error('hotel_meal_plan')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
