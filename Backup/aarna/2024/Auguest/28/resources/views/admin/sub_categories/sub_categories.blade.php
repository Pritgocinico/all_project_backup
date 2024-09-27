@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header card-no-border">
                        <h4>Add Sub Category</h4>
                    </div>
                    <div class="card-body">
                        @if (Session::has('alert'))
                            <p class="alert
                        {{ Session::get('alert-class', 'alert-danger') }}">
                                {{ Session::get('alert') }}</p>
                        @endif
                        <form action="{{ route('admin.add_subcategory_data') }}" method="POST" class="row g-3"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-12">
                                <label for="Name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="Name"
                                    placeholder="Category Name" value="{{ old('name') }}">
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <label for="Parent" class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="parent" id="Parent" class="form-control">
                                    <option value="0" selected hidden>Select Parent Category</option>
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('parent'))
                                    <span class="text-danger">{{ $errors->first('parent') }}</span>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <label for="Description" class="form-label">Description</label>
                                <textarea name="description" id="Description" class="form-control">{{ old('description') }}</textarea>
                                @if ($errors->has('description'))
                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <label for="GST" class="form-label">GST Rate <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="gst" id="GST"
                                    placeholder="Third Party Gst Rate" value="{{ old('gst') }}">
                                @if ($errors->has('gst'))
                                    <span class="text-danger">{{ $errors->first('gst') }}</span>
                                @endif
                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="form-check align-items-center">
                                    <input type="checkbox" class="form-check-input" name="renewable" id="Renewable" />
                                    <label for="Renewable" class="form-label ms-2 mb-0">Is Renewable ?
                                        {{ old('description') }}</label>
                                </div>
                                @if ($errors->has('renewable'))
                                    <span class="text-danger">{{ $errors->first('renewable') }}</span>
                                @endif
                            </div>
                            @if (Auth()->user()->role == 1)
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            @else
                                @foreach ($permissions as $permission)
                                    @if ($permission->capability == 'sub-category-create' && $permission->value == 1)
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header justify-content-between d-flex card-no-border">
                        <h4>All Sub Categories</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive custom-scrollbar">
                            <table class="display border" id="basic-1">
                                <thead>
                                    <tr>
                                        <th>Sub Category</th>
                                        <th>Category</th>
                                        <th>Renewable</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($sub_categories) > 0)
                                        @foreach ($sub_categories as $category)
                                            <tr>
                                                <td class="align-middle"><a class="text-primary"
                                                        href="{{ route('admin.edit_category', $category->id) }}">{{ $category->name }}</a>
                                                </td>
                                                <td class="align-middle">
                                                    @if (!blank($categories))
                                                        @foreach ($categories as $cat)
                                                            @if ($category->parent == $cat->id)
                                                                {{ $cat->name }}
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    @if ($category->renewable == 1)
                                                        Renewable
                                                    @else
                                                        Non Renewable
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    @if ($category->status == 1)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Deactive</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    <ul class="action">
                                                        @if (Auth()->user()->role == 1)
                                                            <li class="edit"><a
                                                                    href="{{ route('admin.edit_subcategory', $category->id) }}"
                                                                    class=""><i class="icon-pencil-alt"></i></a></li>
                                                            <li class="delete"><a href="javascript:void(0);"
                                                                    data-id="{{ $category->id }}"
                                                                    class="ms-2 delete-btn"><i
                                                                        class="icon-trash"></i></i></a> </li>
                                                        @else
                                                            @foreach ($permissions as $permission)
                                                                @if ($permission->capability == 'sub-category-edit' && $permission->value == 1)
                                                                <li class="edit"><a
                                                                    href="{{ route('admin.edit_subcategory', $category->id) }}"
                                                                    class=""><i class="icon-pencil-alt"></i></a></li>
                                                                @endif
                                                                @if ($permission->capability == 'sub-category-delete' && $permission->value == 1)
                                                                <li class="delete"><a href="javascript:void(0);"
                                                                    data-id="{{ $category->id }}"
                                                                    class="ms-2 delete-btn"><i
                                                                        class="icon-trash"></i></i></a> </li>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                        @if ($category->parent == 1)
                                                            <li class="edit"> <a
                                                                    href="{{ route('admin.tp_calculationparameter', $category->id) }}"
                                                                    class="ms-2"><i class="icon-calendar"></i></a></li>
                                                        @endif
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.delete-btn', function() {
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
                            url: "{{ route('delete.subcategory', '') }}" + "/" +
                                category_id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: "Sub Category has been deleted.",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        top.location.href =
                                            "{{ route('admin.sub_categories') }}";
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
