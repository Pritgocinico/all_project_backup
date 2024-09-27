@extends('admin.layouts.app')



@section('content')

    <div class="project">

        <div class="page-header d-md-flex justify-content-between align-items-center">

            <div class="">

                <h3 class="mb-0">Edit Projects</h3>

            </div>

            <div class="">

                <a href="{{ route('projects') }}" class="btn btn-primary ms-auto">

                    <i class="sub-menu-arrow ti-angle-left me-2"></i> Back

                </a>

            </div>

        </div>

        <div class="card">

            <div class="card-body">

                <form class="alert-repeater" action="{{ route('update.project', $projects->id) }}" enctype="multipart/form-data" method="POST">

                    @csrf

                    <div class="form-row row">

                        <!-- <div class="form-group col-md-4">

                            <label for="projectname">Project Name <span class="text-danger">*</span></label>

                            <input type="text" class="form-control" id="projectname" placeholder="Project Name" name="project_name" value="{{ $projects->project_name }}">

                            @if ($errors->has('project_name'))

                                <span class="text-danger">{{ $errors->first('project_name') }}</span>

                            @endif

                        </div> -->

                        <!-- <div class="form-group col-md-4">

                            <label for="check_business">Select Type <span class="text-danger">*</span></label>

                            <select class="custom-select" id="businessType" name="businessType">

                                <option value="b2c" @if($projects->businessType == 'b2c') selected @endif>B2C</option>

                                <option value="b2b" @if($projects->businessType == 'b2b') selected @endif>B2B</option>

                            </select>

                        </div> -->

                        {{-- @if ($projects->businessType == 'b2b')   --}}

                        <!-- <div class="form-group col-md-4 b2b_div" >

                            <div class="d-flex align-items-end">

                                <div class="w-100">

                                    <div class="form-group mb-0">

                                        <label for="Customername">Business Name <span class="text-danger">*</span></label>

                                        <select class="form-control" id="Customername" name="business_name">

                                            @foreach($business as $user)

                                            <option <?php if($projects->customer_id == $user->id){ echo "selected"; } ?> value="{{ $user->id }}">{{ $user->name }}</option>

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

                        <div class="form-group col-md-4 b2b_div" >

                            <label for="b_gstnum">GST Number<span class="text-danger">*</span></label>

                            <input type="text" class="form-control" name="gstnum" id="b_gstnum"

                                value="{{ old('b_gstnum') }}" placeholder="GST Number">

                        </div>  -->

                        {{-- @else  --}}

                        <div class="form-group col-md-4 b2c_div">

                            <div class="d-flex align-items-end">

                                <div class="w-100">

                                    <div class="form-group mb-0">

                                        <label for="Customername">Customer Name <span class="text-danger">*</span></label>

                                        <select class="form-control" id="Customername" name="customer_name">

                                            @foreach($users as $user)

                                            <option <?php if($projects->customer_id == $user->id){ echo "selected"; } ?> value="{{ $user->id }}">{{ $user->name }}</option>

                                            @endforeach

                                            {{-- @foreach ($users as $user)

                                                <option value="{{$user->id}}">{{$user->name}}</option>

                                            @endforeach --}}

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

                        {{-- @endif --}}

                        <div class="form-group col-md-4">

                            <label for="phone">customer Number </label>

                            <input type="number" class="form-control" name="phone_number" id="phone" value="{{ $projects->user ? $projects->user->phone : '' }}" placeholder="Phone Number">


                        </div>
                        <div class="form-group col-md-4">
    <label for="architecture_name">Architect Name </label>
    <input type="text" class="form-control" name="architecture_name" id="architecture_name" value="{{ old('architecture_name') }}" placeholder="Architect Name">
  
</div>

<div class="form-group col-md-4">
    <label for="architecture_number">Architect Number </label>
    <input type="number" class="form-control" name="architecture_number" id="architecture_number" value="{{ old('architecture_number') }}" placeholder="Architect Number">
 
</div>

<div class="form-group col-md-4">
    <label for="supervisor_name">Supervisor Name </label>
    <input type="text" class="form-control" name="supervisor_name" id="supervisor_name" value="{{ old('supervisor_name') }}" placeholder="Supervisor Name">
  
