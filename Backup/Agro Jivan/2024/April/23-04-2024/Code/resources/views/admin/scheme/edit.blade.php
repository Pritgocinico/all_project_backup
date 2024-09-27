@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid">
                <div id="kt_app_content_container" class="app-container  container-fluid">
                    <form class="form" action="{{ route('scheme.update',$scheme->id) }}" method="post">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Discount Code</label>
                                <input type="text" name="scheme_code" id="scheme_code" class="form-control" value="{{$scheme->discount_code}}">
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($scheme->discountItemDetail as $dicountItem)
                                
                                <div class="col-md-6">
                                <input type="hidden" name="ids[]" value="{{$dicountItem->id}}" />
                                <label class="required fs-6 fw-semibold mb-2">Product (Buy X)</label>
                                <select name="product[]" id="product" class="form-select">
                                    <option value="">Select Product Variants</option>
                                    @foreach ($productList as $product)
                                        @if (count($product->productVariantDetail) > 0)
                                            <optgroup label="{{ $product->product_name }}">
                                                @foreach ($product->productVariantDetail as $child)
                                                    <option
                                                        value="{{ $child->id }}"@if ($child->id == $dicountItem->product_id) {{ 'selected' }} @endif>
                                                        {{ $child->sku_name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @else
                                            <option value="{{ $product->id }}"
                                                @if ($product->id == $dicountItem->product_id) {{ 'selected' }} @endif>
                                                {{ $product->product_name }}</option>
                                        @endif
                                    @endforeach

                                </select>
                                <span class="text-danger"
                                    id="discount_type_error">{{ $errors->getBag('default')->first('product') }}</span>
                                </div>
                                <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Free Products (Get Y)</label>
                                <select name="free_product[]" id="free_product"
                                    class="form-select js-example-basic-multiple">
                                    <option value="">Select Product Variants</option>
                                    @foreach ($productList as $product)
                                        @if (count($product->productVariantDetail) > 0)
                                            <optgroup label="{{ $product->product_name }}">
                                                @foreach ($product->productVariantDetail as $child)
                                                    <option
                                                        value="{{ $child->id }}" @if ($child->id == $dicountItem->free_product_id) {{ 'selected' }} @endif>
                                                        {{ $child->sku_name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @else
                                            <option value="{{ $product->id }}"
                                                @if ($product->id == $dicountItem->free_product_id) {{ 'selected' }} @endif>
                                                {{ $product->product_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <span class="text-danger"
                                    id="discount_type_error">{{ $errors->getBag('default')->first('free_product') }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="products_data"></div>
                        <div class="modal-footer justify-content-start mt-2">
                            <button type="button" name="submit" value="submit" class="btn btn-primary add_product">Add
                                More</button>
                        </div>
                        <div class="row mt-2">
                            @if ($scheme->discount_type_id == '1')
                                <div class="col-md-6">
                                    <label class="required fs-6 fw-semibold mb-2">Discount Percantage</label>
                                    <input type="number" name="discount_percentage" id="discount_percentage"
                                        value="{{$scheme->discount_percentage}}" class="form-control" />
                                    <span class="text-danger"
                                        id="discount_type_error">{{ $errors->getBag('default')->first('discount_percentage') }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer justify-content-start mt-2">
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
        var deleteImage = "{{ asset('public/assets/media/icons/delete.png') }}";
        var product = "{{ $productList }}";
    </script>
    <script>
        var i = 0;
        $(document).on('click', '.add_product', function() {
            i = i + 1;
            $('.product_data').val(i);
            var html = '<input type="hidden" name="ids[]" value="var_' + i + '" />';
            html += '<div class="bg-light p-3 mt-2 product-' + i + '">';
            html += '<div class="row">';
            html += '<div class="col-md-4">';
            html += '<label for="">Product</label>';
            html += '<select name="product[]" id="" class="form-control product products-' + i + '" data-id="' + i +
                '" required>';
            html += '<option value="">Select product...</option>';
            html += '</select>';
            html += '</div>';
            html += '<div class="col-md-6">';
            html += '<label for="Product">Free Product</label>';
            html += '<select name="free_product[]" class="form-control free_product free_products-' + i +
                '" data-id ="' + i + '" id="" required>';
            html += '<option value="">Select Product...</option>';
            html += '</select>';
            html += '</div>';
            html += '<div class="col-md-2 text-end">';
            html += '<a href="javascript:void(0);" class="ms-2 delete-btn delete-' + i + '" data-id="' + i +
                '"><img src="' + deleteImage + '" width="20px" class="me-2"></a>';
            html += '</div>';
            html += '</div>';

            html += '</div>';
            $('.products_data').append(html);
            var productArray = product.replace(/&quot;/g, '"');
            var html1 = "";
            $.each(JSON.parse(productArray), function(i, v) {
                if (v.product_variant_detail.length > 0) {
                    html1 += `<optgroup label="` + v.product_name + `">`;
                    $.each(v.product_variant_detail, function(a, b) {
                        html1 += '<option value="' + b.id + '">' + b.sku_name + '</option>';
                    })
                    html1 += '</optgroup>';
                } else {
                    html1 += '<option value="' + v.id + '">' + v.product_name + '</option>';
                }
            });
            $('.products-' + i).append(html1);
            $('.free_products-' + i).append(html1);
        });
        $(document).on('click', '.delete-btn', function() {
            var data = $(this).data('id');
            $('.product-' + data).remove();
        });
    </script>
@endsection
