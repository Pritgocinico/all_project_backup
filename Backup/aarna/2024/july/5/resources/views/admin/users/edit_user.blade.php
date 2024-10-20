@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
<div class="row">
    <!-- Zero Configuration  Starts-->
    <div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.update.user', ['id' => $user->id])}}" method="post" class="row g-3">
                @csrf
                <div class="col-md-6 form-floating mt-4">
                    <input type="text" class="form-control" name="first_name" id="FirstName" value="{{ old('first_name', $user->first_name) }}" placeholder="" autofocus />
                    <label for="FirstName" class="form-label">First Name (required)</label>
                    @if ($errors->has('first_name'))
                        <span class="text-danger">{{ $errors->first('first_name') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="text" class="form-control" name="last_name" id="LastName" value="{{ old('last_name', $user->last_name) }}" placeholder="" autofocus />
                    <label for="LastName" class="form-label">Last Name (required)</label>
                    @if ($errors->has('last_name'))
                        <span class="text-danger">{{ $errors->first('last_name') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="tel" class="form-control" name="phone" id="Phone" value="{{ old('phone', $user->phone) }}" placeholder="" />
                    <label for="Phone" class="form-label">User Phone Number (required)</label>
                    @if ($errors->has('phone'))
                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="tel" class="form-control" name="mobile" id="Phone" value="{{ old('mobile', $user->mobile) }}" placeholder="" />
                    <label for="Mobile" class="form-label">Other Phone Number</label>
                    @if ($errors->has('mobile'))
                        <span class="text-danger">{{ $errors->first('mobile') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-floating mt-4">
                   <input type="email" class="form-control" name="email" id="email" value="{{ old('email', $user->email) }}" placeholder="" />
                    <label for="email" class="form-label">User Email Address (required)</label>
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input class="form-control" id="password" name="password" placeholder=""  />
                    <label for="password" class="form-label">Password (Leave blank if don't want to change)</label>
                    @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
                </div>
                <div class="col-md-6 mt-4">
                    <select name="user_type" class="form-control" id="UserType">
                        <option value="1" @if($user->role == 1) selected @endif>Admin</option>
                        <option value="2" @if($user->role == 2) selected @endif>Staff</option>
                    </select>
                </div>
                <div class="col-md-6  mt-4">
                    <input type="checkbox" id="teamLead" name="team_lead" placeholder="" @if($user->team_lead == 1) checked @endif />
                    <label for="teamLead">Team Lead</label>
                </div>
                <div class="col-md-6 ms-3">
                    <label for="Status" class="">
                        Status <span class="text-danger">*</span>
                    </label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" @if($user->status == 1) checked @endif id="flexSwitchCheckChecked" />
                        <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                    </div>
                </div>
                <div class="col-12">
                    <input type="hidden" name="user_id" value="{{$user->id}}">
                    <button type="submit" class="btn btn-primary">
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
