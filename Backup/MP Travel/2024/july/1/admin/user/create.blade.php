@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">User Create</h1>
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
                                        placeholder="Enter Password">
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
                                        placeholder="Enter Confirm Password" id="confirm_password">
                                    <div class="input-group-append confirm_button_password">
                                        <span class="input-group-text" id="confirm_eye_button"><i
                                                class="bi bi-eye"></i></span>
                                    </div>
                                    @error('confirm_password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
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
                                <div class="col-md-2"><label class="form-label mb-0">Country</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <select class="form-control js-example-basic-single" name="country" id="country"
                                        onchange="countryChange()">
                                        <option value="">Select Country</option>
                                        @foreach ($countryList as $country)
                                            <option value="{{ $country->iso2 }}"
                                                @if (old('country', $user->country ?? '') == $country->iso2) selected @endif>{{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">State</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <select class="form-control js-example-basic-single" name="state" id="state"
                                        onchange="stateChange()"></select>
                                    @error('state')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">City</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <select class="form-control js-example-basic-single" name="city"
                                        id="city"></select>
                                    @error('city')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Zip Code</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="zip_code" id="zip_code" class="form-control"
                                        placeholder="Enter Zip Code"
                                        value="{{ old('zip_code', $user->zip_code ?? '') }}">
                                    @error('zip_code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Address</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <textarea name="address" class="form-control" placeholder="Enter Address">{{ old('address', $user->address ?? '') }}</textarea>
                                    @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Profile Image</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <div class="hstack gap-2 ms-5">
                                        <label for="file_upload" class="btn btn-sm btn-neutral"><span>Upload</span>
                                            <input type="file" name="profile_image" id="file_upload"
                                                class="visually-hidden"
                                                value="{{ old('profile_image', $user->profile_image ?? '') }}">
                                        </label>
                                    </div>
                                    @error('profile_image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                            <hr class="my-6">
                            <div class="d-flexjustify-content-end gap-2">
                                <button type="button" class="btn btn-sm btn-neutral">Cancel</button>
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
        var oldCountry = "{{ old('country', $user->country ?? '') }}";
        var oldState = "{{ old('state', $user->state ?? '') }}";
        var oldCity = "{{ old('city', $user->city ?? '') }}";
        $(document).ready(function(e) {
            $('#country').select2();
            if (oldCountry !== "") {
                countryChange();
            }
            if (oldState !== "") {
                stateChange();
            }
        })

        function countryChange() {
            var country = $('#country').val();
            $.ajax({
                method: 'get',
                url: "{{ route('state-by-country') }}",
                data: {
                    country_code: country,
                },
                success: function(res) {
                    var html = "<option value=>Select State</option>";
                    $.each(res.data, function(i, v) {
                        var select = "";
                        if (oldState == v.id) {
                            select = "selected";
                        }
                        html += "<option value='" + v.id + "'" + select + ">" + v.name + "</option>"
                    })
                    $('#state').html("")
                    $('#state').html(html)
                    $('#state').select2();
                }
            })
        }

        function stateChange() {
            var state = $('#state').val();
            if (state == null) {
                state = oldState;
            }
            var country = $('#country').val();
            $.ajax({
                method: 'get',
                url: "{{ route('city-by-state') }}",
                data: {
                    country_code: country,
                    state: state,
                },
                success: function(res) {
                    var html = "<option value=>Select City</option>";
                    $.each(res.data, function(i, v) {
                        var select = "";
                        if (oldCity == v.id) {
                            select = "selected";
                        }
                        html += "<option value='" + v.id + "'" + select + ">" + v.name + "</option>"
                    })
                    $('#city').html("")
                    $('#city').html(html)
                    $('#city').select2();
                }
            })
        }
    </script>
@endsection
