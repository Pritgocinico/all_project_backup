@extends('purchase.layouts.app')

@section('content')

<div class="card mb-2">
    <div class="card-body">
        <div class="d-md-flex gap-4 align-items-center">
            <h4 class="mb-0">Edit Profile</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body m-2">
                @if(Session::has('alert'))
                    <p class="alert
                    {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('alert') }}</p>
                @endif
                <form action="{{ route('purchase_save.profile') }}" method="POST" class="row g-3" enctype="multipart/form-data">
                @csrf
                    <input type="hidden" name="hidden_id" value="{{ $user->id }}">
                    <div class="col-md-6">
                        <label for="image" class="form-label">Profile Image</label>
                        <input class="form-control" type="file" id="image" name="image">
                        @if ($errors->has('image'))
                            <span class="text-danger">{{ $errors->first('image') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 mb-2">
                        <?php

                        if(!empty($user->image)){
                            $image=URL::asset("public/settings/".$user->image);
                        }else{
                            $image = URL::asset('public/settings/user-profile.png');
                        }
                        ?>
                            <input type="hidden" name="uploded_image" value="{{ $user->image }}">
                            <img id="preview-image-before-upload" class="br-50" src="{{ $image }}"
                            alt="" style="height: 100px; width:100px;">
                    </div>
                    <div class="col-12">
                        <label for="Name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="Name" placeholder="Name" value="{{ $user->name }}">
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('department_name') }}</span>
                        @endif
                    </div>
                    <div class="col-12">
                        <label for="Email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" id="Email" placeholder="Email" value="{{ $user->email }}">
                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="col-12">
                        <label for="Phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" id="Phone" placeholder="Phone Number" value="{{ $user->phone }}">
                        @if ($errors->has('phone'))
                            <span class="text-danger">{{ $errors->first('phone') }}</span>
                        @endif
                    </div>
                    {{-- <div class="col-12">
                        <label for="Phone" class="form-label">Practice Name</label>
                        <input type="text" class="form-control" name="practice_name" id="practice_name" placeholder="Practice Name" value="{{ $user->practice_name }}">
                        @if ($errors->has('practice_name'))
                            <span class="text-danger">{{ $errors->first('practice_name') }}</span>
                        @endif
                    </div> --}}
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-body m-2">
                <h5>Change Password</h5>
                <form id="change-password-form" name="change-password-form" action="{{ route('purchase.change.password') }}" method="POST">
                    @csrf
                    <div class="col-md-12">
                        <div class="form-group mb-2">
                            <label class="form-label">Old Password <span class="text-danger">*</span></label>
                            <input type="password" id="current_password" name="current_password" placeholder="" class="form-control" value="{{old('current_password')}}">
                            @if ($errors->has('current_password'))
                                <span class="text-danger">{{ $errors->first('current_password') }}</span>
                            @endif
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-label">New Password <span class="text-danger">*</span></label>
                            <input type="password" id="new_password" name="new_password" placeholder="" class="form-control" value="{{old('new_password')}}">
                            @if ($errors->has('new_password'))
                                <span class="text-danger">{{ $errors->first('new_password') }}</span>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Repeat New Password <span class="text-danger">*</span></label>
                            <input type="password" id="new_confirm_password" name="new_confirm_password" placeholder="" class="form-control" value="{{old('new_confirm_password')}}">
                            @if ($errors->has('new_confirm_password'))
                                <span class="text-danger">{{ $errors->first('new_confirm_password') }}</span>
                            @endif
                        </div>
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <button type="submit" class="btn btn-primary"> Change Password </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function (e) {

        $('#image').change(function(){
        let reader = new FileReader();
        reader.onload = (e) => {
            $('#preview-image-before-upload').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
        });

    });
</script>
@endsection
