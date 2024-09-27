@extends('client.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5>Digital</h5>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <h5>Landing Page Widget</h5>
                <p><strong>Embed Code</strong></p>
                <textarea onclick="this.focus(); this.select()" readonly="readonly" class="form-control" rows="5"><div class="reviewmgr-embed" data-url="{{url('/')}}/{{$business->shortname}}"></div><script src="{{url('/')}}/stream.js"></script></textarea>
                <div class="mt-3">
                    <h6>Preview</h6>
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
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <h5>Email Signature Snippets</h5>
                <p>Highlight the signature content and copy/paste into email.</p>
                <div>
                    <h4 style="font-family:'Roboto','Helvetica Neue',Helvetica,Arial,sans-serif;font-size:13px;font-weight:500;color:#fff;padding:0px;margin:0 0 5px;">How did we do?</h4>
                    <table border="0" cellpadding="0" cellspacing="0" style="width:auto;padding:0;margin:3px 0;"><tr class="border-none"><td class="border-none" width="35" height="35"><a href="{{url('/')}}/{{$business->shortname}}/#positive" target="_blank" style="border:none;text-decoration:none;font-size:9px;"><img src="{{url('/')}}/thumbs_up.png" width="35" height="35" /></a></td><td width="15" class="border-none" > </td><td class="border-none" width="35" height="35"><a href="{{url('/')}}/{{$business->shortname}}/#negative" target="_blank" style="border:none;text-decoration:none;font-size:9px;"><img src="{{url('/')}}//thumbs_down.png" width="35" height="35" /></a></td></tr></table>
                    <a href="{{url('/')}}/{{$business->shortname}}" target="_blank" style="font-family:'Roboto','Helvetica Neue',Helvetica,Arial,sans-serif;font-size:11px;text-decoration:underline;color:#4A90E2;">Click to rate your experience with {{$business->business_name}}</a>
                </div>
                <div class="mt-3">
                    <textarea class="form-control" onclick="this.focus(); this.select()" readonly="readonly" rows="5"><h4 style="font-family:'Roboto','Helvetica Neue',Helvetica,Arial,sans-serif;font-size:13px;font-weight:500;color:#000000;padding:0px;margin:0 0 5px;">How did we do?</h4><table border="0" cellpadding="0" cellspacing="0" style="width:auto;padding:0;margin:3px 0;"><tr><td width="35" height="35"><a href="{{url('/')}}/{{$business->shortname}}/#positive" target="_blank" style="border:none;text-decoration:none;font-size:9px;"><img src="{{url('/')}}/thumbs_up.png" width="35" height="35" /></a></td><td width="15"> </td><td width="35" height="35"><a href="{{url('/')}}/{{$business->shortname}}/#negative" target="_blank" style="border:none;text-decoration:none;font-size:9px;"><img src="{{url('/')}}//thumbs_down.png" width="35" height="35" /></a></td></tr></table><a href="{{url('/')}}/{{$business->shortname}}" target="_blank" style="font-family:'Roboto','Helvetica Neue',Helvetica,Arial,sans-serif;font-size:11px;text-decoration:underline;color:#4A90E2;">Click to rate your experience with {{$business->business_name}}</a></textarea>
                </div>
                <hr>
                <div>
                    <h4 style="font-family:'Roboto','Helvetica Neue',Helvetica,Arial,sans-serif;font-size:13px;font-weight:500;color:#fff;padding:0px;margin:0 0 5px;">How did we do?</h4>
                    <table border="0" cellpadding="0" cellspacing="0" style="width:auto;padding:0;margin:3px 0;"><tr class="border-none" ><td class="border-none" width="50" height="50"><a href="{{url('/')}}/{{$business->shortname}}/#positive" target="_blank" style="border:none;text-decoration:none;font-size:9px;"><img src="{{url('/')}}/thumbs_up.png" width="50" height="50" /></a></td><td width="15" class="border-none" > </td><td class="border-none"  width="50" height="50"><a href="{{url('/')}}/{{$business->shortname}}/#negative" target="_blank" style="border:none;text-decoration:none;font-size:9px;"><img src="{{url('/')}}//thumbs_down.png" width="50" height="50" /></a></td></tr></table>
                    <a href="{{url('/')}}/{{$business->shortname}}" target="_blank" style="font-family:'Roboto','Helvetica Neue',Helvetica,Arial,sans-serif;font-size:14px;text-decoration:underline;color:#4A90E2;">Click to rate your experience with {{$business->business_name}}</a>
                </div>
                <div class="mt-3">
                    <textarea class="form-control" onclick="this.focus(); this.select()" readonly="readonly" rows="5"><h4 style="font-family:'Roboto','Helvetica Neue',Helvetica,Arial,sans-serif;font-size:16px;font-weight:500;color:#000000;padding:0px;margin:0 0 5px;">How did we do?</h4><table border="0" cellpadding="0" cellspacing="0" style="width:auto;padding:0;margin:3px 0;"><tr><td width="35" height="35"><a href="{{url('/')}}/{{$business->shortname}}/#positive" target="_blank" style="border:none;text-decoration:none;font-size:9px;"><img src="{{url('/')}}/thumbs_up.png" width="35" height="35" /></a></td><td width="15"> </td><td width="35" height="35"><a href="{{url('/')}}/{{$business->shortname}}/#negative" target="_blank" style="border:none;text-decoration:none;font-size:9px;"><img src="{{url('/')}}//thumbs_down.png" width="35" height="35" /></a></td></tr></table><a href="{{url('/')}}/{{$business->shortname}}" target="_blank" style="font-family:'Roboto','Helvetica Neue',Helvetica,Arial,sans-serif;font-size:11px;text-decoration:underline;color:#4A90E2;">Click to rate your experience with {{$business->business_name}}</a></textarea>
                </div>
                <!-- <div class="reviewmgr-embed" data-url="https://trustedwebcart.com/fwd-reviews/fwd"></div><script src="https://trustedwebcart.com/fwd-reviews/stream.js"></script> -->
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <h5>QR Code</h5>
                <div class="justify-content-center">
                    <div class="qrcode">
                        {!! QrCode::size(256)->generate(url('/').'/'.$business->shortname) !!}
                    </div>
                    <div class="my-3">
                        <a href="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(1024)->generate(url('/').'/'.$business->shortname)) !!}" Download class="btn gc_btn">Download</a> 
                    </div>
                    <a href="{{url('/')}}/{{$business->shortname}}" target="_blank">{{url('/')}}/{{$business->shortname}}</a>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <h5>"Review us" Button</h5>
                <hr>
                <a href="{{url('/')}}/{{$business->shortname}}" style="background-color: #298CFF;color: #FFFFFF;display: inline-block;padding: 10px 20px;margin: 0 2px;font-weight: normal;font-size: 15px;text-decoration: none;text-transform: uppercase;border-width: 0;border-radius: 2px;cursor: pointer;border-width: 1px 1px 3px;" data-content="Review Us" data-replace="true" target="_blank">Review Us</a>
                <div class="mt-3">
                    <p>Embed code:</p>
                    <textarea onclick="this.focus(); this.select()" readonly="readonly" class="form-control" rows="5"><a href="{{url('/')}}/{{$business->shortname}}" style="background-color: #298CFF;color: #FFFFFF;display: inline-block;padding: 10px 20px;margin: 0 2px;font-weight: normal;font-size: 15px;text-decoration: none;text-transform: uppercase;border-width: 0;border-radius: 2px;cursor: pointer;border-width: 1px 1px 3px;" data-content="Review Us" data-replace="true">Review Us</a></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection