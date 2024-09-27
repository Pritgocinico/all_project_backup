@extends('admin.layouts.app')

@section('content')

<div class="content-page">
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Add Lot</h3>
                    <a href="{{ route('admin.lots.index') }}" class="btn btn-secondary">Go Back</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.lots.store') }}" method="POST" enctype="multipart/form-data" id="createLotForm">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="lot_number">Lot Number:</label>
                        <input type="text" class="form-control" id="lot_number" name="lot_number" value="{{old('lot_number')}}">
                        <span id="lotNumberError" class="text-danger"></span>
                        @if ($errors->has('lot_number'))
                            <span class="text-danger">{{ $errors->first('lot_number') }}</span>
                        @endif
                    </div>

                    <div class="form-group mb-3">
                        <label for="product_id">Product:</label>
                        <select class="form-control" id="product_id" name="product_id">
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" @if(old('product_id') == $product->id) @endif>{{ $product->productname }}</option>
                            @endforeach
                        </select>
                        <span id="productError" class="text-danger"></span>
                        @if ($errors->has('product_id'))
                            <span class="text-danger">{{ $errors->first('product_id') }}</span>
                        @endif
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description">{{old('description')}}</textarea>
                        <span id="descriptionError" class="text-danger"></span>
                        @if ($errors->has('description'))
                            <span class="text-danger">{{ $errors->first('description') }}</span>
                        @endif
                    </div>

                    <div class="form-group mb-3">
                        <label for="file">Upload File:</label>
                        <input type="file" class="form-control" id="file" name="file">
                        <span id="fileError" class="text-danger"></span>
                        @if ($errors->has('file'))
                            <span class="text-danger">{{ $errors->first('file') }}</span>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Create Lot</button>

                    <!--<button type="button" onclick="validateForm()" class="btn btn-primary">Create Lot</button>-->

                </form>
            </div>
        </div>
    </div>
</div>

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

        if (file === "") {
            $("#fileError").text("File is required.");
        }

        // Submit the form if there are no errors
        if (lotNumber !== "" && productId !== "" && description !== "" && file !== "") {
            $("#createLotForm").submit();
        }
    }
</script>

@endsection
