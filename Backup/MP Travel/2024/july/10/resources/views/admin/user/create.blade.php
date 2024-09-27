@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Employee {{isset($user) ? 'Update' : 'Create'}}</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    @php
                        $route = route('user.store');
                        $method = 'POST';
                        if (isset($user)) {
                            $route = route('user.update', $user->id);
                            $method = 'PUT';
                        }
                    @endphp
                    <form action="{{ $route }}" enctype="multipart/form-data" method="POST">
                        @method($method)
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id ?? '' }}">
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="name" class="form-control" placeholder="Enter Name"
                                    value="{{ old('name', $user->name ?? '') }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Email</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="email" class="form-control" placeholder="Enter Email"
                                    value="{{ old('email', $user->email ?? '') }}">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Password</label></div>
                            <div class="col-md-4 col-xl-4">
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control" id="password"
                                        placeholder="Enter Password" value="{{ old('password') }}">
                                    <div class="input-group-append login_button_password">
                                        <span class="input-group-text" id="password_eye_button"><i
                                                class="bi bi-eye"></i></span>
                                    </div>
                                </div>
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Confirm Password</label></div>
                            <div class="col-md-4 col-xl-4">
                                <div class="input-group">
                                    <input type="password" name="confirm_password" class="form-control"
                                        placeholder="Enter Confirm Password" id="confirm_password" value="{{ old('confirm_password') }}">
                                    <div class="input-group-append confirm_button_password">
                                        <span class="input-group-text" id="confirm_eye_button"><i
                                                class="bi bi-eye"></i></span>
                                    </div>
                                </div>
                                @error('confirm_password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Phone Number</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="phone_number" class="form-control"
                                        placeholder="Enter Phone Number"
                                        value="{{ old('phone_number', $user->phone_number ?? '') }}">
                                    @error('phone_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Role</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <select name="role" class="form-control" id="role">
                                        <option value="">Select Role</option>
                                        @foreach ($roleList as $role)
                                            <option value="{{ $role->id }}"
                                                @if (old('role', $user->role_id ?? '') == $role->id) selected @endif>
                                                {{ Str::ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Department</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <select name="department" class="form-control" id="department" onchange="getDesignation()">
                                        <option value="">Select Department</option>
                                        @foreach ($departmentList as $department)
                                            <option value="{{ $department->id }}"
                                                @if (old('department', $user->department_id ?? '') == $department->id) selected @endif>
                                                {{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Designation</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <select name="designation" class="form-control" id="designation"></select>
                                    @error('designation')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Profile Image</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <div class="hstack gap-2 ms-5">
                                        <label for="file_upload" class="btn btn-sm btn-neutral"><span>Upload</span>
                                            <input type="file" name="profile_image" id="file_upload"
                                                class="visually-hidden profile_image"
                                                value="{{ old('profile_image', $user->profile_image ?? '') }}">
                                        </label>
                                    </div>
                                    <div id="profile_image_preview"></div>
                                    @error('profile_image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Aadhar Card Image</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <div class="hstack gap-2 ms-5">
                                        <label for="aadhar_card" class="btn btn-sm btn-neutral"><span>Upload</span>
                                            <input type="file" name="aadhar_card" id="aadhar_card"
                                                class="visually-hidden aadhar_card"
                                                value="{{ old('aadhar_card', $user->aadhar_card ?? '') }}">
                                        </label>
                                    </div>
                                    <div id="aadhar_card_preview"></div>
                                    @error('aadhar_card')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Pan Card</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <div class="hstack gap-2 ms-5">
                                        <label for="pan_card" class="btn btn-sm btn-neutral"><span>Upload</span>
                                            <input type="file" name="pan_card" id="pan_card" class="visually-hidden pan_card"
                                                value="{{ old('pan_card', $user->pan_card ?? '') }}">
                                        </label>
                                    </div>
                                    <div id="pan_card_preview"></div>
                                    @error('pan_card')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Agreement</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <div class="hstack gap-2 ms-5">
                                        <label for="user_agreement" class="btn btn-sm btn-neutral"><span>Upload</span>
                                            <input type="file" name="user_agreement" id="user_agreement"
                                                class="visually-hidden user_agreement"
                                                value="{{ old('user_agreement', $user->user_agreement ?? '') }}">
                                        </label>
                                    </div>
                                    <div id="user_agreement_preview"></div>
                                    @error('user_agreement')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <hr class="my-6">
                            <div class="d-flexjustify-content-end gap-2">
                                <a href="{{ route('user.index') }}"  class="btn btn-sm btn-neutral">Cancel</a>
                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                            </div>
                </main>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script>
        $(".login_button_password").click(function() {
            var input = $("#password");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
                $('#password_eye_button').html('<i class="bi bi-eye-slash"></i>');
            } else {
                input.attr("type", "password");
                $('#password_eye_button').html('<i class="bi bi-eye"></i>');
            }
        });
        $(".confirm_button_password").click(function() {
            var input = $("#confirm_password");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
                $('#confirm_eye_button').html('<i class="bi bi-eye-slash"></i>');
            } else {
                input.attr("type", "password");
                $('#confirm_eye_button').html('<i class="bi bi-eye"></i>');
            }
        });
        var oldDepartment = "{{ old('department', $user->department_id ?? '') }}";
        var oldDesignation = "{{ old('designation', $user->designation_id ?? '') }}";
        $(document).ready(function(e) {
            $('#department').select2();
            if (oldDepartment !== "") {
                getDesignation();
            }
        })
        $('.profile_image').on('change', function() {
            var fileName = $(this).val();
            var ext = fileName.split('.').pop();
            let substringToRemove = "C:\\fakepath\\";
            let resultString = fileName.replace(substringToRemove, "");
            $('#profile_image_preview').html(resultString);

        })
        $('.aadhar_card').on('change', function() {
            var fileName = $(this).val();
            var ext = fileName.split('.').pop();
            let substringToRemove = "C:\\fakepath\\";
            let resultString = fileName.replace(substringToRemove, "");
            $('#aadhar_card_preview').html(resultString);

        })
        $('.pan_card').on('change', function() {
            var fileName = $(this).val();
            var ext = fileName.split('.').pop();
            let substringToRemove = "C:\\fakepath\\";
            let resultString = fileName.replace(substringToRemove, "");
            $('#pan_card_preview').html(resultString);

        })
        $('.user_agreement').on('change', function() {
            var fileName = $(this).val();
            var ext = fileName.split('.').pop();
            let substringToRemove = "C:\\fakepath\\";
            let resultString = fileName.replace(substringToRemove, "");
            $('#user_agreement_preview').html(resultString);

        })
        function getDesignation(){
            var department = $('#department').val();
            if (department == null) {
                department = oldDepartment;
            }
            $.ajax({
                method: 'get',
                url: "{{ route('designation-by-department') }}",
                data: {
                    department: department,
                },
                success: function(res) {
                    var html = "<option value=>Select Designation</option>";
                    $.each(res, function(i, v) {
                        var select = "";
                        if (oldDesignation == v.id) {
                            select = "selected";
                        }
                        html += "<option value='" + v.id + "'" + select + ">" + v.name + "</option>"
                    })
                    $('#designation').html("")
                    $('#designation').html(html)
                    $('#designation').select2();
                }
            });
        }
    </script>
@endsection
