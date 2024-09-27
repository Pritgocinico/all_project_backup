@extends('admin.layouts.app')

@section('content')
<div class="content-page">
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <h3 class="mb-0">Edit User</h3>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ms-auto">Go Back</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="post" id="editUserForm">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                value="{{ $user->first_name }}" required>
                            <span class="text-danger" id="first_name_error"></span>
                        </div>
                        <div class="form-group mb-3">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                value="{{ $user->last_name }}" required>
                            <span class="text-danger" id="last_name_error"></span>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}"
                                required>
                            <span class="text-danger" id="email_error"></span>
                        </div>
                        <div class="form-group mb-3">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}"
                                required>
                            <span class="text-danger" id="phone_error"></span>
                        </div>
                        <div class="form-group mb-3">
                            <label for="address">Role Name</label>
                            <input type="text" class="form-control" id="role_name" name="role_name"
                                value="{{ $user->role_name }}" required>
                            <span class="text-danger" id="address_error"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <span class="text-danger" id="password_error"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label>Status</label>
                            <div class="switch-container">
                                <input type="hidden" name="status" value="inactive">
                                <!-- Hidden input to hold the selected value -->
                                <label class="switch">
                                    <input type="checkbox" class="status-switch" id="status_switch" name="status"
                                        value="active" checked>
                                    <span class="slider round"></span>
                                </label>
                                <span class="status-label"></span>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <h4>Permission List</h4>
                            <div class="row">
                            @foreach ($permissions as $permission)
                                <div class="col-md-4">
                                    <input type="checkbox" name="permission[]"
                                        @php
                                            $userPermission = $userPermissions->firstWhere('permission_id', $permission->id);
                                        @endphp
                                        @if($userPermission?->status == 1) checked @endif
                                        value="{{ $permission->id }}">
                                    {{ $permission->permission_name }}
                                </div>
                            @endforeach

                            @if ($errors->has('permission'))
                                <div class="error">{{ $errors->first('permission') }}</div>
                            @endif
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" id="validateForm">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function() {
    $('#validateForm').on('click', function() {
        // Reset previous error messages
        $('.text-danger').text('');

        // Basic validation example
        var isValid = true;

        // Check if the required fields are not empty
        $('input[type="text"], input[type="email"]').each(function() {
            if ($(this).val() === '') {
                isValid = false;
                $(this).addClass('is-invalid');
                $('#' + $(this).attr('id') + '_error').text('This field is required.');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        // Validate phone number length
        var phone = $('#phone').val();
        if (phone.length !== 10) {
            isValid = false;
            $('#phone').addClass('is-invalid');
            $('#phone_error').text('Phone number must be 10 digits.');
        }

        // Additional validation logic can be added as needed

        // Submit the form if valid, otherwise show an alert

    });
});
</script>
@endsection
