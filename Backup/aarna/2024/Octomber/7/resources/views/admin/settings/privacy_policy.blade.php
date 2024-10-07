@extends('admin.layouts.setting')

@section('tabs')
<div class="row">
    <div class="col-md-12">
        <h3 class="mb-2">Update Privacy Policy</h3>
        @if(Session::has('alert'))
            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('alert') }}</p>
        @endif
        <form action="{{ route('save.privacy.policy') }}" method="POST" class="row g-3" enctype="multipart/form-data">
            @csrf
            <div class="col-12">
                <textarea id="editor1" name="privacy_content"></textarea>
                @if ($errors->has('privacy_content'))
                    <span class="text-danger">{{ $errors->first('privacy_content') }}</span>
                @endif
            </div>
           
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const privacyPolicyContent = @json($settings->privacy_policy);

    ClassicEditor
        .create(document.querySelector('#editor1'))
        .then(editor => {
            console.log(editor);
            editor.setData(privacyPolicyContent);
        })
        .catch(error => {
            console.error(error);
        });
});
</script>
@endsection
