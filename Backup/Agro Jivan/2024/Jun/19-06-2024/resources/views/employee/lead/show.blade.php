@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_toolbar" class="app-toolbar  pt-6 pb-2 ">
                <div id="kt_app_toolbar_container" class="container-fluid d-flex align-items-stretch ">
                    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                        <div class="page-title d-flex justify-content-center gap-1 me-3">
                            <h1 class="page-heading d-flex justify-content-center text-gray-900 fw-bold fs-3 m-0 ">
                                {{ $lead->lead_id }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <div id="kt_app_content" class="app-content  flex-column-fluid">
                <div id="kt_app_content_container" class="app-container  container-fluid">
                    <div class="d-flex flex-column flex-xl-row">
                        <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                            <div class="card mb-5 mb-xl-8">
                                <div class="card-body pt-15">
                                    <div class="d-flex flex-center flex-column mb-5">
                                        <span href="#" class="fs-3 text-gray-800 text-hover-gray fw-bold mb-1">
                                            {{ $lead->customer_name }} - {{ $lead->phone_no }} </span>
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
                                            <div class="fw-bold mt-5">Address</div>
                                            <div class="text-gray-600">
                                                {{ $lead->address }},{{ $lead->villageDetail->village_name }},{{ $lead->subDistrictDetail->sub_district_name }}<br />
                                                {{ $lead->districtDetail->district_name }},{{ $lead->stateDetail->name }}
                                                - {{ $lead->pin_code }}
                                            </div>
                                            <div class="fw-bold mt-5">Amount</div>
                                            <div class="text-gray-600">&#x20B9; {{ number_format($lead->amount, 2) }}
                                            </div>
                                            <div class="fw-bold mt-5">Expected Delivery Date</div>
                                            <div class="text-gray-600">
                                                {{ Utility::convertDmyWith12HourFormat($lead->excepted_delievery_date) }}
                                            </div>
                                            <div class="fw-bold mt-5">Created At</div>
                                            <div class="text-gray-600">
                                                {{ Utility::convertDmyWith12HourFormat($lead->created_at) }}</div>
                                            <div class="fw-bold mt-5">Created By</div>
                                            <div class="text-gray-600">{{ $lead->userDetail->name }}</div>
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
                                                <h2>Lead Item Detail</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0 pb-5 table-responsive">
                                            <table class="table align-middle table-row-dashed gy-5"
                                                id="kt_table_customers_payment">
                                                <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                                    <tr class="text-start text-muted text-uppercase gs-0">
                                                        <th>Product Name</th>
                                                        <th>Category Name</th>
                                                        <th>Variant Name</th>
                                                        <th>Price</th>
                                                        <th>Quantity</th>
                                                        <th>Total Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fs-6 fw-semibold text-gray-600">
                                                    @forelse ($lead->leadDetail as $item)
                                                    <tr>
                                                            <td>{{ $item->productDetail->product_name }}</td>
                                                            <td>{{ $item->categoryDetail->name }}</td>
                                                            <td>{{ $item->variantDetail->sku_name }}</td>
                                                            <td>&#x20B9; {{ number_format($item->price, 2) }}</td>
                                                            <td>{{ $item->quantity }}</td>
                                                            <td>{{ number_format($item->price * $item->quantity , 2) }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td class="text-center" colspan="6">No Data Available</td>
                                                        </tr>
                                                    @endforelse
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
