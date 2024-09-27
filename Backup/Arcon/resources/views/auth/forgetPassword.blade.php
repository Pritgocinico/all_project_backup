<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Arcon | ForgotPassword</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap core CSS -->
    <link href="{{ url('/') }}/assets/admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ url('/') }}/assets/admin/css/style.css" rel="stylesheet">
    <link href="{{ url('/') }}/assets/admin/css/dashboard.css" rel="stylesheet">
</head>
<body>
    @if(Session::has('sent'))
    <script>
      Swal.fire('Success!', '{{ Session::get('sent') }}','success');
    </script>
    @endif
    <div class="container-fluid">
        <div class="row login">
            <!--<div class="col-md-6 left">-->
            <!--    <img src="{{ url('/') }}/assets/dist/images/login-url.jpg" alt="">-->
            <!--</div>-->
            <div class="col-md-12 right mt-5 pt-5">
                <div class="row justify-content-center m-auto">
                    <div class="col-lg-4 col-md-4 col-sm-12  gc-login">
                        <h2 class="gc-login-title">Forgot Password</h2>
                        @if(Session::has('message'))
                                    <p class="alert
                                    {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('message') }}</p>
                        @endif
                        @if(Session::has('sent'))
                                    <p class="alert
                                    {{ Session::get('success-class', 'alert-success') }}">{{Session::get('sent') }}</p>
                        @endif
                        <form action="{{ route('forget.password.post') }}" method="post" class="mt-4">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">Email Address</label>
                                    <input type="email" name="email" value="{{old('email')}}" class="form-control" placeholder="Email Address">
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="login-btn w-100">Send Password Reset Link</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ url('/') }}/assets/admin/js/bootstrap.bundle.min.js"></script>
    <script src="{{ url('/') }}/assets/admin/libs/sweetalert/sweetalert.min.js"></script>
</body>
</html>