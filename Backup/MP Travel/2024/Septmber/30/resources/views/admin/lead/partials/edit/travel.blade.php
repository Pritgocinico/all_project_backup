@if (isset($lead->travelLeadData))
    @php $travelData = $lead->travelLeadData;@endphp
    <div id="travel_div" style="display: @if ($type !== 3) none @endif;">
        <hr class="my-6">
        <h4>Travel</h4>

        <div class="row align-items-center g-3 mt-6">
            <div class="col-md-2"><label class="form-label mb-0">Client Name</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="text" name="client_name" class="form-control" value="{{ $travelData->name }}"
                    placeholder="Enter Client Name">
                @error('client_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2"><label class="form-label mb-0">Travel From Date</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="date" name="travel_from_date" value="{{ $travelData->travel_from }}"
                    class="form-control">
                @error('travel_start_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row align-items-center g-3 mt-6">
            <div class="col-md-2"><label class="form-label mb-0">Travel To Date</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="date" name="travel_to_date" value="{{ $travelData->travel_to }}" class="form-control">
                @error('travel_to_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2"><label class="form-label mb-0">Number Of Prex</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="number" name="number_of_prex" class="form-control" placeholder="Enter Number of Prex"
                    value="{{ $travelData->no_of_pax }}">
                @error('number_of_prex')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row align-items-center g-3 mt-6">
            <div class="col-md-2"><label class="form-label mb-0">Travel Destination</label></div>
            <div class="col-md-4 col-xl-4">
                <select name="travel_destination" id="travel_destination" class="form-control">
                    <option value="0" {{ $travelData->travel_destination == '0' ? 'selected' : '' }}>Domestic
                    </option>
                    <option value="1" {{ $travelData->travel_destination == '1' ? 'selected' : '' }}>International
                    </option>
                </select>
            </div>

            <div class="col-md-2"><label class="form-label mb-0">Flight Preference</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="text" name="flight_preference" class="form-control"
                    placeholder="Enter Flight Preference" value="{{ $travelData->flight_preference }}">
                @error('flight_preference')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row align-items-center g-3 mt-6">
            <div class="col-md-2"><label class="form-label mb-0">Hotel Preference</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="text" name="hotel_preference" class="form-control" placeholder="Enter Hotel Preference"
                    value="{{ $travelData->hotel_preference }}">
                @error('hotel_preference')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2"><label class="form-label mb-0">Other Services</label></div>
            <div class="col-md-4 col-xl-4">
                <select name="other_services[]" class="form-control" id="other_services" multiple>
                    <option value="">Select Services</option>
                    <option value="domestic_air_ticket"
                        {{ str_contains($travelData->other_service, 'domestic_air_ticket') ? 'selected' : '' }}>Domestic
                        Air Ticket</option>
                    <option value="visa" {{ str_contains($travelData->other_service, 'visa') ? 'selected' : '' }}>Visa
                    </option>
                    <option value="railway_ticket"
                        {{ str_contains($travelData->other_service, 'railway_ticket') ? 'selected' : '' }}>Railway
                        Ticket</option>
                    <option value="hotel" {{ str_contains($travelData->other_service, 'hotel') ? 'selected' : '' }}>
                        Hotel</option>
                    <option value="passport"
                        {{ str_contains($travelData->other_service, 'passport') ? 'selected' : '' }}>Passport</option>
                    <option value="rent_cab"
                        {{ str_contains($travelData->other_service, 'rent_cab') ? 'selected' : '' }}>Rent a Cab</option>
                    <option value="other" {{ str_contains($travelData->other_service, 'other') ? 'selected' : '' }}>
                        Other</option>
                </select>
                @error('other_services')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row align-items-center g-m mt-6">
            <div class="col-md-2"><label class="form-label mb-0">Travel Mode</label></div>
            <div class="col-md-4 col-xl-4">
                <select name="travel_mode" id="travel_mode" class="form-control">
                    <option value="">Select Mode</option>
                    <option value="family" {{$travelData->travel_mode == 'family' ? 'selected' : ""}}>Family</option>
                    <option value="group" {{$travelData->travel_mode == 'group' ? 'selected' : ""}}>Group</option>
                    <option value="individual" {{$travelData->travel_mode == 'individual' ? 'selected' : ""}}>Individual</option>
                </select>
            </div>
            <div class="col-md-2"><label class="form-label mb-0">Other Attachment</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="file" name="travel_other_attachment[]" id="travel_other_attachment" multiple class="form-control">
            </div>
        </div>
        <div class="row align-items-center g-3 mt-6">
            <div class="col-md-2"><label class="form-label mb-0">Remarks</label></div>
            <div class="col-md-4 col-xl-4">
                <textarea name="itinerary_flow" class="form-control" id="description" placeholder="Enter Remark">{{ $travelData->remarks }}</textarea>
                @error('itinerary_flow')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-2 other_service_div"
                style="display: {{ str_contains($travelData->other_service, 'other') ? '' : 'none' }}">Other Service
            </div>
            <div class="col-md-4 col-xl-4 other_service_div"
                style="display: {{ str_contains($travelData->other_service, 'other') ? '' : 'none' }}">
                <input type="text" name="other_service_charge" id="other_service_charge" class="form-control"
                    value="{{ $travelData->other_service_charge }}" placeholder="Enter Other Service Name">
                @error('other_service_charge')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <hr class="my-6">
        <h4>Other Detail</h4>
        <div class="row justify-content-end">
            <div class="col-md-2">
                <a href="javascript:void(0)" id="add_more_button" class="btn btn-sm btn-dark">Add
                    More</a>
            </div>
        </div>
        <hr class="my-6">
        @if (isset($lead->leadTravelDetail))
            @foreach ($lead->leadTravelDetail as $travel)
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-4">
                        <label class="form-label">Child Type</label>
                        <select class="form-select" name="child_type[]">
                            <option value="">Select</option>
                            <option value="child" {{$travel->child_type == "child" ? "selected" : ""}}>Child</option>
                            <option value="Infant" {{$travel->child_type == "Infant" ? "selected" : ""}}>Infant</option>
                            <option value="Adult" {{$travel->child_type == "Adult" ? "selected" : ""}}>Adult</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Child Name</label>
                        <input type="text" class="form-control" name="child_name[]" placeholder="Enter Name" value="{{$travel->child_name}}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Child Age</label>
                        <input type="number" class="form-control" name="child_age[]" placeholder="Enter Age" value="{{$travel->child_age}}">
                    </div>
                    <div class="col-md-1">
                        <a href="javascript:void(0)" class="btn btn-primary btn-sm " id="remove_add_child_option"
                            onclick="removeRow({{$travel->id}})" data-id="{{$travel->id}}"><i class="fa-solid fa-trash-can"></i></a>
                    </div>
                </div>
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-4">
                        <label class="form-label">DOB</label>
                        <input type="date" class="form-control" name="child_dob[]" id="child_dob" value="{{$travel->child_dob}}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Passport Number</label>
                        <input type="text" class="form-control" name="passport_number[]" value="{{$travel->passport_number}}"
                            placeholder="Enter Passport Number" id="passport_number">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Passport File</label>
                        <input type="file" class="form-control" name="passport_file[]" placeholder="Enter Age"
                            id="passport_file">
                    </div>
                </div>
            @endforeach
        @endif
        <div id="travel_child_detail"></div>
    </div>
@endif
