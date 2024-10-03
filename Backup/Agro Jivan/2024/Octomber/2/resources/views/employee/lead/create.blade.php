@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid">
                <div id="kt_app_content_container" class="app-container  container-fluid">
                    @php
                        $route = route('employee-lead.store');
                        if (isset(Auth()->user()->role_id) && Auth()->user()->role_id == 1) {
                            $route = route('admin-lead.store');
                        }
                    @endphp
                    <form class="form" action="{{ $route }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row mt-5">
                            <div class="col-md-9">
                                <div class="d-md-flex gap-4 align-items-center bg-white p-3">
                                    <div class="d-none d-md-flex">
                                        <p style="width:max-content;" class="mb-0 fs-6 fw-semibold">Search Call</p>
                                    </div>
                                    <div class="w-100 gap-4 align-items-center">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <select class="form-select classic search-table">
                                                    <option value="">Select Option</option>
                                                    <option value="1">Search By Sub District</option>
                                                    <option value="2">Search By Village</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="search-1 d-none">
                                                    <select name="search_sub_district" id="search_sub_district"
                                                        class="form-select">
                                                        <option value="" selected>Select Sub District</option>
                                                        @foreach ($subdistricts as $subdistrict)
                                                            <option value="{{ $subdistrict->sub_district }}">
                                                                {{ $subdistrict->sub_district_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="search-2 d-none">
                                                    <select name="search_village" id="search_village" class="form-select">
                                                        <option value="" selected>Select Village</option>
                                                        @foreach ($villages as $village)
                                                            <option value="{{ $village->village_code }}">
                                                                {{ $village->village_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 d-none" id="clear_detail_div_button">
                                                <a class="btn btn-icon btn-active-light-primary w-30px h-30px me-3 align-bottom"
                                                    href="#" onclick="filterSearchData()" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Reset Form">
                                                    <i class="ki-outline ki-cross-circle text-danger"
                                                        style="font-size: 20px !important"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="required fs-6 fw-semibold mb-2">Phone Number</label>
                                        <input type="number" class="form-control mb-3 mb-lg-0"
                                            onkeypress="return event.keyCode == 13?false:true"
                                            placeholder="Enter Phone Number" name="phoneno" value="{{ old('phoneno') }}"
                                            id="phoneno">
                                        <span class="text-danger"
                                            id="name_error">{{ $errors->getBag('default')->first('phoneno') }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="fs-6 fw-semibold mb-2">Customer Name</label>
                                        <input type="text" class="form-control" placeholder="Enter Customer Name"
                                            name="customer_name" value="{{ old('customer_name') }}" id="customer_name">
                                        <span class="text-danger"
                                            id="email_error">{{ $errors->getBag('default')->first('customer_name') }}</span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <label class="required fs-6 fw-semibold mb-2">Address</label>
                                        <input type="text" class="form-control" placeholder="Enter Address"
                                            name="address" value="{{ old('address') }}" id="address">
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
                                        <select name="district" id="district" class="form-select"
                                            onchange="getSubDistrict()">
                                            <option value="">Select District</option>
                                            @foreach ($district as $districts)
                                                <option value="{{ $districts->district }}">
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
                                        <select name="sub_district" id="sub_district" class="form-select"
                                            onchange="getVillage()">
                                        </select>
                                        <span class="text-danger"
                                            id="pan_card_error">{{ $errors->getBag('default')->first('sub_district') }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="required fs-6 fw-semibold mb-2">Village</label>
                                        <select name="village" id="village" class="form-select">
                                            <option value="">Select Village</option>
                                        </select>
                                        <span class="text-danger"
                                            id="village_error">{{ $errors->getBag('default')->first('village') }}</span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label class="required fs-6 fw-semibold mb-2">Pincode</label>
                                        <input type="number" class="form-control" name="pincode"
                                            placeholder="Enter Pincode" value="{{ old('pincode') }}" id="pincode">
                                        <span class="text-danger"
                                            id="system_code_error">{{ $errors->getBag('default')->first('pincode') }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="required fs-6 fw-semibold mb-2">Expected Delivery Date</label>
                                        <input type="date" class="form-control" name="excepted_delievery_date"
                                            placeholder="Enter Pincode" value="{{ date('Y-m-d') }}"
                                            id="excepted_delievery_date">
                                        <span class="text-danger"
                                            id="system_code_error">{{ $errors->getBag('default')->first('excepted_delievery_date') }}</span>
                                    </div>
                                </div>
                                <h6 class="mt-3">Products</h6>
                                <?php $id = 0; ?>
                                <div class="products_data">
                                    @if (old('category'))
                                        <?php $cnt = count(old('category')); ?>
                                        @for ($i = 0; $i < $cnt; $i++)
                                            <div class="bg-light p-3 mt-2 product-{{ $i }}">
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
                                                                            <option
                                                                                value="{{ $child->id }}"@if ($child->id == old('category' . $i)) {{ 'selected' }} @endif>
                                                                                {{ $child->name }}</option>
                                                                        @endforeach
                                                                    </optgroup>
                                                                @else
                                                                    <option value="{{ $category->id }}"
                                                                        @if ($category->id == old('category_id')) {{ 'selected' }} @endif>
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
                                                                ->where('category_id', old('category.' . $i))
                                                                ->get();
                                                            ?>
                                                            @foreach ($products as $product)
                                                                <option value="{{ $product->id }}"
                                                                    @if ($product->id == old('products.' . $i)) selected @endif>
                                                                    [{{ $product->sku_name }}] {{ $product->sku_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2 text-end">
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
                                                                ->where('product_id', old('products.' . $i))
                                                                ->get();
                                                            ?>
                                                            @foreach ($variants as $variant)
                                                                <option value="{{ $variant->id }}"
                                                                    @if ($variant->id == old('variant.' . $i)) selected @endif>
                                                                    [{{ $variant->sku_name }}] {{ $variant->capacity }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <p class="text-danger">
                                                            <?php $pr = DB::table('product_variant')
                                                                ->where('id', old('variant.' . $i))
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
                                                            value="{{ old('price.' . $i) }}">
                                                        <input type="hidden" id="item_amt-{{ $i }}"
                                                            value="{{ old('price.' . $i) }}">
                                                        <input type="text" name="price[]"
                                                            class="form-control pe-none price-{{ $i }}"
                                                            value="{{ old('price.' . $i) }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="">Quantity</label>
                                                        <input type="number" name="quantity[]" min="0"
                                                            value="{{ old('quantity.' . $i) }}"
                                                            class="form-control qty qty-{{ $i }}"
                                                            data-id="{{ $i }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="Stock">Stock</label>
                                                        <?php $pr = DB::table('product_variant')
                                                            ->where('id', old('variant.' . $i))
                                                            ->first(); ?>
                                                        <input type="number" name="stock[]"
                                                            class="form-control stock-{{ $i }}"
                                                            value="@if (!blank($pr)) {{ $pr->stock }}@else 0 @endif"
                                                            readonly>
                                                    </div>
                                                    <div class="col-md-2 m-auto text-end">
                                                        <input type="hidden" name="product_total[]"
                                                            class="pr-total-{{ $i }}"
                                                            value="{{ old('quantity.' . $i) * old('price.' . $i) }}">
                                                        <h6 class="mb-0">Total : <span class="text-danger"> &#x20B9;
                                                                <span
                                                                    class="total total-{{ $i }}">{{ old('quantity.' . $i) * old('price.' . $i) }}</span></span>
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
                                        @endfor
                                    @endif
                                </div>
                                <input type="hidden" name="product_data" class="product_data"
                                    value="{{ old('product_data') }}">
                                @if ($errors->has('product_data'))
                                    <span class="text-danger">Please Select Order Products.</span>
                                @endif
                                <div class="row justify-content-end mt-3">
                                    <div class="col-md-4">
                                        <h5 class="text-end">Total : <span class="text-danger fs-24"> &#x20B9; <span
                                                    class="g-total fs-24">
                                                    @if (old('amount'))
                                                        {{ old('amount') }}
                                                    @else
                                                        0
                                                    @endif
                                                </span></span></h5>
                                    </div>
                                </div>
                                <div>
                                    <button type="button" name="add" class="btn btn-primary mt-3 add_product">Add
                                        Product</button>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label for="Status" class="fs-6 fw-semibold mb-2">Add Lead As
                                            Order</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="order_lead"
                                                id="flexSwitchCheckChecked">
                                            <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 lead_datetime d-none">
                                        <label for="Lead" class="form-label">Lead Followup Date & Time</label>
                                        <input type="datetime-local" name="lead_datetime" class="form-control"
                                            value="" id="lead_datetime">
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
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-success">Call Detail</h6>
                                <div class="lead_call_detail" id="lead_call_detail"></div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <input type="hidden" name="amount" class="grand_total"
                                value="@if (old('amount')) {{ old('amount') }} @else 0 @endif">
                            <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>

                </div>
            </div>
            <input type="hidden" name="role_id" id="role_id" value="{{Auth()->user()->role_id??1}}">
        </div>
    </div>
@endsection
@section('page')
    <script>
        var role =$('#role_id').val();
        var disUrl = "{{ route('employee-get-subdistricts') }}";
        var villUrl = "{{ route('employee-get-villages') }}";
        var categoryUrl = "{{ route('employee-get-category') }}";
        var productUrlDetail = "{{ route('employee-get-product-list') }}";
        var productVariantUrlDetail = "{{ route('employee-get-product-variant-list') }}";
        var variantUrl = "{{ route('employee-get_variant') }}";
        var deleteImage = "{{ asset('public/assets/media/icons/delete.png') }}";
        var detailAjax = "{{ route('employee-add-lead-ajax') }}";
        if(role == 1){
            disUrl = "{{ route('get-subdistricts') }}";
            villUrl = "{{ route('get-villages') }}";
            categoryUrl = "{{ route('get-category') }}";
            productUrlDetail = "{{ route('get-product-list') }}";
            productVariantUrlDetail = "{{ route('get-product-variant-list') }}";
            variantUrl = "{{ route('get_variant') }}";
            detailAjax = "{{ route('admin-add-lead-ajax') }}";
        }
    </script>
    <script>
        $('#lead_datetime').val(moment().format('YYYY-MM-DDTHH:mm'));
    </script>
    <script src="{{ asset('public\assets\js\custom\employee\lead.js') }}?{{ time() }}"></script>
@endsection
