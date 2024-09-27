@extends('admin.layouts.app')

@section('content')
    <div class="card mb-2 p-3">
        <div class="card-body">
            <div class="d-md-flex gap-4 d-flex justify-content-between">
                <h4 class="mb-0">Hello, {{Auth::user()->name}} </h4>
                <h4 class="houmanity-color">Last Login Time: {{ Auth::user()->last_login_at }}</h4>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-5 mb-2">
            <div class="card p-2">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0">My Profile</h5>
                    <a href="{{ route('edit.profile') }}" class="btn btn-primary mt-2">Edit Profile</a>
                </div>
                <div class="card-body mt-3 d-flex">
                    <?php $image=URL::asset("settings/".Auth::user()->image); ?>
                    @if (!blank(Auth::user()->image))
                        <img id="image" class="br-50" src="{{ $image }}"
                         alt="" style="height: 100px; width:100px;">
                    @else
                        <img id="image" class="br-50" src="{{ URL::asset('assets/user-profile.png') }}"
                        alt="" style="height: 100px; width:100px;">
                    @endif
                    <div class="d-flex ms-3"> 
                        <div class="flex-grow-1">
                        <h3 class="mb-1">{{Auth::user()->name}}</h3>
                        @if(!blank(Auth::user()->email))<p class="mb-1"><i class="icon-email"></i> {{Auth::user()->email}} </p>@endif
                        @if(!blank(Auth::user()->phone))<p class="mb-1"><i class="icon-mobile"></i> {{Auth::user()->phone}} </p>@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
