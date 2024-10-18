<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">From Travel Destination </label></div>
    <div class="col-md-4">
        <input type="text" name="travel_destination" class="form-control" value="{{ old('travel_destination') }}"
            placeholder="Enter Travel Destination">
        @error('travel_destination')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">To Travel Destination </label></div>
    <div class="col-md-4">
        <input type="text" name="travel_destination_to" class="form-control"
            value="{{ old('travel_destination_to') }}" placeholder="Enter Travel Destination To">
        @error('travel_destination_to')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Departure Date</label></div>
    <div class="col-md-4">
        <select name="departure_date" id="departure_date" class="form-control" onchange="getDepartureTime()">
            <option value="">Select Departure Date</option>
            <option value="flexible">Flexible</option>
            <option value="fixed">Fixed</option>
            <option value="anytime">AnyTime</option>
        </select>
        @error('departure_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2 flexible_date_select d-none">
        <label class="form-label mb-0">Month and Year</label>
    </div>
    <div class="col-md-4 flexible_date_select d-none">
        <input type="text" name="domestic_monthYear" class="form-control" id="monthYear"
            placeholder="Select Month and Year">
    </div>
    <div class="col-md-2 fixed_date_select d-none">
        <label class="form-label mb-0">Fixed Date</label>
    </div>
    <div class="col-md-4 fixed_date_select d-none">
        <input type="text" name="domestic_fixed_date" class="form-control" id="domestic_fixed_date"
            placeholder="Select Month and Year">
    </div>
    <div class="col-md-2 any_time_date_select d-none">
        <label class="form-label mb-0">Number Day</label>
    </div>
    <div class="col-md-4 any_time_date_select d-none">
        <input type="text" name="any_time_days" class="form-control" id="any_time_days"
            placeholder="Enter Number Of Days">
    </div>
</div>

<div class="row align-items-center g-3 mt-6 flexible_date_select d-none">
    <div class="col-md-2">
        <label class="form-label mb-0">Week</label>
    </div>
    <div class="col-md-4">
        <select name="domestic_week" id="domestic_week" class="form-control">
            <option value="">Select Week</option>
            <option value="1">1 Week </option>
            <option value="2">2 Week</option>
            <option value="3">3 Week</option>
            <option value="4">4 Week</option>
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
            id="domestic_flexible_number_days" placeholder="Enter Days">
        @error('domestic_flexible_number_days')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Number of Customers</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="domestic_number_of_customers" class="form-control"
            value="{{ old('number_of_customers') }}" placeholder="Enter Number of Customers">
        @error('number_of_customers')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2"><label class="form-label mb-0">Specific Place Interest</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="specific_place_interest" class="form-control"
            value="{{ old('specific_place_interest') }}" placeholder="Enter Specific Place Interest">
        @error('specific_place_interest')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<hr class="my-6">
<div class="row align-items-center g-3 mt-6 passenger_mobile_number_div d-none">
    <h6 class="text-dark">Travel Member Detail</h6>
    <div id="domestic_child_detail"></div>
    <div class="row justify-content-end mt-3">
        <div class="col-md-2">
            <a href="javascript:void(0)" id="domestic_more_button" class="btn btn-sm btn-dark">Add
                More</a>
        </div>
    </div>
</div>
<hr class="my-6">
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Type of Travel</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="travel_type" class="form-control" id="travel_type" value="{{ old('travel_type') }}"
            placeholder="Enter Travel Type">
        @error('travel_type')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Hotel Category</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="hotel_category" class="form-control" id="hotel_category"
            placeholder="Enter Hotel Category">
        @error('hotel_category')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Meal Plan Preference</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" class="form-control" name="meal_plan_preference"
            placeholder="Enter Meal Plan Preference" value="{{ old('meal_plan_preference') }}"></textarea>
        @error('meal_plan_preference')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Transport Category</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="transport_category" class="form-control"
            value="{{ old('transport_category') }}" placeholder="Enter Transport Category">
        @error('transport_category')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Duration of Stay</label></div>
    <div class="col-md-4">
        <input type="text" name="domestic_duration_of_stay" class="form-control"
            value="{{ old('domestic_duration_of_stay') }}" placeholder="Enter Duration of Stay">
        @error('domestic_duration_of_stay')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Other Services</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="domestic_other_services" class="form-select" id="domestic_other_services">
            <option value="">Select Other Services</option>
            @foreach ($serviceList as $service)
                <option value="{{ $service->id }}"
                    {{ old('domestic_other_services') == $service->id ? 'selected' : '' }}>{{ $service->name }}
                </option>
            @endforeach
        </select>
        @error('domestic_other_services')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Other Services Remarks </label></div>
    <div class="col-md-10 col-xl-10">
        <textarea name="domestic_other_services_remarks" id="domestic_other_services_remarks"class="form-control"
            placeholder="Enter Other Services Remarks">{{ old('domestic_other_services_remarks') }}</textarea>
        @error('domestic_other_services_remarks')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
