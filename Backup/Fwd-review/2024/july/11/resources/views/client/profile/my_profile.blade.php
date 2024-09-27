@extends('admin.layouts.app')

@section('content')
<div class="gc_row px-md-4 px-2">
    <div class="card mt-md-3 mb-3">
        <div class="card-body d-flex align-items-center p-lg-3 p-2 staff_header">
            <div class="pe-4 fs-5">My Profile</div>
            <div class="ms-auto">
                <a href="{{route('client.dashboard')}}" class="btn gc_btn">Go Back</a>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                 <div class="pe-4 fs-5 mb-3">Accout Details </div>
                    <div class="mt-4">
                        <label class="form-label">User Name </label>
                        <h6>{{ $user->name }}</h6>
                    </div>
                    <div class="mt-4">
                        <label class="form-label">Email </label>
                        <h6>{{ $user->email }}</h6>
                    </div>
                    <div class="mt-4">
                        <label class="form-label">Phone </label>
                        <h6>{{ $user->phone }}</h6>
                    </div>
                </div>
                <div class="col-md-6">
                <div class="pe-4 fs-5 mb-3">Change Password</div>
                @if($errors->any())
                {!! implode('', $errors->all('<div style="color:red">:message</div>')) !!}
                @endif
                @if(Session::get('error') && Session::get('error') != null)
                <div style="color:red">{{ Session::get('error') }}</div>
                @php
                Session::put('error', null)
                @endphp
                @endif
                @if(Session::get('success') && Session::get('success') != null)
                <div style="color:green">{{ Session::get('success') }}</div>
                @php
                Session::put('success', null)
                @endphp
                @endif
                <form action="{{ route('client.update.password') }}" method="post" class="row g-3 mt-4">
                    @csrf
                    <div class="form-floating mt-4">
                        <input type="password" class="form-control" name="old_password" id="old_password" value="{{ old('old_password') }}" placeholder="" autofocus />
                        <label for="old_password" class="form-label">Old Password (required)</label>
                        @if ($errors->has('old_password'))
                            <span class="text-danger">{{ $errors->first('old_password') }}</span>
                        @endif
                    </div>
                    <div class="form-floating mt-4">
                        <input type="password" class="form-control" name="password" id="password" value="{{ old('password') }}" placeholder="" />
                        <label for="password" class="form-label">New Password (required)</label>
                        @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="form-floating mt-4">
                        <input type="password" class="form-control" name="password_confirmation" id="c_password" value="{{ old('password_confirmation') }}" placeholder="" />
                        <label for="c_password" class="form-label">Confirm Password (required)</label>
                        @if ($errors->has('password_confirmation'))
                            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn gc_btn mt-3">
                            Submit
                        </button>
                    </div>
                </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection