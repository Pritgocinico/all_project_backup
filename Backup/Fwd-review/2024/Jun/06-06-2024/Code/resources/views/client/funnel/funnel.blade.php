@extends('client.layouts.app')

@section('content')
@if(!blank($message))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>{{$message}}
    </div>
@endif
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills mb-3 gap-2 justify-content-center" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#pills-links" class="nav-link active" id="pills-links-tab" data-bs-toggle="pill" data-bs-target="#pills-links" type="button" role="tab" aria-controls="pills-links" aria-selected="true">Links</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#pills-content" class="nav-link" id="pills-content-tab" data-bs-toggle="pill" data-bs-target="#pills-content" type="button" role="tab" aria-controls="pills-content" aria-selected="false">Content</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#pills-setup" class="nav-link" id="pills-setup-tab" data-bs-toggle="pill" data-bs-target="#pills-setup" type="button" role="tab" aria-controls="pills-setup" aria-selected="false">Setup</a>
                    </li>
                </ul>
                <form action="{{route('submit.funnel')}}" method="POST" id="formSubmit" enctype="multipart/form-data">
                    @csrf
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-links" role="tabpanel" aria-labelledby="pills-links-tab">
                            <div class="card card-border">
                                <div class="card-body">
                                    <h5>Business Location Info</h5>
                                    <hr>
                                    <div class="business-location">
                                        <h6>@if(isset($data['name'])){{$data['name']}}@else {{$business->name}} @endif</h6>
                                        {{-- @foreach ($data['address_components'] as $address)
                                            <p>{{$address['long_name']}}</p>
                                        @endforeach --}}
                                        <p>@if(isset($data['formatted_address'])){{ $data['formatted_address'] }}@else @endif</p>
                                        <!-- <div class="business-timezone">
                                            <h6>TimeZone</h6>
                                            <p>Central Time (US & Canada)</p>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-3 card-border">
                                <div class="card-body notificationCard">
                                    <h5>Notifications</h5>
                                    <hr>
                                    <div>
                                        <h6>Send email notifications to:</h6>
                                        <div class="email-notification">
                                            @if(!blank($notification_emails))
                                                <?php $i = 1; ?>
                                                @foreach($notification_emails as $emails)
                                                    <?php $i++; ?>
                                                    <div class="mt-2">
                                                        <input type="email" name="notification_email[{{$i}}][email]" class="form-control" id="" placeholder="email@example.com" value="{{$emails->email}}">
                                                        <input type="hidden" name="notification_email[{{$i}}][id]" value="{{$emails->id}}">
                                                    </div>
                                                @endforeach
                                            @else
                                                <div>
                                                    <input type="email" name="notification_email[0][email]" class="form-control" id="" placeholder="email@example.com">
                                                </div>
                                            @endif
                                        </div>
                                        <div class="mt-3">
                                            <a href="javascript:void(0);" class="btn gc_btn align-items-center addEmail">Add Email</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-3 card-border">
                                <div class="card-body">
                                    <h5>Social Links</h5>
                                    <hr>
                                    <div>
                                        <div>
                                            <input type="checkbox" name="social_media" class="from-control" @if($business->social_media == 'on') checked @endif>
                                            <span>Show social media links</span>
                                        </div>
                                        <div class="social-media">
                                            <div class="facebook">
                                                <img src="{{url('/')}}/assets/Images/facebook.png" class="icon-set" alt="facebook">
                                                <input type="text" name="facebook_url" class="form-control" placeholder="Facebook User Name" value="{{$business->facebook_url}}">
                                            </div>
                                            <div class="twitter">
                                                <img src="{{url('/')}}/assets/Images/twitter.png" class="icon-set" alt="twitter">
                                                <input type="text" name="twitter_url" class="form-control" placeholder="@ Twitter User name" value="{{$business->twitter_url}}">
                                            </div>
                                            <div class="instagram">
                                                <img src="{{url('/')}}/assets/Images/instagram.png" class="icon-set" alt="instagram">
                                                <input type="text" name="instagram_url" class="form-control" placeholder="Instagram User Name" value="{{$business->instagram_url}}">
                                            </div>
                                            <div class="instagram">
                                                <img src="{{url('/')}}/assets/Images/linkedin.png" class="icon-set" alt="instagram">
                                                <input type="text" name="linked_url" class="form-control" placeholder="Linkedin User Name" value="{{$business->linked_url}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-content" role="tabpanel" aria-labelledby="pills-content-tab">
                            <div class="card card-border">
                                <div class="card-body">
                                    <h5>Message to All Visitors</h5>
                                    <hr>
                                    <input type="text" name="visitor_title" id="visitorTitle" class="form-control" placeholder="e.g. Please review..." value="{{$business->visitor_title}}">
                                    <div class="mt-3">
                                        <textarea name="visitor_message" id="visitorMsg" class="form-control" placeholder="To your customers...">{{$business->visitor_message}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="card card-border mt-3">
                                <div class="card-body">
                                    <h5>Interaction Design</h5>
                                    <hr>
                                    <div>
                                        <label for="threshold">Threshold</label>
                                        <select name="threshold" class="form-control mt-2" id="threshold">
                                            <option value="1">All to private feedback</option>
                                            <option value="2" selected>Thumbs up to review</option>
                                            <option value="3">All to review</option>
                                        </select>
                                    </div>
                                </div>
                            </div> -->
                            
                            <div class="card card-border mt-3">
                                <div class="card-body">
                                    <h5>Private Feedback Workflow</h5>
                                    <p>These visitors are presented with your review site links and instructions.</p>
                                    <hr>
                                    <div>
                                        <label for="promptMessage" class="mb-2">Prompt Message</label>
                                        <textarea name="prompt_message" class="form-control">{{$business->prompt_message}}</textarea>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <label for="thumbsupText">Thumbs UP link text</label>
                                        <input type="text" name="thumbsup_text" id="thumbsupText" class="form-control" placeholder="Enter message" value="{{$business->thumbsup_text? $business->thumbsup_text : 'Thumbs Up' }}">
                                    </div>
                                    <div class="mt-3">
                                        <label for="thumbsdownText">Thumbs DOWN link text</label>
                                        <input type="text" name="thumbsdown_text" id="thumbsdownText" class="form-control" placeholder="Enter message" value="{{$business->thumbsdown_text? $business->thumbsdown_text : 'Thumbs Down' }}">
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="card card-border mt-3">
                                <div class="card-body">
                                    <h5>Contact info auto-fill</h5>
                                    <hr>
                                    <div>
                                        <input type="checkbox" name="contact_info" class="from-control" checked>
                                        <span>Contact info auto-fill</span>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                        <div class="tab-pane fade" id="pills-setup" role="tabpanel" aria-labelledby="pills-setup-tab">
                            <div class="card card-border">
                                <div class="card-body">
                                    <h5>Branding & Design</h5>
                                    <p>Settings apply to all pages for this business.</p>
                                    <hr>
                                    <div class="mt-3">
                                        <label for="businessName">Brand/Business Name</label>
                                        <input type="text" name="business_name" id="businessName" class="form-control" placeholder="Enter Business Name " value="@if(isset($data['name']) && $data['name'] != ''){{$data['name']}} @else {{$business->name}} @endif">
                                    </div>
                                    <div class="mt-3">
                                        <label for="thumbsupText">Shortname (for URL)</label>
                                        <input type="text" name="shortname" class="form-control" placeholder="Enter shortname" value="{{$business->shortname}}">
                                    </div>
                                    <div class="mt-3">
                                        <label for="internalId">Logo</label>
                                        <input type="file" name="logo" id="Logo" class="form-control">
                                        <div class="logo-preview">
                                            @if(!blank($business->logo))
                                                <img src="{{url('/')}}/logos/{{$business->logo}}" class="logo_preview w-100" alt="">
                                            @else
                                                <img src="" alt="" class="logo_preview w-100">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <input type="checkbox" name="show_business_name" class="from-control" checked>
                                        <span>Show business name text</span>
                                    </div>
                                    <div class="mt-3">
                                        <label for="brandColor">Brand Color</label>
                                        <div class="mt-3">
                                            <span class="color-picker">
                                                <label for="colorPicker">
                                                    <input type="color" name="brand_color" value="@if(!blank($business->brand_color)){{$business->brand_color}}@else black @endif" id="colorPicker">
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="business_id" value="{{$business->id}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-9 position-relative">
        <div class="card" style="width:100%; position:sticky; top:100px;">
            <div class="card-body">
                <div class="content-area">
                    <div class="header-content" style="background:@if(!blank($business->brand_color)) {{$business->brand_color}} @else #111111 @endif">
                        <p class="v-msg">{{$business->visitor_title}}</p>
                        <h3 class="b-name">{{$business->name}}</h3>
                    </div>
                    <div class="body-content">
                        <div class="mb-5">
                            @if(!blank($business->logo))
                                <div class="logo">
                                    <img src="{{url('/')}}/logos/{{$business->logo}}" class="logo_preview" style="max-width:200px" alt="">
                                </div>
                            @else
                                <div class="logo">
                                    <img src="" class="logo_preview" style="max-width:200px" alt="">
                                </div>
                            @endif
                            <div id="message" class="v-message"><p>{{$business->visitor_message}}</p></div>
                            <div class="review-thumbs">
                                <a href="{{url('/')}}/{{$business->shortname}}/#binary_choice_positive" class="thumbup" target="_blank" style="border:none;text-decoration:none;font-size:9px; padding:0px 20px; text-align:center;">
                                    <img src="{{url('/')}}/assets/Images/thumbs_up.svg" width="120" height="120" alt="Good" />
                                    <p>{{$business->thumbsup_text}}</p>
                                </a>
                                <a href="{{url('/')}}/{{$business->shortname}}/#binary_choice_negative" class="thumbdown" target="_blank" style="border:none;text-decoration:none;font-size:9px; padding:0px 20px; text-align:center;">
                                    <img src="{{url('/')}}/assets/Images/thumbs_down.svg" width="120" height="120" alt="Not bad" />
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
                                    @if($business->linked_url != '' || $business->linked_url != NULL)
                                        <a href="{{$business->linked_url}}" target="_blank"><img src="{{url('/')}}/assets/Images/linkedin.png" class="review-icon" alt="facebook"></a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    var i = 100;
    $(document).on('click','.addEmail',function(){
        i++;
        var html = '';
        html += '<div class="mt-2 d-flex align-items-center notification-'+i+'">';
        html += '<input type="email" name="notification_email['+i+'][email]" class="form-control" id="" placeholder="email@example.com">';
        html += '<a href="javascript:void(0);" class="deleteNotificationEmail p-2" data-id="'+i+'"><i class="fa fa-close"></i></a>';
        html += '</div>';
        $('.email-notification').append(html);
    });
    $(document).on('click','.deleteNotificationEmail',function(){
        var id = $(this).data('id');
        $('.notification-'+id).remove();
    });
    $(document).on('change','input, textarea',function(){
    //    $('#formSubmit').submit();
        var frm = $('#formSubmit');
        var formData = new FormData(frm[0]);
        // formData.append('file', $('input[type=file]')[0].files[0]);
        $.ajax({
            type: frm.attr('method'),
            url: "{{route('submit.funnel')}}",
            data: formData,
            processData: false,
            contentType: false,
            success: function(data){
                $(".content-area").load(location.href + " .content-area");
                $(".notificationCard").load(location.href + " .notificationCard");
            }
        });
    })
     $('#Logo').change(function(){
        let reader = new FileReader();
        reader.onload = (e) => {
            $('.logo_preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    });
    $('#colorPicker').on('change',function(){
        var color = $(this).val();
        $('.header-content').css('background-color',color);
    });
    $('#businessName').on('keyup',function(){
        var businessname = $(this).val();
        $('.b-name').html(businessname);
    });
    $('#visitorMessage').on('keyup',function(){
        var visitormessage = $(this).val();
        $('.v-msg p').html(visitormessage);
    });
    $('#visitorMsg').on('keyup',function(){
        var visitormsg = $(this).val();
        $('.v-message p').html(visitormsg);
    });
    $('#thumbsupText').on('keyup',function(){
        var up = $(this).val();
        $('.thumbup p').html(up);
    });
    $('#thumbsdownText').on('keyup',function(){
        var up = $(this).val();
        $('.thumbdown p').html(up);
    });
    // tinymce.init({
    //  selector: 'textarea#tiny',
    // });
    // tinymce.init({
    //  selector: 'textarea#tiny1',
    // });
    // tinymce.init({
    //  selector: 'textarea#tiny2',
    // });
    document.querySelectorAll('input[type=color]').forEach(function(picker) {
        var targetLabel = document.querySelector('label[for="' + picker.id + '"]'),
        codeArea = document.createElement('span');

        codeArea.innerHTML = picker.value;
        targetLabel.appendChild(codeArea);

        picker.addEventListener('change', function() {
            codeArea.innerHTML = picker.value;
            targetLabel.appendChild(codeArea);
        });
    });
</script>
@endsection
