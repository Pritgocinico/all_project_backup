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
                                        <div class="symbol symbol-circle mb-7">
                                            <img src="{{ asset('public/assets/upload/' . $product->product_image) }}"
                                                alt="image" class="product-view-image" />
                                        </div>
                                        <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">
                                            {{ $product->product_name }} </a>
                                    </div>
                                    <div class="d-flex flex-stack fs-4 py-3">
                                        <div class="fw-bold rotate collapsible" data-bs-toggle="collapse"
                                            href="#kt_customer_view_details" role="button" aria-expanded="false"
                                            aria-controls="kt_customer_view_details">
                                            Details
                                            <span class="ms-2 rotate-180">
                                                <i class="ki-outline ki-down fs-3"></i> </span>
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-3"></div>
                                    <div id="kt_customer_view_details" class="collapse show">
                                        <div class="py-5 fs-6">
                                            <div class="fw-bold mt-5">SKU Name</div>
                                            <div class="text-gray-600">
                                                {{ $product->sku_name }}
                                            </div>
                                            <div class="fw-bold mt-5">Category Detail</div>
                                            <div class="text-gray-600">
                                                {{ isset($product->categoryDetail) ? $product->categoryDetail->name : '-' }}
                                            </div>
                                            <div class="fw-bold mt-5">Product Status</div>
                                            <div class="text-gray-600">
                                                @php
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
                                                    {{ $text }}</div>
                                                </td>
                                            </div>
                                            <div class="fw-bold mt-5">Created At</div>
                                            <div class="text-gray-600">
                                                {{ Utility::convertDmyWith12HourFormat($product->created_at) }}</div>
                                        </div>
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
                                                            <td>&#x20B9; {{ $variant->price }}</td>
                                                            <td>&#x20B9; {{ $variant->price_without_tax }}</td>
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
                                                <h2>Product Descirpiton</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0 pb-5">
                                            {{ $product->description }}
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
