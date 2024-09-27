@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="page-header">
            <div>
                <h3>Add New Workshop Role</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="{{ route('header.workshop.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="name">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Enter Name" value="{{ old('name') }}">
                                        @if ($errors->has('name'))
                                        <small class="form-text text-danger">{{ $errors->first('name') }}</small>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile">Mobile Number <span class="text-danger">*</span></label>
                                        <input type="tel" name="mobile" class="form-control" id="mobile"
                                            placeholder="Enter Mobile Number" value="{{ old('mobile') }}">
                                        @if ($errors->has('mobile'))
                                        <small class="form-text text-danger">{{ $errors->first('mobile') }}</small>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email address </label>
                                        <input type="email" name="email" class="form-control" id="email"
                                            placeholder="Enter Email" value="{{ old('email') }}">
                                        @if ($errors->has('email'))
                                        <small class="form-text text-danger">{{ $errors->first('email') }}</small>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control" id="password"
                                            placeholder="Enter Password">
                                        @if ($errors->has('password'))
                                        <small class="form-text text-danger">{{ $errors->first('password') }}</small>
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
                <h3 class="mb-0">Workshop</h3>

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
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workshops as $workshop)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $workshop->name }}</td>
                            <td>{{ $workshop->email }}</td>
                            <td>{{ $workshop->phone }}</td>
                            <td>{{ $workshop->created_at->format('d/m/Y h:i:s') }}</td>
                            <td>
                                <a href="#" class="editWorkshop" data-id="{{ $workshop->id }}"
                                    data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                    <i data-feather="edit"></i>
                                </a>
                                <a href="javascript:void(0);" data-id="{{ $workshop->id }}" class="ms-2 delete-btn"><i
                                        data-feather="trash-2"></i></a>
                            </td>
                        </tr>
                        @endforeach
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
</div>
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Workshop</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action=""
                            method="POST" id="workshopForm" name="workshopForm">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="adminName">Workshop User Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="editworkshopName"
                                    placeholder="Enter Name" value="{{ old('name') }}">
                                <small id="edit_nameError" class="form-text text-danger"></small>
                                @if ($errors->has('name'))
                                <small class="form-text text-danger">{{ $errors->first('name') }}</small>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="adminMobile">Mobile Number <span class="text-danger">*</span></label>
                                <input type="tel" name="mobile" class="form-control" id="editworkshopMobile"
                                    placeholder="Enter Mobile Number" value="{{ old('mobile') }}">
                                <small id="edit_phoneError" class="form-text text-danger"></small>
                                @if ($errors->has('mobile'))
                                <small class="form-text text-danger">{{ $errors->first('mobile') }}</small>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="adminEmail">Email address </label>
                                <input type="email" name="email" class="form-control" id="editworkshopEmail"
                                    placeholder="Enter Email" value="{{ old('email') }}">
                            </div>

                            <div class="form-group">
                                <label for="adminPassword">Password <span class="text-danger">(*Leave blank if don't want to change)</span></label>
                                <input type="password" name="password" class="form-control" id="adminPassword"
                                    placeholder="Enter Password">
                                @if ($errors->has('password'))
                                <small class="form-text text-danger">{{ $errors->first('password') }}</small>
                                @endif
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
        $(document).on('click', '.editWorkshop', function() {
            var id = $(this).data('id');
            $.ajax({
                type: 'GET',
                url: "{{ route('get_workshop_user') }}",
                data: {
                    'id': id
                },
                success: function(data) {
                    var action = "{{route('workshops.update','')}}"+"/"+data.id;
                    $('#workshopForm').attr('action',action);
                    $('#editworkshopName').val(data.name);
                    $('#editworkshopEmail').val(data.email);
                    $('#editworkshopMobile').val(data.phone);
                    $('#customerid_hidden').val(id);
                },
                error: function(data) {
                }
            });
        });
        $(document).on('click', '#updateCustomerBtn', function() {
            var name_value = document.workshopForm.name.value;
            var phone_value = document.workshopForm.mobile.value;
            var nameError = document.getElementById('edit_nameError');
            var phoneError = document.getElementById('edit_phoneError');
            var i = 0;

            if (name_value == "") {
                i++;
                nameError.innerHTML = "Name must be filled out!";
                nameError.style.color = "Red";
                document.workshopForm.name.focus();
            }
            if (phone_value == 0) {
                i++;
                phoneError.innerHTML = "Phone must be filled out!";
                phoneError.style.color = "Red";
                document.workshopForm.role.focus();
            }
            var hiddenvalue = document.getElementById('customerid_hidden').value;
            if (i == 0) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('workshops.update', '') }}" + '/' + hiddenvalue,
                    data: $('#workshopForm').serialize(),
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
                    text: "You won't be able to rever t this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('delete.workshop.user', '') }}" + "/" + employee_id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: "Workshop User has been deleted.",
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
