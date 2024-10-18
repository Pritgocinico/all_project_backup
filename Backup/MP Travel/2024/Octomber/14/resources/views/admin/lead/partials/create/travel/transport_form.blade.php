<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Departure Date</label></div>
    <div class="col-md-4">
        <select name="transport_departure_date" id="transport_departure_date" class="form-control" onchange="getTransportDepartureTime()">
            <option value="">Select Departure Date</option>
            <option value="flexible">Flexible</option>
            <option value="fixed">Fixed</option>
            <option value="anytime">AnyTime</option>
        </select>
        @error('transport_departure_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2 flexible_date_select d-none">
        <label class="form-label mb-0">Month and Year</label>
    </div>
    <div class="col-md-4 flexible_date_select d-none">
        <input type="text" name="transport_monthYear" class="form-control" id="transport_monthYear"
            placeholder="Select Month and Year">
    </div>
    <div class="col-md-2 fixed_date_select d-none">
        <label class="form-label mb-0">Fixed Date</label>
    </div>
    <div class="col-md-4 fixed_date_select d-none">
        <input type="text" name="transport_fixed_date" class="form-control" id="transport_fixed_date"
            placeholder="Select Month and Year">
    </div>
    <div class="col-md-2 any_time_date_select d-none">
        <label class="form-label mb-0">Number Day</label>
    </div>
    <div class="col-md-4 any_time_date_select d-none">
        <input type="text" name="transport_any_time_days" class="form-control" id="transport_any_time_days"
            placeholder="Enter Number Of Days">
    </div>
</div>

<div class="row align-items-center g-3 mt-6 flexible_date_select d-none">
    <div class="col-md-2">
        <label class="form-label mb-0">Week</label>
    </div>
    <div class="col-md-4">
        <select name="transport_week" id="transport_week" class="form-control">
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
        <input type="number" name="transport_flexible_number_days" class="form-control"
            id="transport_flexible_number_days" placeholder="Enter Days">
        @error('transport_flexible_number_days')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Number of Customers</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="number" name="transport_number_of_customers" class="form-control" value="{{ old('transport_number_of_customers') }}"
            placeholder="Enter Number of Customers">
        @error('transport_number_of_customers')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Self Drive</label></div>
    <div class="col-md-4 col-xl-4">
        <select name="self_drive" class="form-select" id="self_drive">
            <option value="">Select Self Drive</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
        @error('self_drive')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<hr class="my-6">
<div class="row align-items-center g-3 mt-6 passenger_mobile_number_div d-none">
    <h6 class="text-dark">Travel Member Detail</h6>
    <div id="transport_child_detail"></div>
    <div class="row justify-content-end mt-3">
        <div class="col-md-2">
            <a href="javascript:void(0)" id="transport_more_button" class="btn btn-sm btn-dark">Add
                More</a>
        </div>
    </div>
</div>
<hr class="my-6">
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Destination</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="transport_destination" class="form-control"
            value="{{ old('transport_destination') }}" placeholder="Enter Travel Destination">
        @error('transport_destination')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Destination To</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="transport_destination_to" class="form-control" id="transport_destination_to"
            placeholder="Enter Destination To">
        @error('transport_destination_to')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Pickup Date</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="date" name="pickup_date" class="form-control" id="pickup_date" value="{{ old('pickup_date') ?? date('Y-m-d') }}" onchange="setDropDate()"
            placeholder="Enter Pickup Date">
        @error('pickup_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Drop Date</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="date" name="drop_date" class="form-control" id="drop_date" placeholder="Enter Drop Date" value="{{ old('drop_date') ?? date('Y-m-d') }}">
        @error('drop_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Vehicle Chauffer</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="vehicle_chauffer" class="form-control" value="{{ old('vehicle_chauffer') }}"
            placeholder="Enter Vehicle Chauffer">
        @error('vehicle_chauffer')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2"><label class="form-label mb-0">Vehicle Type</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="vehicle_type" class="form-control" id="vehicle_type"
            placeholder="Enter Vehicle Type">
        @error('vehicle_type')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row align-items-center g-3 mt-6">
    <div class="col-md-2"><label class="form-label mb-0">Specific Requirement</label></div>
    <div class="col-md-4 col-xl-4">
        <input type="text" name="specific_requirement" class="form-control"
            value="{{ old('specific_requirement') }}" placeholder="Enter Specific Requirement">
        @error('specific_requirement')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
