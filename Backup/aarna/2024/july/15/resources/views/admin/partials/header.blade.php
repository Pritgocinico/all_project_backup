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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <!-- ico-font-->
     @yield('style')
   <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/icofont.css">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/themify.css">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/flag-icon.css">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/feather-icon.css">
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/slick.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/slick-theme.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/scrollbar.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/animate.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/datatables.css">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/style.css">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/responsive.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/custom.css">
  </head>
  <body> 
    <!-- loader starts-->
    <div class="loader-wrapper">
      <div class="loader">    
        <div class="box"></div>
        <div class="box"></div>
        <div class="box"></div>
        <div class="box"></div>
        <div class="box"></div>
      </div>
    </div>
    <!-- loader ends-->
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
      <!-- Page Header Start-->
      <div class="page-header">
        <div class="header-wrapper row m-0">
          <div class="header-logo-wrapper col-auto p-0">
            <div class="logo-wrapper"><a href="{{route('admin.dashboard')}}"><img class="img-fluid for-light w-25" src="{{ URL::asset('settings/'.$setting->logo) }}" alt=""><img class="img-fluid for-dark w-25" src="{{ URL::asset('settings/'.$setting->logo) }}" alt=""></a></div>
            <div class="toggle-sidebar">
              <svg class="sidebar-toggle"> 
                <use href="{{url('/')}}/assets/svg/icon-sprite.svg#stroke-animation"></use>
              </svg>
            </div>
          </div>
          <div class="left-header col-xxl-5 col-xl-6 col-md-4 col-auto box-col-6 horizontal-wrapper p-0">
            <div class="left-menu-header">
              <ul class="header-left"> 
                <li>
                <div class="form-group w-100"> 
                    <div class="Typeahead Typeahead--twitterUsers">
                      <div class="u-posRelative d-flex">
                       <h5>Welcome,  {{Auth::user()->name}}</h5>
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
          <div class="nav-right col-xxl-7 col-xl-6 col-auto box-col-6 pull-right right-header p-0 ms-auto">
            <ul class="nav-menus">
              <li class="serchinput">
                <div class="serchbox">
                  <svg>
                    <use href="{{url('/')}}/assets/svg/icon-sprite.svg#fill-search"></use>
                  </svg>
                </div>
                <div class="form-group search-form">
                  <input type="text" placeholder="Search here...">
                </div>
              </li>
              <li class="onhover-dropdown">
                <div class="notification-box">
                  <svg>
                    <use href="{{url('/')}}/assets/svg/icon-sprite.svg#fill-Bell"></use>
                  </svg>
                  @if(count($notifications)>0)
                   <span class="badge rounded-pill badge-primary">{{count($notifications)}}</span>
                  @endif
                </div>
                <div class="onhover-show-div notification-dropdown">
                  <div class="align-items-center d-flex dropdown-title justify-content-between">
                  <h6 class="f-18 mb-0">Notifications</h6>
                  <?php if(count($notifications)>0){ ?>
                                    <a href="{{ route('markasread') }}" class="btn btn-dark">
                                        Read All
                                    </a>
                            <?php } ?>
                  </div>          
                  @if(count($notifications)>0)
                  <div class="overflow-y-scroll height-500">          
                   @foreach($notifications as $notification)
                       <div class="d-flex align-items-center figma-line">
                          <div class="flex-grow-1 ms-2"><a href="{{ $notification['data']['url'] }}" class="read_notification" data-id="{{ $notification['id'] }}">
                              <h5>{{ $notification['data']['type'] }} - {{ $notification['data']['data'] }}</h5>
                              <span> {{ $notification['created_at'] }}</span></a></div>
                        </div>
                      @endforeach
                  </div>
                  @else
                   <div class="d-flex align-items-center">
                    <div class="flex-grow-1 ms-2"><a>
                        <h5>No New Notification</h5></a></div>
                  </div>
                  @endif
              </li>


              <li>
                <div class="mode">
                  <svg>
                    <use href="{{url('/')}}/assets/svg/icon-sprite.svg#fill-dark"></use>
                  </svg>
                </div>
              </li>
              <li class="profile-nav onhover-dropdown p-0">
                <div class="d-flex align-items-center profile-media">
                <?php $image=URL::asset("settings/".Auth::user()->image); ?>
                    @if (!blank(Auth::user()->image))
                        <img class="b-r-10 img-40 height-45"  src="{{ $image }}"
                         alt="">
                    @else
                        <img class="b-r-10 img-40 height-45"  src="{{ URL::asset('assets/user-profile.png') }}"
                        alt="">
                    @endif
                  <div class="flex-grow-1"><span>{{Auth::user()->name}}</span>
                    <p class="mb-0">{{Auth::user()->email}} </p>
                  </div>
                </div>
                <ul class="profile-dropdown onhover-show-div">
                <li><a href="{{ route('view.profile') }}"><i data-feather="user"></i><span>My Profile</span></a> </li>
                <li><a href="{{ route('edit.profile') }}"><i data-feather="file-text"></i><span>Edit Profile</span></a> </li>
                @if(Auth::user()->id == 1)
                    <li><i data-feather="settings"></i><a href="{{route('settings')}}"><span>Settings</span></a> </li>
                @endif
                <li><a href="{{route('logout')}}/"><i data-feather="log-in"> </i><span>Logout</span></a> </li>
                </ul>
              </li>
            </ul>
          </div>
          <script class="result-template" type="text/x-handlebars-template">
            <div class="ProfileCard u-cf">                        
            <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
            <div class="ProfileCard-details">
            <div class="ProfileCard-realName">name</div>
            </div>
            </div>
          </script>
          <script class="empty-template" type="text/x-handlebars-template"><div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div></script>
        </div>
      </div>
      <!-- Page Header Ends -->
<!-- Page Body Start-->
<div class="page-body-wrapper">     



