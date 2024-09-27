@extends('layouts.main_layout')
@section('section')
<div class="app-main flex-column flex-row-fluid " id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar  pt-6 pb-2 ">
            <div id="kt_app_toolbar_container" class="container-fluid d-flex align-items-stretch ">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex justify-content-center text-gray-900 fw-bold fs-3 m-0 ">
                            {{ $order->order_id }}
                        </h1>
                    </div>
                    <div class="page-title d-flex justify-content-center gap-1 me-3">
                        <div class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0 ms-3">
                            @if(Auth()->user() !== null && Auth()->user()->role_id == '1')
                            @if($order->order_status==1)
                            <button class="btn btn-warning text-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Pending Order
                            </button>
                            @elseif($order->order_status==2)
                            <button class="btn btn-success text-white btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Confirmed
                            </button>
                            @elseif($order->order_status == 3)
                            <button class="btn btn-info text-white btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                On Delivery
                            </button>
                            @elseif($order->order_status == 4)
                            <button class="btn btn-danger text-white btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Cancelled
                            </button>
                            @elseif($order->order_status == 5)
                            <button class="btn btn-danger text-white btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Returned
                            </button>
                            @elseif($order->order_status == 6)
                            <button class="btn btn-danger text-white btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Delivered
                            </button>
                            @endif

                            <ul class="dropdown-menu task-status">
                                <li><a class="dropdown-item status @if($order->order_status == 1) hide_detail @endif" href="#" data-status="1" data-task="{{ $order->id }}">Pending Order</a></li>
                                <li><a class="dropdown-item status @if($order->order_status == 2) hide_detail @endif" href="#" data-status="2" data-task="{{ $order->id }}">Confirmed</a></li>
                                <li><a class="dropdown-item status @if($order->order_status == 3) hide_detail @endif" href="#" data-status="3" data-task="{{ $order->id }}">On Delivery</a></li>
                                <li><a class="dropdown-item status @if($order->order_status == 4) hide_detail @endif" href="#" data-status="4" data-task="{{ $order->id }}">Cancel Order</a></li>
                                <li><a class="dropdown-item status @if($order->order_status == 5) hide_detail @endif" href="#" data-status="5" data-task="{{ $order->id }}">Returned</a></li>
                                <li><a class="dropdown-item status @if($order->order_status == 6) hide_detail @endif" href="#" data-status="6" data-task="{{ $order->id }}">Delivered</a></li>
                            </ul>
                            @else
                            @if ($order->order_status == 1)
                            <button class="btn btn-warning text-dark btn-sm " type="button" aria-expanded="false">
                                Pending Order
                            </button>
                            @elseif($order->order_status == 2)
                            <button class="btn btn-success text-white btn-sm">
                                Confirmed
                            </button>
                            @elseif($order->order_status == 3)
                            <button class="btn btn-info text-white btn-sm " type="button" aria-expanded="false">
                                On Delivery
                            </button>
                            @elseif($order->order_status == 4)
                            <button class="btn btn-danger text-white btn-sm" type="button">
                                Cancelled
                            </button>
                            @elseif($order->order_status == '5')
                            <button class="btn btn-danger text-white btn-sm" type="button">
                                Returned
                            </button>
                            @elseif($order->order_status == '6')
                            <button class="btn btn-success text-white btn-sm" type="button">
                                Delivered
                            </button>
                            @endif
                            @endif
                        </div>
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
                                        {{ $order->customer_name }} - {{ $order->phoneno }} </span>
                                </div>
                                <div class="d-flex flex-stack fs-4 py-3">
                                    <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_customer_view_details" role="button" aria-expanded="false" aria-controls="kt_customer_view_details">
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
                                            {{ $order->address }},{{ $order->villageDetail->village_name }},{{ $order->subDistrictDetail->sub_district_name }}<br />
                                            {{ $order->districtDetail->district_name }},{{ $order->stateDetail->name }}
                                            - {{ $order->pincode }}
                                        </div>
                                        <div class="fw-bold mt-5">Amount</div>
                                        <div class="text-gray-600">&#x20B9; {{ number_format($order->amount, 2) }}
                                        </div>
                                        <div class="fw-bold mt-5">Expected Delivery Date</div>
                                        <div class="text-gray-600">
                                            {{ Utility::convertMDY($order->excepted_delievery_date) }}
                                        </div>
                                        <div class="fw-bold mt-5">Created At</div>
                                        <div class="text-gray-600">
                                            {{ Utility::convertDmyWith12HourFormat($order->created_at) }}
                                        </div>
                                        <div class="fw-bold mt-5">Created By</div>
                                        <div class="text-gray-600">{{ isset($order->userDetail)?$order->userDetail->name:"-" }}</div>
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
                                            <h2>Order Item Detail</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5 table-responsive">
                                        <table class="table align-middle table-row-dashed gy-5" id="kt_table_customers_payment">
                                            <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                                <tr class="text-start text-muted text-uppercase gs-0">
                                                    <th>Product Name</th>
                                                    <th>Category Name</th>
                                                    <th>Variant Name</th>
                                                    <th>Price</th>
                                                    <th>Quantity</th>
                                                    <th>Total Price</th>
                                                    <th>Discount Code</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fs-6 fw-semibold text-gray-600">
                                                @forelse ($order->orderItem as $item)
                                                <tr>
                                                    <td>{{ isset($item->productDetail)?$item->productDetail->product_name :"" }}</td>
                                                    <td>{{ isset($item->categoryDetail)?$item->categoryDetail->name:"" }}</td>
                                                    <td>{{ isset($item->varientDetail)?$item->varientDetail->sku_name:"" }}</td>
                                                    <td>&#x20B9; {{ number_format($item->price, 2) }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>&#x20B9; {{ number_format($item->amount, 2) }}</td>
                                                    <td>{{ $item->discount_code }}</td>
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
@section('page')
<script>
    $('.task-status li a').on('click', function() {
        var status = $(this).attr('data-status');
        var order_id = $(this).attr('data-task');
        $('#loader').removeClass('hidden');
        new swal({
            title: 'Are you sure Change this Order Status?',
            text: "You won't be able to revert this!",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes Change it!'
        }).then(function(isConfirm) {
            if (isConfirm.isConfirmed) {
                $.ajax({
                    method: "post",
                    url: "{{ route('update-order-status') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'status': status,
                        'order': order_id,
                    },
                    success: function(res) {
                        Swal.fire({
                            title: 'Status Changed!',
                            text: res.message,
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload(true);
                            }
                        });
                    },
                    error: function(error) {
                        toastr.error(error.responseJSON.message);
                    }
                })
            }
        });
    });
</script>
@endsection