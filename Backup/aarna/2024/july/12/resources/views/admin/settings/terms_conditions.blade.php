@extends('admin.layouts.setting')

@section('tabs')
<div class="row">
    <div class="col-md-12">
        <h3 class="mb-2">Update Terms Conditions</h3>
        @if(Session::has('alert'))
            <p class="alert
            {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('alert') }}</p>
        @endif
        <form action="{{ route('save.terms.conditions') }}" method="POST" class="row g-3" enctype="multipart/form-data">
           @csrf
            <div class="col-12">
               <textarea id="editor1" name="terms_conditions_content"></textarea>
                @if ($errors->has('terms_conditions_content'))
                    <span class="text-danger">{{ $errors->first('terms_conditions_content') }}</span>
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
    const privacyPolicyContent = @json($settings->terms_of_service);

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
