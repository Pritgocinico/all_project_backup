@extends('frontend.layouts.app')

@section('content')

<section class="banner-section about-parent prdct-parent position-relative py-sm-5 py-1">
    <div class="container">
        <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start ">
            <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
            <div class="col-md-8">
                <h1 class="text-white text-start">
                    Login
                </h1>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="log-inner">
            <div>
                <h2>Forgot Password</h2>
            </div>
            <form action="{{route('submit.forgot.password')}}" method="post" class="needs-validation" novalidate>
                @csrf
                <div class="input-login">
                    <div class=" mb-3">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" class="form-control py-3" id="email" required>
                        <div class="invalid-feedback">
                            This field is required
                        </div>
                        @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                    </div>
                </div>
                <div class="f-in create">
                    <button class="btn text-white px-5" type="submit">Forgot Password</button>
                </div>
            </form>
            <p>Not A Member?<span><a href="{{ route('register') }}">Create Account.</a></span></p>
        </div>
    </div>
</section>

@include('sweetalert::alert')

@endsection
