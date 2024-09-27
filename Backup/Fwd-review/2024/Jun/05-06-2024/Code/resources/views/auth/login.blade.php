<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReviewLead</title>
    <link rel="stylesheet" href="{{url('/')}}/assets/Css/style.css">
    <link rel="stylesheet" href="{{url('/')}}/assets/Css/media.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
</head>
<body>
    <div class="gc_login_main">
        <div class="container-fluid gc_log">
            <div class="form">
            <form action="{{ route('admin_login') }}" method="post" class="mt-4">
                @csrf
                <img src="{{url('/')}}\assets\Images\fwdreviewswhite.png" alt="" class="img-fluid mb-md-4 md-2" width="150px">
                <h2 class="mt-3">Login</h2>
                @if(Session::has('message'))
                    <p class="alert
                        {{ Session::get('alert-class', 'alert-danger') }}">{!! Session::get('message') !!}
                    </p>
                @endif
                <div class="input">
                    <div class="inputBox my-4">
                        <label for="username">Username</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text px-2" id="basic-addon1"><img
                                    src="{{url('/')}}\assets\Images\login_user.png" alt=""></span>
                            <input type="text" class="form-control" name="email" value="{{old('email')}}" id="username" placeholder="Username"
                                aria-label="Username" aria-describedby="basic-addon1" autofocus>
                        </div>
                        @if ($errors->has('email'))
                            <p class="text-danger">{{ $errors->first('email') }}</p>
                        @endif
                        <div class="error-message text-danger" id="username-error"></div>
                    </div>
                    <div class="inputBox my-4">
                        <label for="password">Password</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text px-2" id="basic-addon2"><img
                                    src="{{url('/')}}\assets\Images\login_pw.png" alt=""></span>
                            <input type="password" name="password" value="{{old('password')}}" class="form-control" id="password" placeholder="Password"
                                aria-label="Password" aria-describedby="basic-addon2">
                        </div>
                        @if ($errors->has('password'))
                            <p class="text-danger">{{ $errors->first('password') }}</p>
                        @endif
                        <div>
                            <input type="checkbox" onclick="showpw()" id="showPassword" class="me-2"><label class="d-inline-block" for="showPassword"><small>Show Password</small></label>
                            <div class="error-message text-danger" id="password-error"></div>
                        </div>
                    </div>
                    <div class="inputBox pt-2 text-center w-100">
                        <button type="submit" class="btn gc_btn w-100">Submit</button>
                    </div>
                    <div class="text-end mt-2 "> <a href="{{route('forget.password')}}" class="text-primary">Forget Password</a></div>
                </div>
            </form>
            </div>
        </div>
    </div>
    </div>
    <script>
        function showpw() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
    <script>
        function validateForm() {
            event.preventDefault();
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;
            var usernameError = document.getElementById("username-error");
            var passwordError = document.getElementById("password-error");
            usernameError.innerText = "";
            passwordError.innerText = "";
            if (username.trim() === "") {
                usernameError.innerText = "Please enter a username.";
                return false;
            } else if (username.length <= 3) {
                usernameError.innerText = 'Enter a valid username';
                return false;
            }
            if (password.trim() === "") {
                passwordError.innerText = "Please enter a password.";
                return false;
            } else if (password.length <= 6) {
                passwordError.innerText = 'Enter a valid password';
                return false;
            }
            window.location.href = "{{url('/')}}";
        }
    </script>
</body>
</html>
