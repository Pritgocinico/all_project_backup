<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Medisource</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- App favicon -->
    <!-- <link rel="shortcut icon" href="{{url('/')}}/assets/images/favicon.ico"> -->
    @if ($setting && $setting->favicon)
    <link rel="shortcut icon" href="{{ asset('/storage/' . $setting->favicon) }}">
    @endif

    <!-- Theme Config Js -->
    <script src="{{url('/')}}/assets/js/config.js"></script>

    <!-- App css -->
    <link href="{{url('/')}}/assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="{{url('/')}}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
</head>

<body class="authentication-bg position-relative">


    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-8 col-lg-10">
                    <div class="card overflow-hidden">
                        <div class="row g-0">
                            <div class="col-lg-6 d-none d-lg-block p-2">
                                <img src="{{url('/')}}/assets/images/auth-img.jpg" alt="" class="img-fluid rounded h-100">
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex flex-column h-100">
                                    <div class="auth-brand p-4">
                                        <a href="{{url('/')}}" class="logo-light">
                                            <img src="{{url('/')}}/assets/images/Logo_Color.png" alt="logo" height="22">
                                        </a>
                                        <a href="{{url('/')}}" class="logo-dark">
                                            <img src="{{url('/')}}/assets/images/Logo_Color.png" alt="dark logo" height="70">
                                        </a>
                                    </div>
                                    <div class="p-4 my-auto">
                                        <h4 class="fs-20">Sign In</h4>
                                        <p class="text-muted mb-3">Enter your email address and password to access
                                            account.
                                        </p>
                                        @if(Session::has('message'))
                                            <p class="alert
                                                {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('message') }}
                                            </p>
                                        @endif
                                        <!-- form -->
                                        <form action="{{ route('admin_login') }}" method="post">
                                        @csrf
                                        <div>
                                                <label for="emailaddress" class="form-label">Email address</label>
                                                <input class="form-control" type="email" id="emailaddress" required="" value="{{old('email')}}" name="email" placeholder="Enter your email">
                                            </div>
                                            @if ($errors->has('email'))
                                                    <p class="text-danger">{{ $errors->first('email') }}</p>
                                                @endif
                                          <!-- ... (Your existing form elements) ... -->

<div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <div class="input-group">
        <input class="form-control" type="password" name="password" required="" id="password"
            placeholder="Enter your password">
        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
            <i class="ri-eye-fill"></i>
        </button>
    </div>
</div>


<div class="mb-3">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="checkbox-signin" name="remember">
        <label class="form-check-label" for="checkbox-signin">Remember me</label>
    </div>
</div>


                                            <div class="mb-0 text-start">
                                                <button class="btn btn-soft-primary w-100" type="submit"><i
                                                        class="ri-login-circle-fill me-1"></i> <span class="fw-bold">Log
                                                        In</span> </button>
                                            </div>

                                        </form>
                                        <!-- end form-->
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>

            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
</div>
</form>
    <!-- end page -->


    <!-- Vendor js -->
    <script src="{{url('/')}}/assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="{{url('/')}}/assets/js/app.min.js"></script>

</body>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        var passwordInput = document.getElementById('password');
        var eyeIcon = this.querySelector('i');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('ri-eye-fill');
            eyeIcon.classList.add('ri-eye-off-fill');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('ri-eye-off-fill');
            eyeIcon.classList.add('ri-eye-fill');
        }
    });
</script>
</html>

