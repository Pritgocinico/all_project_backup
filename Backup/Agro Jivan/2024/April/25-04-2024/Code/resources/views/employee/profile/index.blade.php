@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid">
                <div id="kt_app_content_container" class="app-container  container-fluid">
                    <form class="form" action="{{ route('update-profile') }}" method="post" enctype="multipart/form-data" onsubmit="return updateEmployeeValidate()">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-4">
                                <label class="required fs-6 fw-semibold mb-2">Profile Image</label>
                                <input type="file" class="form-control mb-3 mb-lg-0"
                                    placeholder="Enter Name" name="profile_image" value="" id="profile_image">
                                <span class="text-danger"
                                    id="name_error">{{ $errors->getBag('default')->first('profile_image') }}</span>
                            </div>
                            <div class="col-md-2">
                                @php
                                     $image = asset('public/assets/media/avatars/300-2.jpg'); @endphp
                                
                                @if(Auth()->user()->profile_image !== null)
                                    @php $image = asset('public/assets/upload/'.Auth()->user()->profile_image); @endphp
                                @endif
                                <img id="preview-image-before-upload" class="br-50" src="{{$image}}" alt="" style="height: 100px; width:100px;">
                            </div>
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Name</label>
                                <input type="text" class="form-control mb-3 mb-lg-0"
                                    placeholder="Enter Name" name="name" value="{{ Auth()->user()->name }}" id="name">
                                <span class="text-danger"
                                    id="name_error">{{ $errors->getBag('default')->first('name') }}</span>
                            </div>
                            <div class="col-md-6">
                                <label class="fs-6 fw-semibold mb-2">Email</label>
                                <input type="email" class="form-control" placeholder="Enter Email" name="email"
                                    value="{{ Auth()->user()->email }}" id="email">
                                <span class="text-danger"
                                    id="email_error">{{ $errors->getBag('default')->first('email') }}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Phone Number</label>
                                <input type="number" class="form-control" placeholder="Enter Phone Number"
                                    name="phone_number" value="{{ Auth()->user()->phone_number }}" id="phone_number">
                                <span class="text-danger"
                                    id="phone_number_error">{{ $errors->getBag('default')->first('phone_number') }}</span>
                            </div>
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Password</label>
                                <input type="password" class="form-control" placeholder="Enter Password" name="password"
                                    value="{{ old('password') }}" id="password">
                                <span class="text-danger"
                                    id="password_error">{{ $errors->getBag('default')->first('password') }}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Aadhar Card</label>
                                <input type="file" class="form-control" name="aadhar_card"
                                    value="{{ old('aadhar_card') }}" id="aadhar_card">
                                <span class="text-danger"
                                    id="aadhar_card_error">{{ $errors->getBag('default')->first('aadhar_card') }}</span>
                            </div>
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Pan Card</label>
                                <input type="file" class="form-control" name="pan_card" value="{{ old('pan_card') }}"
                                    id="pan_card">
                                <span class="text-danger"
                                    id="pan_card_error">{{ $errors->getBag('default')->first('pan_card') }}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Qualification</label>
                                <input type="file" class="form-control" name="qualification"
                                    value="{{ old('qualification') }}" id="qualification">
                                <span class="text-danger"
                                    id="qualification_error">{{ $errors->getBag('default')->first('qualification') }}</span>
                            </div>
                            <div class="col-md-6">
                                <label for="Status" class="required fs-6 fw-semibold mb-2">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status"
                                        id="flexSwitchCheckChecked" @if(Auth()->user()->status == 1) {{'checked'}} @endif>
                                    <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Role</label>
                                <select name="role" id="role" class="form-select">
                                    <option value="">Select Role</option>
                                    @foreach ($roleList as $role)
                                        <option value="{{ $role->id }}"
                                            @if (Auth()->user()->role_id == $role->id) selected @endif>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger"
                                    id="role_error">{{ $errors->getBag('default')->first('role') }}</span>
                            </div>

                        </div>
                        <div class="mt-2">
                            <button type="submit" name="Update" value="Update" class="btn btn-primary">Update</button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection
@section('page')
    <script src="{{ asset('public\assets\js\custom\admin\employee.js') }}?{{time()}}"></script>
@endsection
