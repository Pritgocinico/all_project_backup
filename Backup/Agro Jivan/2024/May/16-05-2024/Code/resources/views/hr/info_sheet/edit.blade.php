@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid">
                <div id="kt_app_content_container" class="app-container  container-fluid">
                    <form class="form" action="{{ route('hr-info-sheet-update', $info->id) }}" method="post"
                        enctype="multipart/form-data" onsubmit="">
                        @csrf
                        <input type="hidden" name="id" value="{{$info->id}}">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Title</label>
                                <input type="text" class="form-control mb-3 mb-lg-0" placeholder="Enter Title"
                                    name="title" value="{{ $info->title }}" id="title">
                                @if ($errors->has('title'))
                                    <span class="text-danger">{{ $errors->first('title') }}</span>
                                @endif
                            </div>
                            <div class="col-md-5">
                                <label class="required fs-6 fw-semibold mb-2">Info Sheet</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="info_sheet" name="info_sheet">
                                </div>
                                @if ($errors->has('info_sheet'))
                                    <span class="text-danger">{{ $errors->first('info_sheet') }}</span>
                                @endif
                            </div>
                            <div class="col-md-1">
                                <a href="{{ url('/') }}/public/assets/upload/{{ $info->info_sheet }}" target="_blank">
                                    <img src="{{ url('/') }}/public/assets/media/png_images/file.png" width="60px">
                                </a>
                                <input type="hidden" name="hidden_info_sheet" value="{{$info->info_sheet}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="Phone" class="form-label">Description</label>
                                <textarea name="description" id="" class="form-control" rows="4">{{ $info->description }}</textarea>
                                @if ($errors->has('description'))
                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="Status" class="required fs-6 fw-semibold mb-2">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status"
                                        id="flexSwitchCheckChecked" @if($info->status == 1) {{'checked'}} @endif>
                                    <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
                            </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection
