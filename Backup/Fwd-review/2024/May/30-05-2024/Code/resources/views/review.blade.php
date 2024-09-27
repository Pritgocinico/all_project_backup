<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReviewLead</title>
    <link rel="icon" type="image/x-icon" href="{{url('/')}}/assets/Images/fwd-favicon.png">
    <link rel="stylesheet" href="{{url('/')}}/assets/Css/style.css">
    <link rel="stylesheet" href="{{url('/')}}/assets/Css/media.css">
    <link href="{{url('/')}}/assets/Css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{url('/')}}/assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="{{url('/')}}/assets/sweetalert/sweetalert.min.css">
    <script src="{{url('/')}}/assets/JS/3.5.1_jquery.min.js" charset="utf-8"></script>
    <script src="{{url('/')}}/assets/JS/bootstrap.bundle.min.js"></script>
    <script src="{{url('/')}}/assets/sweetalert/sweetalert.min.js"></script>
    <style>
        body{
            background: transparent;
        }
        .main-container {
            background-color: #fff;
            margin: 0 auto;
            max-width: 1024px;
            box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
        }
        .modal-body{
            padding: 0 !important;
        }
        .model-header-custom{
            background: #f73859;
            padding: 35px 20px;
        }
        .model-header-custom p{
            color: #fff !important;
            font-weight: 500;
            margin-bottom: 0 !important;
        }
        .submitfor-feed{
            background: black;
            border: 0;
            margin-top: 18px;
        }
        /* .body-content{
            padding: 30px 10px !important;
        } */
        .header-content{
            color: #ffffff;
        }
        .reviewCard{
            width: 50%;
        }
        .modal-content{
            background-color: #fff !important; 
        }
        .modal-footer a{
            color: #428bca !important;
            text-align: center;
        }
        .form-control{
            background-color: #ffffff !important;
            color: #111111 !important;
        }
        .form-control:focus{
            border-image-source: none !important;
        }
        .thumbup, .thumbdown{
            padding: 20px;
        }
        @media (max-width: 768px){
            .main-container {
                width:100%;
            }
        }
        @media (min-width: 768px){
            .main-container {
                margin: 5% auto;
            }
        }
    </style>
