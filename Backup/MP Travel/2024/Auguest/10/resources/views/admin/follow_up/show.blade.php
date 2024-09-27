@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Follow Up</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <div class="card-header" id="kt_chat_messenger_header">
                        <div class="card-title">
                            <div class="d-flex justify-content-center flex-column me-3">
                                <span
                                    class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1">{{ $ticket->event_name }}
                                    ( {{ isset($ticket->leadDetail) ? $ticket->leadDetail->lead_id : '-' }} )</span>
                                <div class="mb-0 lh-1">
                                    <span class="badge badge-success badge-circle w-10px h-10px me-1"></span>
                                    <span class="fs-7 fw-semibold text-muted">Active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body" id="kt_chat_messenger_body">
                        <div class="scroll-y me-n5 pe-5 h-300px h-lg-auto" id="scroll_message_div">
                            @foreach ($ticket->commentDetail as $ticketComment)
                                @if ($ticketComment->user_id !== Auth()->user()->id)
                                    <div class="d-flex justify-content-start mb-10 ">
                                        <div class="d-flex flex-column align-items-start">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="symbol  symbol-35px symbol-circle" style="width: 5%"><img
                                                        alt="Pic"
                                                        src="{{ Common::getImageUrl($ticketComment->userDetail?$ticketComment->userDetail->profile_image :"") }}">
                                                </div>
                                                <div class="ms-3">
                                                    <a href="#"
                                                        class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">{{ $ticketComment->userDetail ? $ticketComment->userDetail->name: "" }}</a>
                                                    <span
                                                        class="text-muted fs-7 mb-1">{{ str_replace('after', 'ago', Utility::getHumanReadDiff($ticketComment->created_at)) }}</span>
                                                </div>

                                            </div>
                                            <div class="p-5 rounded bg-light-info text-gray-900 fw-semibold mw-lg-400px text-start"
                                                data-kt-element="message-text">
                                                @if ($ticketComment->message_type == 'file' || $ticketComment->message_type == 'text_file')
                                                    <a href="{{ asset('storage/' . $ticketComment->message_file) }}"
                                                        download>
                                                        <img src="{{ asset('assets/img/message/file.png') }}"
                                                            width="25px">
                                                    </a></br>
                                                @endif
                                                @if ($ticketComment->message_type == 'text' || $ticketComment->message_type == 'text_file')
                                                    {{ $ticketComment->comment }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex justify-content-end mb-10" id="add_new_message_div">
                                        <div class="d-flex flex-column align-items-end">
                                            <div class="d-flex align-items-center mb-2 justify-content-end">
                                                <div class="me-3">
                                                    <span
                                                        class="text-muted fs-7 mb-1">{{ str_replace('after', 'ago', Utility::getHumanReadDiff($ticketComment->created_at)) }}</span>
                                                    <a href="#"
                                                        class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1">{{ Auth()->user()->name }}</a>
                                                </div>
                                                <div class="symbol  symbol-35px symbol-circle" style="width: 5%"><img
                                                        alt="Pic"
                                                        src="{{ Common::getImageUrl(Auth()->user()->profile_image) }}">
                                                </div>
                                            </div>
                                            <div class="p-5 rounded bg-light-primary text-gray-900 fw-semibold mw-lg-400px text-end"
                                                data-kt-element="message-text">
                                                @if ($ticketComment->message_type == 'file' || $ticketComment->message_type == 'text_file')
                                                    <a href="{{ asset('storage/' . $ticketComment->message_file) }}"
                                                        download>
                                                        <img src="{{ asset('assets/img/message/file.png') }}"
                                                            width="25px">
                                                    </a></br>
                                                @endif
                                                @if ($ticketComment->message_type == 'text' || $ticketComment->message_type == 'text_file')
                                                    {{ $ticketComment->comment }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div id="uploded_image_div">
                        <img id="upload_image" alt="your image" class="d-none" />
                        <i class="ki-outline ki-cross-circle text-danger d-none" id="cross_icon_for_image"
                            style="font-size: 20px !important" onclick="removeUploadImage()"></i>
                    </div>
                    <div class="card-footer pt-4" id="kt_chat_messenger_footer">
                        <form id="send_message_form" action="{{ route('follow-comment-store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="follow_id" value="{{ $ticket->id }}" />
                            <input type="hidden" name="lead_id" value="{{ $ticket->leadDetail->id }}" />
                            <textarea class="form-control form-control-flush mb-3" rows="1" data-kt-element="input" id="comment_message"
                                name="comment_message" placeholder="Type a message"></textarea>
                            <div class="d-flex flex-stack position-relative">
                                <div class="d-flex align-items-center me-2">
                                    <input class="btn btn-sm btn-icon btn-active-light-primary me-1 opacity-0 w-25"
                                        type="file" data-bs-toggle="tooltip" title="Image Upload"
                                        onchange="checkImageType(this)" id="message_file" name="message_file" />
                                    <i class="ki-outline ki-paper-clip fs-3 position-absolute"
                                        style="left: 10px!important"></i>
                                </div>
                                <button class="btn btn-primary" type="submit" id="sendBtn">Send</button>
                            </div>
                        </form>
                        <span id="comment_message_error" class="text-danger"></span>
                    </div>
                </main>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(e) {
            $("#scroll_message_div").animate({
                scrollTop: $(
                    '#scroll_message_div').get(0).scrollHeight
            });
        })

        function checkImageType(input) {
            var url = input.value;
            $('#cross_icon_for_image').addClass('d-none');
            $('#upload_image').addClass('d-none');
            $('#comment_message_error').html("")
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#upload_image').attr('src', e.target.result);
                    $('#cross_icon_for_image').removeClass('d-none');
                    $('#upload_image').removeClass('d-none');
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                $('#comment_message_error').html("Please upload only image file.")
            }
        }

        function removeUploadImage() {
            $('#message_file').val("");
            $('#upload_image').attr('src', "");
            $('#cross_icon_for_image').addClass('d-none');
            $('#upload_image').addClass('d-none');
        }
        $('#send_message_form').submit(function(e) {
            var message = $('#comment_message').val();
            var file = $('#message_file').val();
            var cnt = 0;
            $('#comment_message_error').html("")
            if (message.trim() == "" && file == "") {
                $('#comment_message_error').html("Please end comment other wise upload a file.")
                cnt = 1;
            }
            if (cnt == 1) {
                return false;
            }
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ route('follow-comment-store') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    var res = data.data;
                    this.reset();
                    $('#cross_icon_for_image').addClass('d-none');
                    $('#upload_image').addClass('d-none');
                    var html = "";
                    var textMessage = "";
                    if (res.message_type == 'file' || res.message_type == 'text_file') {
                        textMessage += `<a href="{{ asset('storage/`+res.message_file+`') }}"
                                                                    download>
                                                                    <img src="{{ url('/') }}/public/assets/media/png_images/file.png"
                                                                        width="25px">
                                                                </a></br>`;
                    }
                    if (res.message_type == 'text' || res.message_type == 'text_file') {
                        textMessage += res.comment;
                    }

                    html += `<div class="d-flex justify-content-end mb-10">
                                                    <div class="d-flex flex-column align-items-end">
                                                        <div class="d-flex align-items-center mb-2" >
                                                            <div class="me-3">
                                                                <span
                                                                    class="text-muted fs-7 mb-1">{{ str_replace('after', 'ago', Utility::getHumanReadDiff(`+res.created_at+`)) }}</span>
                                                                <a href="#"
                                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1">{{ Auth()->user()->name }}</a>
                                                            </div>
                                                            <div class="symbol  symbol-35px symbol-circle" style="width: 5%"><img
                                                                    alt="Pic"
                                                                    src="{{ Common::getImageUrl(Auth()->user()->profile_image) }}">
                                                            </div>
                                                        </div>
                                                        <div class="p-5 rounded bg-light-primary text-gray-900 fw-semibold mw-lg-400px text-end"
                                                            data-kt-element="message-text">
                                                            ` + textMessage + `
                                                        </div>
                                                    </div>`;
                    $('#scroll_message_div').append(html);
                    $("#scroll_message_div").animate({
                        scrollTop: $(
                            '#scroll_message_div').get(0).scrollHeight
                    });
                },
                error: function(data) {
                    toastr.errot(data.responseJSON.message);
                }
            });
        });
    </script>
@endsection
