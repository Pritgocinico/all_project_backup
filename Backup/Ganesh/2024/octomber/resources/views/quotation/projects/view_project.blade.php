@extends('quotation.layouts.viewproject')
@section('pages')
    <div class="fieldset">
        <div class="project">
            <h2 class="fs-title">
                @if ($projects->type == 1)
                    Project Details
                @else
                    Lead Details
                @endif
            </h2>
            <hr>
            <form class="alert-repeater" action="{{ route('quotation_update.project', $projects->id) }}" enctype="multipart/form-data"
                method="POST">
                @csrf
                <div class="form-row row">
                    <div class="form-group col-md-4">
                        <div class="d-flex align-items-end">
                            <div class="w-100">
                                <div class="form-group mb-0">
                                    <label for="Customername">Customer Name <span class="text-danger">*</span></label>
                                    <select class="form-control" id="Customername" name="customer_name">
                                        @foreach ($users as $user)
                                            <option <?php if ($projects->customer_id == $user->id) {
                                                echo 'selected';
                                            } ?> value="{{ $user->id }}">{{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('customer_name'))
                                        <span class="text-danger">{{ $errors->first('customer_name') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
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
                        <label for="reference_phone">Reference Number</label>
                        <input type="text" class="form-control" name="reference_phone" id="reference_phone"
                            placeholder="Reference Phone Number" value="{{ $projects->reference_phone }}">
                        @if ($errors->has('reference_phone'))
                            <span class="text-danger">{{ $errors->first('reference_phone') }}</span>
                        @endif
                    </div>
                    <div class="form-group col-md-4">
                        <label for="phone">Phone Number <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="phone_number" id="phone"
                            value="{{ $projects->phone_number }}" placeholder="Phone Number">
                        @if ($errors->has('phone_number'))
                            <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                        @endif
                    </div>

                    <div class="form-group col-md-4">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email Address"
                            value="{{ $projects->email }}">
                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>

                    <div class="form-group col-md-4">
                        <label for="addressone">Site Address </label>
                        <input type="text" class="form-control" name="addressone" id="addressone" placeholder="Address"
                            value="{{ $projects->address }}">
                        @if ($errors->has('addressone'))
                            <span class="text-danger">{{ $errors->first('addressone') }}</span>
                        @endif
                    </div>

                    {{-- <div class="form-group col-md-4">
                    <label for="cityname">City </label>
                    <select class="form-control select2-example" id="cityname" name="cityname">
                        <!-- <option value="Ahmedabad">Ahmedabad</option> -->
                        @foreach ($cities as $city)
                        <option <?php if ($projects->cityname == $city['name']) {
                            echo 'selected';
                        } ?>
                           value="{{ $city['name'] }}">{{$city['name']}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('cityname'))
                    <span class="text-danger">{{ $errors->first('cityname') }}</span>
                    @endif
                </div> --}}
                    <div class="form-group col-md-4">
                        <label for="state">State </label>
                        <select class="form-control select2-example" id="state" name="state">
                            <option value="">Select State</option>
                            @foreach ($states as $state)
                                <option value="{{ $state['id'] }}"
                                    {{ old('state', $projects->statename) == $state['id'] ? 'selected' : '' }}>
                                    {{ $state['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="cityname">City </label>
                        <select class="form-control select2-example" id="cityname" name="cityname">
                            @foreach ($cities as $city)
                                <option value="{{ $city['id'] }}"
                                    {{ old('city', $projects->cityname) == $city['id'] ? 'selected' : '' }}>
                                    {{ $city['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('cityname'))
                            <span class="text-danger">{{ $errors->first('cityname') }}</span>
                        @endif
                    </div>
                    <!-- <div class="form-group col-md-4">
                        <label for="statename">State <span class="text-danger">*</span></label>
                        <select class="form-control select2-example" id="statename" name="statename" disabled>
                            <option value="1">Gujarat</option>
                        </select>
                        @if ($errors->has('statename'))
    <span class="text-danger">{{ $errors->first('statename') }}</span>
    @endif
                    </div> -->
                    <div class="form-group col-md-4">
                        <label for="zipcode">Zip Code </label>
                        <input type="text" class="form-control" name="zipcode" id="zipcode" placeholder="Zip Code"
                            value="{{ $customer->zipcode }}">
                        @if ($errors->has('zipcode'))
                            <span class="text-danger">{{ $errors->first('zipcode') }}</span>
                        @endif
                    </div>
                    <div class="form-group col-md-4">
                        <label for="measurementdate">Estimated Measurement Date <span class="text-danger">*</span></label>
                        <input type="text" name="measurementdate" class="form-control"
                            value="{{ date('d/m/Y', strtotime($projects->measurement_date)) }}">
                        @if ($errors->has('measurementdate'))
                            <span class="text-danger">{{ $errors->first('measurementdate') }}</span>
                        @endif
                    </div>
                    <div class="form-group col-md-8">
                        <label for="measurement_user">Send SMS of Measurement Users</label>
                        <select class="form-control select2-example" id="measurement_user" name="measurement_user[]"
                            multiple>
                            <option value="">Select User</option>
                            @foreach ($measurement_users as $measurement)
                                <option value="{{ $measurement->id }}" @if (in_array($measurement->id, $measurementtaskUsers->pluck('user_id')->toArray())) selected @endif>
                                    {{ $measurement->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="projectdesc">Project Description</label>
                        <textarea class="form-control" id="projectdesc" rows="5" name="description">{{ $projects->description }}</textarea>
                        @if ($errors->has('description'))
                            <span class="text-danger">{{ $errors->first('description') }}</span>
                        @endif
                    </div>
                </div>
                <hr>
                <input type="hidden" name="project_info" value="1">
                <input type="hidden" id="project_id" value="{{ $projects->id }}">
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form>
            <div class="d-flex align-items-center justify-content-between mt-3">
                <button type="button" class="btn btn-danger" onclick="cancelLead()">Cancel Lead</button>
                <a href="{{ route('quotation_view.measurement', $projects->id) }}" class="btn btn-primary">Next <i
                        data-feather="arrow-right" class="ms-2 fw-bold"></i></a>
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
                                            <label for="userNameedit">User Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control userName"
                                                id="userNameedit" aria-describedby="edit_nameError"
                                                placeholder="Enter Name" value="{{ old('name') }}">
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
                                            <label for="city">City <span class="text-danger">*</span></label>
                                            <select class="form-control " id="cityedit" name="city">
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city['name'] }}">{{ $city['name'] }}</option>
                                                @endforeach
                                            </select>
                                            <small id="edit_cityError" class="form-text text-danger"></small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="state">State <span class="text-danger">*</span></label>
                                            <select class="form-control select2-example" id="stateedit" name="state"
                                                disabled>
                                                <option value="Gujarat">Gujarat</option>
                                            </select>
                                            <small id="edit_stateError" class="form-text text-danger"></small>
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
                                            <small id="edit_passwordError" class="form-text text-danger"></small>
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
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        // $('input[name="startdaterangepicker"]').daterangepicker({
        //     singleDatePicker: true,
        //     showDropdowns: true,
        //     locale: {
        //         format: 'DD-MM-YYYY'
        //     },
        // });
        // $('input[name="enddaterangepicker"]').daterangepicker({
        //     singleDatePicker: true,
        //     showDropdowns: true,
        //     locale: {
        //         format: 'DD-MM-YYYY'
        //     },
        // });
        // $('input[name="projectconfirmdate"]').daterangepicker({
        //     singleDatePicker: true,
        //     showDropdowns: true,
        //     locale: {
        //         format: 'DD-MM-YYYY'
        //     },
        // });
        $('input[name="measurementdate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD/MM/YYYY'
            }
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
                url: '{{ route('quotation_get_customer') }}',
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
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                    $('#addressone').val(data.address);
                    $('#zipcode').val(data.zipcode);
                    $("#cityname").val(data.city).trigger('change');
                },
                error: function(data) {
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
                    url: "{{ route('admin.add.user.data', '') }}" + '/' + 1,
                    headers: {
                        'X-CSRFToken': "{{ csrf_token() }}"
                    },
                    data: $('#updateUserForm').serialize(),
                    success: function(data) {
                        $('#Customername').append('<option value="' + data.user.id + '" selected>' +
                            data.user.name + '</option>');
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
            var gst_value = document.updateBusinessForm.gstnum.value;
            var password_value = document.updateBusinessForm.password.value;
            var role_value = document.updateBusinessForm.role.value;
            var address_value = document.updateBusinessForm.address.value;
            var city_value = document.updateBusinessForm.city.value;
            var zipcode_value = document.updateBusinessForm.zipcode.value;
            var nameError = document.getElementById('business_nameError');
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
                        // console.log(data);
                    }
                });
            }
        });
    </script>
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

        function cancelLead() {
            var project_id = $('#project_id').val();
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to cancel this lead?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        'method': 'get',
                        'url': "{{ route('quotation.cancel-lead') }}",
                        'data': {
                            project_id: project_id,
                        },
                        success: function(res) {
                            Swal.fire({
                                title: 'Cancel!',
                                text: "Lead has been Cancelled.",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ route('leads') }}"
                                }
                            });
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    })
                }
            });

        }
    </script>
@endsection
