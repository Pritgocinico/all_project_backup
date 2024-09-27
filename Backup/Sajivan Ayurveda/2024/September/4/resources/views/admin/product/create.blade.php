@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Create Product</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('product.store') }}" enctype="multipart/form-data" method="POST">
                        @method('POST')
                        @csrf
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="name" class="form-control" placeholder="Enter Product Name"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">SKU</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="sku" class="form-control" placeholder="Enter SKU"
                                    value="{{ old('sku') }}">
                                @error('sku')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Category</label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="category" class="form-control" id="">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">Image</label></div>
                            <div class="col-md-4 col-xl-4">
                                <div class="hstack gap-2 ms-5">
                                    <label for="file_upload" class="btn btn-sm btn-neutral"><span>Upload</span>
                                        <input type="file" name="image" id="file_upload"
                                            class="visually-hidden product_image"
                                            value="{{ old('image') }}">
                                    </label>
                                </div>
                                <div id="product_image_preview"></div>
                                @error('image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Price</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" name="price" class="form-control" placeholder="Enter Price"
                                    value="{{ old('price') }}">
                                @error('price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">Quantity</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" name="quantity" class="form-control" placeholder="Enter Quantity"
                                    value="{{ old('quantity') }}">
                                @error('quantity')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Stock</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" name="stock" class="form-control" placeholder="Enter Stock"
                                    value="{{ old('stock') }}">
                                @error('stock')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">Description</label></div>
                            <div class="col-md-4 col-xl-4">
                                <textarea name="description" class="form-control" id="description" placeholder="Enter Description">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row align-items-center g-3 mt-6" id="status_div">
                            <div class="col-md-2"><label class="form-label mb-0">Status</label></div>
                            <div class="col-md-10 col-xl-10">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" id="status" {{ old('status') ? "checked" : "" }}>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-6">
                        <div class="d-flexjustify-content-end gap-2">
                            <a href="{{ route('product.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-dark" id="saveSubmitButton">Save</button>
                        </div>
                    </form>
                </main>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script>
        $('form').on('submit',function(e){
            $('#saveSubmitButton').html('<i class="fa fa-spinner fa-spin"></i>');
        });
        $('.product_image').on('change', function() {
            var fileName = $(this).val();
            var ext = fileName.split('.').pop();
            let substringToRemove = "C:\\fakepath\\";
            let resultString = fileName.replace(substringToRemove, "");
            $('#product_image_preview').html(resultString);

        })
    </script>
@endsection
