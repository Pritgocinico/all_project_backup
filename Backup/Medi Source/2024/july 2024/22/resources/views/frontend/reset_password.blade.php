@extends('frontend.layouts.app')

@section('content')
    <section class="banner-section about-parent prdct-parent position-relative py-sm-5 py-1">
        <div class="container">
            <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start ">
                <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
                <div class="col-md-8">
                    <h1 class="text-white text-start">
                        Reset Password
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="log-inner">
                <div>
                    <h2>Reset Password</h2>
                </div>
                <form action="{{ route('submit.reset.password') }}" method="post" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="token" value="{{$token}}">
                    <div class="input-login">
                        <div class=" mb-3">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" class="form-control py-3" id="email"
                                value="{{ $email }}" required readonly>
                            <div class="invalid-feedback">
                                This field is required
                            </div>
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class=" mb-3">
                            <label for="email">Password</label>
                            <input type="password" name="password" class="form-control py-3" id="password"
                                value="{{ old('password') }}" required>
                            <div class="invalid-feedback">
                                This field is required
                            </div>
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <div class=" mb-3">
                            <label for="email">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control py-3" id="confirm_password"
                                value="{{ old('confirm_password') }}" required>
                            <div class="invalid-feedback">
                                This field is required
                            </div>
                            @if ($errors->has('confirm_password'))
                                <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="f-in create">
                        <button class="btn text-white px-5" type="submit">Reset Password</button>
                    </div>
                </form>
                <p>Not A Member?<span><a href="{{ route('register') }}">Create Account.</a></span></p>
            </div>
        </div>
    </section>

    @include('sweetalert::alert')
@endsection