@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-fluid ">
                    <!--begin::Stats-->
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                            <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" onkeyup="employeeOrderAjaxList(1)"
                                            placeholder="Search order" />
                                    </div>
                                </div>
                        
                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end custom-responsive-div" data-kt-user-table-toolbar="base">
                                        <div class="parent-filter-menu">
                                            <button type="button" class="btn btn-light-primary me-3 order_filter_option"
                                                id="search_main_menu">
                                                <i class="ki-outline ki-filter fs-2"></i> Filter
                                            </button>
                                            <div class="menu filter-menu w-300px w-md-325px" data-kt-menu="true">
                                                <div class="px-7 py-5">
                                                    <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                                                </div>
                                                <div class="separator border-gray-200"></div>
                                                <div class="px-7 py-5" data-kt-user-table-filter="form">
                                                    <div class="mb-10">
                                                        <label class="form-label fs-6 fw-semibold">District:</label>
                                                        <select class="form-select form-select-solid fw-bold"
                                                            id="order_district" data-placeholder="Select option"
                                                            data-allow-clear="true" data-kt-user-table-filter="two-step"
                                                            data-hide-search="true">
                                                            <option value="">Select District</option>
                                                            @foreach ($orderDistricts as $distrcit)
                                                                @if(isset($distrcit->districtDetail))
                                                                    <option value="{{$distrcit->district}}">{{$distrcit->districtDetail->district_name}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                   
                                                    @if($type == "")
                                                    <div class="mb-10">
                                                        <label class="form-label fs-6 fw-semibold">Status:</label>
                                                        <select class="form-select form-select-solid fw-bold"
                                                        id="order_status" data-placeholder="Select option"
                                                        data-allow-clear="true" data-kt-user-table-filter="two-step"
                                                        data-hide-search="true">
                                                            <option value="">Select Status</option>
                                                            <option value="1">Pending Order</option>
                                                            <option value="2">Confirmed </option>
                                                            <option value="3">On Delivery </option>
                                                            <option value="4">Cancel Order </option>
                                                            <option value="5">Returned </option>
                                                        </select>
                                                    </div>
                                                @endif
                                                <div class="mb-10">
                                                    <label class="form-label fs-6 fw-semibold">Date:</label>
                                                    <input type="text" name="order_date" id="order_date" class="form-control form-select-solid fw-bold search_order_date" max="{{date('Y-m-d')}}" placeholder="Select Date" onchange="employeeOrderAjaxList(1)">
                                                </div>

                                                    <div class="d-flex justify-content-end">
                                                        <button type="reset"
                                                            class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" onclick="resetForm()"
                                                            data-kt-user-table-filter="reset">Reset</button>
                                                        <button type="button"
                                                            class="btn btn-primary fw-semibold px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                                            onclick="employeeOrderAjaxList(1)">Apply</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-4 table-responsive" id="order_table_ajax"></div>
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

    </body>
@endsection
@section('page')
    <script>
        var ajaxList = "{{ route('delivery-order-ajax') }}";
        var exportUrl = "{{ route('orders-export') }}";
        var updateStatus = "{{route('order-status-update')}}"
        $(document).ready(function(e) {
            employeeOrderAjaxList(1)
        });
        function resetForm(){
            $('#search_data').val('')
            $('#order_district').val('')
            $('#order_date').val('')
            employeeOrderAjaxList(1)
        }
        function employeeOrderAjaxList(page) {
            var search = $('#search_data').val();
            var district = $('#order_district').val();
            var date = $('#order_date').val();
            $.ajax({
                method: 'get',
                url: ajaxList,
                data: {
                    page: page,
                    district: district,
                    date: date,
                    search: search,
                },
                success: function(res) {
                    $('#order_table_ajax').html('')
                    $('#order_table_ajax').html(res)
                    $('[data-bs-toggle="tooltip"]').tooltip()
                },
            })
        }
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            employeeOrderAjaxList(page);
        });

        function OrderStatusUpdate(id) {
            new swal({
                title: 'Are you sure change order status?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes Complete it!',
                cancelButtonText: 'Close',
            }).then(function(isConfirm) {
                if (isConfirm.isConfirmed) {
                    $.ajax({
                        method: "get",
                        url: updateStatus,
                        data: {
                            status: '6',
                            id: id,
                        },
                        success: function(res) {
                            toastr.success(res.message);
                            employeeOrderAjaxList(1);
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message);
                        }
                    })
                }
            });
        }

        function cancelOrderStatus(id) {
            new swal({
                title: 'Are you sure change status Cancel this Leave?',
                text: "Enter Reason for Rejection",
                showCancelButton: true,
                input: 'text',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes Cancel it!',
                cancelButtonText: 'Close',
            }).then(function(isConfirm) {
                if (isConfirm.isConfirmed) {
                    $.ajax({
                        method: "get",
                        url: updateStatus,
                        data: {
                            status: '5',
                            id: id,
                            reject_reason: isConfirm.value
                        },
                        success: function(res) {
                            toastr.success(res.message);
                            employeeOrderAjaxList(1);
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message);
                        }
                    })
                }
            });
        }
    </script>

    <script src="{{ asset('public/assets/js/custom/apps/user-management/users/list/export-users.js') }}"></script>
    <script src="{{ asset('public/assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
    <script src="{{ asset('public/assets/js/custom/utilities/modals/create-campaign.js') }}"></script>
    <script src="{{ asset('public/assets/js/custom/utilities/modals/users-search.js') }}"></script>
@endsection
