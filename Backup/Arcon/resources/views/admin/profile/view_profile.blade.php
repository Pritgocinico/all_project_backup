@extends('admin.layouts.app')

@section('content')
    <div class="card mb-2 p-3">
        <div class="card-body">
            <div class="d-md-flex gap-4 align-items-center">
                <h4 class="mb-0">Hello, {{Auth::user()->name}} </h4>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6 mb-2">
            <div class="card p-2">
                <div class="card-header">
                    <h5 class="mb-0">My Profile</h5>
                </div>
                <div class="card-body text-center mt-3">
                    <?php $image=URL::asset("settings/".Auth::user()->image); ?>
                    @if (!blank(Auth::user()->image))
                        <img id="image" class="br-50" src="{{ $image }}"
                         alt="" style="height: 100px; width:100px;">
                    @else
                        <img id="image" class="br-50" src="{{ URL::asset('assets/user-profile.png') }}"
                        alt="" style="height: 100px; width:100px;">
                    @endif
                    
                    <div class="my-2">
                        <h4>{{Auth::user()->name}}</h4>
                        @if(!blank(Auth::user()->email))<h6><i class="bi bi-envelope"></i><a href="mailto:{{Auth::user()->email}}"> {{Auth::user()->email}} </a></h6>@endif
                        @if(!blank(Auth::user()->phone))<h6><i class="bi bi-phone"></i>{{Auth::user()->phone}} </h6>@endif
                        <a href="{{ route('edit.profile') }}" class="btn btn-primary mt-2">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card p-2">
                <div class="card-header">
                    <h5 class="mb-0">Last Login Time</h5>
                </div>
                <div class="card-body">
                    <div class="text-center m-5">
                        <h4 class="houmanity-color">{{ Auth::user()->last_login_at }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
