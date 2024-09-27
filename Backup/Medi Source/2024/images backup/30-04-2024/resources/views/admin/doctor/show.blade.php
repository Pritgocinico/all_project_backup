@extends('admin.layouts.app')

@section('content')
<div class="content-page">
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-center">
                    <h3 class="mb-0">Doctor Products Prices</h3>
                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary ms-auto ">Go Back</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="container ">
                    <form action="{{ route('admin.doctors.submitForm', ['id' => $id]) }}" method="POST" id="fieldsForm">
                        @csrf
                        <div class="d-none">
                        <!-- Initial Product Dropdown and Price Input -->
                            <div class="clone-div d-flex gap-2 align-items-center" id="initialClone" style="display: none;">
                                <div class="form-group mb-3 w-100">
                                    <label for="product">Product:</label>
                                    <select class="form-control product-select" name="newProducts[]">
                                        <option value="" disabled selected>Select product</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->productname }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-3 w-100">
                                    <label for="package">Product Package:</label>
                                    <select class="form-control package-select" name="newPackages[]">
                                        <option value="">Select Product Package</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3 w-100">
                                    <label for="price">Price:</label>
                                    <input type="number" name="newPrices[]" class="form-control prices"
                                        placeholder="Enter price">
                                </div>

                                <!-- Add save button for the initial field -->
                            </div>
                        </div>
                        <div id="dynamicFieldsContainer">
                            <!-- Display Existing Doctor Prices -->
                            @foreach($existingData as $key => $data)
                            <div class="clone-div added-field d-flex gap-2 align-items-center"
                                data-field-id="{{ $data->id }}">
                                <div class="form-group mb-3 w-100">
                                    <label for="product">Product:</label>
                                    <select class="form-control product-select" name="products[]" required>
                                        <option value="" disabled selected>Select product</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ $product->id == $data->product_id ? 'selected' : '' }}>
                                            {{ $product->productname }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                @php
                                    $packages = DB::table('product_packages')
                                        ->where('product_id', $data->product_id)
                                        ->get();
                                @endphp
                                <div class="form-group mb-3 w-100">
                                    <label for="package">Product Package:</label>
                                    <select class="form-control package-select" name="packages[]">
                                        <option value="">Select Product Package</option>
                                        @foreach($packages as $key => $package)
                                        <option value="{{$package->id}}" {{ $package->id == $data->package_id ? 'selected' : '' }}>{{ $package->varient_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-3 w-100">
                                    <label for="price">Price:</label>
                                    <input type="number" name="prices[]" class="form-control prices"
                                        value="{{ $data->price }}" placeholder="Enter price" required>
                                </div>
                                <!-- Add edit, update, and delete buttons for dynamically added fields -->
                                <button type="button" class="btn btn-danger removeFieldBtn">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <input type="hidden" name="existingFields[]" value="{{ $data->id }}">
                            </div>
                            @endforeach
                        </div>
                        <!-- Add Button for Dynamically Adding More Fields -->
                        <button type="button" class="btn btn-info" id="addFieldBtn">
                            <i class="fas fa-plus"></i> Add Product
                        </button>
                        <hr>
                        <input type="hidden" name="doctor_id" value="{{ $id }}">
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery and Font Awesome -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

<script>
$(document).ready(function() {
    // Add Button for Dynamically Adding More Fields
    $("#addFieldBtn").click(function() {
        // Clone the initial field
        var clonedFields = $("#initialClone").clone(true);

        // Show the cloned fields
        clonedFields.show();

        // Remove ID from cloned fields to ensure they are treated as new fields
        clonedFields.removeAttr('id');

        // Add classes and append remove, edit, update, and save buttons for dynamically added fields
        clonedFields.removeClass('initial-field').addClass('added-field');
        clonedFields.append(
            '<button type="button" class="btn btn-danger removeFieldBtn"><i class="fas fa-trash"></i></button>'
            );

        // Remove the "readonly" attribute from the cloned fields to allow user input
        clonedFields.find('input[name="newPrices[]"]').prop('readonly', false);

        // Generate a unique ID for the cloned field
        var newId = Date.now();
        clonedFields.attr('id', 'cloneDiv_' + newId);

        // Update the names of the cloned fields to ensure uniqueness
        clonedFields.find('select[name="newProducts[]"]').attr('name', 'newProducts[' + newId + ']').attr('id',  newId);
        clonedFields.find('select[name="newPackages[]"]').attr('name', 'newPackages[' + newId + ']').attr('id',  'ids-'+newId);
        clonedFields.find('input[name="newPrices[]"]').attr('name', 'newPrices[' + newId + ']').attr('id',  newId);

        // Append the cloned fields to the dynamicFieldsContainer
        $("#dynamicFieldsContainer").append(clonedFields);
    });

    $('.product-select').change(function(){
                var selectedId = $(this).attr('id');
        var productID = $(this).val();
        $.ajax({
            type: "POST",
            url: "{{ route('getproductPackage') }}",
            data:{
                "_token": "{{ csrf_token() }}",
                productID: productID
            },
            success : function(data) {
                var productPackage = $('#ids-' + selectedId);

                        productPackage.empty();
                        productPackage.append('<option value="" selected disabled> Select Product Package </option>');
                        
                        var product_packages = data;
                        for (var i = 0; i < product_packages.length; ++i) {
                                var product_package = product_packages[i];
                                var option = '<option value="' + product_package.id + '">' + product_package.varient_name + '</option>';
                                productPackage.append(option);
                            }
                    }
            });
        });

    // Remove Button for Dynamically Removing Fields
    $("#dynamicFieldsContainer").on("click", ".removeFieldBtn", function() {
        var removedFieldDiv = $(this).closest(".clone-div");
        var removedFieldId = removedFieldDiv.data('field-id');

        // If the field has an ID, mark it for deletion
        if (removedFieldId) {
            // Append the removed field ID to a hidden input
            $("#fieldsForm").append('<input type="hidden" name="removedFields[]" value="' +
                removedFieldId + '">');

            // Perform an AJAX request to remove the entry from the database
            $.ajax({
                type: "POST",
                url: "{{ route('admin.doctors.removeField') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "removedFieldId": removedFieldId,
                },
                success: function(response) {
                    // Handle success (e.g., remove the field from the UI)
                    removedFieldDiv.remove();
                },
                error: function(error) {
                    // Handle error
                    console.error(error);
                    alert("An error occurred while removing the field.");
                }
            });
        } else {
            // If the field doesn't have an ID, simply remove it from the UI
            removedFieldDiv.remove();
        }
    });


    // Remove Button for Dynamically Removing Fields
    $("#dynamicFieldsContainer").on("click", ".removeFieldBtn", function() {
        if (confirm('Are you sure you want to remove this field?')) {
            var removedFieldId = $(this).closest(".clone-div").data('field-id');
            // If the field has an ID, mark it for deletion
            if (removedFieldId) {
                $("#fieldsForm").append('<input type="hidden" name="removedFields[]" value="' +
                    removedFieldId + '">');
            }
            $(this).closest(".clone-div").remove();
        }
    });

    // ... (rest of your existing JavaScript code)

});


$("#submitBtn").click(function() {
    // Validate form before submission
    var isValid = true;

    // Validate existing fields
    $(".added-field").each(function() {
        var productSelect = $(this).find('.product-select');
        var packageSelect = $(this).find('.package-select');
        var priceInput = $(this).find('.prices');

        if (productSelect.val() === "" || priceInput.val() === "") {
            isValid = false;
            alert("Please fill in all fields for existing entries.");
            return false; // Exit each loop if validation fails
        }
    });

    // Validate initial field
    var initialProductSelect = $("#initialClone").find('.product-select');
    var initialPackageSelect = $("#initialClone").find('.package-select');
    var initialPriceInput = $("#initialClone").find('.prices');

    // if (initialProductSelect.val() === "" || initialPriceInput.val() === "") {
    //     isValid = false;
    //     alert("Please fill in all fields for the initial entry.");
    // }

    // If all validations pass, submit the form
    if (isValid == true) {
        $("#fieldsForm").submit();
    }
});
</script>

@endsection
