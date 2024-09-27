@extends('admin.layouts.app')

@section('content')
<div class="card mb-2 p-3">
    <div class="card-body">
        <div class="d-md-flex gap-4 align-items-center">
            <h4 class="mb-0">Edit Category</h4>
            <div class="ms-auto">
                <a href="{{ route('admin.categories') }}" class="btn btn-primary ustify-content-end float-right">
                    Go Back
                </a>
            </div>
        </div>
    </div>
</div>
<div class="card p-3">
    <div class="card-body">
        @if(Session::has('alert'))
            <p class="alert
            {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('alert') }}</p>
        @endif
        <form action="{{ route('admin.edit.category') }}" method="POST" class="row g-3" enctype="multipart/form-data">
            @csrf
            <div class="col-md-6">
                <label for="Name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="Name" placeholder="Category Name" value="{{$category->name}}">
                @if ($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
            </div>
            {{-- <div class="col-md-6">
                <label for="BrandName" class="form-label">Brand Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="brand_name" id="BrandName" placeholder="Category Brand Name" value="{{$category->brand_name}}">
                @if ($errors->has('brand_name'))
                    <span class="text-danger">{{ $errors->first('brand_name') }}</span>
                @endif
            </div> --}}
            <div class="col-md-12">
                <label for="Parent" class="form-label">Parent</label>
                <select name="parent" id="Parent" class="form-control">
                    <option value="0" selected hidden>Select Parent Category</option>
                    @foreach ($categories as $item)
                        <option value="{{$item->id}}" @if ($category->parent == $item->id) selected @endif>{{$item->name}}</option>
                    @endforeach
                </select>
                @if ($errors->has('parent'))
                    <span class="text-danger">{{ $errors->first('parent') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="Image" class="form-label">Category Image</label>
                <input type="file" name="image" id="Image" class="form-control">
                @if ($errors->has('image'))
                    <span class="text-danger">{{ $errors->first('image') }}</span>
                @endif
            </div>
            <div class="col-md-6 mt-4">
                <div class="row">
                    <div class="col-md-2">
                        <img id="uploaded-image-preview" src="{{url('/')}}/public/categories/{{$category->image}}" width="80px" alt="">
                        <input type="hidden" name="hidden_image" class="hidden_image" value="{{$category->image}}">
                    </div>
                    <div class="col-md-10">
                        @if (!blank($category->image))
                            <a href="javascript:void(0);" class="text-danger delete-img">Delete</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <label for="Description" class="form-label">Description</label>
                <textarea name="description" id="Description" class="form-control">{{$category->description}}</textarea>
                @if ($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
            </div>
            <div class="col-md-6">
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
@endsection
@section('script')
<script>
    $('#Image').change(function(){
        let reader = new FileReader();
        reader.onload = (e) => { 
            $('#uploaded-image-preview').attr('src', e.target.result); 
        }
        reader.readAsDataURL(this.files[0]); 
        $('.delete-img').show();
    });
    $(document).on('click','.delete-img',function(){
        $('#uploaded-image-preview').attr('src','');
        $('.hidden_image').val('');
        $('#image').val('');
        $('.delete-img').hide(); 
    })
</script>
@endsection