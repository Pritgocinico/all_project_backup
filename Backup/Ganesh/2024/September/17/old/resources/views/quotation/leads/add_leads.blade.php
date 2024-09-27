@extends('quotation.layouts.app')
@section('content')
    <div class="project">
        <div class="page-header d-md-flex justify-content-between align-items-center">
            <div class="">
                <h3 class="mb-0">Add Leads</h3>
            </div>
            <div class="">
                <a href="{{ route('quotation_leads') }}" class="btn btn-primary ms-auto">
                    <i class="sub-menu-arrow ti-angle-left me-2"></i> Back
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form class="alert-repeater" action="{{ route('quotation_storeleads') }}" enctype="multipart/form-data"
                    method="POST">
                    @csrf
                    <div class="form-row row">
                        <div class="form-group col-md-4" id="customerNameField">
                            <label for="customername">Customer Name <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center">
                                <select class="form-control select2-example" id="Customername" name="customer_name">
                                    <option>Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                                @error('customer_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="projectname">Reference Name </label>
                            <input type="text" class="form-control" id="reference_name" placeholder="Reference Name"
                                name="reference_name" value="{{ old('reference_name') }}">
                            @if ($errors->has('reference_name'))
                                <span class="text-danger">{{ $errors->first('reference_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="projectname">Reference Number </label>
                            <input type="text" class="form-control" id="reference_number" placeholder="Reference Number"
                                name="reference_number" value="{{ old('reference_number') }}">
                            @if ($errors->has('reference_number'))
                                <span class="text-danger">{{ $errors->first('reference_number') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="phone">Customer Number <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="phone_number" id="phone"
                                value="{{ old('phone_number') }}" placeholder="Customer Number">
                            @if ($errors->has('phone_number'))
                                <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" name="email" id="email"
                                placeholder="Email Address" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="addressone">Site Address</label>
                            <input type="text" class="form-control" name="addressone" id="addressone"
                                placeholder="Address" value="{{ old('addressone') }}">
                            @if ($errors->has('addressone'))
                                <span class="text-danger">{{ $errors->first('addressone') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="state">State </label>
                            <select class="form-control select2-example" id="state" name="state">
                                <option value="">Select State</option>
                                @foreach($states as $state)
                                    <option value="{{$state['id']}}">{{$state['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="cityname">City </label>
                            <select class="form-control select2-example" id="cityname" name="city">
                                <option default>Select City</option>
                            </select>
                            @if ($errors->has('cityname'))
                                <span class="text-danger">{{ $errors->first('cityname') }}</span>
                            @endif
                        </div>
                        {{-- <div class="form-group col-md-4">
                            <label for="statename">State </label>
                            <select class="form-control" id="statename" name="statename" disabled>
                                <option value="1">Gujarat</option>
                            </select>
                            @if ($errors->has('statename'))
                                <span class="text-danger">{{ $errors->first('statename') }}</span>
                            @endif
                        </div> --}}
                        <div class="form-group col-md-4">
                            <label for="zipcode">Zip Code </label>
                            <input type="text" class="form-control" name="zipcode" id="zipcode"
                                placeholder="Zip Code" value="{{ old('zipcode') }}">
                            @if ($errors->has('zipcode'))
                                <span class="text-danger">{{ $errors->first('zipcode') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="measurementdate">Lead Estimated Measurement Date <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="measurementdate" class="form-control">
                            @if ($errors->has('measurementdate'))
                                <span class="text-danger">{{ $errors->first('measurementdate') }}</span>
                            @endif
                        </div>
                        {{-- <div class="form-group col-md-4">
                            <label for="architecture_name">Architecture Name</label>
                            <input type="text" class="form-control" name="architecture_name" id="architecture_name"
                                placeholder="Architecture Name" value="{{ old('architecture_name') }}">
                            @if ($errors->has('architecture_name'))
                                <span class="text-danger">{{ $errors->first('architecture_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="architecture_number">Architecture Number</label>
                            <input type="number" class="form-control" name="architecture_number" id="architecture_number"
                                placeholder="Architecture Number" value="{{ old('architecture_number') }}">
                            @if ($errors->has('architecture_number'))
                                <span class="text-danger">{{ $errors->first('architecture_number') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="supervisor_name">Supervisor Name</label>
                            <input type="text" class="form-control" name="supervisor_name" id="supervisor_name"
                                placeholder="Supervisor Name" value="{{ old('supervisor_name') }}">
                            @if ($errors->has('supervisor_name'))
                                <span class="text-danger">{{ $errors->first('supervisor_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="supervisor_number">Supervisor Number</label>
                            <input type="number" class="form-control" name="supervisor_number" id="supervisor_number"
                                placeholder="Supervisor Number" value="{{ old('supervisor_number') }}">
                            @if ($errors->has('supervisor_number'))
                                <span class="text-danger">{{ $errors->first('supervisor_number') }}</span>
                            @endif
                        </div> --}}
                        <div class="form-group">
                            <label for="projectdesc">Description</label>
                            <textarea class="form-control" id="projectdesc" rows="5" name="description">{{ old('description') }}</textarea>
                            @if ($errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index: 1050;">
        <div class="modal-dialog modal-dialog-centered  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="updateUserForm" name="updateuserform"
                                action="{{ isset($customer) ? route('admin.update.customer', ['id' => $customer->id]) : '#' }}"
                                method="POST">
                                @csrf
                                <div class="alert alert-danger print-error-msg" style="display:none">
                                    <ul></ul>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="userNameedit">Customer Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control userName"
                                            id="userNameedit" aria-describedby="edit_nameError" placeholder="Enter Name"
                                            value="{{ old('name') }}">
                                        <small id="edit_nameError" class="form-text text-danger"></small>
                                    </div>
                                    <div class="form-group col-md-6 ">
                                        <label for="userEmailedit">Email address </label>
                                        <input type="email" class="form-control userEmail" name="email"
                                            id="userEmailedit" value="{{ old('email') }}"
                                            aria-describedby="edit_emailError" placeholder="Enter email">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="Phoneedit">Customer Number </label>
                                        <input type="tel" class="form-control userPhone" name="phone"
                                            id="Phoneedit" value="{{ old('phone') }}" aria-describedby="phoneError"
                                            placeholder="Enter Customer Number">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="address">Address </label>
                                        <input type="text" class="form-control" name="address" id="addressedit"
                                            placeholder="Address" value="{{ old('address') }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="state">State <span class="text-danger">*</span></label>
                                        <select class="form-control select2-example" id="customer_state" name="state">
                                            <option value="">Select State</option>
                                            @foreach($states as $state)
                                                <option value="{{$state['id']}}">{{$state['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="city">City <span class="text-danger">*</span></label>
                                        <select class="form-control select2-example" id="customer_city" name="city">
                                            <option default>Select City</option>
                                        </select>
                                        <small id="edit_cityError" class="form-text text-danger"></small>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="zipcode">Zip Code </label>
                                        <input type="text" class="form-control" name="zipcode" id="editzipcode"
                                            placeholder="Zip Code" value="{{ old('zipcode') }}">
                                    </div>
                                        <input type="hidden" name="role" value="2">
                                </div>
                                <button type="button" class="btn btn-primary" id="updateUserBtn">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('input[name="measurementdate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
        var $example = $('.select2-example').select2();

        $(document).on('change','#state',function(){
            var state = $(this).val();
            $.ajax({
                url : "{{route('quotation_get.cities', '')}}"+"/"+state,
                type : 'GET',
                dataType:'json',
                success : function(data) {
                    $('#cityname').html(data);
                }
            });
        });

        $(document).on('change','#customer_state',function(){
            var state = $(this).val();
            $.ajax({
                url : "{{route('quotation_get.cities', '')}}"+"/"+state,
                type : 'GET',
                dataType:'json',
                success : function(data) {
                    $('#customer_city').html(data);
                }
            });
        });

    </script>
    <script>
        $(document).on('change', '#Customername', function() {
            var id = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('quotation_get_customer') }}',
                data: {
                    'id': id
                },
                success: function(data) {
                    console.log(data);
                    $('#email').val(data.customer.email);
                    $('#phone').val(data.customer.phone);
                    $('#addressone').val(data.customer.address);
                    $('#zipcode').val(data.customer.zipcode);
                    $('#state option[value="' + data.customer.state + '"]').prop('selected', true);

                    var statesSelect = $('#state');
                    statesSelect.empty();
                    $.each(data.states, function(index, states) {
                        statesSelect.append($('<option>', {
                            value: states.id,
                            text: states.name,
                            selected: data.customer.state == states.id // Select the city that matches data.city
                        }));
                    });

                    // Set city value
                    // $('#cityname').append('<option  omer.city_id).trigger("change");

                    var citiesSelect = $('#cityname');
                    citiesSelect.empty(); // Clear existing options

                    // Append new options based on the cities data received
                    $.each(data.editcities, function(index, editcity) {
                        citiesSelect.append($('<option>', {
                            value: editcity.id,
                            text: editcity.name,
                            selected: data.customer.city == editcity.id // Select the city that matches data.city
                        }));
                    });
                },
                error: function(data) {
                }
            });
        });
        $(document).on('click', '#updateUserBtn', function() {
            var form = $('#updateUserForm');
            var nameError = $('#edit_nameError');
            var cityError = $('#edit_cityError');
            nameError.html('');
            cityError.html('');

            if (form[0].name.value === "") {
                nameError.html("Name must be filled out!").css("color", "Red");
                form.find('[name="name"]').focus();
                return;
            }

            if (form[0].customer_city.value === "0") {
                cityError.html("City must be filled out!").css("color", "Red");
                form.find('[name="city"]').focus();
                return;
            }

            $.ajax({
                type: 'POST',
                url: "{{ route('admin.add.customer.data', '') }}" + '/' + 1,
                headers: {
                    'X-CSRFToken': "{{ csrf_token() }}"
                },
                data: form.serialize(),
                success: function(data) {
                    console.log(data);
                    $('#Customername').append('<option value="' + data.customer.id + '" selected>' +
                        data
                        .customer.name + '</option>')
                    $('#phone').val(data.customer.phone);
                    $('#email').val(data.customer.email);
                    $('#addressone').val(data.customer.address);
                    $('#zipcode').val(data.customer.zipcode);

                    var statesSelect = $('#state');
                    statesSelect.empty();
                    $.each(data.customer_states, function(index, customer_states) {
                        statesSelect.append($('<option>', {
                            value: customer_states.id,
                            text: customer_states.name,
                            selected: data.customer.state == customer_states.id
                        }));
                    });
                    var citiesSelect = $('#cityname');
                    citiesSelect.empty();

                    // Append new options based on the cities data received
                    $.each(data.customer_cities, function(index, customer_cities) {
                        citiesSelect.append($('<option>', {
                            value: customer_cities.id,
                            text: customer_cities.name,
                            selected: data.customer.city == customer_cities.id
                        }));
                    });

                    // $example.val(data.customer.city).trigger("change");
                    $('#staticBackdrop').find('form')[0].reset();
                    $("#staticBackdrop").modal('hide');
                },
                error: function(data) {
                    // Handle error
                }
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {
            $("#businessType").change(function() {
                var selectedOption = $(this).val();
                // Show/hide Customer Name field based on the selected option
                if (selectedOption === "b2c") {
                    $("#customerNameField").show();
                } else {
                    $("#customerNameField").hide();
                }
                // Show/hide B2B specific fields based on the selected option
                if (selectedOption === "b2b") {
                    $(".b2b_div").show();
                } else {
                    $(".b2b_div").hide();
                }
            });
        });

    </script>
@endsection