</div>

<div class="form-group col-md-4">
    <label for="supervisor_number">Supervisor Number </label>
    <input type="number" class="form-control" name="supervisor_number" id="supervisor_number" value="{{ old('supervisor_number') }}" placeholder="Supervisor Number">
  
</div>
                        <div class="form-group col-md-4">

                            <label for="email">Email Address </label>

                            <input type="email" class="form-control" name="email" id="email" placeholder="Email Address" value="{{ $projects->user ? $projects->user->email : '' }}">

                           

                        </div>

                        <div class="form-group col-md-4">

                            <label for="addressone">Address </label>

                            <input type="text" class="form-control" name="addressone" id="addressone" placeholder="Address" value="{{ $projects->user ? $projects->user->address : '' }}">

                     

                        </div>

                        <div class="form-group col-md-4">

                            <label for="cityname">City <span class="text-danger">*</span></label>

                            <select class="form-control select2-example" id="cityname" name="cityname"> 

                                {{-- <option default>Select City</option>   --}}

                                {{-- @foreach ($cities as $city)

                                    <option value="{{$city['id']}}">{{$city['name']}}</option>  

                               @endforeach --}}

                               @foreach ($cities as $city)
    <option {{ ($projects->user && $projects->user->id == $city['id']) ? 'selected' : '' }} value="{{ $city['id'] }}">{{ $city['name'] }}</option>
