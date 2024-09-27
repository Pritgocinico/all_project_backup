@extends('admin.layouts.app')

@section('content')
<div class="content-page">
    <div class="content">
        <div class="container">
            <h3>Admin Profile</h3>

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <!-- Profile Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Profile Information</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2">
                            <label for="profile_image" class="form-label">Profile Image:</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="profile_image" name="profile_image"
                                    accept="image/*">
                                <label class="input-group-text" for="profile_image">Choose file</label>
                            </div>

                            <!-- Display existing profile image if available -->
                            @if(Auth::user()->profile_image)
                            <div class="uploaded-image mt-2">
                                <label></label>
                                <img src="{{ asset('/storage/' . Auth::user()->profile_image) }}" alt="Uploaded Image"
                                    class="img-thumbnail" width="50px" id="uploadedImage">
                            </div>
                            @else
                            <div class="existing-profile-image mt-2">
                                <label>No Profile Image Available</label>
                            </div>
                            @endif



                            <div class="form-group mt-3">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ Auth::user()->name }}">
                            </div>

                            <!-- Email -->
                            <div class="form-group mt-3">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ Auth::user()->email }}" required>
                                <div class="invalid-feedback" id="email_error"></div>
                                <!-- Display error message here -->
                            </div>

                            <!-- Phone -->
                            <div class="form-group mt-3">
                                <label for="phone">Phone:</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="{{ Auth::user()->phone }}">
                            </div>

                            <!-- Save Profile Button -->
                            <button type="submit" class="btn btn-primary mt-3" id="validateForm">Save Admin
                                Profile</button>
                    </form>
                </div>
            </div>

            <!-- Change Password Section -->
            <div class="mt-3">
                <div class="card-header">
                    <h4 class="mb-0">Change Password</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.update-password') }}" method="post">
                        @csrf
                        <div class="form-group ">
                            <label for="old_password">Old Password:</label>
                            <input type="password" class="form-control @error('old_password') is-invalid @enderror"
                                id="old_password" name="old_password" required>
                            @error('old_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="form-group mt-3">
                            <label for="new_password">New Password:</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                id="new_password" name="new_password" required>
                            @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Repeat New Password -->
                        <div class="form-group mt-3">
                            <label for="new_password_confirmation">Repeat New Password:</label>
                            <input type="password" class="form-control" id="new_password_confirmation"
                                name="new_password_confirmation" required>
                        </div>

                        <!-- Save Password Button -->
                        <button type="submit" class="btn btn-primary mt-3 mb-2">Save Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function() {
    // Display the selected image instantly
    $('#profile_image').on('change', function() {
        var input = this;
        var url = URL.createObjectURL(input.files[0]);
        $('#uploadedImage').attr('src', url);
        $('.uploaded-image').show();
    });

    // Validate email format
    $('#email').on('input', function() {
        var emailInput = $(this).val();
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(emailInput)) {
            $('#email_error').text('Please enter a valid email address');
            $(this).addClass('is-invalid');
        } else {
            $('#email_error').text('');
            $(this).removeClass('is-invalid');
        }
    });

    // Validate form on submit
    $('#validateForm').on('click', function(event) {
        var emailInput = $('#email').val();
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(emailInput)) {
            $('#email_error').text('Please enter a valid email address');
            $('#email').addClass('is-invalid');
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });
});
</script>

@endsection