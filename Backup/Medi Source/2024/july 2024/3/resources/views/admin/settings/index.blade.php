@extends('admin.layouts.app')

@section('content')

<div class="content-page">
    <div class="content">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-pills card-header-pills">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/settings') ? 'active' : '' }}"
                            href="{{ route('admin.settings') }}">
                            <i class="fas fa-cogs"></i> General Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/settings/companysetting') ? 'active' : '' }}"
                            href="{{ route('admin.settings.companysetting') }}">
                            <i class="fas fa-building"></i> Company Information
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/settings/email') ? 'active' : '' }}"
                            href="{{ route('admin.settings.email') }}">
                            <i class="fas fa-envelope"></i> Email Settings
                        </a>
                    </li>
                </ul>
            </div>



            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
    <label for="logo" class="form-label">Logo</label>

    @if ($settings->logo)
        <div class="uploaded-image mt-2">
            <label></label>
            <img src="{{ asset('/storage/' . $settings->logo) }}" alt="Logo" class="img-thumbnail" width="100px"
                id="logoImage">
        </div>
    @else
        <div class="existing-image mt-2">
            <label>No Logo Available</label>
        </div>
    @endif

    <input type="file" class="form-control" id="logo" name="logo" onchange="previewImage('logo', 'logoImage')">
</div>

<div class="mb-3">
    <label for="favicon" class="form-label">Favicon</label>

    @if ($settings->favicon)
        <div class="uploaded-image mt-2">
            <label></label>
            <img src="{{ asset('/storage/' . $settings->favicon) }}" alt="Favicon" class="img-thumbnail" width="100px"
                id="faviconImage">
        </div>
    @else
        <div class="existing-image mt-2">
            <label>No Favicon Available</label>
        </div>
    @endif


                            <input type="file" class="form-control" id="favicon" name="favicon"
                                onchange="previewImage('favicon', 'faviconImage')">
                        </div>


                        <div class="mb-3">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="company_name" name="company_name"
                                value="{{ old('company_name', $settings->company_name) }}">
                        </div>

                        <div class="mb-3">
                            <label for="site_url" class="form-label">Site URL</label>
                            <input type="text" class="form-control" id="site_url" name="site_url"
                                value="{{ old('site_url', $settings->site_url) }}">
                        </div>

                        <div class="mb-3">
                            <label for="date_format" class="form-label">Date Format</label>
                            <select class="form-select" id="date_format" name="date_format">
                                <option value="d/m/y"
                                    {{ old('date_format', $settings->date_format) == 'd/m/y' ? 'selected' : '' }}>
                                    31/1/2023 (d/m/y)</option>
                                <option value="m/d/y"
                                    {{ old('date_format', $settings->date_format) == 'm/d/y' ? 'selected' : '' }}>
                                    1/31/2023 (m/d/y)</option>
                                <option value="F d,Y"
                                    {{ old('date_format', $settings->date_format) == 'F d,Y' ? 'selected' : '' }}>
                                    January 31, 2023 (F d,Y)</option>
                                <option value="d F,Y"
                                    {{ old('date_format', $settings->date_format) == 'd F,Y' ? 'selected' : '' }}>31
                                    January 2023 (d F,Y)</option>
                                <option value="d/m/Y H:i:s"
                                    {{ old('date_format', $settings->date_format) == 'd/m/Y H:i:s' ? 'selected' : '' }}>
                                    31/1/2023 12:40:00 (d/m/Y H:i:s)</option>
                                <option value="d-m-Y H:i:s"
                                    {{ old('date_format', $settings->date_format) == 'd-m-Y H:i:s' ? 'selected' : '' }}>
                                    31-1-2023 12:40:00 (d-m-Y H:i:s)</option>
                            </select>
                        </div>



                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function previewImage(inputId, imageId) {
    var input = document.getElementById(inputId);
    var image = document.getElementById(imageId);

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            // Display the uploaded image
            image.src = e.target.result;
            image.style.display = 'block';
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection