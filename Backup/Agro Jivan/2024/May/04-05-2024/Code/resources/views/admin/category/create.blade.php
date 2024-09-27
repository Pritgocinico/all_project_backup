@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid">
                <div id="kt_app_content_container" class="app-container  container-fluid">
                    <form class="form" action="{{ route('category.store') }}" method="post" enctype="multipart/form-data" onsubmit="return categoryValidate()">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Name</label>
                                <input type="text" class="form-control mb-3 mb-lg-0"
                                    placeholder="Enter Name" name="name" value="{{ old('name') }}" id="name">
                                <span class="text-danger"
                                    id="name_error">{{ $errors->getBag('default')->first('name') }}</span>
                            </div>
                            <div class="col-md-6">
                                <label class=" fs-6 fw-semibold mb-2">Parent Category</label>
                                <select name="parent_category_id" id="parent_category_id" class="form-select">
                                    <option value="">Select Parent Category</option>
                                    @foreach ($parentCategory as $parent)
                                        <option value="{{ $parent->id }}"
                                            @if (old('parent_category_id') == $parent->id) selected @endif>{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger"
                                    id="parent_category_error">{{ $errors->getBag('default')->first('parent_category_id') }}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="fs-6 fw-semibold mb-2">Category Image</label>
                                <input type="file" class="form-control" placeholder="Enter Phone Number"
                                    name="category_image" value="{{ old('category_image') }}" id="category_image">
                                <span class="text-danger"
                                    id="category_image_error">{{ $errors->getBag('default')->first('category_image') }}</span>
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
@section('page')
    <script src="{{ asset('public\assets\js\custom\admin\category.js') }}?{{time()}}"></script>
@endsection
