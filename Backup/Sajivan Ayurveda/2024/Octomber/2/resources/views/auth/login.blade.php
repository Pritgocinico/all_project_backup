<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon"
        href="{{ $settings ? asset('/storage/' . $settings->fa_icon) : asset('assets/img/login/favicon-sajivan.png') }}" />
    <title>{{ isset($page) ? $page . '-' : '' }}{{ $settings ? $settings->site_name : 'Sajivan Ayurveda' }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/utility.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('plugin/toastr/toastr.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
    <div class="row g-0 justify-content-center gradient-bottom-right start-purple middle-indigo end-pink">
        <div class="col-md-6 col-lg-5 col-xl-5 position-fixed start-0 top-0 vh-100 overflow-y-hidden d-none d-lg-flex flex-lg-column login-background login-left-panel-outer">
            <div class="login-left-panel">
                <div>
                    <div>
                        <h1 class="ls-tight fw-bolder display-6 text-white mb-5">
                            Sajivan Ayurveda
                        </h1>
                        <p class="text-white text-opacity-75 pe-xl-24">
                            Ocean Of Ayurveda
                        </p>
                    </div>
                </div>
                <div><img src="{{ url('assets/img/login/sajivan-login.png') }}"
                        class="img-fluid rounded-top-start-4" alt="...">
                </div>
            </div>
                

        </div>
        <div
            class="col-12 col-md-12 col-lg-7 offset-lg-5 min-vh-100 overflow-y-auto d-flex flex-column justify-content-center position-relative bg-body rounded-top-start-lg-4 border-start-lg shadow-soft-5">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="w-md-50 mx-auto px-10 px-md-0 py-10">
                    <div class="mb-10">
                        <h1 class="ls-tight fw-bolder h3">Sign in to your account</h1>
                    </div>
                    <div class="mb-5"><label class="form-label" for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Enter Email" value="{{ old('email') }}">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-5">
                        {{-- <div class="d-flex justify-content-between gap-2 mb-2 align-items-center">
                            <label class="form-label mb-0" for="password">Password</label> <a href="#"
                                class="text-sm text-muted text-primary-hover text-underline">Forgot password?</a>
                        </div> --}}
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" autocomplete="current-password"
                                name="password" placeholder="Enter Password">

                            <div class="input-group-append login_button_password">
                                <span class="input-group-text" id="password_eye_button"><i class="fa fa-eye"></i></span>
                            </div>
                        </div>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="check_example"
                                id="check_example"> <label class="form-check-label" for="check_example">Keep me logged
                                in</label></div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-dark w-100">Sign In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('plugin/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugin/toastr/toastr.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".login_button_password").click(function() {
                var input = $("#password");
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                    $('#password_eye_button').html('<i class="fa fa-eye-slash"></i>');
                } else {
                    input.attr("type", "password");
                    $('#password_eye_button').html('<i class="fa fa-eye"></i>');
                }
            });
        });
    </script>
    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}")
        </script>
    @endif
    @if (session('error'))
        <script>
            toastr.error("{{ session('error') }}")
        </script>
    @endif
</body>

</html>
