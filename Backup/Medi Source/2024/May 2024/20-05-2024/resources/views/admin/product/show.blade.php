@extends('admin.layouts.app')

@section('content')
<div class="content-page">
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <h2 class="mb-0">View Product Details</h2>
                    <a href="{{ route('admin.product-details.index') }}" class="btn btn-secondary ms-auto">Go Back</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">SKU</label>
                            <p>{{ $product->sku }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <p>{{ $product->productname }}</p>
                        </div>

                        <!-- Add more fields as needed, following the same pattern -->
                        <!-- Example: -->
                        <div class="mb-3">
                            <label class="form-label">Inactive Ingredients</label>
                            <p>{{ $product->inactive_ingredients }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unit Size/Type</label>
                            <p>{{ $product->unit_size_type }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Package Size</label>
                            <p>{{ $product->package_size }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Product Code</label>
                            <p>{{ $product->product_code }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NDC</label>
                            <p>{{ $product->ndc }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Storage</label>
                            <p>{{ $product->storage }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tags</label>
                            <p>{{ $product->tags }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Vial weight</label>
                            <p>{{ $product->vial_weight }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Medical Necessity</label>
                            <p>{{ $product->medical_necessity }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">medical_necessity</label>
                            <p>{{ $product->medical_necessity }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Preservative Free</label>
                            <p>{{ $product->preservative_free }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Controlled States</label>
                            <p>{{ $product->controlled_state }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Product Stock</label>
                            <p>{{ $product->stock }}</p>
                        </div>
                    </div>
                </div>

                <!-- Display product packages -->
                <h3 class="mb-3">Product Package Price</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Variant Name</th>
                            <th>Price Per Vial</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product_packages as $package)
                        <tr>
                            <td>{{ $package->varient_name }}</td>
                            <td>${{ $package->vial_price }}</td>
                            <td>{{ $package->vial_quantity }}</td>
                            <td>${{ $package->vial_total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="col-md-12">
                    <div class="Product">
                        @if (!empty($accordionItems))
                        <h3 class="mt-3">Product Information</h3>
                        <div class="accordion border-0 accordion-flush" id="accordionFlushExample">
                            <ul>
                                @foreach ($accordionItems as $item)
                                    @if($item['title'] !== "")
                                        <li>
                                            <div class="product-information-list">
                                                <h5>{{ $item['title'] }}</h5>
                                                <p>{{ $item['content'] }}</p>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection