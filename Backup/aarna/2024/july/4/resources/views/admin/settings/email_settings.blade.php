@extends('admin.layouts.setting')
@section('tabs')
<div class="row">
    <div class="col-md-12">
        @if(Session::has('alert'))
            <p class="alert
            {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('alert') }}</p>
        @endif
        <form action="{{ route('save_email_settings') }}" method="POST" class="row g-3" enctype="multipart/form-data">
           @csrf
            <div class="col-12">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="" name="admin_email" placeholder=" " value="{{$settings->admin_email}}">
                @if ($errors->has('admin_email'))
                    <span class="text-danger">{{ $errors->first('admin_email') }}</span>
                @endif
            </div>
            <div class="col-12">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="" name="admin_password" placeholder=" " value="{{$settings->admin_password}}">
                @if ($errors->has('admin_password'))
                    <span class="text-danger">{{ $errors->first('admin_password') }}</span>
                @endif
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
