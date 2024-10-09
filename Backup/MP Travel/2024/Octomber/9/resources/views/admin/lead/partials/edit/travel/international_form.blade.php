<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Travel Destination</label></div>
    <div class="col-md-4">
        <input type="text" name="international_travel_destination" class="form-control"
            value="{{ $travelData->travel_destination }}" placeholder="Enter Travel Destination">
        @error('international_travel_destination')
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
    <div class="col-md-2"><label class="form-label mb-0">Departure Date</label></div>
    <div class="col-md-4">
        <select name="departure_date" id="departure_date" class="form-control" onchange="getDepartureTime()">
            <option value="">Select Departure Date</option>
            <option value="flexible" {{ $travelData->departure_date == 'flexible' ? 'selected' : '' }}>Flexible</option>
            <option value="fixed" {{ $travelData->departure_date == 'fixed' ? 'selected' : '' }}>Fixed</option>
            <option value="anytime" {{ $travelData->departure_date == 'anytime' ? 'selected' : '' }}>AnyTime</option>
        </select>
        @error('departure_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2 flexible_date_select  {{ $travelData->departure_date == 'flexible' ? '': 'd-none' }}">
        <label class="form-label mb-0">Month and Year</label>
    </div>
    <div class="col-md-4 flexible_date_select {{ $travelData->departure_date == 'flexible' ? '': 'd-none' }}">
        <input type="text" name="domestic_monthYear" class="form-control" id="monthYear" value="{{ $travelData->flexible_month_year }}"
            placeholder="Select Month and Year">
    </div>
    <div class="col-md-2 fixed_date_select {{ $travelData->departure_date == 'fixed' ? '': 'd-none' }}">
        <label class="form-label mb-0">Fixed Date</label>
    </div>
    <div class="col-md-4 fixed_date_select {{ $travelData->departure_date == 'fixed' ? '': 'd-none' }}">
        <input type="text" name="domestic_fixed_date" class="form-control" id="domestic_fixed_date" value="{{ $travelData->domestic_fixed_date }}"
            placeholder="Select Month and Year">
    </div>
    <div class="col-md-2 any_time_date_select {{ $travelData->departure_date == 'anytime' ? '': 'd-none' }}">
        <label class="form-label mb-0">Number Day</label>
    </div>
    <div class="col-md-4 any_time_date_select {{ $travelData->departure_date == 'anytime' ? '': 'd-none' }}">
        <input type="text" name="any_time_days" class="form-control" id="any_time_days" value="{{ $travelData->number_of_day }}"
            placeholder="Enter Number Of Days">
    </div>
</div>

<div class="row align-items-center g-3 mt-6 flexible_date_select {{ $travelData->departure_date == 'flexible' ? '': 'd-none' }}">
    <div class="col-md-2">
        <label class="form-label mb-0">Week</label>
    </div>
    <div class="col-md-4">
        <select name="domestic_week" id="domestic_week" class="form-control">
            <option value="">Select Week</option>
            <option value="1" {{ $travelData->domestic_week == '1' ? 'selected' : '' }}>1 Week </option>
            <option value="2" {{ $travelData->domestic_week == '2' ? 'selected' : '' }}>2 Week</option>
            <option value="3" {{ $travelData->domestic_week == '3' ? 'selected' : '' }}>3 Week</option>
            <option value="4" {{ $travelData->domestic_week == '4' ? 'selected' : '' }}>4 Week</option>
        </select>
        @error('week')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label mb-0">Days</label>
    </div>
    <div class="col-md-4">
        <input type="number" name="domestic_flexible_number_days" class="form-control"
            id="domestic_flexible_number_days" placeholder="Enter Days" value="{{ $travelData->number_of_day }}">
        @error('domestic_flexible_number_days')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Number of Customers</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="international_number_of_customers" class="form-control"
            value="{{ $travelData->number_of_customers }}" placeholder="Enter Number of Customers">
        @error('international_number_of_customers')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Specific Place Interest</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="international_specific_place_interest" class="form-control"
            value="{{ $travelData->specific_place_interest }}" placeholder="Enter Specific Place Interest">
        @error('international_specific_place_interest')
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
    <div class="col-md-2"><label class="form-label mb-0">Type of Travel</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="international_travel_type" class="form-control"
            id="international_travel_type" value="{{ $travelData->travel_type }}" placeholder="Enter Travel Type">
        @error('international_travel_type')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Hotel Category</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="international_hotel_category" class="form-control" id="international_hotel_category" placeholder="Enter Hotel Category" value="{{ $travelData->hotel_category }}">
        @error('international_hotel_category')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Meal Plan Preference</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" class="form-control" name="international_meal_plan_preference" placeholder="Enter Meal Plan Preference" value="{{ $travelData->meal_plan_preference }}"></textarea>
        @error('international_meal_plan_preference')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Transport Category</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="international_transport_category" class="form-control"
            value="{{ old('international_transport_category') }}" placeholder="Enter Transport Category" value="{{ $travelData->transport_category }}">
        @error('international_transport_category')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Duration of Stay</label></div>
    <div class="col-md-4">
        <input type="text" name="international_duration_of_stay" class="form-control"
            value="{{ $travelData->duration_of_stay }}" placeholder="Enter Duration of Stay">
        @error('international_duration_of_stay')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Other Services</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="international_other_services" class="form-select" id="international_other_services">
            <option value="">Select Other Services</option>
            @foreach ($serviceList as $service)
                <option value="{{$service->id}}" {{ $travelData->domestic_other_services == $service->id ? 'selected' : '' }}>{{$service->name}}</option>
            @endforeach
        </select>
        @error('international_other_services')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Other Services Remarks </label></div>
    <div class="col-md-10 col-xl-10">
        <textarea name="international_other_services_remarks" id="international_other_services_remarks"class="form-control" placeholder="Enter Other Services Remarks">{{ $travelData->domestic_other_services_remarks }}</textarea>
        @error('international_other_services_remarks')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>