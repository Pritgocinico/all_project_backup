@extends('admin.layouts.app')

@section('content')
<div class="card mb-2 p-3">
    <div class="card-body">
        <div class="d-md-flex gap-4 align-items-center">
            <h4 class="mb-0">Edit Agent</h4>
            <div class="ms-auto">
                <a href="javascript:void(0);" onclick="window.history.go(-1); return false;" class="btn btn-primary ustify-content-end float-right">
                    Go Back
                </a>
            </div>
        </div>
    </div>
</div>
<div class="card p-3">
    <div class="card-body">
        @if(Session::has('alert'))
            <p class="alert
            {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('alert') }}</p>
        @endif
        <form action="{{ route('admin.edit.agent') }}" method="POST" class="row g-3">
        @csrf
            <div class="col-md-6">
                <label for="Name" class="form-label">Agent Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="Name" placeholder="Agent Code" value="{{$user->name}}">
                @if ($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="AgentName" class="form-label">Agent Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="agent_name" id="AgentName" placeholder="User Name" value="{{$user->agent_name}}">
                @if ($errors->has('agent_name'))
                    <span class="text-danger">{{ $errors->first('agent_name') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="Phone" class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" name="phone" id="Phone" placeholder="User phone" value="{{$user->phone}}">
                @if ($errors->has('phone'))
                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="Email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="User Email Address" value="{{$user->email}}">
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="HeadQuarter" class="form-label">Headquarter <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="headquarter" id="HeadQuarter" placeholder="Head Quarter" value="{{$user->headquarter}}">
                @if ($errors->has('headquarter'))
                    <span class="text-danger">{{ $errors->first('headquarter') }}</span>
                @endif
            </div>
            <input type="hidden" name="role" value="3">
            <!--<div class="col-md-6">-->
            <!--    <label for="GST" class="form-label">Gst Number <span class="text-danger">*</span></label>-->
            <!--    <input type="text" class="form-control" name="gst_number" id="GST" placeholder="GST Number" value="{{$user->gst_number}}">-->
            <!--    @if ($errors->has('gst_number'))-->
            <!--        <span class="text-danger">{{ $errors->first('gst_number') }}</span>-->
            <!--    @endif-->
            <!--</div>-->
            <div class="col-md-6">
                <label for="password" class="form-label">Password <span class="text-danger">(Leave blank if don't want to change)</span></label>
                <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" placeholder=" " >
                    <span class="input-group-btn"><button type="button" class="btn btn-primary btn-lg getNewPass" style="border-radius:unset !important;" onclick="showPassword()"><span class="bi bi-eye"></span></button></span>
                </div>
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="Status" class="form-label">Status <span class="text-danger">*</span></label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckChecked" @if($user->status==1) checked @endif>
                    <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                </div>
            </div>
            <div class="col-12">
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')
    <script>
        function showPassword() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
@endsection
