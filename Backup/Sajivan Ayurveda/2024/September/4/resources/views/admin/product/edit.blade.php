@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Update Product</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('product.update', $product->id) }}" enctype="multipart/form-data" method="POST">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="id" value="{{$product->id}}">
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="name" class="form-control" placeholder="Enter Product Name"
                                    value="{{ $product->name }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">SKU</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="sku" class="form-control" placeholder="Enter SKU"
                                    value="{{ $product->sku }}">
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
                                        <option value="{{$category->id}}" @if ($category->id == $product->category_id) selected @endif>{{$category->name}}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">Image</label></div>
                            <div class="col-md-2 col-xl-2">
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
                            @if(isset($product->image))
                                <div class="col-md-1 col-xl-1 adhar_image_div">
                                    <a href="{{ asset('storage/'.$product->image) }}" target="_blank" class="btn btn-sm btn-dark "><span>View</span></a>
                                </div>
                                <div class="col-md-1 col-xl-1">
                                    <a href="#" class="btn btn-sm btn-danger remove_document"  data-type="image"><span>Remove</span></a>
                                </div>
                                
                            @endif
                        </div>

                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Price</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" name="price" class="form-control" placeholder="Enter Price"
                                    value="{{ $product->price }}">
                                @error('price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">Quantity</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" name="quantity" class="form-control" placeholder="Enter Quantity"
                                    value="{{ $product->quantity }}">
                                @error('quantity')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Stock</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" name="stock" class="form-control" placeholder="Enter Stock"
                                    value="{{ $product->stock }}">
                                @error('stock')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">Description</label></div>
                            <div class="col-md-4 col-xl-4">
                                <textarea name="description" class="form-control" id="description" placeholder="Enter Description">{{ $product->description }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row align-items-center g-3 mt-6" id="status_div">
                            <div class="col-md-2"><label class="form-label mb-0">Status</label></div>
                            <div class="col-md-10 col-xl-10">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" id="status" {{ ($product->status == 1) ? "checked" : "" }}>
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
        $('.remove_document').on('click',function(e){
            var type = $(this).data('type');
            var id = "{{$product->id ?? ""}}";
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the delete this?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('remove.product.image') }}" ,
                        type: 'get',
                        data: {
                            type: type,
                            id:id,
                        },
                        success: function(data) {
                            location.reload();
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message)
                        }
                    });
                }
            });
        })
    </script>
@endsection
