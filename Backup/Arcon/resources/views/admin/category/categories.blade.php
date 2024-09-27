@extends('admin.layouts.app')

@section('content')
<div class="mb-4 px-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">
                    Dashboard
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="">
                    Products
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <a href="javascript:void(0);">Categories</a> 
            </li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-md-5">
        <div class="houmanity-card">
            <div class="card p-3">
                <div class="card-body">
                    @if(Session::has('alert'))
                        <p class="alert
                        {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('alert') }}</p>
                    @endif
                    <form action="{{route('admin.add_category_data')}}" method="POST" class="row g-3" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12">
                            <label for="Name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="Name" placeholder="Category Name" value="{{old('name')}}">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        {{-- <div class="col-md-12">
                            <label for="BrandName" class="form-label">Brand Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="brand_name" id="BrandName" placeholder="Category Brand Name" value="{{old('brand_name')}}">
                            @if ($errors->has('brand_name'))
                                <span class="text-danger">{{ $errors->first('brand_name') }}</span>
                            @endif
                        </div> --}}
                        <div class="col-md-12">
                            <label for="Parent" class="form-label">Parent</label>
                            <select name="parent" id="Parent" class="form-control">
                                <option value="0" selected hidden>Select Parent Category</option>
                                @foreach ($categories as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('parent'))
                                <span class="text-danger">{{ $errors->first('parent') }}</span>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <label for="Image" class="form-label">Category Image</label>
                            <input type="file" name="image" id="Image" class="form-control">
                            @if ($errors->has('image'))
                                <span class="text-danger">{{ $errors->first('image') }}</span>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <label for="Description" class="form-label">Description</label>
                            <textarea name="description" id="Description" class="form-control">{{old('description')}}</textarea>
                            @if ($errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="houmanity-card">
            <div class="card-body card-head">
                <div class="d-md-flex gap-4 align-items-center bg-white p-3">
                    <div class="d-none d-md-flex">All Categories</div>
                    <div class="d-md-flex gap-4 align-items-center">
                        <form class="mb-3 mb-md-0">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <select class="form-select classic order-table">
                                        <option hidden>Sort by</option>
                                        <option value="desc">Desc</option>
                                        <option value="asc">Asc</option>
                                    </select>
                                </div>
                                <!-- <div class="col-md-5">
                                    <select class="form-select classic" id="maxRows">
                                        <option value="10" selected="selected">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                        <option value="40">40</option>
                                        <option value="50">50</option>
                                    </select>
                                </div> -->
                            </div>
                        </form>
                    </div>
                    <div class="ms-auto d-flex">
                        <form action="">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control src d-none" id="search-table" placeholder="Search">
                                        <span class="search-btn mt-2 ms-2" type="button">
                                            <i class="bi bi-search"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="houmanity-card">
            <div class="card-body table-responsive">
                <table id="" class="table table-custom" style="width:100%">
                    <thead>
                        <tr>
                            <th>Category Name</th>
                            <th>Image</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($parent_categories) > 0)
                            @foreach ($parent_categories as $category)
                                <tr>
                                    <td class="align-middle"><a class="text-primary" href="{{route('admin.edit_category',$category->id)}}">{{$category->name }}</a></td>
                                    <td class="align-middle">
                                        @if (!blank($category->image))
                                          <img src="{{url('/')}}/public/categories/{{$category->image}}" width="50px" alt="">  
                                        @endif
                                    </td>
                                    <td class="text-end align-middle">
                                        <div class="d-flex float-end">
                                            <a href="{{ route('admin.edit_category',$category->id) }}" class=""><img src="{{url('/')}}/assets/admin/images/edit.png" width="20px" class="me-2"></a>
                                            <a href="javascript:void(0);" data-id="{{ $category->id }}" class="ms-2 delete-btn"><img src="{{url('/')}}/assets/admin/images/delete.png" width="20px" class="me-2"></a>
                                        </div>
                                    </td>
                                </tr>
                                @foreach ($categories as $item)
                                    @if($category->id == $item->parent)
                                    <tr>
                                        <td class="align-middle"><a href="{{ route('admin.edit_category',$item->id) }}" class="text-primary"><span class="ps-3">- {{$item->name }}</span></a></td>
                                        <td class="align-middle">
                                            @if (!blank($item->image))
                                              <img src="{{url('/')}}/public/categories/{{$item->image}}" width="50px" alt="">  
                                            @endif
                                        </td>
                                        <td class="text-end align-middle">
                                            <div class="d-flex float-end">
                                                <a href="{{ route('admin.edit_category',$item->id) }}" class=""><img src="{{url('/')}}/assets/admin/images/edit.png" width="20px" class="me-2"></a>
                                                <a href="javascript:void(0);" data-id="{{ $item->id }}" class="ms-2 delete-btn"><img src="{{url('/')}}/assets/admin/images/delete.png" width="20px" class="me-2"></a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        @else
                            <tr class="text-center">
                                <td colspan="6">Categories not found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function(){
        $(document).on('click','.delete-btn',function(){
            var category_id = $(this).attr('data-id');
            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url : "{{route('delete.category', '')}}"+"/"+category_id,
                    type : 'GET',
                    dataType:'json',
                    success : function(data) {  
                        Swal.fire({
                            title: 'Deleted!',
                            text: "Category has been deleted.",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                top.location.href="{{ route('admin.categories') }}";
                            }
                        });
                    }
                });
            }
            });
        });
    });
</script>
@endsection