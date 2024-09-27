
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Arcon | Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap core CSS -->
    <link href="{{ url('/') }}/assets/admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ url('/') }}/assets/admin/css/style.css" rel="stylesheet">
    <link href="{{ url('/') }}/assets/admin/css/dashboard.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <h2 class="gc-login-title">Reset Password</h2>
                        @if(Session::has('status'))
                                    <p class="alert
                                    {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('status') }}</p>
                        @endif
                        <form action="{{ route('reset.password.post') }}" method="post" class="mt-4">
                           @csrf
                            <div class="row">
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div class="col-md-12">
                                    <input type="email" name="email" value="{{old('email')}}" class="form-control" placeholder="Email Address">
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-12 form-group mt-2">
                                    <input type="password" id="password" name="password" placeholder="password" class="form-control" value="{{old('password')}}">
                                    @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-12 form-group mt-2">
                                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" class="form-control" value="{{old('password_confirmation')}}">
                                    @if ($errors->has('password_confirmation'))
                                        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="login-btn w-100">Reset Password</button>
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