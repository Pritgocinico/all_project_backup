<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shree Ganesh Alluminium</title>
    <link rel="shortcut icon" href="{{url('/')}}/assets/media/image/favicon.png"/>
    <link rel="stylesheet" href="{{url('/')}}/vendors/bundle.css" type="text/css">
    <link rel="stylesheet" href="{{url('/')}}/assets/css/app.min.css" type="text/css">
</head>
<body class="form-membership">
    <div class="form-wrapper">
        <div id="logo">
            <h1>SGA</h1>
            {{-- <img src="{{url('/')}}/assets/media/image/dark-logo.png" alt="image"> --}}
        </div>
        <h5>Sign in</h5>
        @if(Session::has('message'))
            <p class="alert
                {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('message') }}
            </p>
        @endif
        <form action="{{ route('admin_login') }}" method="post">
            @csrf
            <div class="form-group">
                <input type="text" name="email" id="email" class="form-control" placeholder="Username or email" value="{{old('email')}}" required autofocus>
            </div>
            @if ($errors->has('email'))
                <p class="text-danger">{{ $errors->first('email') }}</p>
            @endif
            <div class="form-group d-flex justify-content-between">
                <div class="input-group">
                    <input type="password" name="password" class="form-control" value="{{ old('password') }}" id="password" placeholder="Password" required style="margin-bottom: 0;">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fas fa-eye" id="togglePassword"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group custom-control custom-checkbox">
                <input type="checkbox" name="remember_me" class="custom-control-input" id="rememberMe">
                <label class="custom-control-label" for="rememberMe">Remember Me</label>
            </div>
            {{-- <div class="form-group">
                <input type="password" name="password" class="form-control" value="{{old('password')}}" id="password" placeholder="Password" required>
            </div>
            <div class="form-group d-flex justify-content-between">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" onclick="showpw()" class="custom-control-input" id="showPassword">
                    <label class="custom-control-label" for="showPassword">Show Password</label>
                </div> --}}
                {{-- <a href="recovery-password.html">Reset password</a> --}}
            {{-- </div> --}}
            <button type="submit" class="btn btn-primary btn-block">Sign in</button>
        </form>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="{{url('/')}}/vendors/bundle.js"></script>
    <script src="{{url('/')}}/assets/js/app.min.js"></script>
    {{-- <script>
        function showpw() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script> --}}
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            var passwordField = document.getElementById('password');
            var type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        document.getElementById('rememberMe').addEventListener('change', function() {
            var emailField = document.getElementById('email');
            var passwordField = document.getElementById('password');

            if (this.checked) {
                localStorage.setItem('rememberedEmail', emailField.value);
                localStorage.setItem('rememberedPassword', passwordField.value);
            } else {
                localStorage.removeItem('rememberedEmail');
                localStorage.removeItem('rememberedPassword');
            }
        });

        // Check if there are remembered credentials and populate the fields
        window.addEventListener('DOMContentLoaded', function() {
            var rememberedEmail = localStorage.getItem('rememberedEmail');
            var rememberedPassword = localStorage.getItem('rememberedPassword');
            var rememberMeCheckbox = document.getElementById('rememberMe');

            if (rememberedEmail && rememberedPassword) {
                document.getElementById('email').value = rememberedEmail;
                document.getElementById('password').value = rememberedPassword;
                rememberMeCheckbox.checked = true;
            }
        });
    </script>
</body>
</html>
