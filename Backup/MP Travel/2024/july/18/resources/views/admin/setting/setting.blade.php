@extends('admin.partials.header',['active'=>''])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Dashboard Setting</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('setting-update') }}" enctype="multipart/form-data" method="POST">
                        @csrf

                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Logo</label></div>
                            <div class="col-md-8 col-xl-5">
                                <input type="file" class="form-control" placeholder="Select Logo" name="logo">
                                @error('logo')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                @if(isset($settings) && $settings->logo)
                                <img id="preview-image-before-upload" src="{{ asset('storage/'.$settings->logo) }}" alt="" style="width:150px;">
                                @endif 
                            </div>
                        </div>
                        <hr class="my-6">
                        <div class="row align-items-center g-3">
                            <div class="col-md-2">
                                <label class="form-label mb-0">Favicon</label>
                            </div>
                            <div class="col-md-8 col-xl-5">
                                <input type="file" class="form-control" placeholder="Select Fa Icon" name="fa_icon">
                                @error('fa_icon')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                @if(isset($settings) && $settings->fa_icon)
                                <img id="preview-image-before-upload" src="{{ asset('storage/'.$settings->fa_icon) }}" alt="" style="width:150px;">
                                @endif 
                            </div>
                        </div>
                        <hr class="my-6">
                        <div class="row align-items-center g-3">
                            <div class="col-md-2"><label class="form-label mb-0">Site Name</label></div>
                            <div class="col-md-8 col-xl-5">
                                <input type="text" class="form-control" placeholder="Enter Site Name" name="site_name" value="{{old('site_name',$settings->site_name ?? '')}}">
                                @error('site_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr class="my-6">
                        <div class="row align-items-center g-3">
                            <div class="col-md-2"><label class="form-label mb-0">Avatar Image</label></div>
                            <div class="col-md-8 col-xl-5">
                                <input type="file" class="form-control" placeholder="Select Avatar Image" name="profile_image">
                                @error('profile_image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                @if(Auth()->user()->profile_image)
                                <img id="preview-image-before-upload" src="{{ asset('storage/'.Auth()->user()->profile_image) }}" alt="" style="width:150px;">
                                @endif
                            </div>
                        </div>
                        <hr class="my-6">
                        <div class="d-flexjustify-content-end gap-2">
                            <a type="button" class="btn btn-sm btn-neutral" href="{{ route('dashboard') }}">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                </main>
            </div>
        </main>
    </div>
@endsection
