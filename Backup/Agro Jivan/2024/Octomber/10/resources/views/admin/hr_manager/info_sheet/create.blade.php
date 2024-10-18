@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            
            <div id="kt_app_content" class="app-content  flex-column-fluid">
                <div id="kt_app_content_container" class="app-container  container-fluid">
                    <form class="form" action="{{ route('info-sheet.store') }}" method="post"
                        enctype="multipart/form-data" onsubmit="">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Title</label>
                                <input type="text" class="form-control mb-3 mb-lg-0" placeholder="Enter Title"
                                    name="title" value="{{ old('title') }}" id="title">
                                @if ($errors->has('title'))
                                    <span class="text-danger">{{ $errors->first('title') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Info Sheet</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="info_sheet" name="info_sheet">
                                </div>
                                @if ($errors->has('info_sheet'))
                                    <span class="text-danger">{{ $errors->first('info_sheet') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="Phone" class="form-label">Description</label>
                                <textarea name="description" id="" class="form-control" rows="4">{{ old('description') }}</textarea>
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
                                        id="flexSwitchCheckChecked" checked>
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
