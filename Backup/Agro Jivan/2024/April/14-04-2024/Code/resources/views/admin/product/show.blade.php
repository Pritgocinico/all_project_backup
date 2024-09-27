@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid">
                <div id="kt_app_content_container" class="app-container  container-fluid">
                    <div class="d-flex flex-column flex-xl-row">
                        <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                            <div class="card mb-5 mb-xl-8">
                                <div class="card-body pt-15">
                                    <div class="d-flex flex-center flex-column mb-5">
                                        <div class="symbol symbol-100px mb-7">
                                            <img src="{{ asset('public/assets/upload/' . $product->product_image) }}"
                                                alt="image" class="product-view-image" />
                                        </div>
                                        <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">
                                            {{ $product->product_name }} </a>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="flex-lg-row-fluid ms-lg-15">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="kt_customer_view_overview_tab" role="tabpanel">
                                    <div class="card pt-4 mb-6 mb-xl-9">
                                        <div class="card-header border-0">
                                            <div class="card-title">
                                                <h2>Variant Detail</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0 pb-5 table-responsive">
                                            <table class="table align-middle table-row-dashed gy-5"
                                                id="kt_table_customers_payment">
                                                <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                                    <tr class="text-start text-muted text-uppercase gs-0">
                                                        <th class="min-w-100px">SKU Name</th>
                                                        <th>Capacity</th>
                                                        <th>Price</th>
                                                        <th class="min-w-100px">Price Without Tax</th>
                                                        <th class="min-w-100px pe-4">Stock</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fs-6 fw-semibold text-gray-600">
                                                    @forelse ($product->productVariantDetail as $variant)
                                                        <tr>
                                                            <td>{{ $variant->sku_name }}</td>
                                                            <td>{{ $variant->capacity }}</td>
                                                            <td>{{ $variant->price }}</td>
                                                            <td>{{ $variant->price_without_tax }}</td>
                                                            <td>{{ $variant->stock }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td class="text-center" colspan="5">No Data Available</td>
                                                        </tr>
                                                    @endforelse

                                                </tbody>
                                            </table>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="kt_customer_view_overview_tab" role="tabpanel">
                                    <div class="card pt-4 mb-6 mb-xl-9">
                                        <div class="card-header border-0">
                                            <div class="card-title">
                                                <h2>Product Detail</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0 pb-5 table-responsive">
                                            <table class="table align-middle table-row-dashed gy-5"
                                                id="kt_table_customers_payment">
                                                <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                                    <tr class="text-start text-muted text-uppercase gs-0">
                                                        <th class="min-w-85px">SKU Name</th>
                                                        <th class="min-w-85px">Category Detail</th>
                                                        <th class="min-w-85px">Product Status</th>
                                                        <th class="min-w-50px">SGST</th>
                                                        <th class="min-w-50px">CGST</th>
                                                        <th class="min-w-85px">Product Type</th>
                                                        <th class="min-w-100px">Created At</th>
                                                        <th class="min-w-100px">Description</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fs-6 fw-semibold text-gray-600">
                                                        <tr>
                                                            <td>{{ $product->sku_name }}</td>
                                                            <td>{{ isset($product->categoryDetail)?$product->categoryDetail->name: "-" }}</td>
                                                            <td>@php
                                                                $text = 'Active';
                                                                $class = 'success';
                                                            @endphp
                                                            @if ($product->status == 0)
                                                                @php
                                                                    $text = 'Inactive';
                                                                    $class = 'danger';
                                                                @endphp
                                                            @endif
                                                            <div class="badge badge-light-{{ $class }} fw-bold">
                                                                {{ $text }}</div></td>
                                                            <td>{{ $product->s_gst }}%</td>
                                                            <td>{{ $product->c_gst }}%</td>
                                                            <td>{{ str_replace('_', ' ', $product->product_type) }}</td>
                                                            <td>{{ Utility::convertDmyWith12HourFormat($product->created_at) }}</td>
                                                            <td>{{ $product->description }}</td>
                                                        </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
