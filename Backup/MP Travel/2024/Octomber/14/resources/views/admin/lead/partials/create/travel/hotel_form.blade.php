<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Duration of Stay</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="hotel_duration_stay" class="form-control" value="{{ old('hotel_duration_stay') }}"
            placeholder="Enter Duration of their stay">
        @error('hotel_duration_stay')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Number of Customers</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="hotel_number_of_customers" class="form-control"
            value="{{ old('hotel_number_of_customers') }}" placeholder="Enter Number of Customers">
        @error('hotel_number_of_customers')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<hr class="my-6">
<div class="row align-items-center g-3 mt-6 passenger_mobile_number_div d-none">
    <h6 class="text-dark">Travel Member Detail</h6>
    <div id="hotel_child_detail"></div>
    <div class="row justify-content-end mt-3">
        <div class="col-md-2">
            <a href="javascript:void(0)" id="hotel_more_button" class="btn btn-sm btn-dark">Add
                More</a>
        </div>
    </div>
</div>
<hr class="my-6">
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Departure Date</label></div>
    <div class="col-md-4">
        <select name="hotel_departure_date" id="hotel_departure_date" class="form-control" onchange="getHotelDepartureTime()">
            <option value="">Select Departure Date</option>
            <option value="flexible">Flexible</option>
            <option value="fixed">Fixed</option>
            <option value="anytime">AnyTime</option>
        </select>
        @error('hotel_departure_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2 flexible_date_select d-none">
        <label class="form-label mb-0">Month and Year</label>
    </div>
    <div class="col-md-4 flexible_date_select d-none">
        <input type="text" name="hotel_monthYear" class="form-control" id="hotel_monthYear"
            placeholder="Select Month and Year">
    </div>
    <div class="col-md-2 fixed_date_select d-none">
        <label class="form-label mb-0">Fixed Date</label>
    </div>
    <div class="col-md-4 fixed_date_select d-none">
        <input type="text" name="hotel_fixed_date" class="form-control" id="hotel_fixed_date"
            placeholder="Select Month and Year">
    </div>
    <div class="col-md-2 any_time_date_select d-none">
        <label class="form-label mb-0">Number Day</label>
    </div>
    <div class="col-md-4 any_time_date_select d-none">
        <input type="text" name="hotel_days" class="form-control" id="hotel_days"
            placeholder="Enter Number Of Days">
    </div>
</div>

<div class="row align-items-center g-3 mt-6 flexible_date_select d-none">
    <div class="col-md-2">
        <label class="form-label mb-0">Week</label>
    </div>
    <div class="col-md-4">
        <select name="hotel_week" id="hotel_week" class="form-control">
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
        <input type="number" name="hotel_flexible_number_days" class="form-control"
            id="hotel_flexible_number_days" placeholder="Enter Days">
        @error('hotel_flexible_number_days')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Travel Destination</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="hotel_travel_destination" class="form-control" id="hotel_travel_destination"
            value="{{ old('hotel_travel_destination') }}" placeholder="Enter Travel Destination">
        @error('hotel_travel_destination')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Travel Destination To</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="hotel_travel_destination_to" class="form-control" id="hotel_travel_destination_to"
            value="{{ old('hotel_travel_destination_to') }}" placeholder="Enter Travel Destination">
        @error('hotel_travel_destination_to')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">

    <div class="col-md-2"><label class="form-label mb-0">Preferable Area for stay</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="area_stay" class="form-control" id="area_stay"
            placeholder="Enter Preferable Area for stay">
        @error('area_stay')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Hotel Category</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" class="form-control" name="hotel_hotel_category" placeholder="Enter Hotel Category"
            value="{{ old('hotel_hotel_category') }}"></textarea>
        @error('hotel_hotel_category')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">

    <div class="col-md-2"><label class="form-label mb-0">Meal Plan</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="hotel_meal_plan" class="form-control" value="{{ old('hotel_meal_plan') }}"
            placeholder="Enter Meal Plan">
        @error('hotel_meal_plan')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
