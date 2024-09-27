@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Zero Configuration  Starts-->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('alert'))
                    <p class="alert
                    {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('alert') }}</p>
                    @endif
                    <form action="{{ route('admin.edit.category') }}" method="POST" class="row g-3"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12 form-floating mt-4">
                            <input type="text" class="form-control" name="name" id="Name" placeholder="Category Name"
                                value="{{$category->name}}">
                            <label for="Name" class="form-label">Name <span class="text-danger">*</span></label>
                            @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="col-md-12 form-floating mt-4">
                            <textarea name="description" id="Description" class="form-control"
                                style="min-height:120px;">{{$category->description}}</textarea>
                            <label for="Description" class="form-label">Description</label>
                            @if ($errors->has('description'))
                            <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6 ms-2">
                            <label for="Status" class="form-label">Status <span class="text-danger">*</span></label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status"
                                    id="flexSwitchCheckChecked" @if($category->status==1)
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
</div>
@endsection