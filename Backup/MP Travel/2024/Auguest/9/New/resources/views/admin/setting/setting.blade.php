@extends('admin.layouts.app')

@section('content')
    <div class="gc_row px-md-4 px-2">
        <div class="card my-3">
            <div class="card-body d-sm-flex d-block  align-items-center p-lg-3 p-2 staff_header ">
                <div class="pe-4 fs-5">Setting
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-profile-overview" role="tabpanel"
                        aria-labelledby="pills-profile-overview-tab">
                        <form action="{{ route('save_general_settings') }}" method="POST" class="row g-3"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-6">
                                <label for="logo" class="form-label">Logo <span class="text-danger">*</span></label>
                                <input class="form-control" type="file" id="logo" name="logo">
                                @if ($errors->has('logo'))
                                    <span class="text-danger">{{ $errors->first('logo') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6 mt-5">
                                @php $logoUrl = asset($settings->logo); @endphp
                                <input type="hidden" name="uploaded_logo" value="{{ $settings->logo }}">
                                <img id="preview-image-before-upload" src="{{ $logoUrl }}" alt=""
                                    style="max-height: 100px; width:100px;">
                            </div>
                            <div class="col-md-6">

                                <label for="favicon" class="form-label">Favicon <span class="text-danger">*</span></label>
                                <input class="form-control" type="file" id="favicon" name="favicon">
                                @if ($errors->has('favicon'))
                                    <span class="text-danger">{{ $errors->first('favicon') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6 mt-5">
                                @php $faUrl = asset($settings->favicon); @endphp
                                <input type="hidden" name="uploded_favicon" value="{{ $settings->favicon }}">
                                <img id="preview-image-before-upload-favicon" src="{{ $faUrl }}" alt=""
                                    style="max-height: 50px;">
                            </div>
                            <div class="col-12">
                                <label for="SiteName" class="form-label">Company Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="site_name" id="SiteName"
                                    placeholder="Company Name" value="{{ $settings->site_name }}">
                                @if ($errors->has('site_name'))
                                    <span class="text-danger">{{ $errors->first('site_name') }}</span>
                                @endif
                            </div>
                            <div class="col-12">
                                <label for="SiteUrl" class="form-label">Site Url <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="site_url" id="SiteUrl"
                                    placeholder="Company Name" value="{{ $settings->site_url }}">
                                @if ($errors->has('site_url'))
                                    <span class="text-danger">{{ $errors->first('site_url') }}</span>
                                @endif
                            </div>
                            <div class="col-12">
                                <label for="SiteUrl" class="form-label">API Key<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="api_key" id="SiteUrl" readonly
                                    placeholder="Api Key" value="{{ $settings->api_key }}">
                                @if ($errors->has('api_key'))
                                    <span class="text-danger">{{ $errors->first('api_key') }}</span>
                                @endif
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn gc_btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