</head>
<body>
    @if(Session::has('success'))
        <script>
            Swal.fire('success',"{{ Session::get('success') }}",'success');
        </script>
    @endif
    <div class="main-container w-md-50 reviewCard">
        <div class="card-body">
            <div class="content-area">
                <div class="header-content"  style="background:@if(!blank($business->brand_color)) {{$business->brand_color}} @else #111111 @endif">
                    <p class="v-msg">{{$business->visitor_title}}</p>
                    <h3 class="b-name">{{$business->business_name}}</h3>
                </div>
                <div class="body-content">
                    <div class="mb-5">
                        @if(!blank($business->logo))
                            <div class="logo">
                                <img src="{{url('/')}}/logos/{{$business->logo}}" class="logo_preview" style="max-width:200px" alt="">
                            </div>
                        @endif
                        <div id="message" class="v-message"><p>{{$business->visitor_message}}</p></div>
                        <div class="review-thumbs">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="thumbup" data-action="Clicked Thumbs Up">
                            {{-- <a href="{{url('/')}}/{{$business->shortname}}#binary_choice_positive" class="thumbup" target="_blank" style="border:none;text-decoration:none;font-size:9px; padding:0px 20px; text-align:center;"> --}}
                                <img src="{{url('/')}}/assets/Images/thumbs_up.svg" class="thumbImg" alt="Good" />
                                <p>{{$business->thumbsup_text}}</p>
                            {{-- </a> --}}
                            </a>
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#contactModule" class="thumbdown" data-action="Clicked Thumbs Down">
                            {{-- <a href="{{url('/')}}/{{$business->shortname}}#binary_choice_positive" class="thumbdown" target="_blank" style="border:none;text-decoration:none;font-size:9px; padding:0px 20px; text-align:center;"> --}}
                                <img src="{{url('/')}}/assets/Images/thumbs_down.svg" class="thumbImg" alt="Not bad" />
                                <p>{{$business->thumbsdown_text}}</p>
                            </a>
                        </div>
                    </div>
                    @if($business->social_media == 'on')
                        <div class="footer-content">
                            <div class="d-flex justify-content-center">
                                @if($business->facebook_url != '' || $business->facebook_url != NULL)
                                    <a href="{{$business->facebook_url}}" target="_blank"><img src="{{url('/')}}/assets/Images/facebook.png" class="review-icon" alt="facebook"></a>
                                @endif
                                @if($business->twitter_url != '' || $business->twitter_url != NULL)
                                    <a href="{{$business->twitter_url}}" target="_blank"><img src="{{url('/')}}/assets/Images/twitter.png" class="review-icon" alt="facebook"></a>
                                @endif
                                @if($business->instagram_url != '' || $business->instagram_url != NULL)
                                    <a href="{{$business->instagram_url}}" target="_blank"><img src="{{url('/')}}/assets/Images/instagram.png" class="review-icon" alt="facebook"></a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-body">
                <div class="text-center model-header-custom">
                    <p>@if(!blank($business->public_review_message)) {{$business->public_review_message}} @else Thank you! <strong>We need your help.</strong> Would you share your experience on Google! @endif</p>
                    <div class="text-center">
                        <a href="http://search.google.com/local/reviews?placeid={{$business->place_id}}">
                            <img src="{{url('/')}}/assets/Images/google-logo.png" alt="" width="200px">
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#contactModule">If you have concerns you wish to address in private, please get in touch.</a>
            </div>
          </div>
        </div>
    </div>
    <div class="modal fade" id="contactModule" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-body">
                <div class="">
                    <div class="text-center model-header-custom">
                        <p>@if(!blank($business->prompt_message)) {{$business->prompt_message}} @else We strive for 100% customer satisfaction. If we felt short, please tell us more so we can address your concerns. @endif</p>
                    </div>
                    <div class="mb-5 mt-4 px-4">
                        <form action="{{route('customer.feedback')}}" method="POST" id="customerFeedback">
                            @csrf
                            <div class="mb-3">
                              <label for="YourName" class="form-label">Your Name*</label>
                              <input type="text" class="form-control" id="YourName" name="name" value="{{old('name')}}">
                              <div id="nameError" class="form-text text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label for="YourPhone" class="form-label">Phone*</label>
                                <input type="text" class="form-control" id="YourPhone" name="phone" value="{{old('phone')}}">
                                <div id="phoneError" class="form-text text-danger"></div>
                              </div>
                            <div class="mb-3">
                              <label for="Email" class="form-label">Email*</label>
                              <input type="email" class="form-control" name="email" id="Email" value="{{old('email')}}">
                              <div id="emailError" class="form-text text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label for="Message" class="form-label">Message</label>
                                <textarea class="form-control" name="message" id="Message">{{old('message')}}</textarea>
                            </div>
                            <div class="text-center">
                                <input type="hidden" name="action" class="action" value="">
                                <button type="button" class="btn btn-primary submitfor-feed feedbackBtn">SEND MESSAGE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            var hash = window.location.hash;
            if(hash == '#positive'){
                $('#staticBackdrop').modal('show');
            }else if(hash == '#negative'){
                $('#contactModule').modal('show');
            }
        });
        $(document).on('click','.thumbup, .thumbdown',function(){
            var action = $(this).data('action');
            $('.action').val(action);
        });
        $(document).on('click','.feedbackBtn',function(){
            var name = $('#name').val();
            var phone = $('#phone').val();
            var email = $('#email').val();
            if(name == ''){
                $('#nameError').html('Please enter your name.')
            }
            if(phone == ''){
                $('#phoneError').html('Please enter your phone number.')
            }
            if(email == ''){
                $('#emailError').html('Please enter your email address.')
            }
            if(name != '' && phone != '' && email != ''){
                var frm = $('#customerFeedback');
                var formData = new FormData(frm[0]);
                // formData.append('file', $('input[type=file]')[0].files[0]);
                $.ajax({
                    type: frm.attr('method'),
                    url: "{{route('customer.feedback')}}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data){
                        if(data == 'success'){
                            Swal.fire('success',"{{$business->private_feedback_thankyou}}",'success');
                            Swal.fire({
                                title: 'Successfully Submitted!',
                                text: "{{$business->private_feedback_thankyou}}",
                                icon: 'warning',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                                }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();          
                                }
                            });
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>