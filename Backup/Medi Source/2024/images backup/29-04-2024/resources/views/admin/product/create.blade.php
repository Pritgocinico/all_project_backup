@extends('admin.layouts.app')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h2 class="mb-0">Add Product</h2>
                        <a href="{{ route('admin.product-details.index') }}" class="btn btn-secondary ms-auto">Go Back</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.product-details.store') }}" method="post" id="productForm"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="sku" class="form-label">SKU</label>
                            <input type="text" class="form-control" id="sku" name="sku"
                                value="{{ old('sku') }}">
                            @error('sku')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="productname" name="productname"
                                value="{{ old('inactive_ingredients') }}">
                            @error('productname')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Inactive Ingredients</label>
                            <textarea class="form-control" id="inactive_ingredients" name="inactive_ingredients">{{ old('inactive_ingredients') }}</textarea>
                            @error('inactive_ingredients')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="unit_size_type" class="form-label">Unit Size/Type</label>
                            <input type="text" class="form-control" id="unit_size_type" name="unit_size_type"
                                value="{{ old('unit_size_type') }}">
                            @error('unit_size_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="package_size" class="form-label">Package Size</label>
                            <input type="text" class="form-control" id="package_size" name="package_size"
                                value="{{ old('package_size') }}">
                            @error('package_size')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="product_code" class="form-label">Product Code</label>
                            <input type="text" class="form-control" id="product_code" name="product_code"
                                value="{{ old('product_code') }}">
                            @error('product_code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="ndc" class="form-label">NDC</label>
                            <input type="text" class="form-control" id="ndc" name="ndc"
                                value="{{ old('ndc') }}">
                            @error('ndc')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="storage" class="form-label">Storage</label>
                            <input type="text" class="form-control" id="storage" name="storage"
                                value="{{ old('storage') }}">
                            @error('storage')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="text" class="form-control" id="price" name="price"
                                value="{{ old('price', $product->price ?? '') }}">
                            @error('price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Add this inside the form -->
                        <div class="mb-3">
                            <label for="categories" class="form-label">Categories</label>
                            <select class="form-control" id="categories" name="categories[]" multiple>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('categories')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="dosageForms" class="form-label">Dosage Forms</label>
                            <select class="form-control" id="dosageForms" name="dosageForms[]" multiple>
                                @foreach ($dosageForms as $dosageForm)
                                    <option value="{{ $dosageForm->id }}">{{ $dosageForm->name }}</option>
                                @endforeach
                            </select>
                            @error('dosageForms')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="Meta Title" class="form-label">Tags</label>
                            <input type="text" class="form-control" id="tags" name="tags" placeholder="Add comma seperated values. eg: tag1, tag2..."
                                value="{{ old('tags', $product->tags ?? '') }}">
                            @error('tags')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="Meta Title" class="form-label">Meta Title</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title"
                                value="{{ old('meta_title', $product->meta_title ?? '') }}">
                            @error('meta_title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="Keyword" class="form-label">Keyword</label>
                            <input type="text" class="form-control" id="keyword" name="keyword"
                                value="{{ old('keyword', $product->keyword ?? '') }}">
                            @error('keyword')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="Description" class="form-label">Description</label>
                            <textarea name="description" class="form-control" id="description"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="Slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug"
                                value="{{ old('slug', $product->slug ?? '') }}">
                            @error('slug')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="weight" class="form-label">Vial weight</label>
                            <input type="text" class="form-control" id="vial_weight" name="vial_weight"
                                value="{{ old('vial_weight', $product->vial_weight ?? '') }}">
                            @error('vial_weight')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="Medical Necessity" class="form-label">Medical Necessity</label>
                            <input type="text" class="form-control" id="medical_necessity" name="medical_necessity" placeholder="Add comma seperated values. eg: necessity1, necessity2..."
                                value="{{ old('medical_necessity', $product->medical_necessity ?? '') }}">
                            @error('medical_necessity')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="Preservative Free" class="form-label">Preservative Free</label>
                            <input type="text" class="form-control" id="preservative_free" name="preservative_free"
                                value="{{ old('preservative_free', $product->preservative_free ?? '') }}">
                            @error('preservative_free')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="Sterile Type" class="form-label">Sterile Type</label>
                            <input type="text" class="form-control" id="sterile_type" name="sterile_type"
                                value="{{ old('sterile_type', $product->sterile_type ?? '') }}">
                            @error('sterile_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="Controlled States" class="form-label">Controlled States</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="controlled_state" id="controlled_state1"
                                    value="Yes">
                                <label class="form-check-label" for="controlled_state1">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="controlled_state" id="controlled_state2"
                                    value="No">
                                <label class="form-check-label" for="controlled_state2">No</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="Cold Ship" class="form-label">Cold Ship</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="cold_ship" id="cold_ship1"
                                    value="Yes">
                                <label class="form-check-label" for="cold_ship1">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="cold_ship" id="cold_ship2"
                                    value="No">
                                <label class="form-check-label" for="cold_ship2">No</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="Order Quantity" class="form-label">Maximum order quantity</label>
                            <input type="number" class="form-control" name="max_order_qty" id="max_order_qty">
                            @error('max_order_qty')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="single_image" class="form-label">Thumbnail Images</label>
                            <input type="file" class="form-control" id="single_image" name="single_image">
                            @error('single_image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <h3 class="mb-3">Product Gallery </h3>
                        <div class="mb-3">
                            <label for="images" class="form-label">Images</label>
                            <div id="image-input-container">
                                <input type="file" class="form-control" name="images[]" multiple>
                            </div>
                            <a href="javascript:void(0);" class="btn btn-success mt-2" id="addImageButton">Add Image</a>
                            @error('images')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="image-preview-container" class="mb-3">
                            <!-- Preview container for images -->
                        </div>

                        <h3 class="mb-3">Product Package Price</h3>
                        <div class="mb-3" id="packageContentField">
                            <div class="package-content-field row">
                                <div class="col-2">
                                    <label for="varient_name" class="form-label">Varient Name</label>
                                    <input type="text" class="form-control" name="varient_name[]" placeholder="Varient Name">
                                </div>
                                <div class="col-2">
                                    <label for="vial_price" class="form-label">Price Per Vial</label>
                                    <input type="number" class="form-control" name="vial_price[]" id="vialPrice_1" onchange="fill(1)" placeholder="Price Per Vial">
                                </div>
                                <div class="col-2">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" name="vial_quantity[]" id="vialQuantity_1" onchange="fill(1)" placeholder="Quantity">
                                </div>
                                <div class="col-2">
                                    <label for="total" class="form-label">Total</label>
                                    <input type="number" class="form-control" name="vial_total[]" id="vialTotal_1" placeholder="Total">
                                </div>
                            </div>
                        </div>
                        <div>
                            <a href="javascript:void(0);" class="btn btn-success" id="add_productPackage">
                                &#43; Add
                            </a>
                        </div>

                        <div class="mb-3">
                            <label for="Stock" class="form-label">Product Stock</label>
                            <input type="number" class="form-control" name="stock" id="stock">
                            @error('stock')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <h3 class="mb-3">Product Information</h3>
                        <div class="mb-3" id="titleContentFieldsContainer">
                            <div class="title-content-field">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" name="titles[]" placeholder="Title">
                                <label for="content" class="form-label mt-2">Content</label>
                                <textarea class="form-control" name="contents[]" placeholder="Content"></textarea>

                                </a>
                            </div>
                        </div>
                        <div>
                            <a href="javascript:void(0);" class="btn btn-success" id="addFieldButton">
                                &#43; Add Product Information
                            </a>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Product</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#productForm").validate({
                rules: {
                    sku: "required",
                    productname: "required",
                    inactive_ingredients: "required",
                    unit_size_type: "required",
                    package_size: "required",
                    product_code: "required",
                    ndc: "required",
                    storage: "required",
                    pdf_file: "required",
                    single_image: "required",
                    'images[]': {
                        required: true,
                        accept: "image/*"
                    }
                },
                messages: {
                    sku: "Please enter SKU",
                    productname: "Please enter Product Name",
                    inactive_ingredients: "Please enter Inactive Ingredients",
                    unit_size_type: "Please enter Unit Size/Type",
                    package_size: "Please enter Package Size",
                    product_code: "Please enter Product Code",
                    ndc: "Please enter NDC",
                    storage: "Please enter Storage",
                    pdf_file: "Please select Product Insert",
                    single_image: "Please select Thumbnail Image",
                    'images[]': {
                        required: "Please select at least one image",
                        accept: "Only image files are allowed"
                    }
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).closest(".mb-3").addClass("has-error").removeClass("has-success");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).closest(".mb-3").removeClass("has-error").addClass("has-success");
                },
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                }
            });
        });
    </script>



    <script>
        function previewImages(inputId, previewContainerId) {
            var input = document.getElementById(inputId);
            var previewContainer = document.getElementById(previewContainerId);

            if (input.files && input.files.length > 0) {
                for (var i = 0; i < input.files.length; i++) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        // Display the uploaded image
                        var imgElement = document.createElement('img');
                        imgElement.src = e.target.result;
                        imgElement.className = 'mb-2 magnifier';

                        // Add a remove link
                        var removeLink = document.createElement('a');
                        removeLink.href = 'javascript:void(0);';
                        removeLink.innerHTML = 'Remove';
                        removeLink.className = 'btn btn-danger btn-sm ml-2 remove-image';
                        removeLink.addEventListener('click', function() {
                            // Remove the image and the link when clicked
                            previewContainer.removeChild(imgElement);
                            previewContainer.removeChild(removeLink);
                            input.value = ''; // Clear the removed file from the input
                        });

                        // Append the image and remove link to the container
                        previewContainer.appendChild(imgElement);
                        previewContainer.appendChild(removeLink);
                    };

                    reader.readAsDataURL(input.files[i]);
                }
            }
        }

        // Listen for changes in the file input and update the preview
        document.querySelector('#image-input-container input[type="file"]').addEventListener('change', function() {
            previewImages('images', 'image-preview-container');
        });

        // Add Image link functionality
        document.getElementById('addImageButton').addEventListener('click', function() {
            var imageInputContainer = document.getElementById('image-input-container');
            var newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.className = 'form-control mt-2';
            newInput.name = 'images[]';

            // Add change event listener to the new input element
            newInput.addEventListener('change', function() {
                previewImages('images', 'image-preview-container');
            });

            imageInputContainer.appendChild(newInput);
        });

        // Remove Image link functionality
        document.getElementById('image-preview-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-image')) {
                // Remove the image and the link when clicked
                var imageContainer = e.target.parentElement;
                imageContainer.removeChild(e.target.previousElementSibling);
                imageContainer.removeChild(e.target);
            }
        });
    </script>



    <script>
        // ... (Your existing JavaScript code)

        // Add Field link functionality
        document.getElementById('addFieldButton').addEventListener('click', function() {
            var fieldInputContainer = document.getElementById('titleContentFieldsContainer');
            var newTitleContentField = document.createElement('div');
            newTitleContentField.className = 'title-content-field';

            var newTitleInput = document.createElement('input');
            newTitleInput.type = 'text';
            newTitleInput.className = 'form-control mt-2';
            newTitleInput.name = 'titles[]';
            newTitleInput.placeholder = 'Title';

            var newTitleLabel = document.createElement('label');
            newTitleLabel.innerText = 'Title:';
            newTitleLabel.className = 'mt-2';

            var newContentTextarea = document.createElement('textarea');
            newContentTextarea.className = 'form-control mt-2';
            newContentTextarea.name = 'contents[]';
            newContentTextarea.placeholder = 'Content';

            var newContentLabel = document.createElement('label');
            newContentLabel.innerText = 'Content:';
            newContentLabel.className = 'mt-2';

            var removeFieldButton = document.createElement('a');
            removeFieldButton.href = 'javascript:void(0);';
            removeFieldButton.className = 'btn btn-danger mt-2 removeFieldButton';
            removeFieldButton.innerHTML = ' &times;'; // &times; is the HTML entity for the multiplication sign (×)

            newTitleContentField.appendChild(newTitleLabel);
            newTitleContentField.appendChild(newTitleInput);
            newTitleContentField.appendChild(newContentLabel);
            newTitleContentField.appendChild(newContentTextarea);
            newTitleContentField.appendChild(removeFieldButton);

            fieldInputContainer.appendChild(newTitleContentField);
        });

        // Remove Field link functionality
        document.getElementById('titleContentFieldsContainer').addEventListener('click', function(e) {
            if (e.target.classList.contains('removeFieldButton')) {
                var fieldContainer = e.target.parentElement;
                fieldContainer.parentNode.removeChild(fieldContainer);
            }
        });

        // Add Product Package
        var i = 1;
        document.getElementById('add_productPackage').addEventListener('click', function() {
            i++;
            var packageInputContainer = document.getElementById('packageContentField');
            var newPackageContentField = document.createElement('div');
            newPackageContentField.className = 'package-content-field row align-items-end';

            var VarientCol = document.createElement('div');
            VarientCol.className = 'col-2';

            var PriceCol = document.createElement('div');
            PriceCol.className = 'col-2';

            var QuantityCol = document.createElement('div');
            QuantityCol.className = 'col-2';

            var TotalCol = document.createElement('div');
            TotalCol.className = 'col-2';

            var newVarientInput = document.createElement('input');
            newVarientInput.type = 'text';
            newVarientInput.className = 'form-control mt-2';
            newVarientInput.name = 'varient_name[]';
            newVarientInput.placeholder = 'Varient Name';

            var newVarientLabel = document.createElement('label');
            newVarientLabel.innerText = 'Varient Name';
            newVarientLabel.className = 'mt-2';

            var newPriceInput = document.createElement('input');
            newPriceInput.type = 'number';
            newPriceInput.className = 'form-control mt-2';
            newPriceInput.id = 'vialPrice_'+i;
            newPriceInput.name = 'vial_price[]';
            newPriceInput.placeholder = 'Price Per Vial';
            newPriceInput.setAttribute('onchange', 'fill('+i+')');

            var newPriceLabel = document.createElement('label');
            newPriceLabel.innerText = 'Price Per Vial';
            newPriceLabel.className = 'mt-2';

            var newQuantityInput = document.createElement('input');
            newQuantityInput.type = 'number';
            newQuantityInput.className = 'form-control mt-2';
            newQuantityInput.id = 'vialQuantity_'+i;
            newQuantityInput.name = 'vial_quantity[]';
            newQuantityInput.placeholder = 'Quantity';
            newQuantityInput.setAttribute('onchange', 'fill('+i+')');

            var newQuantityLabel = document.createElement('label');
            newQuantityLabel.innerText = 'Quantity';
            newQuantityLabel.className = 'mt-2';

            var newTotalInput = document.createElement('input');
            newTotalInput.type = 'number';
            newTotalInput.className = 'form-control mt-2';
            newTotalInput.id = 'vialTotal_'+i;
            newTotalInput.name = 'vial_total[]';
            newTotalInput.placeholder = 'Total';

            var newTotalLabel = document.createElement('label');
            newTotalLabel.innerText = 'Total';
            newTotalLabel.className = 'mt-2';

            var removePackageButton = document.createElement('a');
            removePackageButton.href = 'javascript:void(0);';
            removePackageButton.className = 'btn btn-danger mt-2 removePackageButton';
            removePackageButton.innerHTML = ' &times;';

            VarientCol.appendChild(newVarientLabel);
            VarientCol.appendChild(newVarientInput);
            PriceCol.appendChild(newPriceLabel);
            PriceCol.appendChild(newPriceInput);
            QuantityCol.appendChild(newQuantityLabel);
            QuantityCol.appendChild(newQuantityInput);
            TotalCol.appendChild(newTotalLabel);
            TotalCol.appendChild(newTotalInput);

            newPackageContentField.appendChild(VarientCol);
            newPackageContentField.appendChild(PriceCol);
            newPackageContentField.appendChild(QuantityCol);
            newPackageContentField.appendChild(TotalCol);
            newPackageContentField.appendChild(removePackageButton);

            packageInputContainer.appendChild(newPackageContentField); 
            
        });

        // Remove Package link functionality
        document.getElementById('packageContentField').addEventListener('click', function(e) {
            if (e.target.classList.contains('removePackageButton')) {
                var packageContainer = e.target.parentElement;
                packageContainer.parentNode.removeChild(packageContainer);
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#categories').select2();
            $('#dosageForms').select2();
        });
    </script>
    <script>
        function fill(id) {
            var vial_price = document.getElementById("vialPrice_"+id).value;
            var vial_quantity = document.getElementById("vialQuantity_"+id).value;
            document.getElementById("vialTotal_"+id).value = vial_price * vial_quantity;
        }
    </script>
    <style>
        .removePackageButton {
            width: 45px;
            height: 37px;
        }
    </style>
@endsection
