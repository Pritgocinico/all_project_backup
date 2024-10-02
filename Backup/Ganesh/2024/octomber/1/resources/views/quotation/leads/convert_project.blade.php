@extends('quotation.layouts.app')



@section('content')

<div class="project">
    <div class="page-header d-md-flex justify-content-between align-items-center">
        <div class="">
            <h3 class="mb-0">Convert Project</h3>
        </div>
        <div class="">
            <a href="{{ route('quotation_leads') }}" class="btn btn-primary ms-auto">
                <i class="sub-menu-arrow ti-angle-left me-2"></i> Back
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form class="alert-repeater" action="{{ route('quotation_store.project') }}" enctype="multipart/form-data"
                method="POST">
                @csrf
                <div class="form-row row">
                     <div class="form-group col-md-4" id="customerNameField">
                        <label for="customername">Customer Name <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center">
                            <select class="form-control" id="Customername" name="customer_name">
                                <option>Select Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" @if($customer->id == $leads->customer_id) selected @endif>{{ $customer->name }}</option>
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
                    {{-- <div class="form-group col-md-4 b2b_div" style="@if ($leads->business_type != 2) display:none; @endif">
                        <div class="d-flex align-items-end">
                            <div class="w-100">
                                <div class="form-group mb-0">
                                    <label for="business_name">Business Name <span class="text-danger">*</span></label>
                                    <select class="form-control" id="b_name" name="business_name">
                                        <option>Select Business</option>
                                        @foreach ($business as $user)
                                            <option value="{{ $user->id }}" @if($user->id == $leads->customer_id) selected @endif>{{ $user->name }}</option>
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
                    {{-- <div class="form-group col-md-4">
                        <label for="reference_name">Reference Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="reference_name" placeholder="Reference Name"
                            name="reference_name" value="{{ $leads->reference_name }}">
                        @if ($errors->has('reference_name'))
                            <span class="text-danger">{{ $errors->first('reference_name') }}</span>
                        @endif
                    </div> --}}
                    {{-- <div class="form-group col-md-4">
                        <label for="check_business">Select Type <span class="text-danger">*</span></label>
                        <select class="custom-select" id="businessType" name="businessType">
                            <option value="b2c" @if ($leads->business_type == 1) selected @endif>B2C</option>
                            <option value="b2b" @if ($leads->business_type == 2) selected @endif>B2B</option>
                        </select>
                    </div> --}}
                   
                    <div class="form-group col-md-4 b2b_div" style="display: none;">
                        <!-- B2B specific fields go here -->
                        <label for="gstNumber">GST Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="gstNumber" placeholder="GST Number" name="gst_number"
                            value="{{ old('gst_number') }}">
                        {{-- @error('gst_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror --}}
                    </div>
                    <div class="form-group col-md-4">
                        <label for="phone">Customer Number</label>
                        <input type="text" class="form-control" id="phone" placeholder="Phone Number" name="phone_number"
                            value="{{$leads->phone_number}}">
                        {{-- @error('phone_number')
                            <span class="text-danger">{{$phone_number}}</span>
                        @enderror --}}
                    </div>
                    <div class="form-group col-md-4">
    <label for="architecture_name">Architect Name</label>
    <input type="text" class="form-control" id="architecture_name" placeholder="Architect Name" name="architecture_name"
           value="{{ old('architecture_name', $leads->architecture_name) }}">
    @if ($errors->has('architecture_name'))
        <span class="text-danger">{{ $errors->first('architecture_name') }}</span>
    @endif
</div>

<div class="form-group col-md-4">
    <label for="architecture_number">Architect Number</label>
    <input type="text" class="form-control" id="architecture_number" placeholder="Architect Number" name="architecture_number"
           value="{{ old('architecture_number', $leads->architecture_number) }}">
    @if ($errors->has('architecture_number'))
        <span class="text-danger">{{ $errors->first('architecture_number') }}</span>
    @endif
</div>

<div class="form-group col-md-4">
    <label for="supervisor_name">Supervisor Name</label>
    <input type="text" class="form-control" id="supervisor_name" placeholder="Supervisor Name" name="supervisor_name"
           value="{{ old('supervisor_name', $leads->supervisor_name) }}">
    @if ($errors->has('supervisor_name'))
        <span class="text-danger">{{ $errors->first('supervisor_name') }}</span>
    @endif
</div>

<div class="form-group col-md-4">
    <label for="supervisor_number">Supervisor Number</label>
    <input type="text" class="form-control" id="supervisor_number" placeholder="Supervisor Number" name="supervisor_number"
           value="{{ old('supervisor_number', $leads->supervisor_number) }}">
    @if ($errors->has('supervisor_number'))
        <span class="text-danger">{{ $errors->first('supervisor_number') }}</span>
    @endif
</div>

                    <div class="form-group col-md-4">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" placeholder="Email Address" name="email"
                            value="{{$leads->email}}">
                        {{-- @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror --}}
                    </div>
                    <div class="form-group col-md-4">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" placeholder="Address" name="addressone"
                            value="{{$leads->addressone}}">
                        {{-- @error('address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror --}}
                    </div>
                    <!-- <div class="form-group col-md-4">
                        <label for="projectconfirmdate">Project Confirmation Date <span class="text-danger">*</span></label>
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
                        <label for="measurementdate">Estimated Measurement Date <span class="text-danger">*</span></label>
                        <input type="text" name="measurementdate" class="form-control">
                        @if ($errors->has('measurementdate'))
                            <span class="text-danger">{{ $errors->first('measurementdate') }}</span>
                        @endif
                    </div>
                    <div class="form-group col-md-4">
                        <label for="referencename">Reference Name</label>
                        <input type="text" class="form-control" id="referencename" placeholder="Reference Name"
                            name="reference_name" value="{{$leads->reference_name}}">
                        @error('reference_name')
                            <span class="text-danger">{{ $errors->first('reference_name')  }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="referencephone">Reference Phone Number</label>
                        <input type="text" class="form-control" id="referencephone" placeholder="Reference Phone Number"
                            name="reference_phone" value="{{ old('reference_phone') }}">
                        @error('reference_phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-12">
                        <label for="projectdesc">Project Description</label>
                        <textarea class="form-control" id="projectdesc" rows="5"
                            name="description">{{$leads->description}}</textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <hr>
                <input type="hidden" name="lead_id" value="{{$leads->id}}">
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
                </form>
            </div>
        </div>
    </div>
   <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"

    aria-labelledby="staticBackdropLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered  modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="staticBackdropLabel">Add User</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-md-12">

                        <form id="updateUserForm" name="updateuserform"

                            action="{{ isset($user) ? route('quotation_admin.update.user', ['id' => $user->id]) : '#' }}"

                            method="POST">

                            @csrf

                            <div class="alert alert-danger print-error-msg" style="display:none">

                                <ul></ul>

                            </div>

                            <div class="row">

                                <div class="form-group col-md-6">

                                    <label for="userNameedit">Customer Name <span class="text-danger">*</span></label>

                                    <input type="text" name="name" class="form-control userName" id="userNameedit"

                                        aria-describedby="edit_nameError" placeholder="Enter Name"

                                        value="{{ old('name') }}">

                                    <small id="edit_nameError" class="form-text text-danger"></small>



                                </div>

                                <div class="form-group col-md-6 ">

                                    <label for="userEmailedit">Email address <span class="text-danger">*</span></label>

                                    <input type="email" class="form-control userEmail" name="email" id="userEmailedit"

                                        value="{{ old('email') }}" aria-describedby="edit_emailError"

                                        placeholder="Enter email">

                                    <small id="edit_emailError" class="form-text text-danger"></small>



                                </div>

                                <div class="form-group col-md-6">

                                    <label for="Phoneedit">Phone Number <span class="text-danger">*</span></label>

                                    <input type="tel" class="form-control userPhone" name="phone" id="Phoneedit"

                                        value="{{ old('phone') }}" aria-describedby="phoneError"

                                        placeholder="Enter Phone Number">

                                    <small id="edit_phoneError" class="form-text text-danger"></small>



                                </div>

                                <div class="form-group col-md-6">

                                    <label for="address">Address <span class="text-danger">*</span></label>

                                    <input type="text" class="form-control" name="address" id="addressedit"

                                        placeholder="Address" value="{{old('address')}}">

                                    <small id="edit_addressError" class="form-text text-danger"></small>

                                </div>

                                <div class="form-group col-md-6">

                                    <label for="city">City <span class="text-danger">*</span></label>

                                    <select class="form-control " id="cityedit" name="city">

                                        {{-- <option default>Select City</option>   --}}

                                        @foreach ($cities as $city)

                                        <option value="{{ $city['name'] }}">{{ $city['name'] }}</option>

                                        @endforeach

                                    </select>

                                    <small id="edit_cityError" class="form-text text-danger"></small>

                                </div>

                                <div class="form-group col-md-6">

                                    <label for="state">State <span class="text-danger">*</span></label>

                                    <select class="form-control select2-example" id="stateedit" name="state" disabled>

                                        <option value="Gujarat">Gujarat</option>

                                    </select>

                                    <small id="edit_stateError" class="form-text text-danger"></small>

                                </div>

                                <div class="form-group col-md-6">

                                    <label for="zipcode">Zip Code <span class="text-danger">*</span></label>

                                    <input type="text" class="form-control" name="zipcode" id="zipcodeedit"

                                        placeholder="Zip Code" value="{{old('zipcode')}}">

                                    <small id="edit_zipcodeError" class="form-text text-danger"></small>

                                </div>

                                <div class="form-group col-md-6">

                                    <label for="Passwordedit">Password <span class="text-danger">*</span></label>

                                    <input type="password" name="password" class="form-control userPassword"

                                        id="Passwordedit" placeholder="Password">

                                    <small class="form-text text-muted">* Leave Blank if don't want to change</small>

                                    <small id="edit_passwordError" class="form-text text-danger"></small>

                                </div>
                                <input type="hidden" name="role" value="2">
                                <!--<div class="form-group col-md-6">-->

                                <!--    <label for="editroles">Role <span class="text-danger">*</span></label>-->

                                <!--    <select name="role" class="form-control userRole" id="editroles">-->

                                <!--        <option value="0">Select Role...</option>-->

                                <!--        <option value="2">user</option>-->

                                <!--    </select>-->

                                <!--    <small id="edit_roleError" class="form-text text-danger"></small>-->

                                <!--</div>-->

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
<div class="modal fade" id="staticBackdropBusiness" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"

    aria-labelledby="staticBackdropLabel" aria-hidden="true">

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

                            action="{{ isset($user) ? route('quotation_admin.update.user', ['id' => $user->id]) : '#' }}"

                            method="POST">

                            @csrf

                            <div class="alert alert-danger print-error-msg" style="display:none">

                                <ul></ul>

                            </div>

                            <div class="row">

                                <div class="form-group col-md-6">

                                    <label for="userNameedit">Business Name <span class="text-danger">*</span></label>

                                    <input type="text" name="name" class="form-control userName" id="userNameedit"

                                        aria-describedby="edit_nameError" placeholder="Enter Name"

                                        value="{{ old('name') }}">

                                    <small id="edit_nameError" class="form-text text-danger"></small>



                                </div>

                                <div class="form-group col-md-6 ">

                                    <label for="userEmailedit">Email address <span class="text-danger">*</span></label>

                                    <input type="email" class="form-control userEmail" name="email" id="userEmailedit"

                                        value="{{ old('email') }}" aria-describedby="edit_emailError"

                                        placeholder="Enter email">

                                    <small id="edit_emailError" class="form-text text-danger"></small>



                                </div>

                                <div class="form-group col-md-6">

                                    <label for="Phoneedit">Phone Number <span class="text-danger">*</span></label>

                                    <input type="tel" class="form-control userPhone" name="phone" id="Phoneedit"

                                        value="{{ old('phone') }}" aria-describedby="phoneError"

                                        placeholder="Enter Phone Number">

                                    <small id="edit_phoneError" class="form-text text-danger"></small>



                                </div>

                                <div class="form-group col-md-6">

                                    <label for="address">Address <span class="text-danger">*</span></label>

                                    <input type="text" class="form-control" name="address" id="addressedit"

                                        placeholder="Address" value="{{old('address')}}">

                                    <small id="edit_addressError" class="form-text text-danger"></small>

                                </div>

                                <div class="form-group col-md-6">

                                    <label for="city">City <span class="text-danger">*</span></label>

                                    <select class="form-control " id="cityedit" name="city">

                                        {{-- <option default>Select City</option>   --}}

                                        @foreach ($cities as $city)

                                        <option value="{{ $city['name'] }}">{{ $city['name'] }}</option>

                                        @endforeach

                                    </select>

                                    <small id="edit_cityError" class="form-text text-danger"></small>

                                </div>

                                <div class="form-group col-md-6">

                                    <label for="state">State <span class="text-danger">*</span></label>

                                    <select class="form-control select2-example" id="stateedit" name="state" disabled>

                                        <option value="Gujarat">Gujarat</option>

                                    </select>

                                    <small id="edit_stateError" class="form-text text-danger"></small>

                                </div>

                                <div class="form-group col-md-6">

                                    <label for="zipcode">Zip Code <span class="text-danger">*</span></label>

                                    <input type="text" class="form-control" name="zipcode" id="zipcodeedit"

                                        placeholder="Zip Code" value="{{old('zipcode')}}">

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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

    $(function() {
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

                url: '{{ route('get_user') }}',

                data: {

                    'id': id

                },

                success: function(data) {

                    console.log(data.email);

                    $('#email').val(data.email);

                    $('#phone').val(data.phone);

                    $('#addressone').val(data.address);

                    $('#zipcode').val(data.zipcode);

                    console.log(data.city);

                    $('#cityname').val(data.city).trigger('change');

                },

                error: function(data) {

                    // Handle error

                }

            });

        });
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
        $(document).on('click', '#updateUserBtn', function() {

    var name_value = document.updateuserform.name.value;

    var phone_value = document.updateuserform.phone.value;

    var email_value = document.updateuserform.email.value;

    var password_value = document.updateuserform.password.value;

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

            url: "{{ route('quotation_admin.add.user.data','') }}" + '/' + 1,

            headers: {

                'X-CSRFToken': "{{ csrf_token() }}"

            },

            data: $('#updateUserForm').serialize(),

            success: function(data) {

                $('#Customername').append('<option value="' + data.user.id + '" selected>' + data

                    .user.name + '</option>')

                $("#staticBackdrop").modal('hide');

            },

            error: function(data) {

                // console.log(data);

            }

        });

    }

});
$(document).on('click', '#updateUserBtnBusiness', function() {

    var name_value = document.updateuserformbusiness.name.value;

    var phone_value = document.updateuserformbusiness.phone.value;

    var email_value = document.updateuserformbusiness.email.value;

    var password_value = document.updateuserformbusiness.password.value;

    var role_value = document.updateuserformbusiness.role.value;

    var address_value = document.updateuserformbusiness.address.value;

    var city_value = document.updateuserformbusiness.city.value;

    var zipcode_value = document.updateuserformbusiness.zipcode.value;

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

        document.updateuserformbusiness.name.focus();

    }

    if (phone_value == "") {

        i++;

        phoneError.innerHTML = "Valid Contact Details must be filled out!";

        phoneError.style.color = "Red";

        document.updateuserformbusiness.phone.focus();

    }

    if (email_value == "") {

        i++;

        emailError.innerHTML = "Email must be filled out!";

        emailError.style.color = "Red";

        document.updateuserformbusiness.email.focus();

    }

    if (role_value == 0) {

        i++;

        roleError.innerHTML = "Role must be Selected!";

        roleError.style.color = "Red";

        document.updateuserformbusiness.role.focus();

    }

    if (address_value == 0) {

        i++;

        addressError.innerHTML = "Address must be filled out!";

        addressError.style.color = "Red";

        document.updateuserformbusiness.role.focus();

    }

    if (city_value == 0) {

        i++;

        cityError.innerHTML = "City must be filled out!";

        cityError.style.color = "Red";

        document.updateuserformbusiness.role.focus();

    }

    if (zipcode_value == 0) {

        i++;

        zipcodeError.innerHTML = "ZipCode must be filled out!";

        zipcodeError.style.color = "Red";

        document.updateuserformbusiness.role.focus();

    }

    if (i == 0) {

        $.ajax({

            type: 'POST',

            url: "{{ route('quotation_admin.add.user.data','') }}" + '/' + 1,

            headers: {

                'X-CSRFToken': "{{ csrf_token() }}"

            },

            data: $('#updateUserFormBusiness').serialize(),

            success: function(data) {

                $('#b_name').append('<option value="' + data.user.id + '" selected>' + data

                    .user.name + '</option>');

                $("#staticBackdropBusiness").modal('hide');

            },

            error: function(data) {

                // console.log(data);

            }

        });

    }

});

    });

</script>

@endsection