<iframe name="iframe2" src="{{route()}}"></iframe>
<div style="font-family: 'Arial', sans-serif; line-height: 1.6; margin: 0; padding: 0;">
    <div style="margin: 20px auto; background-color: #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" class="fwd-review-main">
      <div style="background: #000000; padding: 20px;">
          <p style="color: white; margin-top: 0 !important; margin-bottom: 10px !important; text-align: center;">{{$business->visitor_title}}</p>
          <h3 style="font-size: 1.75rem; color: white; margin: 0 !important; text-align: center;">{{$business->business_name}}</h3>
      </div>
      <div style="text-align: center; padding: 30px 10px !important;">
        @if(!blank($business->logo))
            <div class="logo">
                <img src="{{url('/')}}/logos/{{$business->logo}}" class="logo_preview" style="max-width:200px; margin: 20px auto;" alt="">
            </div>
        @endif
        <p style="max-width: 510px; margin: 20px auto; text-align: center; font-size: 15px; font-weight: 400; font-style: normal; line-height: 100%; text-transform: none; line-height: 25px; color: #000000;">{{$business->visitor_message}}</p>
        <div style="display: flex; align-items: center; justify-content: center; margin-top: 40px;">
          <a href="javascript:void(0);" data-bs-toggle="modal" style="padding: 20px; text-decoration: none;" data-bs-target="#staticBackdrop" data-action="Clicked Thumbs Up">
              <img src="{{url('/')}}/assets/Images/thumbs_up.svg" style="width: 120px; height: 120px;" alt="Good">
              <p style="max-width: 510px; margin: 20px auto; text-align: center; font-size: 15px; font-weight: 400; font-style: normal; line-height: 100%; text-transform: none; line-height: 25px; color: #000000;">I had a good experience.</p>
          </a>
         <a href="javascript:void(0);" data-bs-toggle="modal" style="padding: 20px; text-decoration: none;" data-bs-target="#staticBackdrop" data-action="Clicked Thumbs Up">
              <img src="{{url('/')}}/assets/Images/thumbs_down.svg" style="width: 120px; height: 120px;" alt="Not bad">
              <p style="max-width: 510px; margin: 20px auto; text-align: center; font-size: 15px; font-weight: 400; font-style: normal; line-height: 100%; text-transform: none; line-height: 25px; color: #000000;">I had a bad experience.</p>
          </a>
      </div>
      </div>
    </div>
</div>
<style>
  .fwd-review-main{
    width:60%;
    justify-content:center;
  }
  @media (max-width: 1024px) {
    .fwd-review-main {
      width: 100%;
    }
  }
</style>
<div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <p>Thank you! <strong>We need your help.</strong> Would you share your experience on Google!</p>
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
                    <div class="text-center">
                        <p>We strive for 100% customer satisfaction. If we felt short, please tell us more so we can address your concerns.</p>
                    </div>
                    <div class="mt-4">
                        <form action="https://trustedwebcart.com/fwd-reviews/customer-feedback" method="POST" id="customerFeedback">
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
                                <button type="button" class="btn btn-primary feedbackBtn">SEND MESSAGE</button>
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