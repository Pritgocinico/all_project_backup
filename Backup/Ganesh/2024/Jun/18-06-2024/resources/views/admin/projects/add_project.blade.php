@extends('admin.layouts.app')
@section('content')
    <div class="project">
        <div class="page-header d-md-flex justify-content-between align-items-center">
            <div class="">
                <h3 class="mb-0">Add Projects</h3>
            </div>
            <div class="">
                <a href="{{ route('projects') }}" class="btn btn-primary ms-auto">
                    <i class="sub-menu-arrow ti-angle-left me-2"></i> Back
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form class="alert-repeater" action="{{ route('storeproject') }}" enctype="multipart/form-data"
                    method="POST">
                    @csrf
                    <div class="form-row row">
                        <div class="form-group col-md-4 b2b_div" style="display: none">
                            <div class="d-flex align-items-end">
                                <div class="w-100">
                                    <div class="form-group mb-0">
                                        <label for="business_name">Business Name <span class="text-danger">*</span></label>
                                        <select class="form-control" id="b_name" name="business_name">
                                            <option>Select Business</option>
                                            @foreach ($business as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('business_name'))
                                            <span class="text-danger">{{ $errors->first('business_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="ms-2 me-3">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop_business">
                                        <i class="sub-menu-arrow ti-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4 b2b_div" style="display: none">
                            <label for="b_gstnum">GST Number<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="gstnum" id="b_gstnum"
                                value="{{ old('b_gstnum') }}" placeholder="GST Number">
                            {{-- @if ($errors->has('b_gstnum'))
                                <span class="text-danger">{{ $errors->first('b_gstnum') }}</span>
                            @endif --}}
                        </div>
                        <div class="form-group col-md-4 b2c_div">
                            <div class="d-flex align-items-end">
                                <div class="w-100">
                                    <div class="form-group mb-0">
                                        <label for="Customername">Customer Name <span class="text-danger">*</span></label>
                                        <select class="form-control" id="Customername" name="customer_name">
                                            <option value="">Select Customer</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('customer_name'))
                                            <span class="text-danger">{{ $errors->first('customer_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="ms-2 me-3">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop">
                                        <i class="sub-menu-arrow ti-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="phone">Customer Number </label>
                            <input type="number" class="form-control" name="phone_number" id="phone"
                                value="{{ old('phone_number') }}" placeholder="Phone Number">
                            @if ($errors->has('phone_number'))
                                <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="architecture_name">Architect Name </label>
                            <input type="text" class="form-control" name="architecture_name" id="architecture_name"
                                value="{{ old('architecture_name') }}" placeholder="Architect Name">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="architecture_number">Architect Number </label>
                            <input type="number" class="form-control" name="architecture_number" id="architecture_number"
                                value="{{ old('architecture_number') }}" placeholder="Architect Number">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="supervisor_name">Supervisor Name </label>
                            <input type="text" class="form-control" name="supervisor_name" id="supervisor_name"
                                value="{{ old('supervisor_name') }}" placeholder="Supervisor Name">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="supervisor_number">Supervisor Number </label>
                            <input type="number" class="form-control" name="supervisor_number" id="supervisor_number"
                                value="{{ old('supervisor_number') }}" placeholder="Supervisor Number">
                        </div>
                        <div class="form-group col-md-4 ">
                            <label for="email">Email Address </label>
                            <input type="email" class="form-control" name="email" id="email"
                                placeholder="Email Address" value="{{ old('email') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="addressone">Address </label>
                            <input type="text" class="form-control" name="address" id="addressone"
                                placeholder="Address" value="{{ old('address') }}">
                            @if ($errors->has('address'))
                                <span class="text-danger">{{ $errors->first('address') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="state">State </label>
                            <select class="form-control select2-example" id="state" name="state">
                                <option value="">Select State</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state['id'] }}">{{ $state['name'] }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('state'))
                                <span class="text-danger">{{ $errors->first('state') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="cityname">City <span class="text-danger">*</span></label>
                            <select class="form-control select2-example" id="cityname" name="cityname">
                                <option value="" default>Select City</option>
                            </select>
                            @if ($errors->has('cityname'))
                                <span class="text-danger">{{ $errors->first('cityname') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="zipcode">Zip Code </label>
                            <input type="text" class="form-control" name="zipcode" id="zipcode"
                                placeholder="Zip Code" value="{{ old('zipcode') }}">
                        </div>
                        <!-- <div class="form-group col-md-4">

                                            <label for="projectconfirmdate">Project Confirmation Date <span

                                                    class="text-danger">*</span></label>

                                            <input type="text" name="projectconfirmdate" class="form-control">

                                            @if ($errors->has('projectconfirmdate'))
    <span class="text-danger">{{ $errors->first('projectconfirmdate') }}</span>
    @endif

                                        </div> -->
                        <div class="form-group col-md-4">
                            <label for="startdate">Start Date <span class="text-danger">*</span></label>
                            <input type="text" name="startdaterangepicker" class="form-control">
                            @if ($errors->has('startdaterangepicker'))
                                <span class="text-danger">{{ $errors->first('startdaterangepicker') }}</span>
                            @endif
                        </div>
                        <!-- <div class="form-group col-md-4">

                                            <label for="enddate">End Date <span class="text-danger">*</span></label>

                                            <input type="text" name="enddaterangepicker" class="form-control">

                                            @if ($errors->has('enddaterangepicker'))
    <span class="text-danger">{{ $errors->first('enddaterangepicker') }}</span>
    @endif

                                        </div> -->
                        <div class="form-group col-md-4">
                            <label for="measurementdate">Estimated Measurement Date <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="measurementdate" class="form-control">
                            @if ($errors->has('measurementdate'))
                                <span class="text-danger">{{ $errors->first('measurementdate') }}</span>
                            @endif

                        </div>

                        <div class="form-group col-md-4">

                            <label for="reference_name">Reference Name </label>

                            <input type="text" class="form-control" name="reference_name" id="reference_name"
                                placeholder="Reference Name" value="{{ old('reference_name') }}">

                            @if ($errors->has('reference_name'))
                                <span class="text-danger">{{ $errors->first('reference_name') }}</span>
                            @endif

                        </div>

                        <div class="form-group col-md-4">

                            <label for="reference_phone">Reference Phone Number</label>

                            <input type="text" class="form-control" name="reference_phone" id="reference_phone"
                                placeholder="Reference Phone Number" value="{{ old('reference_phone') }}">

                            @if ($errors->has('reference_phone'))
                                <span class="text-danger">{{ $errors->first('reference_phone') }}</span>
                            @endif

                        </div>

                        <div class="form-group col-md-4 measurement_user_select">
                            <label for="measurement_user">Send SMS of Measurement Users </label>
                            <select class="form-control select2-example" id="measurement_user" name="measurement_user[]"
                                multiple>
                                <option value="">Select User</option>
                                @foreach ($measurement_users as $measurement)
                                    <option value="{{ $measurement['id'] }}">{{ $measurement['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">

                            <label for="projectdesc">Project Description </label>

                            <textarea class="form-control" id="projectdesc" rows="5" name="description">{{ old('description') }}</textarea>

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
                                action="{{ isset($user) ? route('admin.update.customer', ['id' => $user->id]) : '#' }}"
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
                                            @foreach ($states as $state)
                                                <option value="{{ $state['id'] }}">{{ $state['name'] }}</option>
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

    <div class="modal fade" id="staticBackdrop_business" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered  modal-lg">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="staticBackdropLabel">Add Business User</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12">

                            <form id="updateBusinessForm" name="updateBusinessForm"
                                action="{{ isset($user) ? route('admin.update.user', ['id' => $user->id]) : '#' }}"
                                method="POST">

                                @csrf

                                <div class="alert alert-danger print-error-msg" style="display:none">

                                    <ul></ul>

                                </div>

                                <div class="row">

                                    <div class="form-group col-md-6">

                                        <label for="userNameedit">Business Name <span class="text-danger">*</span></label>

                                        <input type="text" name="name" class="form-control userName"
                                            id="businessNameedit" aria-describedby="edit_nameError"
                                            placeholder="Enter Name" value="{{ old('name') }}">

                                        <small id="business_nameError" class="form-text text-danger"></small>



                                    </div>

                                    <div class="form-group col-md-6 ">

                                        <label for="userEmailedit">Email address <span
                                                class="text-danger">*</span></label>

                                        <input type="email" class="form-control userEmail" name="email"
                                            id="userEmailedit" value="{{ old('email') }}"
                                            aria-describedby="edit_emailError" placeholder="Enter email">

                                        <small id="edit_emailError" class="form-text text-danger"></small>



                                    </div>

                                    <div class="form-group col-md-6">

                                        <label for="Phoneedit">Phone Number <span class="text-danger">*</span></label>

                                        <input type="tel" class="form-control userPhone" name="phone"
                                            id="businessPhoneedit" value="{{ old('phone') }}"
                                            aria-describedby="phoneError" placeholder="Enter Phone Number">

                                        <small id="business_phoneError" class="form-text text-danger"></small>



                                    </div>

                                    <div class="form-group col-md-6">

                                        <label for="address">Address <span class="text-danger">*</span></label>

                                        <input type="text" class="form-control" name="address"
                                            id="businessaddressedit" placeholder="Address" value="{{ old('address') }}">

                                        <small id="business_addressError" class="form-text text-danger"></small>

                                    </div>

                                    <div class="form-group col-md-6">

                                        <label for="city">City <span class="text-danger">*</span></label>

                                        <select class="form-control select2" id="businesscityedit" name="city">

                                            {{-- <option default>Select City</option>   --}}



                                        </select>

                                        <small id="business_cityError" class="form-text text-danger"></small>

                                    </div>

                                    <div class="form-group col-md-6">

                                        <label for="state">State <span class="text-danger">*</span></label>

                                        <select class="form-control select2-example" id="business_stateedit"
                                            name="state" disabled>

                                            <option value="Gujarat">Gujarat</option>

                                        </select>

                                        <small id="business_stateError" class="form-text text-danger"></small>

                                    </div>

                                    <div class="form-group col-md-6">

                                        <label for="zipcode">Zip Code <span class="text-danger">*</span></label>

                                        <input type="text" class="form-control" name="zipcode"
                                            id="businesszipcodeedit" placeholder="Zip Code"
                                            value="{{ old('zipcode') }}">

                                        <small id="business_zipcodeError" class="form-text text-danger"></small>

                                    </div>

                                    <div class="form-group col-md-6">

                                        <label for="b_gstnum">GST Number<span class="text-danger">*</span></label>

                                        <input type="text" class="form-control" name="gstnum" id="b_model_gstnum"
                                            value="{{ old('b_gstnum') }}" placeholder="GST Number">

                                        {{-- <small id="business_gstError" class="form-text text-danger"></small> --}}

                                    </div>

                                    <div class="form-group col-md-6">

                                        <label for="Passwordedit">Password <span class="text-danger">*</span></label>

                                        <input type="password" name="password" class="form-control userPassword"
                                            id="businessPasswordedit" placeholder="Password">

                                        {{-- <small class="form-text text-muted">* Leave Blank if don't want to change</small> --}}

                                        <small id="business_passwordError" class="form-text text-danger"></small>

                                    </div>

                                    <div class="form-group col-md-6">

                                        <label for="editroles">Role <span class="text-danger">*</span></label>

                                        <select name="role" class="form-control userRole" id="businesseditroles">

                                            <option value="0">Select Role...</option>

                                            <option value="7">Business</option>

                                        </select>

                                        <small id="business_roleError" class="form-text text-danger"></small>

                                    </div>

                                </div>

                                <button type="button" class="btn btn-primary" id="updateBusinessBtn">Submit</button>

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
        $('input[name="startdaterangepicker"]').daterangepicker({

            singleDatePicker: true,

            showDropdowns: true,

            locale: {
                format: 'DD/MM/YYYY'
            }

        });

        $('input[name="enddaterangepicker"]').daterangepicker({

            singleDatePicker: true,

            showDropdowns: true,

            locale: {
                format: 'DD/MM/YYYY'
            }

        });

        $('input[name="projectconfirmdate"]').daterangepicker({

            singleDatePicker: true,

            showDropdowns: true,

            locale: {
                format: 'DD/MM/YYYY'
            }

        });

        $('input[name="measurementdate"]').daterangepicker({

            singleDatePicker: true,

            showDropdowns: true,

            locale: {
                format: 'DD/MM/YYYY'
            }

        });

        $(document).on('change', '#state', function() {
            var state = $(this).val();
            $.ajax({
                url: "{{ route('get.cities', '') }}" + "/" + state,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#cityname').html(data);
                }
            });
        });

        $(document).on('change', '#customer_state', function() {
            var state = $(this).val();
            $.ajax({
                url: "{{ route('get.cities', '') }}" + "/" + state,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#customer_city').html(data);
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            $('.alert-repeater').repeater();

        });

        $('.select2-example').select2({

            placeholder: 'Select'

        });
    </script>

    <script>
        $(document).on('click', '#b_name', function() {

            var id = $(this).val();

            $.ajax({

                type: 'GET',

                url: '{{ route('get_user') }}',

                data: {

                    'id': id

                },

                success: function(data) {

                    $('#email').val(data.email);

                    $('#phone').val(data.phone);

                    $('#addressone').val(data.address);

                    $('#b_zipcode').val(data.zipcode);

                    console.log(data.city);

                    $('#cityname').val(data.city).trigger('change');

                },

                error: function(data) {

                    // Handle error

                }

            });

        });



        $(document).on('click', '#Customername', function() {

            var id = $(this).val();

            $.ajax({

                type: 'GET',

                url: '{{ route('get_customer') }}',

                data: {

                    'id': id

                },

                success: function(data) {

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
                            selected: data.customer.state == states
                                .id // Select the city that matches data.city
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
                            selected: data.customer.city == editcity
                                .id // Select the city that matches data.city
                        }));
                    });

                },

                error: function(data) {

                    // Handle error

                }

            });

        });



        $(document).on('click', '#updateUserBtn', function() {

            var name_value = document.updateuserform.name.value;

            var phone_value = document.updateuserform.phone.value;

            var email_value = document.updateuserform.email.value;

            // var password_value = document.updateuserform.password.value;

            var role_value = document.updateuserform.role.value;

            var address_value = document.updateuserform.address.value;

            var city_value = document.updateuserform.city.value;

            var zipcode_value = document.updateuserform.zipcode.value;

            var nameError = document.getElementById('edit_nameError');

            var emailError = document.getElementById('edit_emailError');

            var phoneError = document.getElementById('edit_phoneError');

            var passwordError = document.getElementById('edit_passwordError');

            var roleError = document.getElementById('edit_roleError');

            var addressError = document.getElementById('edit_addressError');

            var cityError = document.getElementById('edit_cityError');

            var zipcodeError = document.getElementById('edit_zipcodeError');

            var i = 0;



            if (name_value == "") {

                i++;

                nameError.innerHTML = "Name must be filled out!";

                nameError.style.color = "Red";

                document.updateuserform.name.focus();

            }

            if (phone_value == "") {

                i++;

                phoneError.innerHTML = "Valid Contact Details must be filled out!";

                phoneError.style.color = "Red";

                document.updateuserform.phone.focus();

            }

            if (email_value == "") {

                i++;

                emailError.innerHTML = "Email must be filled out!";

                emailError.style.color = "Red";

                document.updateuserform.email.focus();

            }

            if (role_value == 0) {

                i++;

                roleError.innerHTML = "Role must be Selected!";

                roleError.style.color = "Red";

                document.updateuserform.role.focus();

            }

            if (address_value == 0) {

                i++;

                addressError.innerHTML = "Address must be filled out!";

                addressError.style.color = "Red";

                document.updateuserform.role.focus();

            }

            if (city_value == 0) {

                i++;

                cityError.innerHTML = "City must be filled out!";

                cityError.style.color = "Red";

                document.updateuserform.role.focus();

            }

            if (zipcode_value == 0) {

                i++;

                zipcodeError.innerHTML = "ZipCode must be filled out!";

                zipcodeError.style.color = "Red";

                document.updateuserform.role.focus();

            }

            if (i == 0) {

                $.ajax({

                    type: 'POST',

                    url: "{{ route('admin.add.customer.data', '') }}" + '/' + 1,

                    headers: {

                        'X-CSRFToken': "{{ csrf_token() }}"

                    },

                    data: $('#updateUserForm').serialize(),

                    success: function(data) {

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

                    }

                });

            }

        });

        $(document).on('click', '#updateBusinessBtn', function() {

            var name_value = document.updateBusinessForm.name.value;

            var phone_value = document.updateBusinessForm.phone.value;

            // var email_value = document.updateBusinessForm.email.value;

            var gst_value = document.updateBusinessForm.gstnum.value;

            var password_value = document.updateBusinessForm.password.value;

            var role_value = document.updateBusinessForm.role.value;

            var address_value = document.updateBusinessForm.address.value;

            var city_value = document.updateBusinessForm.city.value;

            var zipcode_value = document.updateBusinessForm.zipcode.value;

            var nameError = document.getElementById('business_nameError');

            // var emailError = document.getElementById('business_emailError');

            var gstError = document.getElementById('business_gstError');

            var phoneError = document.getElementById('business_phoneError');

            var passwordError = document.getElementById('business_passwordError');

            var roleError = document.getElementById('business_roleError');

            var addressError = document.getElementById('business_addressError');

            var cityError = document.getElementById('business_cityError');

            var zipcodeError = document.getElementById('business_zipcodeError');

            var i = 0;



            if (name_value == "") {

                i++;

                nameError.innerHTML = "Name must be filled out!";

                nameError.style.color = "Red";

            }

            if (phone_value == "") {

                i++;

                phoneError.innerHTML = "Valid Contact Details must be filled out!";

                phoneError.style.color = "Red";

            }

            if (role_value == 0) {

                i++;

                roleError.innerHTML = "Role must be Selected!";

                roleError.style.color = "Red";

            }

            if (address_value == 0) {

                i++;

                addressError.innerHTML = "Address must be filled out!";

                addressError.style.color = "Red";

            }

            if (city_value == 0) {

                i++;

                cityError.innerHTML = "City must be filled out!";

                cityError.style.color = "Red";

            }

            if (zipcode_value == 0) {

                i++;

                zipcodeError.innerHTML = "ZipCode must be filled out!";

                zipcodeError.style.color = "Red";

            }

            if (i == 0) {

                $.ajax({

                    type: 'POST',

                    url: "{{ route('admin.add.user.data', '') }}" + '/' + 1,

                    headers: {

                        'X-CSRFToken': "{{ csrf_token() }}"

                    },

                    data: $('#updateBusinessForm').serialize(),

                    success: function(data) {

                        $('#b_name').append('<option value="' + data.user.id + '" selected>' +

                            data.user.name + '</option>')

                        $("#staticBackdrop_business").modal('hide');

                    },

                    error: function(data) {

                    }

                });

            }

        });
    </script>

    <script>
        $(function() {

            $("#businessType").change(function() {

                var selectedOption = $(this).val();

                if (selectedOption === "b2c") {

                    $(".b2c_div").show();

                    $(".b2b_div").hide();

                } else if (selectedOption === "b2b") {

                    $(".b2b_div").show();

                    $(".b2c_div").hide();

                }

            });

        });
    </script>

    <!-- Include jQuery -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            // Datepicker for project confirmation date

            $('input[name="projectconfirmdate"]').datepicker({

                dateFormat: 'yy-mm-dd',

                changeMonth: true,

                changeYear: true

            });

            // Datepicker for start date

            $('input[name="startdaterangepicker"]').datepicker({

                dateFormat: 'yy-mm-dd',

                changeMonth: true,

                changeYear: true

            });

            // Datepicker for end date

            $('input[name="enddaterangepicker"]').datepicker({

                dateFormat: 'yy-mm-dd',

                changeMonth: true,

                changeYear: true

            });

            // Datepicker for estimated measurement date

            $('input[name="measurementdate"]').datepicker({

                dateFormat: 'yy-mm-dd',

                changeMonth: true,

                changeYear: true

            });

        });
    </script>
@endsection
