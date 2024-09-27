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
                <div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}" class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page"><a href="" class="text-decoration-none text-white">Login</a></li>
                            </ol>
                        </nav>
                    </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="log-inner">
            {{-- <div>
                <h2>ACCOUNT SIGN IN</h2>
            </div> --}}
            {{-- <p>Sign in to your account to access your profile, history, and any private pages you've been granted access to.</p> --}}
            <form action="{{ route('doctor.login') }}" method="post" class="needs-validation" novalidate>
                @csrf
                <div class="input-login">
                    <div class=" mb-3">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" class="form-control py-3" id="email" required>
                        <div class="invalid-feedback">
                            This field is required
                        </div>
                    </div>
                    <div class="">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control py-3" id="password" required>
                        <div class="invalid-feedback">
                            This field is required
                        </div>
                    </div>
                </div>
                <div class="f-in create">
                    <button class="btn text-white px-5" type="submit">SIGN IN</button>
                </div>
                <div class="login-reset-link">
                    <a href="{{route('forgot.password')}}">Forgot Password</a>
                </div>
            </form>
            <p>Not A Member? <span><a href="{{ route('register') }}">Create Account.</a></span></p>
        </div>
    </div>
</section>
<script>
    @if(session('account_approval_notification'))
        // Display SweetAlert message for account approval notification
        Swal.fire({
            icon: 'info',
            title: 'Your account should be processed and active within 48 hours.',
            text: 'Once you receive your "Account Approval Notification" email, you can begin to order using your account.',
            confirmButtonText: 'OK'
        });
    @endif
</script>
@include('sweetalert::alert')

@endsection
