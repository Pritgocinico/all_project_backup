<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="AARNA INSURANCE SERVICES">
    <link rel="icon" href="{{url('/')}}/assets/images/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="{{url('/')}}/assets/images/favicon.png" type="image/x-icon">
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
              <form action="{{route('store.contact-us')}}" method="post" class="row g-3">
                  <h3>Contact Us</h3>
                @csrf
                <div class="col-md-12 form-floating mt-4">
                    <input type="text" class="form-control" name="name" id="name" value="{{old('name')}}" placeholder="" autofocus />
                    <label for="FirstName" class="form-label">Name (required)</label>
                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="col-md-12 form-floating mt-4">
                    <input type="email" class="form-control" name="email_address" id="email_address" value="{{old('email_address')}}" placeholder="" autofocus />
                    <label for="LastName" class="form-label">Email Address (required)</label>
                    @if ($errors->has('email_address'))
                        <span class="text-danger">{{ $errors->first('email_address') }}</span>
                    @endif
                </div>
                <div class="col-md-12 form-floating mt-4">
                    <input type="tel" class="form-control" name="phone" id="phone" value="{{old('phone')}}" placeholder="" />
                    <label for="Phone" class="form-label">Phone Number (required)</label>
                    @if ($errors->has('phone'))
                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                    @endif
                </div>
                <div class="col-md-12 form-floating mt-4">
                    <textarea class="form-control" name="message" id="message" placeholder="">{{old('message')}}</textarea>
                    <label for="message" class="form-label">Message</label>
                                    </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary mt-3">
                        Submit
                    </button>
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