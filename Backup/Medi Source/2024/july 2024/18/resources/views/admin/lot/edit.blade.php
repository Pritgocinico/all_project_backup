@extends('admin.layouts.app')

@section('content')

<div class="content-page">
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Edit Lot</h3>
                    <a href="{{ route('admin.lots.index') }}" class="btn btn-secondary">Go Back</a>
                </div>
            </div>
        </div>
        <div class="card">

            <div class="card-body">
                <form action="{{ route('admin.lots.update', $lot->id) }}" method="POST" enctype="multipart/form-data" id="editLotForm">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="lot_number">Lot Number:</label>
                        <input type="text" class="form-control" id="lot_number" name="lot_number" value="{{ old('lot_number', $lot->lot_number) }}" required>
                        <span id="lotNumberError" class="text-danger"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="product_id">Product:</label>
                        <select class="form-control" id="product_id" name="product_id" required>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ $product->id == old('product_id', $lot->product_id) ? 'selected' : '' }}>
                                    {{ $product->productname }}
                                </option>
                            @endforeach
                        </select>
                        <span id="productError" class="text-danger"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description" required>{{ old('description', $lot->description) }}</textarea>
                        <span id="descriptionError" class="text-danger"></span>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group mb-3">
                                <label for="file">Upload File: (*Leave blank if you don't want to change file)</label>
                                <input type="file" class="form-control" id="file" name="file">
                                <span id="fileError" class="text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            @if($lot->file)
                                <a href="{{ asset('storage/lot-files/' . $lot->file) }}" target="_blank" class="btn btn-info mt-2">View File</a>
                                <input type="hidden" name="existing_file" value="{{ $lot->file }}">
                            @endif
                        </div>
                    </div>
                    <button type="button" onclick="validateForm()" class="btn btn-primary">Update Lot</button>
                    <a href="{{ route('admin.lots.index') }}" class="btn btn-secondary">Go Back</a>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add this in the head section or include from a CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function validateForm() {
        var lotNumber = $("#lot_number").val();
        var productId = $("#product_id").val();
        var description = $("#description").val();
        var file = $("#file").val();

        // Reset error messages
        $("#lotNumberError").text("");
        $("#productError").text("");
        $("#descriptionError").text("");
        $("#fileError").text("");

        // Perform validation
        if (lotNumber === "") {
            $("#lotNumberError").text("Lot Number is required.");
        }

        if (productId === "") {
            $("#productError").text("Product is required.");
        }

        if (description === "") {
            $("#descriptionError").text("Description is required.");
        }

        // You can add more validation as needed...

        // if (file === "") {
        //     $("#fileError").text("File is required.");
        // }

        // Submit the form if there are no errors
        if (lotNumber !== "" && productId !== "" && description !== "") {
            $("#editLotForm").submit();
        }
    }
</script>

@endsection
