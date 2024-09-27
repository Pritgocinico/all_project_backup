<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Arcon | Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap core CSS -->
    <link href="{{ url('/') }}/assets/admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ url('/') }}/assets/admin/css/style.css" rel="stylesheet">
    <link href="{{ url('/') }}/assets/admin/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row login">
            <!--<div class="col-md-6 left">-->
            <!--    <img src="{{ url('/') }}/assets/dist/images/login-url.jpg" alt="">-->
            <!--</div>-->
            <div class="col-md-12 right mt-5 pt-5">
                <div class="row justify-content-center m-auto">
                    <div class="col-lg-4 col-md-4 col-sm-12  gc-login">
                        <h2 class="gc-login-title">Login</h2>
                        @if(Session::has('message'))
                            <p class="alert
                                {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('message') }}
                            </p>
                        @endif
                        <form action="{{ route('admin_login') }}" method="post" class="mt-4">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">Email / Phone Number</label>
                                    <input type="text" name="email" value="{{old('email')}}" class="form-control" placeholder="Email or Phone Number">
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label for="">Password</label>
                                    <input type="password" name="password" value="{{old('password')}}" class="form-control" placeholder="Password">
                                    @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 mt-5">
                                    <input type="checkbox" name="remember_me" id="remember_me"> <label for="remember_me"> Remember Me </label>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="login-btn w-100">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ url('/') }}/assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>