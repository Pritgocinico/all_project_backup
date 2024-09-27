@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="page-header">
                <div>
                    <h3>Add New Customer</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{ route('admin.add.customer.data') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="userName">Customer Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" id="userName"
                                                aria-describedby="nameError" placeholder="Enter Name"
                                                value="{{ old('name') }}">
                                            @if ($errors->has('name'))
                                                <small id="nameError"
                                                    class="form-text text-danger">{{ $errors->first('name') }}</small>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email address</label>
                                            <input type="email" class="form-control" name="email"
                                                id="exampleInputEmail1" value="{{ old('email') }}"
                                                aria-describedby="emailHelp" placeholder="Enter email">
                                            {{-- @if ($errors->has('email'))
                                                <small id="emailError"
                                                    class="form-text text-danger">{{ $errors->first('email') }}</small>
                                            @endif --}}
                                        </div>
                                        <div class="form-group">
                                            <label for="">Customer Id</label>
                                            <input type="text" class="form-control" name="customer_id"
                                                value="{{ $customerId }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPhone1">Customer Number</label>
                                            <input type="tel" class="form-control" name="phone"
                                                id="exampleInputPhone1" value="{{ old('phone') }}"
                                                aria-describedby="phoneError" placeholder="Enter Customer Number">
                                            @if ($errors->has('phone'))
                                                <small id="phoneError"
                                                    class="form-text text-danger">{{ $errors->first('phone') }}</small>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPhone1">Password</label>
                                            <input type="password" class="form-control" name="password"
                                                id="exampleInputPhone1" value="{{ old('password') }}"
                                                aria-describedby="phoneError" placeholder="Enter password">
                                            @if ($errors->has('password'))
                                                <small id="phoneError"
                                                    class="form-text text-danger">{{ $errors->first('password') }}</small>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" class="form-control" name="address" id="address"
                                                placeholder="Address" value="{{ old('address') }}">
                                            {{-- @if ($errors->has('address'))
                                                <span class="text-danger">{{ $errors->first('address') }}</span>
                                            @endif --}}
                                        </div>
                                        <div class="form-group">
                                            <label for="state">State <span class="text-danger">*</span></label>
                                            <select class="form-control select2-example" id="state" name="state">
                                                <option value="">Select State</option>
                                                @foreach ($states as $state)
                                                    <option value="{{ $state['id'] }}">{{ $state['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="city">City <span class="text-danger">*</span></label>
                                            <select class="form-control select2-example" id="city" name="city">
                                                <option default>Select City</option>
                                            </select>
                                            @if ($errors->has('city'))
                                                <span class="text-danger">{{ $errors->first('city') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="zipcode">Zip Code</label>
                                            <input type="text" class="form-control" name="zipcode" id="zipcode"
                                                placeholder="Zip Code" value="{{ old('zipcode') }}">
                                            {{-- @if ($errors->has('zipcode'))
                                                <span class="text-danger">{{ $errors->first('zipcode') }}</span>
                                            @endif --}}
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
                    <h3 class="mb-0">Customers</h3>

                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table id="example2" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Customer Id</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!blank($customer))
                                @foreach ($customer as $customers)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $customers->name }}</td>
                                        <td>{{ $customers->customer_id }}</td>
                                        <td>{{ $customers->email }}</td>
                                        <td>{{ $customers->phone }}</td>
                                        <td>{{ date('d/m/Y - H:i:s', strtotime($customers->created_at)) }}</td>
                                        <td>
                                            @if ($customers->app_user_active == '0')
                                                <a href="javascript:void(0);" data-id="{{ $customers->id }}"
                                                    class="ms-2 verify-btn"><i class="fa fa-check"
                                                        aria-hidden="true"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('admin.edit.customer', $customers->id) }}"
                                                class="editCustomer" data-id="{{ $customers->id }}"
                                                data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i
                                                    data-feather="edit"></i></a>
                                            <a href="javascript:void(0);" data-id="{{ $customers->id }}"
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
        aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index: 1050;">
        <div class="modal-dialog modal-dialog-centered  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="updateCustomerForm" name="updatecustomerform"
                                action="{{ isset($customers) ? route('admin.update.customer', ['id' => $customers->id]) : '#' }}"
                                method="POST">
                                @csrf
                                <div class="alert alert-danger print-error-msg" style="display:none">
                                    <ul></ul>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="userNameedit">Customer ID <span class="text-danger">*</span></label>
                                        <input type="text" name="customer_id" class="form-control userName"
                                            id="customer_id" aria-describedby="edit_nameError"
                                            placeholder="Enter Customer ID" value="{{ old('customer_id') }}" readonly>
                                        <small id="edit_nameError" class="form-text text-danger"></small>

                                    </div>
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
                                        {{-- <small id="edit_emailError" class="form-text text-danger"></small> --}}

                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="Phoneedit">Customer Number</label>
                                        <input type="tel" class="form-control userPhone" name="phone"
                                            id="Phoneedit" value="{{ old('phone') }}" aria-describedby="phoneError"
                                            placeholder="Enter Customer Number">
                                        {{-- <small id="edit_phoneError" class="form-text text-danger"></small> --}}

                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="zipcode">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password" id="password"
                                                placeholder="Enter password" value="{{ old('password') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">
                                                    <i class="fa fa-eye" id="togglePassword"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="address">Address </label>
                                        <input type="text" class="form-control" name="address" id="addressedit"
                                            placeholder="Address" value="{{ old('address') }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="state">State <span class="text-danger">*</span></label>
                                        <select class="form-control" id="stateedit" name="stateedit">
                                            <option value="">Select State</option>
                                            @foreach ($states as $stateOption)
                                                <option value="{{ $stateOption['id'] }}">
                                                    {{ $stateOption['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="city">City <span class="text-danger">*</span></label>
                                        <select class="form-control " id="cityedit" name="cityedit">
                                            @foreach ($cities as $editcity)
                                                <option value="{{ $editcity['id'] }}">{{ $editcity['name'] }}</option>
                                            @endforeach
                                        </select>
                                        <small id="edit_cityError" class="form-text text-danger"></small>
                                    </div>
                                    {{-- <div class="form-group col-md-6">
                                        <label for="city">City <span class="text-danger">*</span></label>
                                        <select class="form-control" id="cityedit" name="cityedit">
                                            @foreach ($editcities as $editcity)
                                                <option value="{{ $editcity['id'] }}" {{ old('cityedit') == $editcity['id'] ? 'selected' : '' }}>
                                                    {{ $editcity['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small id="edit_cityError" class="form-text text-danger"></small>
                                    </div> --}}
                                    {{-- <div class="form-group col-md-6">
                                        <label for="state">State <span class="text-danger">*</span></label>
                                        <select class="form-control select2-example" id="stateedit" name="state" disabled>
                                            <option value="Gujarat">Gujarat</option>
                                        </select>   
                                        <small id="edit_stateError" class="form-text text-danger"></small>
                                    </div> --}}
                                    <div class="form-group col-md-6">
                                        <label for="zipcode">Zip Code</label>
                                        <input type="text" class="form-control" name="zipcode" id="zipcodeedit"
                                            placeholder="Zip Code" value="{{ old('zipcode') }}">
                                    </div>

                                </div>
                                <button type="button" class="btn btn-primary" id="updateCustomerBtn">Update</button>
                                <input type="hidden" name="customerid" value="" id="customerid_hidden">
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
        $(document).on('click', '#togglePassword', function() {
            var passwordField = document.getElementById('password');
            var type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            if (type == 'text') {
                $('#basic-addon2').html('<i class="fa fa-eye-slash" id="togglePassword"></i>');
            } else {
                $('#basic-addon2').html('<i class="fa fa-eye" id="togglePassword"></i>');
            }
        });
        $(document).on('click', '.editCustomer', function() {
            var id = $(this).data('id');
            $.ajax({
                type: 'GET',
                url: '{{ route('get_customer') }}',
                data: {
                    'id': id
                },
                success: function(data) {
                    console.log(data.customer);
                    $('#userNameedit').val(data.customer.name);
                    $('#customer_id').val(data.customer.customer_id);
                    $('#userEmailedit').val(data.customer.email);
                    $('#Phoneedit').val(data.customer.phone);
                    $('#addressedit').val(data.customer.address);
                    // $('#stateedit').val(data.state);
                    $('#stateedit option[value="' + data.customer.state + '"]').prop('selected', true);
                    $('#zipcodeedit').val(data.customer.zipcode);
                    $('#customerid_hidden').val(data.customer.id);
                    // $('#cityedit option[value="' + data.city + '"]').prop('selected', true);
                    // $('#cityedit').val(data.city);
                    // $('#editroles option[value="' + data.role + '"]').prop('selected', true);
                    var citiesSelect = $('#cityedit');
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
                    // console.log(data);
                }
            });
        });

        $(document).on('change', '#state', function() {
            var state = $(this).val();
            $.ajax({
                url: "{{ route('get.cities', '') }}" + "/" + state,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#city').html(data);
                }
            });
        });

        $(document).on('change', '#stateedit', function() {
            var state = $(this).val();
            $.ajax({
                url: "{{ route('get.cities', '') }}" + "/" + state,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#cityedit').html(data);
                }
            });
        });

        $(document).on('click', '#updateCustomerBtn', function() {
            var name_value = document.updatecustomerform.name.value;
            // var city_value = document.updatecustomerform.city.value;
            var nameError = document.getElementById('edit_nameError');
            var cityError = document.getElementById('edit_cityError');
            var i = 0;

            if (name_value == "") {
                i++;
                nameError.innerHTML = "Name must be filled out!";
                nameError.style.color = "Red";
                document.updatecustomerform.name.focus();
            }
            // if (city_value == 0) {
            //     i++;
            //     cityError.innerHTML = "City must be filled out!";
            //     cityError.style.color = "Red";
            //     document.updatecustomerform.role.focus();
            // }
            var hiddenvalue = document.getElementById('customerid_hidden').value;
            if (i == 0) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.update.customer', '') }}' + '/' + hiddenvalue,
                    data: $('#updateCustomerForm').serialize(),
                    success: function(data) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: data.message,
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
                            url: "{{ route('delete.customer', '') }}" + "/" + employee_id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                if (data == 'success') {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: "Customer has been deleted.",
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
                                } else {
                                    Swal.fire({
                                        title: 'Please Delete all the related Projects and Tasks to delete customer!',
                                        text: "Cannot Delete Customer.",
                                        icon: 'error',
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

                            }
                        });
                    }
                });
            });
            $(document).on('click', '.verify-btn', function() {
                var employee_id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Are you sure the user is verified?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, verify it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('verify.user', '') }}" + "/" + employee_id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: "Customer has been Verified.",
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
                            },
                            error: function(error) {
                                Swal.fire({
                                    title: 'error!',
                                    text: "Something went to wrong.",
                                    icon: 'error',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ok'
                                })
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
