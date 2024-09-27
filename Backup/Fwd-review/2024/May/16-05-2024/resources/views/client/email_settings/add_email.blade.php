@extends('client.layouts.app')
@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css">
@endsection
@section('content')
<div class="gc_row px-md-4 px-2">
    <div class="card mt-md-3 mb-3">
        <div class="card-body d-flex align-items-center p-lg-3 p-2 staff_header">
            <div class="pe-4 fs-5">Add Email</div>
            <div class="ms-auto">
                <a href="{{route('client.email.settings')}}" class="btn gc_btn">Go Back</a>
            </div>
        </div>
    </div>
    <div class="card">
    <form action="{{route('client.add.email.data')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-floating mt-4">
                                <input type="text" class="form-control" name="name" id="Name" value="{{old('name')}}" placeholder="" autofocus />
                                <label for="Name" class="form-label">Email Name (internal)</label>
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6 form-floating mt-4">
                                <select class="form-control" name="delay_days" id="delayDays">
                                    <option value="0">Immediately</option>
                                    <option value="1">next day</option>
                                    <option value="2">in 2 days</option>
                                    <option value="3">in 3 days</option>
                                    <option value="4">in 4 days</option>
                                    <option value="5">in 5 days</option>
                                    <option value="6">in 6 days</option>
                                    <option value="7">in 7 days</option>
                                    <option value="14">in 14 days</option>
                                </select>
                                <label for="delayDays" class="form-label">When to Send</label>
                                @if ($errors->has('delay_days'))
                                    <span class="text-danger">{{ $errors->first('delay_days') }}</span>
                                @endif
                            </div>
                            <div class="col-md-12 form-floating mt-4">
                                <input type="text" class="form-control" name="subject" id="Subject" value="{{old('subject')}}" placeholder="" />
                                <label for="Subject" class="form-label">Subject Line (required)</label>
                                @if ($errors->has('subject'))
                                    <span class="text-danger">{{ $errors->first('subject') }}</span>
                                @endif
                            </div>
                            <div class="col-md-12 mt-4">
                                <label for="EmailHtml" class="form-label">Email HTML</label>
                                <textarea id="summernote" name="email_html"></textarea>
                                @if ($errors->has('email_html'))
                                    <span class="text-danger">{{ $errors->first('email_html') }}</span>
                                @endif
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn gc_btn mt-3">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h6>Mail Settings</h6>
                        <div class="form-floating mt-4">
                            <input type="text" class="form-control" name="from_name" id="FromName" value="{{old('from_name')}}" placeholder="" />
                            <label for="FromName" class="form-label">From Name</label>
                            @if ($errors->has('from_name'))
                                <span class="text-danger">{{ $errors->first('from_name') }}</span>
                            @endif
                        </div>
                        <div class="form-floating mt-4">
                            <input type="text" class="form-control" name="from_email" id="FromEmail" value="{{old('from_email')}}" placeholder="" />
                            <label for="FromEmail" class="form-label">From email</label>
                            @if ($errors->has('from_email'))
                                <span class="text-danger">{{ $errors->first('from_email') }}</span>
                            @endif
                            <p class="mt-2">ATTENTION: Using any custom email address here may cause deliverability issues. Addresses from free email providers (e.g. Gmail) will cause emails to bounce.</p>
                        </div>
                        <div class="form-floating mt-4">
                            <input type="text" class="form-control" name="reply_to" id="ReplyTo" value="{{old('reply_to')}}" placeholder="" />
                            <label for="ReplyTo" class="form-label">Reply To</label>
                            @if ($errors->has('reply_to'))
                                <span class="text-danger">{{ $errors->first('reply_to') }}</span>
                            @endif
                        </div>
                        <div class="form-floating mt-4">
                            <select name="timezone" class="form-control select2" id="TimeZone">
                                @if(!blank($timezones))
                                    @foreach ($timezones as $timezone)
                                        <option value="{{$timezone['id']}}" @if(old('timezone') == $timezone['id']) selected @endif>{{$timezone['name']}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="TimeZone" class="form-label">Timezone</label>
                            @if ($errors->has('timezone'))
                                <span class="text-danger">{{ $errors->first('timezone') }}</span>
                            @endif
                        </div>
                        <hr>
                        <div class="mt-3">
                            <span>
                                <h6>Tip:</h6>
                                <p>Use the following placeholders to personalize your messages:</p>
                            </span>
                        </div>
                        <div>
                            <input onclick="this.focus(); this.select()" readonly="readonly" class="form-control" type="text" value="[[first_name]]">
                        </div>
                        <div class="mt-2">
                            <input onclick="this.focus(); this.select()" readonly="readonly" class="form-control" type="text" value="[[last_name]]">
                        </div>
                        <div class="mt-3">
                            <span>
                                <h6>Note:</h6>
                                <p>We also use placeholders for the following values:</p>
                            </span>
                        </div>
                        <div>
                            <input onclick="this.focus(); this.select()" readonly="readonly" class="form-control" type="text" value="[[profile_name]]">
                            <p class="fs-12">"{{url('/')}}/{{$business->business_name}}"</p>
                        </div>
                        <div>
                            <input onclick="this.focus(); this.select()" readonly="readonly" class="form-control" type="text" value="[[profile_url]]">
                            <span>
                                <p class="fs-12">*Required
                                    "{{url('/')}}/{{$business->shortname}}"
                                </p>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        var $editor = $('#summernote');
        // $editor.summernote();
        $editor.summernote({
            height: 200,
            callbacks: {
            onPaste: function(e) {
            console.log('Called event paste', e);
            },
            onImageUpload: function(files) {
            console.log(files);
            // upload image to server and create imgNode...
            $summernote.summernote('insertNode', imgNode);
            }
            },
            toolbar: [
            // [groume, [list of button]]
            ['style', ['bold', 'italic', 'underline']],
                    ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['height', ['height']],
            ['operation', ['undo', 'redo']],
            ['font', ['strikethrough', 'superscript', 'subscript', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['object', ['link', 'table', 'picture', 'video']],
            ['misc', [ 'help', 'fullscreen', 'codeview']]
            ]
        });
    });
</script>
@endsection
