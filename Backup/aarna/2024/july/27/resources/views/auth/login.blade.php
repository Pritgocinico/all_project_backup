<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="AARNA INSURANCE SERVICES">
    <link rel="icon" href="{{ URL::asset('settings/'.$setting->favicon) }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ URL::asset('settings/'.$setting->favicon) }}" type="image/x-icon">
    <title>Login- {{$setting->site_name}}</title>
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/font-awesome.css">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/icofont.css">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/themify.css">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/flag-icon.css">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/feather-icon.css">
    <!-- Plugins css start-->
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/style.css">
    <link id="color" rel="stylesheet" href="{{url('/')}}/assets/css/color-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/responsive.css">
  </head>
  <body>
    <!-- login page start-->
    <div class="container-fluid p-0">
      <div class="row m-0">
        <div class="col-12 p-0">    
          <div class="login-card login-dark">
            <div>
              <div><a class="logo">
                <img class="img-fluid for-light w-25" src="{{url('/')}}/assets/images/logo.png" alt="looginpage">
                <img class="img-fluid for-dark  w-50" src="{{url('/')}}/assets/images/logo-w.svg" alt="looginpage"></a></div>
              <div class="login-main"> 
              <form action="{{ route('admin_login') }}" method="post" class="theme-form">
               @csrf
                  <h3>Sign in to account</h3>
                  @if(Session::has('message'))
                    <p class="alert mt-2 text-white 
                        {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('message') }}
                    </p>
                  @endif
                  <p>Enter your email & password to login</p>
                  <div class="form-group">
                    <label class="col-form-label">Email Address</label>
                    <input type="text" class="form-control" name="email" value="{{old('email')}}" id="username" placeholder="Username"
                                aria-label="Username" aria-describedby="basic-addon1" autofocus>
                        @if ($errors->has('email'))
                         <p class="text-danger">{{ $errors->first('email') }}</p>
                        @endif
                        <div class="error-message text-danger" id="username-error"></div>
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Password</label>
                    <div class="form-input position-relative">
                     <input type="password" name="password" value="{{old('password')}}" class="form-control" id="password" placeholder="Password"
                                aria-label="Password" aria-describedby="basic-addon2">
                      <div class="show-hide"><span class="show">                         </span></div>
                    </div>
                    @if ($errors->has('password'))
                            <p class="text-danger">{{ $errors->first('password') }}</p>
                    @endif
                  </div>
                  <div class="form-group mb-0">
                    <div class="checkbox p-0">
                      <input id="checkbox1" onclick="showpw()" type="checkbox">
                      <label class="text-muted" for="checkbox1">Show Password</label>
                    </div>
                    <div class="error-message text-danger" id="password-error"></div>
                    <div class="text-end mt-3">
                      <button class="btn btn-primary btn-block w-100" type="submit">Sign in</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- latest jquery-->
      <script src="{{url('/')}}/assets/js/jquery.min.js"></script>
      <!-- Bootstrap js-->
      <script src="{{url('/')}}/assets/js/bootstrap/bootstrap.bundle.min.js"></script>
      <!-- feather icon js-->
      <script src="{{url('/')}}/assets/js/icons/feather-icon/feather.min.js"></script>
      <script src="{{url('/')}}/assets/js/icons/feather-icon/feather-icon.js"></script>
      <!-- scrollbar js-->
      <!-- Sidebar jquery-->
      <script src="{{url('/')}}/assets/js/config.js"></script>
      <!-- Plugins JS start-->
      <!-- Plugins JS Ends-->
      <!-- Theme js-->
      <script src="{{url('/')}}/assets/js/script.js"></script>
      <script src="{{url('/')}}/assets/js/custom.js"></script>
      <!-- Plugin used-->
    </div>
  </body>
</html>