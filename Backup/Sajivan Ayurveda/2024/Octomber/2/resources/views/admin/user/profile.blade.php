@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <img src="{{ asset('storage/' . $user->profile_image) }}" class="img-fluid rounded-top-start-4"
                            style="height: 100px !important" alt="...">
                    </div>
                    <div class="col">
                        <h1 class="ls-tight">{{ $user->name }}
                        </h1>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('update.profile') }}">
                @csrf
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                    <div class="col-md-4 col-xl-4">
                        <input type="text" name="name" class="form-control" placeholder="Enter Name"
                            value="{{ $user->name }}">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Email</label></div>
                    <div class="col-md-4 col-xl-4">
                        <input type="text" name="email" class="form-control" placeholder="Enter Email"
                            value="{{ $user->email }}">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Phone Number</label></div>
                    <div class="col-md-4 col-xl-4">
                        <input type="text" name="phone_number" class="form-control" placeholder="Enter Phone Number"
                            value="{{ $user->phone_number }}">
                        @error('phone_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Role</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ isset($user->roleDetail) ? $user->roleDetail->name : '-' }}
                    </div>
                </div>
                <hr class="my-6" />
                <h4>Change Password</h4>
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">New Password</label></div>
                    <div class="col-md-4 col-xl-4">
                        <div class="input-group">
                            <input type="password" name="new_password" class="form-control" id="new_password"
                                placeholder="Enter New Password" value="{{ old('new_password') }}">
                            <div class="input-group-append confirm_button_password">
                                <span class="input-group-text password_eye_button" id="confirm_eye_button"><i
                                        class="fa fa-eye"></i></span>
                            </div>
                        </div>
                        @error('new_password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">New Confirm Password</label></div>
                    <div class="col-md-4 col-xl-4">
                        <div class="input-group">
                            <input type="password" name="new_confirm_password" class="form-control"
                                id="new_confirm_password" placeholder="Enter New Password"
                                value="{{ old('new_confirm_password') }}">
                            <div class="input-group-append confirm_new_button_password">
                                <span class="input-group-text password_eye_button" id="confirm_new_eye_button"><i
                                        class="fa fa-eye"></i></span>
                            </div>
                        </div>
                        @error('new_confirm_password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <hr class="my-6">
                <div class="d-flex justify-content-start gap-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-neutral">Cancel</a>
                    <button type="submit" class="btn btn-sm btn-dark">Save</button>
                </div>
            </form>
        </main>
    </div>
@endsection
@section('script')
    <script>
        $(".confirm_button_password").click(function() {
            var input = $("#new_password");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
                $('#confirm_eye_button').html('<i class="fa fa-eye-slash"></i>');
            } else {
                input.attr("type", "password");
                $('#confirm_eye_button').html('<i class="fa fa-eye"></i>');
            }
        });
        $(".confirm_new_button_password").click(function() {
            var input = $("#new_confirm_password");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
                $('#confirm_new_eye_button').html('<i class="fa fa-eye-slash"></i>');
            } else {
                input.attr("type", "password");
                $('#confirm_new_eye_button').html('<i class="fa fa-eye"></i>');
            }
        });
    </script>
@endsection