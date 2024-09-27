@extends('admin.layouts.app')

@section('content')
        <div class="project">
            <div class="page-header d-md-flex justify-content-between">
                <div class="">
                    <h3 class="mb-0">Add Measurement - Project 1</h3>
                </div>
                <div class="">
                    <a href="{{ route('measurement') }}" class="btn btn-primary ms-auto">
                        <i class="sub-menu-arrow ti-angle-left me-2"></i> Back
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('storemeasurement') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="myeditorinstance">Paste the rough measurement <span class="text-danger">*</span></label>
                            <textarea id="myeditorinstance" placeholder="Paste the rough measurement here..." name="measurement"></textarea>
                            @if ($errors->has('measurement'))
                                <span class="text-danger">{{ $errors->first('measurement') }}</span>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <label for="projectdesc">Project Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="projectdesc" rows="5" name="description">{{old('description')}}</textarea>
                            @if ($errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Submit</button>
                     </form>
                </div>
            </div>
        </div>
@endsection
@section('script')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
   tinymce.init({
     selector: 'textarea#myeditorinstance', // Replace this CSS selector to match the placeholder element for TinyMCE
     plugins: 'powerpaste advcode table lists checklist',
     toolbar: 'undo redo | blocks| bold italic | bullist numlist checklist | code | table'
   });
</script>
@endsection

