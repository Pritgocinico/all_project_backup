@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_toolbar" class="app-toolbar  pt-6 pb-2 ">
                <div id="kt_app_toolbar_container" class="container-fluid d-flex align-items-stretch ">
                    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                            <h1
                                class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                                Manual Confirm Order
                            </h1>
                        </div>

                    </div>
                </div>
            </div>
            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-fluid ">
                    <!--begin::Stats-->
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                            <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" onkeyup="allOrderAjax(1)"
                                            placeholder="Search order" />
                                    </div>
                                </div>

                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end custom-responsive-div" data-kt-user-table-toolbar="base">
                                        <button type="button" class="btn btn-light-primary me-3"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <i class="ki-outline ki-filter fs-2"></i> Filter
                                        </button>
                                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                                            <div class="px-7 py-5">
                                                <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                                            </div>
                                            <div class="separator border-gray-200"></div>
                                            <div class="px-7 py-5" data-kt-user-table-filter="form">


                                                <div class="mb-10">
                                                    <label class="form-label fs-6 fw-semibold">Status:</label>
                                                    <select class="form-select form-select-solid fw-bold" id="order_status"
                                                        data-placeholder="Select option" data-allow-clear="true"
                                                        data-kt-user-table-filter="two-step" data-hide-search="true">
                                                        <option value="">Select Status</option>
                                                        <option value="1">Pending Order</option>
                                                        <option value="2">Confirmed </option>
                                                        <option value="3">On Delivery </option>
                                                        <option value="4">Cancel Order </option>
                                                        <option value="5">Returned </option>
                                                    </select>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button type="reset"
                                                        class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                                        data-kt-menu-dismiss="true"
                                                        data-kt-user-table-filter="reset">Reset</button>
                                                    <button type="submit" class="btn btn-primary fw-semibold px-6"
                                                        data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                                        onclick="allOrderAjax(1)">Apply</button>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-4 table-responsive" id="manual_order_table_ajax">
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
    </div>

    </body>
@endsection

@section('page')
    <script>
        var deleteURL = "{{ route('confirm-department-manual-orders-cancel') }}";
        var confirmURL = "{{ route('confirm-department-manual-orders-confirm') }}";
        var token = "{{csrf_token()}}"
        $(document).ready(function(e) {
            allOrderAjax(1);
        })

        function allOrderAjax(page) {
            $.ajax({
                method: 'get',
                url: "{{ route('pending-order-ajax') }}",
                data: {
                    page: page,
                    status: $('#order_status').val(),
                    search: $('#search_data').val(),
                },
                success: function(res) {
                    $('#manual_order_table_ajax').html('');
                    $('#manual_order_table_ajax').html(res);
                    $('[data-bs-toggle="tooltip"]').tooltip()
                }
            })
        }
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            allOrderAjax(page);
        });

        function confirmOrder(id) {
            var url = confirmURL
            new swal({
                title: 'Are you sure confirm this order?',
                showCancelButton: true,
                confirmButtonColor: '#17c653',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes Confirm it!',
                cancelButtonText: 'Close',
            }).then(function(isConfirm) {
                if (isConfirm.isConfirmed) {
                    $.ajax({
                        method: "POST",
                        url: url,
                        data: {
                            _token: token,
                            id: id,
                        },
                        success: function(res) {
                            toastr.success(res.message);
                            allOrderAjax(1);
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message);
                        }
                    })
                }
            });
        }

        function cancelOrder(id) {
            var url = deleteURL
            new swal({
                title: 'Are you sure cancel this order?',
                text: "Enter Reason for cancellation",
                showCancelButton: true,
                input: 'text',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes Cancel it!',
                cancelButtonText: 'Close',
                customClass: {
                    validationMessage: 'my-validation-message',
                },
                preConfirm: (value) => {
                    if (!value) {
                        Swal.showValidationMessage('Reason for cancellation is required')
                    }
                },
            }).then(function(isConfirm) {
                if (isConfirm.isConfirmed) {
                    $.ajax({
                        method: "POST",
                        url: url,
                        data: {
                            _token: token,
                            id: id,
                            reason: isConfirm.value
                        },
                        success: function(res) {
                            toastr.success(res.message);
                            allOrderAjax(1);
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message);
                        }
                    })
                }
            });
        }
    </script>
@endsection
