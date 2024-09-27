@extends('admin.layouts.app')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0">Add user</h3>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ms-auto">Go Back</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <form action="{{ route('admin.users.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="first_name">First Name:</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                                @if ($errors->has('first_name'))
                                    <div class="error">{{ $errors->first('first_name') }}</div>
                                @endif
                                <span class="text-danger" id="first_name_error"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="last_name">Last Name:</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                                @if ($errors->has('last_name'))
                                    <div class="error">{{ $errors->first('last_name') }}</div>
                                @endif
                                <span class="text-danger" id="last_name_error"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                @if ($errors->has('email'))
                                    <div class="error">{{ $errors->first('email') }}</div>
                                @endif
                                <span class="text-danger" id="email_error"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="phone">Phone:</label>
                                <input type="text" class="form-control" id="phone" name="phone" required
                                    maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                @if ($errors->has('phone'))
                                    <div class="error">{{ $errors->first('phone') }}</div>
                                @endif
                                <span class="text-danger" id="phone_error"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="address">Address:</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                                @if ($errors->has('address'))
                                    <div class="error">{{ $errors->first('address') }}</div>
                                @endif
                                <span class="text-danger" id="address_error"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                @if ($errors->has('password'))
                                    <div class="error">{{ $errors->first('password') }}</div>
                                @endif
                                <span class="text-danger" id="password_error"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Status:</label>
                                <div class="switch-container">
                                    <input type="hidden" name="status" value="inactive">
                                    <!-- Hidden input to hold the selected value -->
                                    @if ($errors->has('status'))
                                        <div class="error">{{ $errors->first('status') }}</div>
                                    @endif
                                    <label class="switch">
                                        <input type="checkbox" class="status-switch" id="status_switch" name="status"
                                            value="active" checked>
                                        <span class="slider round"></span>
                                    </label>
                                    <span class="status-label"></span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" id="validateForm">Save User</button>
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
                $('input[type="text"], input[type="email"], input[type="password"]').each(function() {
                    if ($(this).val() === '') {
                        isValid = false;
                        $(this).addClass('is-invalid');
                        $(this).next('.text-danger').text('This field is required.');
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

                // Display error messages
                if (!isValid) {
                    $('#errorMessages').html(
                        '<div class="alert alert-danger">Please fix the errors in the form.</div>');
                } else {
                    $('#errorMessages').html(''); // Clear error messages if the form is valid
                    // Submit the form if valid, otherwise show an alert
                }
            });
        });
    </script>
@endsection
