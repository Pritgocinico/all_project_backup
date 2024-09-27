@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid">
                <div id="kt_app_content_container" class="app-container  container-fluid">
                    @php $route = route('confirm-orders.update', $orderData->id) @endphp
                    @if (Auth()->user() == null)
                        @php $route = route('login') @endphp
                    @elseif(Auth()->user()->id == 1)
                        @php $route = route('orders.update', $orderData->id) @endphp
                    @elseif(Auth()->user()->id == 9)
                        @php $route = route('sale-orders.update', $orderData->id) @endphp
                    @endif
                    <form class="form" action="{{ $route }}" method="post" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Phone Number</label>
                                <input type="number" class="form-control mb-3 mb-lg-0" placeholder="Enter Phone Number"
                                    name="phoneno" value="{{ $orderData->phoneno }}" id="phoneno">
                                <span class="text-danger"
                                    id="name_error">{{ $errors->getBag('default')->first('phoneno') }}</span>
                            </div>
                            <div class="col-md-6">
                                <label class="fs-6 fw-semibold mb-2">Customer Name</label>
                                <input type="text" class="form-control" placeholder="Enter Customer Name"
                                    name="customer_name" value="{{ $orderData->customer_name }}" id="customer_name">
                                <span class="text-danger"
                                    id="email_error">{{ $errors->getBag('default')->first('customer_name') }}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="required fs-6 fw-semibold mb-2">Address</label>
                                <input type="text" class="form-control" placeholder="Enter Address" name="address"
                                    value="{{ $orderData->address }}" id="address">
                                <span class="text-danger"
                                    id="phone_number_error">{{ $errors->getBag('default')->first('address') }}</span>
                            </div>

                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6 employeePassword">
                                <input type="hidden" name="state" value="12">
                                <label class="required fs-6 fw-semibold mb-2">State</label>
                                <select name="state_id" id="state_id" class="form-select">
                                    <option value="">Select State</option>
                                    @foreach ($state as $states)
                                        <option value="{{ $states->id }}"
                                            @if ($states->id == 12) {{ 'selected' }} @endif disabled>
                                            {{ $states->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger"
                                    id="password_error">{{ $errors->getBag('default')->first('state') }}</span>
                            </div>
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">District</label>
                                <select name="district" id="district" class="form-select" onchange="getSubDistrict()">
                                    <option value="">Select District</option>
                                    @foreach ($district as $districts)
                                        <option value="{{ $districts->district }}"
                                            @if ($districts->district == $orderData->district) {{ 'selected' }} @endif>
                                            {{ $districts->district_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger"
                                    id="aadhar_card_error">{{ $errors->getBag('default')->first('district') }}</span>
                            </div>

                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Sub District</label>
                                <select name="sub_district" id="sub_district" class="form-select" onchange="getVillage()">
                                    <option value="">Select District</option>
                                    @foreach ($subdistrict as $sub)
                                        <option value="{{ $sub->sub_district }}"
                                            @if ($sub->sub_district == $orderData->sub_district) {{ 'selected' }} @endif>
                                            {{ $sub->sub_district_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger"
                                    id="pan_card_error">{{ $errors->getBag('default')->first('sub_district') }}</span>
                            </div>
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Village</label>
                                <select name="village" id="village" class="form-select">
                                    @foreach ($village as $vill)
                                        <option value="{{ $vill->village_code }}"
                                            @if ($vill->village_code == $orderData->village) {{ 'selected' }} @endif>
                                            {{ $vill->village_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger"
                                    id="qualification_error">{{ $errors->getBag('default')->first('village') }}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Pincode</label>
                                <input type="number" class="form-control" name="pincode" placeholder="Enter Pincode"
                                    value="{{ $orderData->pincode }}" id="pincode">
                                <span class="text-danger"
                                    id="system_code_error">{{ $errors->getBag('default')->first('pincode') }}</span>
                            </div>
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Expected Delivery Date</label>
                                <input type="date" class="form-control" name="excepted_delievery_date"
                                    placeholder="Enter Pincode" value="{{ $orderData->excepted_delievery_date }}"
                                    id="excepted_delievery_date">
                                <span class="text-danger"
                                    id="system_code_error">{{ $errors->getBag('default')->first('excepted_delievery_date') }}</span>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label>Scheme Code</label>
                                    <select name="scheme_code[]" class="form-control scheme_code "id="scheme_code">
                                        <option value="">Select Scheme Code...</option>
                                        @foreach ($allScheme as $scheme)
                                            <option value="{{ $scheme->discount_code }}"
                                                @if ($scheme->discount_code == $orderData->scheme_code) selected @endif>
                                                {{ $scheme->discount_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 d-none" id="scheme_product_div">
                                    <label>Product</label>
                                    <select name="scheme_product_id[]"
                                        class="form-control scheme_product_id "id="scheme_product_id">
                                        <option value="">Select Scheme Product</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <h6 class="mt-3">Products</h6>
                        <?php $id = 0; ?>
                        <div class="products_data">
                            @php $i = 1;@endphp
                            @if (isset($orderData->orderItem))
                                @foreach ($orderData->orderItem as $item)
                                    <input type="hidden" name="ids[]" value="{{ $item->id }}" />

                                    <div class="bg-light p-3 mt-2 product-{{ $item->id }}">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="">Category</label>
                                                <select name="category[]" id=""
                                                    class="form-control cat cat-{{ $i }}"
                                                    data-id="{{ $i }}" required>
                                                    <option value="">Select Category...</option>
                                                    @foreach ($categoryList as $category)
                                                        @if (count($category->childCategoryDetails) > 0)
                                                            <optgroup label="{{ $category->name }}">
                                                                @foreach ($category->childCategoryDetails as $child)
                                                                    <option value="{{ $child->id }}"
                                                                        @if ($child->id == $item->category_id) {{ 'selected' }} @endif>
                                                                        {{ $child->name }}</option>
                                                                @endforeach
                                                            </optgroup>
                                                        @else
                                                            <option value="{{ $category->id }}"
                                                                @if ($category->id == $item->category_id) {{ 'selected' }} @endif>
                                                                {{ $category->name }}</option>
                                                        @endif
                                                    @endforeach

                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="Product">Product</label>
                                                <select name="products[]"
                                                    class="form-control products products-{{ $i }}"
                                                    data-id ="{{ $i }}" id="" required>
                                                    <option value="">Select Product...</option>
                                                    <?php
                                                    $products = DB::table('product')
                                                        ->where('category_id', $item->category_id)
                                                        ->get();
                                                    ?>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}"
                                                            @if ($product->id == $item->product_id) selected @endif>
                                                            [{{ $product->sku_name }}] {{ $product->sku_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <a href="javascript:void(0);" class="delete-btn"
                                                    data-id="{{ $item->id }}" data-type="">
                                                    <i class="fa fa-trash fs-2"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <label for="">Variant</label>
                                                <select name="variant[]"
                                                    class="form-control variants-{{ $i }} variant"
                                                    data-id="{{ $i }}" id="" required>
                                                    <option value="">Select Product Variant...</option>
                                                    <?php
                                                    $variants = DB::table('product_variant')
                                                        ->where('product_id', $item->product_id)
                                                        ->get();
                                                    ?>
                                                    @foreach ($variants as $variant)
                                                        <option value="{{ $variant->id }}"
                                                            @if ($variant->id == $item->variant_id) selected @endif>
                                                            [{{ $variant->sku_name }}] {{ $variant->capacity }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    <?php $pr = DB::table('product_variant')
                                                        ->where('id', $item->variant_id)
                                                        ->first(); ?>
                                                <p class="text-danger fs-12">( Stock : <span
                                                        class="stock-{{ $i }} fs-12">
                                                        @if (!blank($pr))
                                                            {{ $pr->stock }}
                                                        @else
                                                            0
                                                        @endif
                                                    </span> )</p>
                                                </p>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="">Price</label>
                                                <input type="hidden" name="pr_price[]"
                                                    class="form-control pr-price-{{ $i }}"
                                                    value="{{ $item->price }}">
                                                <input type="hidden" id="item_amt-{{ $i }}"
                                                    value="{{ $item->price }}">
                                                <input type="text" name="price[]"
                                                    class="form-control pe-none price-{{ $i }}"
                                                    value="{{ $item->price }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="">Quantity</label>
                                                <input type="number" name="quantity[]" min="0"
                                                    value="{{ $item->quantity }}"
                                                    class="form-control qty qty-{{ $i }}"
                                                    data-id="{{ $i }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="Stock">Stock</label>
                                                <?php $pr = DB::table('product_variant')
                                                    ->where('id', $item->variant_id)
                                                    ->first(); ?>
                                                <input type="number" name="stock[]"
                                                    class="form-control stock-{{ $i }}"
                                                    value="@if (!blank($pr)) {{ $pr->stock }}@else 0 @endif"
                                                    disabled>
                                            </div>
                                            <div class="col-md-2 m-auto text-end">
                                                <input type="hidden" name="product_total[]"
                                                    class="pr-total-{{ $item->id }}"
                                                    value="{{ $item->quantity * $item->price }}">
                                                <h6 class="mb-0">Total : <span class="text-danger"> &#x20B9; <span
                                                            class="total total-{{ $i }}">{{ $item->quantity * $item->price }}</span></span>
                                                </h6>
                                            </div>
                                        </div>
                                        <div class="row text-danger">
                                            @if (!blank($pr))
                                                <?php $stock = $pr->stock; ?>
                                            @else
                                                <?php $stock = 0; ?>
                                            @endif
                                            @if ($stock == 0)
                                                * Stock is not available for this product.
                                            @endif
                                        </div>

                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <input type="hidden" name="product_data" class="product_data" value="1">
                        @if ($errors->has('product_data'))
                            <span class="text-danger">Please Select Order Products.</span>
                        @endif
                        <div class="row justify-content-end mt-3">
                            <div class="col-md-4">
                                <h5 class="text-end">Total : <span class="text-danger fs-24"> &#x20B9; <span class="g-total fs-24"> {{ $orderData->amount }}</span></span></h5>
                            </div>
                        </div>
                        <div>
                            <button type="button" name="add" class="btn btn-primary mt-3 add_product"
                                onclick="addProductDetail()">Add
                                Product</button>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="Status" class="required fs-6 fw-semibold mb-2">Add Order As Lead</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="orderlead"
                                        id="flexSwitchCheckChecked"
                                        @if ($orderData->order_lead_status == 1) {{ 'checked' }} @endif>
                                    <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                </div>
                            </div>
                            <div class="col-md-6 lead_datetime @if ($orderData->order_lead_status != 1) d-none @endif">
                                <label for="Lead" class="form-label">Lead Followup Date & Time</label>
                                <input type="datetime-local" name="lead_datetime" class="form-control"
                                    value="{{ $orderData->lead_followup_datetime }}">
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="Status" class="form-label">Order Status<span
                                        class="text-danger">*</span></label>
                                <select name="order_status" class="form-control" id="order_status">
                                    <option value="1" @if ($orderData->order_status == 1) selected @endif>Pending
                                        Order</option>
                                    <option value="2" @if ($orderData->order_status == 2) selected @endif>Confirmed
                                    </option>
                                    <option value="3" @if ($orderData->order_status == 3) selected @endif>On Delivery
                                    </option>
                                    <option value="4" @if ($orderData->order_status == 4) selected @endif>Cancel Order
                                    </option>
                                    <option value="5" @if ($orderData->order_status == 5) selected @endif>Returned
                                    </option>
                                    <option value="6" @if ($orderData->order_status == 6) selected @endif>Delivered
                                    </option>
                                </select>
                                @if ($errors->has('order_status'))
                                    <span class="text-danger">{{ $errors->first('order_status') }}</span>
                                @endif
                            </div>
                        </div>
                        <hr class="mt-5">
                        <div class="col-md-12">
                            <label for="Remarks" class="form-label">Remarks </label>
                            <textarea class="form-control" name="remarks" rows="3" id="Remarks" placeholder="Remarks">{{ old('remarks') }}</textarea>
                            @if ($errors->has('remarks'))
                                <span class="text-danger">{{ $errors->first('remarks') }}</span>
                            @endif
                        </div>


                        <div class="mt-2">
                            <input type="hidden" name="amount" class="grand_total" value="{{ $orderData->amount }}">
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
        var disUrl = "{{ route('get-subdistricts') }}";
        var villUrl = "{{ route('get-villages') }}";
        var categoryUrl = "{{ route('get-category') }}";
        var productUrlDetail = "{{ route('get-product-list') }}";
        var productVariantUrlDetail = "{{ route('get-product-variant-list') }}";
        var variantUrl = "{{ route('get_variant') }}";
        var deleteImage = "{{ asset('public/assets/media/icons/delete.png') }}";
        var schemeList = "{{ $allScheme }}";
        var schemeCodeDetail = "{{ route('scheme-detail-code') }}"
        var schemeCodeProduct = "{{ route('scheme-detail-product') }}"
        var count = "{{ count($orderData->orderItem) }}";
        var deleteOrderItem = "{{route('delete-order-item')}}"
    </script>
    <script src="{{ asset('public\assets\js\custom\admin\order.js') }}?{{ time() }}"></script>
@endsection
