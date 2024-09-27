@extends('admin.layouts.app')

@section('content')
<main class="col-md-9 ms-sm-auto col-lg-9 col-xl-9 col-xxl-9 px-md-4 gc-main">
    <div class="content">
        <div class="card mb-2 p-2">
            <div class="card-body">
                <div class="d-md-flex gap-4 align-items-center">
                    <h4 class="mb-0">{{ $template->name }}</h4>
                    <div class="ms-auto">
                        <a href="{{ route('email_template.setting') }}" class="btn btn-primary mt-2">Go Back</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="card p-2">
                    <div class="card-body">
                        <form action="{{ route('update.template') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <label for="Subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="Subject" name="subject" placeholder=" " value="{{$template->subject }}">
                                @if ($errors->has('subject'))
                                    <span class="text-danger">{{ $errors->first('subject') }}</span>
                                @endif
                            </div>
                            <div class="col-12">
                                <label for="FromName" class="form-label">From Name</label>
                                <input type="text" class="form-control" id="FromName" name="from" placeholder=" " value="{{$template->fromname }}">
                                @if ($errors->has('from'))
                                    <span class="text-danger">{{ $errors->first('from') }}</span>
                                @endif
                            </div>
                            <div class="col-12">
                                <label for="Message" class="form-label">Message</label>
                                <textarea name="editor" id="editor1" class="mt-2">{!! $template->message !!}</textarea>
                            </div>
                            <div class="col-12">
                                <label for="Status" class="form-label">Status <span class="text-danger">*</span></label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckChecked" <?php if($template->active == 1){ ?>checked <?php } ?>>
                                    <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                </div>
                            </div>
                            <input type="hidden" name="template_id" value="{{ $template->id }}">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary mt-2">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card p-3">
                    <div class="card-body row">
                        @foreach($data as $fields)
                            <div class="col-md-6 mt-2">
                                <table class="table fs-12">
                                    <tbody>
                                        @foreach($fields as $key=>$value)
                                            <tr>
                                                <td class="font-weight-bold">{{ $key }}</td>
                                                <td class="text-right"><a href="#" onclick="insert_merge_field('<?php echo $value; ?>'); return false"><?php echo $value; ?></a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')
<script src="https://cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>
<script>
    $(document).ready(function(){
        CKEDITOR.replace('editor1', {
            skin: 'moono',
            enterMode: CKEDITOR.ENTER_BR,
            shiftEnterMode:CKEDITOR.ENTER_P,
            toolbar: [{ name: 'basicstyles', groups: [ 'basicstyles' ], items: [ 'Bold', 'Italic', 'Underline', "-", 'TextColor', 'BGColor' ] },
                        { name: 'styles', items: [ 'Format', 'FontSize' ] },
                        { name: 'justify', groups: [ 'blocks', 'align' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                        { name: 'paragraph', groups: [ 'list', 'indent' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
                        { name: 'links', items: [ 'Link', 'Unlink' ] },
                        { name: 'insert', items: [ 'Image'] },
                        { name: 'spell', items: [ 'jQuerySpellChecker' ] },
                        { name: 'table', items: [ 'Table' ] }
                        ],

            });
    });
    function insert_merge_field(field) {
        CKEDITOR.instances['editor1'].insertHtml(field);
    }
</script>
@endsection
