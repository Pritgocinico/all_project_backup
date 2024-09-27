@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid">
                <div id="kt_app_content_container" class="app-container  container-fluid">
                    <form class="form" action="{{ route('product.store') }}" method="post" enctype="multipart/form-data"
                        onsubmit="return productValidate()">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Name</label>
                                <input type="text" class="form-control mb-3 mb-lg-0" placeholder="Enter Name"
                                    onchange="generateSKUName()" name="name" value="{{ old('name') }}" id="name">
                                <span class="text-danger"
                                    id="name_error">{{ $errors->getBag('default')->first('name') }}</span>
                            </div>
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">SKU</label>
                                <input type="text" class="form-control" placeholder="Enter SKU" name="sku" readonly
                                    value="{{ old('sku') }}" id="sku">
                                <span class="text-danger"
                                    id="sku_error">{{ $errors->getBag('default')->first('sku') }}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Category</label>
                                <select name="category_id" id="category_id" class="form-select">
                                    <option value="">Select Category</option>
                                    @foreach ($categoryList as $item)
                                        @if (count($item->childCategoryDetails) > 0)
                                            <optgroup label="{{ $item->name }}">
                                                @foreach ($item->childCategoryDetails as $child)
                                                    <option
                                                        value="{{ $child->id }}"@if ($child->id == old('category_id')) {{ 'selected' }} @endif>
                                                        {{ $child->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @else
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <span class="text-danger"
                                    id="category_id_error">{{ $errors->getBag('default')->first('category_id') }}</span>
                            </div>
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Product Image</label>
                                <input type="file" class="form-control" placeholder="Enter Password" name="product_image"
                                    value="{{ old('product_image') }}" id="product_image">
                                <span class="text-danger"
                                    id="product_image_error">{{ $errors->getBag('default')->first('product_image') }}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Product Type</label>
                                <select name="product_type" id="product_type" class="form-select"
                                    onchange="productTypeCheck()">
                                    <option value="">Select Product Type</option>
                                    <option value="tax_product">Tax Product</option>
                                    <option value="without_tax_product">Without Tax Product</option>
                                </select>
                                <span class="text-danger"
                                    id="product_type_error">{{ $errors->getBag('default')->first('product_type') }}</span>
                            </div>
                            <div class="col-md-3 tax_div" style="display: none;">
                                <label class="fs-6 fw-semibold mb-2">CGST (For example : 9)</label>
                                <input type="number" class="form-control" name="c_gst" min="0" id="c_gst">
                                <span class="text-danger"
                                    id="c_gst_error">{{ $errors->getBag('default')->first('c_gst') }}</span>
                            </div>
                            <div class="col-md-3 tax_div" style="display: none;">
                                <label class="fs-6 fw-semibold mb-2">SGST (For example : 9)</label>
                                <input type="number" class="form-control" name="s_gst" min="0" id="s_gst">
                                <span class="text-danger"
                                    id="s_gst_error">{{ $errors->getBag('default')->first('s_gst') }}</span>
                            </div>
                        </div>
                        <h5 class="mt-2">Product Variants</h5>
                        <span id="variant_error" class="text-danger"></span>
                        <div class="variants d-none">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">SKU</label>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Capacity</label>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Price (With Tax)</label>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Price (without Tax)</label>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Stock</label>
                                </div>
                                <div class="col-md-1">
                                    <label for="">Action</label>
                                </div>
                            </div>
                            <div class="variant">

                            </div>
                        </div>
                        <div>
                            <button type="button" name="add" class="btn btn-primary mt-3 add_variant">Add
                                Variant</button>
                        </div>
                        <div class="row mt-2">
                            <label class="fs-6 fw-semibold mb-2">Description</label>

                            <textarea cols="10" rows="5" name="description" id="description" class="form-control"></textarea>
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
    <script>
        var image = "{{ asset('public/assets/media/icons/delete.png') }}";
        var lastId = "{{ $lastInsertId }}"
    </script>
    <script src="{{ asset('public\assets\js\custom\admin\product.js') }}?{{ time() }}"></script>
@endsection
