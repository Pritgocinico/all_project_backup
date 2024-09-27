<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="AARNA INSURANCE SERVICES">
    <link rel="icon" href="{{ URL::asset('settings/'.$setting->favicon) }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ URL::asset('settings/'.$setting->favicon) }}" type="image/x-icon">
    <title>AARNA INSURANCE SERVICES</title>
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
             
                <form action="{{ route('accountdel') }}" method="post" class="mt-4">
                @csrf
                <h2 class="mt-3">Account Delete</h2>
               @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="input">
                    <div class="inputBox my-4">
                        <label for="username">Username</label>
                        <div class="input-group mb-3">
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
                        <button type="submit" class="btn btn-primary w-100">Delete</button>
                    </div>
                    <!--<div class="text-end mt-2 "> <a href="./forget_password.php" class="text-primary">Forget Password</a></div>-->
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