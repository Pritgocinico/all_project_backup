@extends('quotation.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="page-header">
                <div>
                    <h3>Add New User</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{ route('admin.add.user.data') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="userName">User Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" id="userName"
                                                aria-describedby="nameError" placeholder="Enter Name"
                                                value="{{ old('name') }}">
                                            @if ($errors->has('name'))
                                                <small id="nameError"
                                                    class="form-text text-danger">{{ $errors->first('name') }}</small>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email address </label>
                                            <input type="email" class="form-control" name="email"
                                                id="exampleInputEmail1" value="{{ old('email') }}"
                                                aria-describedby="emailHelp" placeholder="Enter email">
                                         
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPhone1">Phone Number </label>
                                            <input type="tel" class="form-control" name="phone"
                                                id="exampleInputPhone1" value="{{ old('phone') }}"
                                                aria-describedby="phoneError" placeholder="Enter Phone Number">
                                           
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" class="form-control" name="address" id="address"
                                                placeholder="Address" value="{{ old('address') }}">
                                            
                                        </div>
                                        <div class="form-group">
                                            <label for="city">City <span class="text-danger">*</span></label>
                                            <select class="form-control select2-example" id="city" name="city">
                                                <option default>Select City</option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city['name'] }}">{{ $city['name'] }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('city'))
                                                <span class="text-danger">{{ $errors->first('city') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="state">State <span class="text-danger">*</span></label>
                                            <select class="form-control select2-example" id="state" name="state"
                                                disabled>
                                                <option value="Gujarat">Gujarat</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="zipcode">Zip Code</label>
                                            <input type="text" class="form-control" name="zipcode" id="zipcode"
                                                placeholder="Zip Code" value="{{ old('zipcode') }}">
                                         
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" name="password" class="form-control"
                                                id="exampleInputPassword1" placeholder="Password">
                                            @if ($errors->has('password'))
                                                <small id="passwordError"
                                                    class="form-text text-danger">{{ $errors->first('password') }}</small>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="roles">Role</label>
                                            <select name="role" class="form-control" id="roles">
                                                <option value="0" @if (old('role') == 0) selected @endif>
                                                    Select Role...</option>
                                                <option value="1" @if (old('role') == 1) selected @endif>
                                                    Admin</option>
                                                <!-- <option value="2" @if (old('role') == 2) selected @endif>
                                                    User</option> -->
                                                <option value="3" @if (old('role') == 3) selected @endif>
                                                    Measurement</option>
                                                <option value="4" @if (old('role') == 4) selected @endif>
                                                    Quatation</option>
                                                <option value="5" @if (old('role') == 5) selected @endif>
                                                    Workshop</option>
                                                <option value="6" @if (old('role') == 6) selected @endif>
                                                    Fitting</option>
                                                <!-- <option value="7" @if (old('role') == 7) selected @endif>
                                                    Business</option> -->
                                            </select>
                                            @if ($errors->has('role'))
                                                <small id="roleError"
                                                    class="form-text text-danger">{{ $errors->first('role') }}</small>
                                            @endif
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="page-header d-md-flex justify-content-between">
                <div>
                    <h3 class="mb-0">Users</h3>

                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table id="example2" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!blank($users))
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>
                                            @if ($user->role == 1)
                                                Admin
                                            @elseif ($user->role == 2)
                                                User
                                            @elseif ($user->role == 3)
                                                Measurement
                                            @elseif ($user->role == 4)
                                                Quatation
                                            @elseif ($user->role == 5)
                                                Workshop
                                            @elseif ($user->role == 6)
                                                Fitting
                                            @elseif ($user->role == 7)
                                                Business
                                            @endif
                                        </td>
                                        <td>{{ date('Y-m-d', strtotime($user->created_at)) }}</td>
                                        <td>
                                            <a href="{{ route('admin.edit.user', $user->id) }}" class="editUser"
                                                data-id="{{ $user->id }}" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdrop"><i data-feather="edit"></i></a>
                                            <a href="javascript:void(0);" data-id="{{ $user->id }}"
                                                class="ms-2 delete-btn"><i data-feather="trash-2"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit User</h5>
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
                                        <label for="Passwordedit">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control userPassword"
                                            id="Passwordedit" placeholder="Password">
                                        <small class="form-text text-muted">* Leave Blank if don't want to change</small>
                                        <small id="edit_passwordError" class="form-text text-danger"></small>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="editroles">Role </label>
                                        <select name="role" class="form-control userRole" id="editroles">
                                            <option value="0">Select Role...</option>
                                            <option value="1">Admin</option>
                                            <!-- <option value="2">User</option> -->
                                            <option value="3">Measurement</option>
                                            <option value="4">Quatation</option>
                                            <option value="5">Workshop</option>
                                            <option value="6">Fitting</option>
                                            <!-- <option value="7">Business</option> -->
                                        </select>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" id="updateUserBtn">Update</button>
                                <input type="hidden" name="userid" value="" id="userid_hidden">
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
        $(document).on('click', '.editUser', function() {
            var id = $(this).data('id');
            $.ajax({
                type: 'GET',
                url: '{{ route('get_user') }}',
                data: {
                    'id': id
                },
                success: function(data) {
                    console.log(data);
                    $('#userNameedit').val(data.name);
                    $('#userEmailedit').val(data.email);
                    $('#Phoneedit').val(data.phone);
                    $('#addressedit').val(data.address);
                    // $('#cityedit').val(data.city);
                    $('#stateedit').val(data.state);
                    $('#zipcodeedit').val(data.zipcode);
                    $('#userid_hidden').val(id);
                    // $('#editroles option:eq('+data.role+')').prop('selected', true);
                    $('#cityedit option[value="' + data.city + '"]').prop('selected', true);
                    $('#editroles option[value="' + data.role + '"]').prop('selected', true);
                },
                error: function(data) {
                    // console.log(data);
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
            var hiddenvalue = document.getElementById('userid_hidden').value;
            if (i == 0) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.update.user', '') }}' + '/' + hiddenvalue,
                    data: $('#updateUserForm').serialize(),
                    success: function(data) {
                        location.reload();
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
            $(document).on('click', '.delete-btn', function() {
                var employee_id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('delete.user', '') }}" + "/" + employee_id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: "Employee has been deleted.",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
    <script>
        $('.select2-example').select2({
            placeholder: 'Select'
        });
    </script>
@endsection
