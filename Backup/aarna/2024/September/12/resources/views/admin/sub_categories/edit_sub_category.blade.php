@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
            <div class="row">
                <div class="card">
                <div class="card-body">
                    @if(Session::has('alert'))
                        <p class="alert
                        {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('alert') }}</p>
                    @endif
                    <form action="{{route('admin.edit.subcategory')}}" method="POST" class="row g-3" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12">
                            <label for="Name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="Name" placeholder="Category Name" value="{{$category->name}}">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <label for="Parent" class="form-label">Category</label>
                            <select name="parent" id="Parent" class="form-control">
                                <option value="0" selected hidden>Select Parent Category</option>
                                @foreach ($categories as $item)
                                    <option value="{{$item->id}}" @if ($item->id == $category->parent)
                                        selected
                                    @endif>{{$item->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('parent'))
                                <span class="text-danger">{{ $errors->first('parent') }}</span>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <label for="Description" class="form-label">Description</label>
                            <textarea name="description" id="Description" class="form-control">{{$category->description}}</textarea>
                            @if ($errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <label for="GST" class="form-label">GST Rate <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="gst" id="GST" placeholder="Third Party Gst Rate" value="{{$category->gst}}">
                            @if ($errors->has('gst'))
                                <span class="text-danger">{{ $errors->first('gst') }}</span>
                            @endif
                        </div>
                        <div class="col-md-12 mt-4">
                            <div class="form-check align-items-center">
                                <input type="checkbox" class="form-check-input" name="renewable" id="Renewable" @if ($category->renewable == 1)
                                    checked
                                @endif>
                                <label for="Renewable" class="form-label ms-2 mb-0">Is Renewable ?</label>
                            </div>
                            @if ($errors->has('renewable'))
                                <span class="text-danger">{{ $errors->first('renewable') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6 ms-2">
                            <label for="Status" class="form-label">Status <span class="text-danger">*</span></label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckChecked" @if($category->status==1)
                                checked
                                @endif >
                                <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="hidden" name="category_id" value="{{$category->id}}">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
