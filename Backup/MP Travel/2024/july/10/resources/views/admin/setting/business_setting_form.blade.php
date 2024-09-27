@extends('admin.partials.header',['active'=>''])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">{{$depart->name}} Business Setting</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('business.setting-update',$depart->id) }}" enctype="multipart/form-data" method="POST">
                        @csrf

                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Comapny Name</label></div>
                            <div class="col-md-8 col-xl-5">
                                <input type="text" class="form-control" placeholder="Enter Comapny Name" name="company_name" value="{{old('company_name',$businessSetting->company_name ?? '')}}">
                                @error('company_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr class="my-6">
                        <div class="row align-items-center g-3">
                            <div class="col-md-2">
                                <label class="form-label mb-0">GST Number</label>
                            </div>
                            <div class="col-md-8 col-xl-5">
                                <input type="text" class="form-control" placeholder="Enter GST Number" name="gst_number" value="{{old('gst_number',$businessSetting->gst_number ?? '')}}">
                                @error('gst_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr class="my-6">
                        <div class="row align-items-center g-3">
                            <div class="col-md-2"><label class="form-label mb-0">Support Phone Number</label></div>
                            <div class="col-md-8 col-xl-5">
                                <input type="text" class="form-control" placeholder="Enter Support Phone Number" name="support_phone" value="{{old('support_phone',$businessSetting->support_phone ?? '')}}">
                                @error('support_phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr class="my-6">
                        <div class="row align-items-center g-3">
                            <div class="col-md-2"><label class="form-label mb-0">Address</label></div>
                            <div class="col-md-8 col-xl-5">
                                <textarea name="address" class="form-control" placeholder="Enter Address" id="address">{{old('address',$businessSetting->address ?? '')}}</textarea>
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr class="my-6">
                        <div class="row align-items-center g-3">
                            <div class="col-md-2"><label class="form-label mb-0">Description</label></div>
                            <div class="col-md-8 col-xl-5">
                                <textarea name="description" class="form-control" placeholder="Enter Description" id="description">{{old('description',$businessSetting->description ?? '')}}</textarea>
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr class="my-6">
                        <div class="d-flexjustify-content-end gap-2">
                            <a type="button" class="btn btn-sm btn-neutral" href="{{ route('business-setting') }}">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                </main>
            </div>
        </main>
    </div>
@endsection
