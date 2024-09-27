@extends('admin.layouts.app')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Add Coupon Code</h3>
                        <a href="{{ route('coupon.index') }}" class="btn btn-secondary">Go Back</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    @php $route = route('coupon.store');$button="Create"; @endphp
                    @if($type == "edit")
                        @php $route = route('coupon.update',$coupon->id);$button="Update";$edit="Edit"; @endphp
                    @endif
                    <form action="{{ $route }}" method="POST" enctype="multipart/form-data"
                        id="createLotForm">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="lot_number">Coupon Code:</label>
                            <input type="text" name="coupon_code" id="coupon_code" class="form-control" placeholder="Coupon Code" value="{{ old('coupon_code', $coupon->coupon_code) }}">
                            @if ($errors->has('coupon_code'))
                                <span class="text-danger">{{ $errors->first('coupon_code') }}</span>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="product_id">Product:</label>
                            <select class="form-control" id="product_id" name="product_id">
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" {{ $product->id == old('product_id', $coupon->product_id) ? 'selected' : '' }}>
                                        {{ $product->productname }}</option>
                                @endforeach
                            </select>
                            <span id="productError" class="text-danger"></span>
                            @if ($errors->has('product_id'))
                                <span class="text-danger">{{ $errors->first('product_id') }}</span>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="description">Quantity:</label>
                            <input type="number" class="form-control" id="quantity" name="quantity"
                                value="{{ old('quantity', $coupon->quantity) == null ? 1: old('quantity', $coupon->quantity) }}">
                            <span id="descriptionError" class="text-danger"></span>
                            @if ($errors->has('quantity'))
                                <span class="text-danger">{{ $errors->first('quantity') }}</span>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label>Discount Type:</label>
                            <select class="form-control" id="discount_type" name="discount_type" onchange="showProductDetail()">
                                <option value="percentage" {{ 'percentage' == old('discount_type', $coupon->coupon_type) ? 'selected' : '' }}>Percentage</option>
                                <option value="dollar" {{ 'dollar' == old('discount_type', $coupon->coupon_type) ? 'selected' : '' }}>Amount</option>
                            </select>
                            @if ($errors->has('discount_type'))
                                <span class="text-danger">{{ $errors->first('discount_type') }}</span>
                            @endif
                        </div>
                        <div class="form-group mb-3 @if(old('discount_type', $coupon->coupon_type) == 'dollar') d-none @endif" id="discount_percentage_div">
                            <label for="description">Discount Percentage:</label>
                            <input type="number" class="form-control" id="discount_percentage" name="discount_percentage"
                                value="{{ old('discount_percentage', $coupon->discount_percentage) == null ? 0: old('discount_percentage', $coupon->discount_percentage) }}">
                            <span id="descriptionError" class="text-danger"></span>
                            @if ($errors->has('discount_percentage'))
                                <span class="text-danger">{{ $errors->first('discount_percentage') }}</span>
                            @endif
                        </div>
                        <div class="form-group mb-3 @if(old('discount_type', $coupon->coupon_type) == "percentage" || old('discount_type', $coupon->coupon_type) == "") d-none @endif" id="discount_amount_div">
                            <label for="description">Discount Amount:</label>
                            <input type="number" class="form-control" id="discount_amount" name="discount_amount"
                                value="{{ old('discount_amount', $coupon->discount_amount) == null ? 0: old('discount_amount', $coupon->discount_amount) }}">
                            @if ($errors->has('discount_amount'))
                                <span class="text-danger">{{ $errors->first('discount_amount') }}</span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">{{$button}} Coupon</button>

                        <!--<button type="button" onclick="validateForm()" class="btn btn-primary">Create Lot</button>-->

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function showProductDetail() {
            var type = $('#discount_type').val();
            $('#discount_percentage_div').addClass('d-none')
            $('#discount_amount_div').removeClass('d-none')
            if (type == 'percentage') {
                $('#discount_percentage_div').removeClass('d-none')
                $('#discount_amount_div').addClass('d-none')
            }
        }

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
