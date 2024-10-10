@extends('admin.partials.header', ['active' => 'follow_up'])

@section('style')
    <link rel="stylesheet" href="{{ asset('plugin/mentiony/jquery.mentiony.css') }}">
    <link rel="stylesheet" href="{{ asset('plugin/dropzone/dropzone.min.css') }}">
@endsection
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="content">
                <div class="card mb-4 p-3">
                    <div class="card-body">
                        <div class="d-md-flex gap-4 align-items-center">
                            <h4 class="text-capitalize mb-0 fs-24">{{ $followUp->event_name }}</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card task-view-sidebar p-2">
                            <div class="card-body my-3">
                                <h5 class="font-weight-bold fs-20 houmanity-color">Task information</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="ps-3 mt-3"
                                            style="border-left:3px solid #fe7070; border-spacing: 10px 0px;">
                                            <p class="mb-1">Created By </p>
                                            <h6><span
                                                    class="font-weight-bold">{{ isset($followUp->userDetail) ? $followUp->userDetail->name : '-' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-2 mt-3">
                                            <h6 class="font-weight-bold">Status </h6>
                                            <div class="btn-group ml-5">
                                                @if ($followUp->event_status == 1)
                                                    <span class="badge bg-primary text-white fs-14">In Progress</span>
                                                @elseif($followUp->event_status == 5)
                                                    <span class="badge bg-info fs-14">On Hold</span>
                                                @elseif($followUp->event_status == 2)
                                                    <span class="badge bg-success text-white fs-14">Completed</span>
                                                @endif
                                                <ul class="dropdown-menu task-status">
                                                    <li><a class="dropdown-item status @if ($followUp->event_status == 1) hide @endif"
                                                            href="#" data-status="1"
                                                            data-task="{{ $followUp->id }}">Not Started</a></li>
                                                    <li><a class="dropdown-item status @if ($followUp->event_status == 5) hide @endif"
                                                            href="#" data-status="2"
                                                            data-task="{{ $followUp->id }}">In Progress</a></li>
                                                    <li><a class="dropdown-item status @if ($followUp->event_status == 2) hide @endif"
                                                            href="#" data-status="5"
                                                            data-task="{{ $followUp->id }}">Completed</a></li>
                                                </ul>
                                            </div>-
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="ps-3 mt-3" style="border-left:3px solid #689efd;">
                                            <p class="mb-1">Start Date </p>
                                            <h6><span
                                                    class="fs-14 font-weight-bold">{{ Utility::convertFDYFormat($followUp->start_date) }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="ps-3 mt-3">
                                            <h6 class="font-weight-bold">Priority</h6>
                                            <div class="ml-2">
                                                @if ($followUp->priority == 1)
                                                    <div class="d-flex justify-content-between">
                                                        <span class="badge bg-light text-dark fs-14">Low</span>
                                                    </div>
                                                @elseif($followUp->priority == 2)
                                                    <div class="d-flex justify-content-between">
                                                        <span class="badge bg-info fs-14">Medium</span>
                                                    </div>
                                                @elseif($followUp->priority == 3)
                                                    <div class="d-flex justify-content-between">
                                                        <span class="badge bg-secondary fs-14">High </span>
                                                    </div>
                                                @elseif($followUp->priority == 4)
                                                    <div class="d-flex justify-content-between">
                                                        <span class="badge bg-danger fs-14">Urgent</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="ps-3 mt-3" style="border-left:3px solid #7ddabd;">
                                            <p class="mb-1">Due Date : </p>
                                            <h6><span
                                                    class="fs-14 font-weight-bold">{{ Utility::convertFDYFormat($followUp->due_date) }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h5 class="fs-17 mt-2">Assign Task To</h5>
                                @if (count($followUp->followUpMemberDetail) > 0)
                                    @foreach ($followUp->followUpMemberDetail as $assignee)
                                        @php $image = asset('assets/img/follow_up/profile.png') @endphp
                                        @if (isset($assignee->userDetail) && $assignee->userDetail->profile_image)
                                            @php $image = asset('storage/'.$assignee->userDetail->profile_image) @endphp
                                        @endif
                                        <img src="{{ $image }}"
                                            title="{{ isset($assignee->userDetail) ? $assignee->userDetail->name : '-' }}"
                                            data-bs-toggle="tooltip" data-bs-placement="top" height="30px" width="30px"
                                            class="rounded-circle">
                                    @endforeach
                                @endif
                                <div>
                                    <a href="{{ route('follow-up.edit', $followUp->id) }}"
                                        class="btn btn-primary fs-12 btn-icon mt-2 py-1">
                                        Add
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card p-2">
                            <div class="card-body m-4">
                                <h5 class="font-weight-bold houmanity-color fs-20">Task Comments</h5>
                                <hr>
                                <a class="btn btn-primary btn-sm add-item fs-12">
                                    <i class="bi bi-plus"></i>Add Sub Tasks
                                </a>
                                <div class="checklist-items">
                                    @if (isset($followUp->subTaskData))
                                    @foreach ($followUp->subTaskData as $item)
                                            <div class="checklists mt-3">
                                                <div class="d-flex mb-1">
                                                    <div class="mr-25">
                                                        <div class="round">
                                                            <input type="checkbox" id="checkbox-{{ $item->id }}"
                                                                class="chk" data-id="{{ $item->id }}"
                                                                name="chk-item"
                                                                {{ $item->task_status == 1 ? 'checked' : '' }} />
                                                            <label for="checkbox-{{ $item->id }}"></label>
                                                        </div>
                                                    </div>
                                                    <div class="w-100">
                                                        <textarea class="form-control ml-2 rounded-0 form-control-sm list-note" name="checklist-note"
                                                            data-id="{{ $item->id }}">{{ $item->note }}</textarea>
                                                        <p class="p-0 mt-1 fs-14">Created By : <span class="fw-bold">
                                                                {{ isset($item->createdUserDetail) ? $item->createdUserDetail->name : '-' }}</span>
                                                            @if ($item->checked_by != null)
                                                                <?php $checked = DB::table('users')
                                                                    ->where('id', $item->checked_by)
                                                                    ->first(); ?>
                                                                @if (!empty($checked))
                                                                    , Checked By :
                                                                    <span class="fw-bold">{{ $checked->name }}</span>
                                                                @endif
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <a class="ms-3 remove-item align-self-center" href="#"
                                                        data-id="{{ $item->id }}" style="display: inline;"><i
                                                            class="bi bi-trash-fill fs-20"></i></a>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <hr>
                                <h5 class="font-weight-bold fs-20">Attachments</h5>
                                <div class="row">
                                    @if (isset($followUp->followUpFiles))
                                        @foreach ($followUp->followUpFiles as $file)
                                            <div class="col-md-3">
                                                <a href="{{ asset('storage/' . $file->file) }}" download>
                                                    @php
                                                        $imageUrl = asset('assets/img/message/file.png');

                                                        if ($file->file_type == 'pdf') {
                                                            $imageUrl = asset('assets/img/message/pdf.png');
                                                        } elseif (
                                                            $file->file_type == 'csv' ||
                                                            $file->file_type == 'exls'
                                                        ) {
                                                            $imageUrl = asset('assets/img/message/excel.png');
                                                        } elseif (
                                                            $file->file_type == 'gif' ||
                                                            $file->file_type == 'png' ||
                                                            $file->file_type == 'jpeg' ||
                                                            $file->file_type == 'jpg'
                                                        ) {
                                                            $imageUrl = asset('storage/' . $file->file);
                                                        }
                                                    @endphp
                                                    <img src="{{ $imageUrl }}" width="40px">
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="mt-3">
                                    <a class="btn btn-primary fs-12" data-bs-toggle="collapse" href="#collapseUpload"
                                        role="button" aria-expanded="false" aria-controls="collapseExample">
                                        Add Attachments
                                    </a>
                                    <div class="collapse" id="collapseUpload">
                                        <div class="col-md-12 mt-3">
                                            <div class="card p-2">
                                                <form action="{{ route('upload_follow_up_file') }}" method="post"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <h6>Upload File</h6>
                                                    <input type="file" name="document" class="form-control" required>
                                                    <input type="hidden" name="task_id" id=""
                                                        value="{{ $followUp->id }}">
                                                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h5>
                                    <a class="" data-bs-toggle="collapse" href="#collapseExample" role="button"
                                        aria-expanded="false" aria-controls="collapseExample">
                                        Comments
                                    </a>
                                </h5>
                                <div class="container mt-5">
                                    @if (isset($followUp->commentDetail))
                                        @foreach ($followUp->commentDetail as $comment)
                                            <div class="row mt-2">
                                                <div class="col-md-3 card p-2 dis-comments justify-content-center border-0">
                                                    <div class="d-flex">
                                                        <div class="avatar me-3">
                                                            @php
                                                                $image = asset('assets/img/user/user.jpg');
                                                                if (isset($comment->userDetail) && $comment->userDetail->profile_image !== null) {
                                                                    $image = asset(
                                                                        'storage/' .$comment->userDetail->profile_image,
                                                                    );
                                                                } elseif (isset($settings)) {
                                                                    $image = asset('storage/' . $settings->fa_icon);
                                                                }
                                                            @endphp
                                                            <img src="{{$image}}" height="30px" width="30px" class="rounded-circle"></img>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ isset($comment->userDetail) ? $comment->userDetail->name : "-" }}</h6>
                                                            <p class="mb-0 fs-14">
                                                                {{ isset($comment->userDetail) && isset($comment->userDetail->designationDetail) ? $comment->userDetail->designationDetail->name : "-" }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-9 card p-2 dis-comments ">
                                                    <div class="">
                                                        <div class="border border-commet p-2">
                                                            <p class="">{!! $comment->comment !!}</p>
                                                            @if (isset($comment->commentFileDetail))
                                                                @foreach ($comment->commentFileDetail as $file)
                                                                        <a href="{{ URL::asset('task-files/' . $file->file) }}"
                                                                            download="{{ $file->file_name }}"><i
                                                                                class="bi bi-file-earmark-arrow-down-fill"></i>{{ $file->file_name }}</a>
                                                                @endforeach
                                                            @endif
                                                            <p class="text-end mb-0">
                                                                <i class="bi bi-clock me-1"></i>
                                                                <i>{{ Utility::convertDmyAMPMFormat($comment->created_at) }}</i>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-12 mt-5">
                                    <div class="mb-3">
                                        <div class="label mb-2">Remarks</div>
                                        <textarea name="comment" class="task-comment form-control" id="comment" rows="2" placeholder="Enter Remarks"></textarea>
                                        <span class="error-comment text-danger"></span>
                                    </div>
                                    <div class="label mb-2">Upload document</div>
                                    <form method="post" action="{{ route('follow_up.comment') }}"
                                        enctype="multipart/form-data" class="dropzone" id="dropzone1">
                                        @csrf
                                        <input type="hidden" name="comment" class="hidden_comment" value="">
                                        <input type="hidden" name="task_id" value="{{ $followUp->id }}">
                                    </form>

                                    <a href="javascript:void(0);" class="btn-comment btn btn-primary btn-icon mt-3 fs-12"
                                        id="btn-comment" style="float:right;">
                                        Add Comment
                                    </a>
                                    <div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

@section('script')
    <script src="{{ asset('plugin/mentiony/jquery.mentiony.min.js') }}"></script>
    <script src="{{ asset('plugin/dropzone/dropzone.js') }}"></script>
    <script>
        $('textarea').mentiony({
            debug: 0,
            applyInitialSize: true,
            timeout: 400,
            triggerChar: '@',
            onDataRequest: function(mode, keyword, onDataRequestCompleteCallback) {
                $.ajax({
                    url: "{{ route('task.employee') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: {{ $followUp->id }}
                    },
                    dataType: 'json',
                    success: function(data) {
                        var data = data.emp;
                        data = jQuery.grep(data, function(item) {
                            return item.name.toLowerCase().indexOf(keyword.toLowerCase()) >
                                -1;
                        });
                        onDataRequestCompleteCallback.call(this, data);
                    }
                });

            },
            onKeyPress: function(event, oldInputEle, newEditableEle) {
                oldInputEle.trigger(event);
            },
            onKeyUp: function(event, oldInputEle, newEditableEle) {
                oldInputEle.trigger(event);
            },
            onBlur: function(event, oldInputEle, newEditableEle) {
                oldInputEle.trigger(event);
            },
            onPaste: function(event, oldInputEle, newEditableEle) {
                oldInputEle.trigger(event);
            },
            onInput: function(oldInputEle, newEditableEle) {

            },
            popoverOffset: {
                x: -30,
                y: 0
            },
            templates: {
                container: '<div id="mentiony-container-[ID]" class="mentiony-container"></div>',
                content: '<div id="mentiony-content-[ID]" class="mentiony-content" contenteditable="true"></div>',
                popover: '<div id="mentiony-popover-[ID]" class="mentiony-popover"></div>',
                list: '<ul id="mentiony-popover-[ID]" class="mentiony-list"></ul>',
                listItem: '<li class="mentiony-item p-3" data-item-id="">' +
                    '<div class="row">' +
                    '<div class="pl0 col-xs-12 col-sm-12 col-md-12 col-lg-12">' +
                    '<p class="title">Company name</p>' +
                    // '<p class="help-block fs-12">Addition information</p>' +
                    '</div>' +
                    '</div>' +
                    '</li>',
                normalText: '<span class="normal-text">&nbsp;</span>',
                highlight: '<span class="highlight"></span>',
                highlightContent: '<a href="[HREF]" class="mentiony-link">@[TEXT]</a>',
            }
        });
    </script>
    <script>
        "use strict";
        Dropzone.autoDiscover = false;
        $(document).ready(function() {
            $(document).on('click', '.add-item', function() {
                var count = 0;
                $('.chk-comment').each(function() {
                    count = $(this).data('count');
                });
                let number = parseInt(count) + 1;
                var item = '';
                item += '<div class="checklists chk-comment chk-comment-' + number +
                    ' d-flex mt-3" data-count="' + number + '">';
                item += '<div class="mr-25">';
                item += '<div class="round">';
                item += '<input type="checkbox" id="checkbox" class="chk chk-' + number + '" data-count="' +
                    number + '" name="chk-item[' + number + ']" />';
                item += '<label for="checkbox"></label>';
                item += '</div>';
                item += '</div>';
                item +=
                    '<textarea class="form-control ml-2 form-control-sm checklist-note checklist-note-' +
                    number + '" name="checklist-note[' + number + ']" data-count="' + number +
                    '" data-id="' + number + '" ></textarea>';
                // item += '<input type="input" class="form-control ml-2 checklist-note checklist-note-'+number+' form-control-sm" data-count="'+number+'" name="checklist-note['+number+']">';
                item += '<a class="ms-2 alig-self-center remove-chk" data-id=' + number +
                    ' href="#" style="display: inline;" data-count="' + number +
                    '"><i class="bi bi-trash-fill fs-20"></i></a>';
                item += '</div>';
                $('.checklist-items').append(item);
            });
            $(document).on('click', '.remove-chk', function() {
                var id = $(this).data('id');
                $(this).parent().remove();
            });
            $(document).on('change', '.checklist-note', function() {
                var count = $(this).data('count');
                var chk = 0;
                if ($('.chk-' + count).is(":checked")) {
                    chk = 1;
                } else {
                    chk = 0;
                }
                var note = $(this).val();
                var task_id = {{ $followUp->id }};
                $.ajax({
                    url: "{{ route('add_followup_checklist_item') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'status': chk,
                        'task_id': task_id,
                        'note': note
                    },
                    dataType: 'json',
                    success: function(data) {
                        Swal.fire({
                            title: 'Success',
                            text: "Checklist item added successfully !",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload(true);
                                // top.location.href="#";
                            }
                        });
                    }
                })
            });
            $('.chk').change(function() {
                var val = 0;
                if (this.checked) {
                    val = 1
                } else {
                    val = 0;
                }
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('update_checklist_status') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'id': id,
                        'val': val
                    },
                    dataType: 'json',
                    success: function(data) {
                        location.reload(true);
                    }
                })
            });
            $('.list-note').change(function() {
                var val = $(this).val();
                var id = $(this).data('id');
                $.ajax({
                    url: "#",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'id': id,
                        'val': val
                    },
                    dataType: 'json',
                    success: function(data) {
                        location.reload(true);
                    }
                })
            });
            $('.priority li a').on('click', function() {
                var priority = $(this).attr('data-priority');
                var task_id = $(this).attr('data-task');
                $('#loader').removeClass('hidden');
                $.ajax({
                    url: "#",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'priority': priority,
                        'task': task_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#loader').addClass('hidden');
                        Swal.fire({
                            title: 'Priority Changed!',
                            text: "Priority Changed Successfully.",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload(true);
                            }
                        });
                    }
                });
            });
            $('.task-status li a').on('click', function() {
                var status = $(this).attr('data-status');
                var task_id = $(this).attr('data-task');
                $('#loader').removeClass('hidden');
                $.ajax({
                    url: "#",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'status': status,
                        'task': task_id,
                    },
                    success: function(data) {
                        $('#loader').addClass('hidden');
                        Swal.fire({
                            title: 'Status Changed!',
                            text: "Status Changed Successfully.",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload(true);
                            }
                        });
                    }
                });
            });
            $(document).on('click', '.remove-item', function() {
                var item_id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "#",
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: "Checklist item has been deleted.",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload(true);
                                        top.location.href =
                                            "{{ URL::route('follow-up.show', $followUp->id) }}";
                                    }
                                });
                            }
                        });
                    }
                });
            });
            var j = 0;
            var myDropzone1 = new Dropzone("#dropzone1", {
                autoProcessQueue: false,
                maxFilesize: 10,
                acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf,.doc,.csv,.docx",
                addRemoveLinks: true,
                timeout: 60000,
                renameFile: function(file) {
                    return file.name;
                },
                init: function() {
                    this.on("addedfile", function(file) {
                        j++;
                    });
                },
                success: function(file, response) {
                    Swal.fire({
                        title: 'Uploaded Successfully!',
                        text: "File has been uploaded.",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload(true);
                        }
                    });
                },
                error: function(file, response) {
                    return false;
                }
            });
            $(document).on('click', '.btn-comment', function() {
                var val = $('.task-comment').val();
                if (val == "") {
                    $('.error-comment').html("Please Enter Comment.");
                } else {
                    if (j == 0) {
                        var comment = $('.hidden_comment').val(val);
                        var formdata = new FormData(document.getElementById('dropzone1'));

                        $(this).html('<i class="fa fa-spinner fa-spin"></i>');
                        $.ajax({
                            url: "{{ route('follow_up.comment') }}",
                            type: 'POST',
                            data: formdata,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(data) {
                                Swal.fire('success', 'Comment added successfully!', 'success');
                                location.reload(true);
                            }
                        });
                    } else {
                        $(this).html('Add Comment');
                        var comment = $('.hidden_comment').val(val);
                        myDropzone1.processQueue();
                    }
                }
            });
        });
    </script>
@endsection