@endforeach

                            </select>

                            @if ($errors->has('cityname'))

                                <span class="text-danger">{{ $errors->first('cityname') }}</span>

                            @endif

                        </div>

                        <div class="form-group col-md-4">

                            <label for="statename">State <span class="text-danger">*</span></label>

                            <select class="form-control select2-example" id="statename" name="statename" disabled>

                                <option value="1">Gujarat</option>

                            </select>

                            @if ($errors->has('statename'))

                                <span class="text-danger">{{ $errors->first('statename') }}</span>

                            @endif

                        </div>

                        <div class="form-group col-md-4">

                            <label for="zipcode">Zip Code </label>

                            <input type="text" class="form-control" name="zipcode" id="zipcode" placeholder="Zip Code" value="{{ $projects->user ? $projects->user->zipcode : '' }}">

                           

                        </div>



                        <!-- <div class="form-group col-md-4">

                            <label for="projectconfirmdate">Project Confirmation Date <span class="text-danger">*</span></label>

                            <input type="text" name="projectconfirmdate" class="form-control" value="{{ date('d-m-Y',strtotime($projects->project_confirm_date)) }}">

                            @if ($errors->has('projectconfirmdate'))

                                <span class="text-danger">{{ $errors->first('projectconfirmdate') }}</span>

                            @endif

                        </div> -->

                        <div class="form-group col-md-4">

                            <label for="startdate">Start Date <span class="text-danger">*</span></label>

                            <input type="text" name="startdaterangepicker" class="form-control" value="{{ date('d-m-Y',strtotime($projects->start_date)) }}">

                            @if ($errors->has('startdaterangepicker'))

                                <span class="text-danger">{{ $errors->first('startdaterangepicker') }}</span>

                            @endif

                        </div>

                        <!-- <div class="form-group col-md-4">

                            <label for="enddate">End Date <span class="text-danger">*</span></label>

                            <input type="text" name="enddaterangepicker" class="form-control" value="{{ date('d-m-Y',strtotime($projects->end_date)) }}">

                            @if ($errors->has('enddaterangepicker'))

                                <span class="text-danger">{{ $errors->first('enddaterangepicker') }}</span>

                            @endif

                        </div> -->

                        <div class="form-group col-md-4">

                            <label for="measurementdate">Estimated Measurement Date <span class="text-danger">*</span></label>

                            <input type="text" name="measurementdate" class="form-control" data-date-format="yyyy-mm-dd"  value="{{ date('d-m-Y',strtotime($projects->measurement_date)) }}"> 

                            @if ($errors->has('measurementdate'))

                                <span class="text-danger">{{ $errors->first('measurementdate') }}</span>

                            @endif

                        </div>

                        <div class="form-group col-md-4">

                            <label for="reference_name">Reference Name </label>

                            <input type="text" class="form-control" name="reference_name" id="reference_name"

                                placeholder="Reference Name" value="{{ $projects->reference_name }}">

                            @if ($errors->has('reference_name'))

                                <span class="text-danger">{{ $errors->first('reference_name') }}</span>

                            @endif

                        </div>

                        <div class="form-group col-md-4">

                            <label for="reference_phone">Reference Phone Number</label>

                            <input type="text" class="form-control" name="reference_phone" id="reference_phone"

                                placeholder="Reference Phone Number" value="{{ $projects->reference_phone }}">

                            @if ($errors->has('reference_phone'))

                                <span class="text-danger">{{ $errors->first('reference_phone') }}</span>

                            @endif

                        </div>

                        <div class="form-group">

                            <label for="projectdesc">Project Description </label>

                            <textarea class="form-control" id="projectdesc" rows="5" name="description">{{ $projects->description }}</textarea>

                         

                        </div>

                        

                       

                    </div>

                    <hr>

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

                                action="{{ isset($user) ? route('admin.update.user', ['id' => $user->id]) : '#' }}"

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

                                        <label for="userEmailedit">Email address </label>

                                        <input type="email" class="form-control userEmail" name="email"

                                            id="userEmailedit" value="{{ old('email') }}"

                                            aria-describedby="edit_emailError" placeholder="Enter email">




                                    </div>

                                    <div class="form-group col-md-6">

                                        <label for="Phoneedit">Phone Number </label>

                                        <input type="tel" class="form-control userPhone" name="phone" id="Phoneedit"

                                            value="{{ old('phone') }}" aria-describedby="phoneError"

                                            placeholder="Enter Phone Number">

                                       



                                    </div>

                                    <div class="form-group col-md-6">

                                        <label for="address">Address </label>

                                        <input type="text" class="form-control" name="address" id="addressedit" placeholder="Address" value="{{old('address')}}">

                                       

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

                                        <label for="zipcode">Zip Code </label>

                                        <input type="text" class="form-control" name="zipcode" id="zipcodeedit" placeholder="Zip Code"  value="{{old('zipcode')}}">

                                        

                                    </div>

                                    <div class="form-group col-md-6">

                                        <label for="Passwordedit">Password </label>

                                        <input type="password" name="password" class="form-control userPassword"

                                            id="Passwordedit" placeholder="Password">

                                        

                                    </div>

                                    <div class="form-group col-md-6">

                                        <label for="editroles">Role <span class="text-danger">*</span></label>

                                        <select name="role" class="form-control userRole" id="editroles">

                                            <option value="0">Select Role...</option>

                                            <option value="2">user</option>

                                        </select>

                                        <small id="edit_roleError" class="form-text text-danger"></small>

                                    </div>

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



    <div class="modal fade" id="staticBackdrop_business" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"

        aria-labelledby="staticBackdropLabel" aria-hidden="true">

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

                                            id="businessNameedit" aria-describedby="edit_nameError" placeholder="Enter Name"

                                            value="{{ old('name') }}">

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

                                            id="businessPhoneedit" value="{{ old('phone') }}" aria-describedby="phoneError"

                                            placeholder="Enter Phone Number">

                                        <small id="business_phoneError" class="form-text text-danger"></small>



                                    </div>

                                    <div class="form-group col-md-6">

                                        <label for="address">Address <span class="text-danger">*</span></label>

                                        <input type="text" class="form-control" name="address" id="businessaddressedit"

                                            placeholder="Address" value="{{ old('address') }}">

                                        <small id="business_addressError" class="form-text text-danger"></small>

                                    </div>

                                    <div class="form-group col-md-6">

                                        <label for="city">City <span class="text-danger">*</span></label>

                                        <select class="form-control select2" id="businesscityedit" name="city">

                                            {{-- <option default>Select City</option>   --}}

                                            @foreach ($cities as $city)

                                                <option value="{{ $city['name'] }}">{{ $city['name'] }}</option>

                                            @endforeach

                                        </select>

                                        <small id="business_cityError" class="form-text text-danger"></small>

                                    </div>

                                    <div class="form-group col-md-6">

                                        <label for="state">State <span class="text-danger">*</span></label>

                                        <select class="form-control select2-example" id="business_stateedit" name="state"

                                            disabled>

                                            <option value="Gujarat">Gujarat</option>

                                        </select>

                                        <small id="business_stateError" class="form-text text-danger"></small>

                                    </div>

                                    <div class="form-group col-md-6">

                                        <label for="zipcode">Zip Code <span class="text-danger">*</span></label>

                                        <input type="text" class="form-control" name="zipcode" id="businesszipcodeedit"

                                            placeholder="Zip Code" value="{{ old('zipcode') }}">

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

                format: 'DD-MM-YYYY'

            },

        });

        $('input[name="enddaterangepicker"]').daterangepicker({

            singleDatePicker: true,

            showDropdowns: true,

            locale: {

                format: 'DD-MM-YYYY'

            },

        });

        $('input[name="projectconfirmdate"]').daterangepicker({

            singleDatePicker: true,

            showDropdowns: true,

            locale: {

                format: 'DD-MM-YYYY'

            },

        });

        $('input[name="measurementdate"]').daterangepicker({

            singleDatePicker: true,

            showDropdowns: true,

            locale: {

                format: 'DD-MM-YYYY'

            },

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

                url: '{{ route('get_user') }}',

                data: {

                    'id': id

                },

                success: function(data) {

                    $('#email').val(data.email);

                    $('#phone').val(data.phone);

                    $('#addressone').val(data.address);

                    $('#zipcode').val(data.zipcode);

                    $('#cityname').val(data.city).trigger('change');

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

                    url: "{{ route('admin.add.user.data','') }}"+'/'+1,

                    headers: {

                        'X-CSRFToken': "{{ csrf_token() }}"

                   },

                    data: $('#updateUserForm').serialize(),

                    success: function(data) {

                        $('#Customername').append('<option value="'+data.user.id+'" selected>'+data.user.name+'</option>');

                        $("#staticBackdrop").modal('hide');

                    },

                    error: function(data) {

                        // console.log(data);

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

            // if (gst_value == "") {

            //     i++;

            //     gstError.innerHTML = "GST Number must be filled out!";

            //     gstError.style.color = "Red";

            // }

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

                        // console.log(data);

                    }

                });

            }

        });

     </script>

    {{-- <script>

        document.addEventListener("DOMContentLoaded", function() {

        const addButton = document.querySelector(".add_quotation");

            addButton.addEventListener("click", function(event) {

                event.preventDefault();

                const personalInfoDiv = document.querySelector('.quotation_div');

                const newPersonalInfo = personalInfoDiv.cloneNode(true);

                const deleteButton = document.createElement('button');

                deleteButton.textContent = 'Remove';

                deleteButton.classList.add("quotation_delete_button");

                deleteButton.classList.add("btn-primary");

                deleteButton.classList.add("btn");

                deleteButton.addEventListener('click', function() {

                    newPersonalInfo.remove();

                });

                newPersonalInfo.insertBefore(deleteButton , newPersonalInfo.lastChild);

                personalInfoDiv.parentNode.insertBefore(newPersonalInfo, personalInfoDiv.nextSibling);

                newPersonalInfo.querySelectorAll("input").forEach(function(input) {

                    input.value = ""; 

                });

            });

        });

    </script> --}}

    <script>

            $(document).ready(function() {

                function handleBusinessTypeVisibility() {

                    var selectedOption = $("#businessType").val();

                    if (selectedOption === "b2c") {

                        $(".b2c_div").show();

                        $(".b2b_div").hide();

                    } else if (selectedOption === "b2b") {

                        $(".b2b_div").show();

                        $(".b2c_div").hide();

                    }

                }

                $("#businessType").change(handleBusinessTypeVisibility);

                handleBusinessTypeVisibility();

            });

    </script>

@endsection