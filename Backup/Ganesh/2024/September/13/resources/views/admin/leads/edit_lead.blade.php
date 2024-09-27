@extends('admin.layouts.app')
@section('content')
    <div class="project">
        <div class="page-header d-md-flex justify-content-between align-items-center">
            <div class="">
                <h3 class="mb-0">Edit Leads</h3>
            </div>
            <div class="">
                <a href="{{ route('leads') }}" class="btn btn-primary ms-auto">
                    <i class="sub-menu-arrow ti-angle-left me-2"></i> Back
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form class="alert-repeater" action="{{ route('update.leads', $leads->id) }}" enctype="multipart/form-data"
                    method="POST">
                    @csrf
                    <div class="form-row row">
                        {{-- <div class="form-group col-md-4" id="customerNameField"
                            style="@if ($leads->business_type != 1) display:none; @endif">
                            <label for="customername">Customer Name <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center">
                                <select class="form-control" id="Customername" name="customer_name">
                                    <option>Select Customer</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            @if ($user->id == $leads->customer_id) selected @endif>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('customer_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="ms-2 me-3">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop">
                                        <i class="sub-menu-arrow ti-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4 b2b_div"
                            style="@if ($leads->business_type != 2) display:none; @endif">
                            <div class="d-flex align-items-end">
                                <div class="w-100">
                                    <div class="form-group mb-0">
                                        <label for="business_name">Business Name <span class="text-danger">*</span></label>
                                        <select class="form-control" id="b_name" name="business_name">
                                            <option>Select Business</option>
                                            @foreach ($business as $user)
                                                <option value="{{ $user->id }}"
                                                    @if ($user->id == $leads->customer_id) selected @endif>{{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('business_name'))
                                            <span class="text-danger">{{ $errors->first('business_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="ms-2 me-3">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdropBusiness">
                                        <i class="sub-menu-arrow ti-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div> --}}
                        <div class="form-group col-md-4" id="customerNameField">
                            <label for="customername">Customer Name <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center">
                                <select class="form-control" id="Customername" name="customer_name">
                                    <option>Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option <?php if ($leads->customer_id == $customer->id) {
                                            echo 'selected';
                                        } ?> value="{{ $customer->id }}">{{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="ms-2 me-3">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop">
                                        <i class="sub-menu-arrow ti-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="reference_name">Reference Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="reference_name" placeholder="Reference Name"
                                name="reference_name" value="{{ $leads->reference_name }}">
                            @if ($errors->has('reference_name'))
                                <span class="text-danger">{{ $errors->first('reference_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="projectname">Reference Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="reference_number" placeholder="Reference Number"
                                name="reference_number" value="{{$leads->reference_phone}}">
                            @if ($errors->has('reference_number'))
                                <span class="text-danger">{{ $errors->first('reference_number') }}</span>
                            @endif
                        </div>
                        {{-- <div class="form-group col-md-4">
                            <label for="check_business">Select Type <span class="text-danger">*</span></label>
                            <select class="custom-select" id="businessType" name="businessType">
                                <option value="b2c" @if ($leads->business_type == 1) selected @endif>B2C</option>
                                <option value="b2b" @if ($leads->business_type == 2) selected @endif>B2B</option>
                            </select>
                        </div> --}}
                        {{-- <div class="form-group col-md-4 b2b_div" style="display: none;">
                            <!-- B2B specific fields go here -->
                            <label for="gstNumber">GST Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="gstNumber" placeholder="GST Number"
                                name="gst_number" value="{{ old('gst_number') }}">
                            @error('gst_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div> --}}
                        <div class="form-group col-md-4" id="customerNameField">
                            <label for="phone">Customer Number <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="phone_number" id="phone"
                                value="{{ $leads->phone_number }}" placeholder="Phone Number">
                            @if ($errors->has('phone_number'))
                                <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" name="email" id="email"
                                placeholder="Email Address" value="{{ $leads->email }}">
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="addressone">Site Address </label>
                            <input type="text" class="form-control" name="addressone" id="addressone"
                                placeholder="Address" value="{{ $leads->address }}">
                            @if ($errors->has('addressone'))
                                <span class="text-danger">{{ $errors->first('addressone') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="state">State </label>
                            <select class="form-control select2-example" id="state" name="state">
                                <option value="">Select State</option>
                                @foreach($states as $state)
                                    {{-- <option value="{{$state['id']}}">{{$state['name']}}</option> --}}
                                    <option value="{{ $state['id'] }}" {{ old('state', $leads->statename) == $state['id'] ? 'selected' : '' }}>
                                        {{ $state['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="cityname">City </label>
                            <select class="form-control select2-example" id="cityname" name="city">
                                @foreach($cities as $city)
                                    <option value="{{ $city['id'] }}" {{ old('city', $leads->cityname) == $city['id'] ? 'selected' : '' }}>
                                        {{ $city['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('cityname'))
                                <span class="text-danger">{{ $errors->first('cityname') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="zipcode">Zip Code </label>
                            <input type="text" class="form-control" name="zipcode" id="zipcode"
                                placeholder="Zip Code" value="{{$leads->zipcode}}">
                            @if ($errors->has('zipcode'))
                                <span class="text-danger">{{ $errors->first('zipcode') }}</span>
                            @endif
                        </div>
                        {{-- <div class="form-group col-md-4">
                            <label for="architecture_name">Architecture Name</label>
                            <input type="text" class="form-control" name="architecture_name" id="architecture_name"
                                placeholder="Architecture Name" value="{{ $leads->architecture_name }}">
                            @if ($errors->has('architecture_name'))
                                <span class="text-danger">{{ $errors->first('architecture_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="architecture_number">Architecture Number</label>
                            <input type="number" class="form-control" name="architecture_number" id="architecture_number"
                                placeholder="Architecture Number" value="{{ $leads->architecture_number }}">
                            @if ($errors->has('architecture_number'))
                                <span class="text-danger">{{ $errors->first('architecture_number') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="supervisor_name">Supervisor Name</label>
                            <input type="text" class="form-control" name="supervisor_name" id="supervisor_name"
                                placeholder="Supervisor Name"
                                value="{{ old('supervisor_name', $leads->supervisor_name) }}">
                            @if ($errors->has('supervisor_name'))
                                <span class="text-danger">{{ $errors->first('supervisor_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="supervisor_number">Supervisor Number</label>
                            <input type="number" class="form-control" name="supervisor_number" id="supervisor_number"
                                placeholder="Supervisor Number" value="{{ $leads->supervisor_number }}">
                            @if ($errors->has('supervisor_number'))
                                <span class="text-danger">{{ $errors->first('supervisor_number') }}</span>
                            @endif
                        </div> --}}
                        <div class="form-group col-md-4">
                            <label for="measurementdate">Lead Estimated Measurement Date <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="measurementdate" class="form-control" value="{{date('d/m/Y',strtotime($leads->measurement_date))}}">
                            @if ($errors->has('measurementdate'))
                                <span class="text-danger">{{ $errors->first('measurementdate') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="projectdesc">Description</label>
                            <textarea class="form-control" id="projectdesc" rows="5" name="description">{{ $leads->description }}</textarea>
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
                                {{-- <input type="hidden" name="userid" value="" id="userid_hidden"> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="staticBackdropBusiness" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Business</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="updateUserFormBusiness" name="updateuserformbusiness"
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
                                            id="userNameedit" aria-describedby="edit_nameError" placeholder="Enter Name"
                                            value="{{ old('name') }}">
                                        <small id="edit_nameError" class="form-text text-danger"></small>
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
                                            id="Phoneedit" value="{{ old('phone') }}" aria-describedby="phoneError"
                                            placeholder="Enter Phone Number">
                                        <small id="edit_phoneError" class="form-text text-danger"></small>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="address">Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="address" id="addressedit"
                                            placeholder="Address" value="{{ old('address') }}">
                                        <small id="edit_addressError" class="form-text text-danger"></small>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label for="zipcode">Zip Code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="zipcode" id="zipcodeedit"
                                            placeholder="Zip Code" value="{{ old('zipcode') }}">
                                        <small id="edit_zipcodeError" class="form-text text-danger"></small>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="Passwordedit">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control userPassword"
                                            id="Passwordedit" placeholder="Password">
                                        <small class="form-text text-muted">* Leave Blank if don't want to change</small>
                                        <small id="edit_passwordError" class="form-text text-danger"></small>
                                    </div>
                                    <input type="hidden" name="role" value="7">
                                    <!--<div class="form-group col-md-6">-->
                                    <!--    <label for="editroles">Role <span class="text-danger">*</span></label>-->
                                    <!--    <select name="role" class="form-control userRole" id="editroles">-->
                                    <!--        <option value="0">Select Role...</option>-->
                                    <!--        <option value="2">user</option>-->
                                    <!--        <option value="7">Business</option>-->
                                    <!--    </select>-->
                                    <!--    <small id="edit_roleError" class="form-text text-danger"></small>-->
                                    <!--</div>-->
                                </div>
                                <button type="button" class="btn btn-primary" id="updateUserBtnBusiness">Submit</button>
                                {{-- <input type="hidden" name="userid" value="" id="userid_hidden"> --}}
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
        var $example = $('.select2-example').select2({
            placeholder: 'Select'
        });

        $(document).on('change','#state',function(){
            var state = $(this).val();
            $.ajax({
                url : "{{route('get.cities', '')}}"+"/"+state,
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
                url : "{{route('get.cities', '')}}"+"/"+state,
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
                url: '{{ route('get_customer') }}',
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
            var name_value = document.updateuserform.name.value;
            // var phone_value = document.updateuserform.phone.value;
            // var email_value = document.updateuserform.email.value;
            // var password_value = document.updateuserform.password.value;
            // var role_value = document.updateuserform.role.value;
            // var address_value = document.updateuserform.address.value;
            var city_value = document.updateuserform.city.value;
            // var zipcode_value = document.updateuserform.zipcode.value;
            var nameError = document.getElementById('edit_nameError');
            // var emailError = document.getElementById('edit_emailError');
            // var phoneError = document.getElementById('edit_phoneError');
            // var passwordError = document.getElementById('edit_passwordError');
            // var roleError = document.getElementById('edit_roleError');
            // var addressError = document.getElementById('edit_addressError');
            var cityError = document.getElementById('edit_cityError');
            // var zipcodeError = document.getElementById('edit_zipcodeError');
            var i = 0;
            if (name_value == "") {
                i++;
                nameError.innerHTML = "Name must be filled out!";
                nameError.style.color = "Red";
                document.updateuserform.name.focus();
            }
            // if (phone_value == "") {
            //     i++;
            //     phoneError.innerHTML = "Valid Contact Details must be filled out!";
            //     phoneError.style.color = "Red";
            //     document.updateuserform.phone.focus();
            // }
            // if (email_value == "") {
            //     i++;
            //     emailError.innerHTML = "Email must be filled out!";
            //     emailError.style.color = "Red";
            //     document.updateuserform.email.focus();
            // }
            // if (role_value == 0) {
            //     i++;
            //     roleError.innerHTML = "Role must be Selected!";
            //     roleError.style.color = "Red";
            //     document.updateuserform.role.focus();
            // }
            // if (address_value == 0) {
            //     i++;
            //     addressError.innerHTML = "Address must be filled out!";
            //     addressError.style.color = "Red";
            //     document.updateuserform.role.focus();
            // }
            if (city_value == 0) {
                i++;
                cityError.innerHTML = "City must be filled out!";
                cityError.style.color = "Red";
                document.updateuserform.role.focus();
            }
            // if (zipcode_value == 0) {
            //     i++;
            //     zipcodeError.innerHTML = "ZipCode must be filled out!";
            //     zipcodeError.style.color = "Red";
            //     document.updateuserform.role.focus();
            // }
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
                        // console.log(data);
                    }
                });
            }
        });
        // $(document).on('click', '#updateUserBtnBusiness', function() {

        //     var name_value = document.updateuserformbusiness.name.value;

        //     var phone_value = document.updateuserformbusiness.phone.value;

        //     var email_value = document.updateuserformbusiness.email.value;

        //     var password_value = document.updateuserformbusiness.password.value;

        //     var role_value = document.updateuserformbusiness.role.value;

        //     var address_value = document.updateuserformbusiness.address.value;

        //     var city_value = document.updateuserformbusiness.city.value;

        //     var zipcode_value = document.updateuserformbusiness.zipcode.value;

        //     var nameError = document.getElementById('edit_nameError');

        //     var emailError = document.getElementById('edit_emailError');

        //     var phoneError = document.getElementById('edit_phoneError');

        //     var passwordError = document.getElementById('edit_passwordError');

        //     var roleError = document.getElementById('edit_roleError');

        //     var addressError = document.getElementById('edit_addressError');

        //     var cityError = document.getElementById('edit_cityError');

        //     var zipcodeError = document.getElementById('edit_zipcodeError');

        //     var i = 0;



        //     if (name_value == "") {

        //         i++;

        //         nameError.innerHTML = "Name must be filled out!";

        //         nameError.style.color = "Red";

        //         document.updateuserformbusiness.name.focus();

        //     }

        //     if (phone_value == "") {

        //         i++;

        //         phoneError.innerHTML = "Valid Contact Details must be filled out!";

        //         phoneError.style.color = "Red";

        //         document.updateuserformbusiness.phone.focus();

        //     }

        //     if (email_value == "") {

        //         i++;

        //         emailError.innerHTML = "Email must be filled out!";

        //         emailError.style.color = "Red";

        //         document.updateuserformbusiness.email.focus();

        //     }

        //     if (role_value == 0) {

        //         i++;

        //         roleError.innerHTML = "Role must be Selected!";

        //         roleError.style.color = "Red";

        //         document.updateuserformbusiness.role.focus();

        //     }

        //     if (address_value == 0) {

        //         i++;

        //         addressError.innerHTML = "Address must be filled out!";

        //         addressError.style.color = "Red";

        //         document.updateuserformbusiness.role.focus();

        //     }

        //     if (city_value == 0) {

        //         i++;

        //         cityError.innerHTML = "City must be filled out!";

        //         cityError.style.color = "Red";

        //         document.updateuserformbusiness.role.focus();

        //     }

        //     if (zipcode_value == 0) {

        //         i++;

        //         zipcodeError.innerHTML = "ZipCode must be filled out!";

        //         zipcodeError.style.color = "Red";

        //         document.updateuserformbusiness.role.focus();

        //     }

        //     if (i == 0) {

        //         $.ajax({

        //             type: 'POST',

        //             url: "{{ route('admin.add.user.data', '') }}" + '/' + 1,

        //             headers: {

        //                 'X-CSRFToken': "{{ csrf_token() }}"

        //             },

        //             data: $('#updateUserFormBusiness').serialize(),

        //             success: function(data) {

        //                 $('#b_name').append('<option value="' + data.user.id + '" selected>' + data

        //                     .user.name + '</option>');

        //                 $("#staticBackdropBusiness").modal('hide');

        //             },

        //             error: function(data) {

        //                 // console.log(data);

        //             }

        //         });

        //     }

        // });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- <script>
$(function() {
    $("#businessType").change(function() {
        var selectedOption = $(this).val();
        if (selectedOption === "b2c") {
            $("#customerNameField").show();
        } else {
            $("#customerNameField").hide();
        }
        if (selectedOption === "b2b") {
            $(".b2b_div").show();
        } else {
            $(".b2b_div").hide();
        }
    });
});
</script> --}}
@endsection
