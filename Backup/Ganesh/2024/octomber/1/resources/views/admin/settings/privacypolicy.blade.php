@extends('admin.layouts.setting')
@section('tabs')
<div class="row">
    <div class="col-md-12">
        @if(Session::has('alert'))
            <p class="alert
            {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('alert') }}</p>
        @endif
        <form action="{{ route('save_privacyPolicies') }}" method="POST" class="row g-3" enctype="multipart/form-data">
           @csrf
            <div class="col-12">
                <label for="email" class="form-label">Privacy Policies</label>
                <textarea name="privacy_policy" id="privacy_policy" rows="10" cols="80">
                    {{$settings->privacy_policy}}
                  </textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')
<script src="//cdn.gaic.com/cdn/ui-bootstrap/0.58.0/js/lib/ckeditor/ckeditor.js"></script>
<script src="//cdn.gaic.com/cdn/ui-bootstrap/0.58.0/js/lib/jquery.min.js"></script>
<script src="//cdn.gaic.com/cdn/ui-bootstrap/0.58.0/js/lib/angular.min.js"></script>
<script src="//cdn.gaic.com/cdn/ui-bootstrap/0.58.0/js/gaig-ui-bootstrap.js"></script>
<script>
    $(document).ready(function(e){
        CKEDITOR.replace('privacy_policy');
    });
</script>
@endsection