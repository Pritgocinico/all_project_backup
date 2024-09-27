@extends('admin.layouts.app')

@section('content')
<div class="gc_row px-md-4 px-2">
    <div class="card mt-md-3 mb-3">
        <div class="card-body d-flex align-items-center p-lg-3 p-2 staff_header">
            <div class="pe-4 fs-5">Edit profile</div>
            <div class="ms-auto">
                <a href="{{route('admin.dashboard')}}" class="btn gc_btn">Go Back</a>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.update.profile')}}" method="post" class="row g-3">
                @csrf
                <div class="col-md-6 form-floating mt-4">
                    <input type="text" class="form-control" name="name" id="Name" value="{{$client->name}}" placeholder="" autofocus />
                    <label for="Name" class="form-label">User Name (required)</label>
                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="email" class="form-control" name="email" id="email" value="{{$client->email}}" placeholder="" />
                    <label for="email" class="form-label">User Email Address (required)</label>
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="tel" class="form-control" name="phone" id="Phone" value="{{$client->phone}}" placeholder="" />
                    <label for="Phone" class="form-label">User Phone Number (required)</label>
                    @if ($errors->has('phone'))
                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input class="form-control" id="password" name="password" placeholder=""  />
                    <label for="password" class="form-label">Password (Leave blank if don't want to change)</label>
                    @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
                </div>
                <div class="col-md-6">
                    <label for="Status" class="">
                        Status <span class="text-danger">*</span>
                    </label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" @if($client->status == 1) checked @endif id="flexSwitchCheckChecked" />
                        <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                    </div>
                </div>
                <div class="col-12">
                    <input type="hidden" name="user_id" value="{{$client->id}}">
                    <button type="submit" class="btn gc_btn mt-3">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
