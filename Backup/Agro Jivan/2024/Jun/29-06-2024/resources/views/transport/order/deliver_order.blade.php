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
                            <div
                                class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" onkeyup="orderAjaxList(1)"
                                            placeholder="Search order" />
                                    </div>
                                </div>

                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end custom-responsive-div"
                                        data-kt-user-table-toolbar="base">
                                        <div id="invoice_button_div" class="d-none">
                                            <button type="button" class="btn btn-light-primary me-3"
                                                onclick="generateInvoiceOrder()">
                                                <i class="fas fa-file-invoice"></i> Generate Invoice
                                            </button>
                                        </div>
                                        <div class="parent-filter-menu">

                                            <button type="button" class="btn btn-light-primary me-3 order_filter_option">
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
                                                        <select
                                                            class="form-select form-select-solid fw-bold "onchange="getSubDistrictDetail()"
                                                            id="order_district" data-placeholder="Select option"
                                                            data-allow-clear="true" data-kt-user-table-filter="two-step"
                                                            data-hide-search="true">
                                                            <option value="">Select District</option>
                                                            @foreach ($orderDistricts as $distrcit)
                                                                @if (isset($distrcit->districtDetail))
                                                                    <option value="{{ $distrcit->district }}">
                                                                        {{ $distrcit->districtDetail->district_name }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-10">
                                                        <label class="form-label fs-6 fw-semibold">Sub District:</label>
                                                        <select class="form-select form-select-solid fw-bold"
                                                            id="order_sub_district" data-placeholder="Select option"
                                                            data-allow-clear="true" data-kt-user-table-filter="two-step"
                                                            data-hide-search="true">
                                                            <option value="">Select Sub District</option>
                                                            @foreach ($orderSubDistricts as $subDistricts)
                                                                @if (isset($subDistricts->subDistrictDetail))
                                                                    <option value="{{ $subDistricts->sub_district }}">
                                                                        {{ $subDistricts->subDistrictDetail->sub_district_name }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-10">
                                                        <label class="form-label fs-6 fw-semibold">User:</label>
                                                        <select class="form-select form-select-solid fw-bold" id="user_id"
                                                            data-placeholder="Select option" data-allow-clear="true"
                                                            data-kt-user-table-filter="two-step" data-hide-search="true">
                                                            <option value="">Select User</option>
                                                            @foreach ($userData as $user)
                                                                @if (isset($user->userDetail))
                                                                    <option value="{{ $user->created_by }}">
                                                                        {{ $user->userDetail->name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-10">
                                                        <label class="form-label fs-6 fw-semibold">Date:</label>
                                                        <input type="text" placeholder="Select Date"
                                                            class="form-control search_date" id="search_date"
                                                            name="search_date" value="">&nbsp;
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <button type="reset"
                                                            class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" onclick="resetOrderForm()"
                                                            data-kt-user-table-filter="reset">Reset</button>
                                                        <button type="submit"
                                                            class="btn btn-primary fw-semibold px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                                            onclick="orderAjaxList(1)">Apply</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_export_users">
                                            <i class="ki-outline ki-exit-up fs-2"></i> Export
                                        </button>

                                        <div class="d-flex align-items-center gap-2 gap-lg-3">
                                            @php $route = route('confirm-orders.create') @endphp
                                            @if (Auth()->user() == null)
                                                @php $route = route('login') @endphp
                                            @elseif(Auth()->user()->id == 1)
                                                @php $route = route('orders.create') @endphp
                                            @elseif(Auth()->user()->id == 9)
                                                @php $route = route('sale-orders.create') @endphp
                                            @endif
                                            <a href="{{ $route }}"
                                                class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                                                <i class="ki-outline ki-plus fs-2"></i> New Order
                                            </a>
                                        </div>

                                    </div>

                                    <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="fw-bold">Export Order</h2>

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                    <form id="" class="form" action="#">


                                                        <div class="fv-row mb-10">
                                                            <label class="required fs-6 fw-semibold form-label mb-2">Select
                                                                Export Format:</label>
                                                            <select name="format" data-placeholder="Select a format"
                                                                id="export_format" data-hide-search="true"
                                                                class="form-select form-select-solid fw-bold">
                                                                <option value="">Select Format</option>
                                                                <option value="excel">Excel</option>
                                                                <option value="pdf">PDF</option>
                                                                <option value="csv">CSV</option>
                                                            </select>
                                                            <span id="export_format_error" class="text-danger"></span>
                                                        </div>
                                                        <div class="text-center">
                                                            <button type="reset" class="btn btn-light me-3"
                                                                data-bs-dismiss="modal">
                                                                Discard
                                                            </button>

                                                            <button type="button" class="btn btn-primary"
                                                                onclick="exportCSVTransport()">
                                                                Submit
                                                            </button>
                                                        </div>
                                                    </form>
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
        var ajaxList = "{{ route('deliver-order-list') }}";
        var exportUrl = "{{ route('export-delivered-order') }}";
        var generateBulkInvoice = "{{ route('generate-bulk-invoice') }}";
        var token = "{{ csrf_token() }}"
        var subDistrictData = "{{ route('get-sub-district-order') }}"
        var type = "deliver"
        var orderIdArray = [];

        function checkCheckbox() {
            var checkBox = document.getElementById("all_order_id");
            if (checkBox.checked) {
                $(".order_id").each(function() {
                    orderIdArray.push($(this).val());
                    $(this).prop("checked", true);
                });
                $('#invoice_button_div').removeClass('d-none')
            } else {
                $(".order_id").each(function() {
                    $(this).prop("checked", false);
                });
                orderIdArray = [];
                $('#invoice_button_div').addClass('d-none')
            }
        }

        function singleCheckbox() {
            orderIdArray = [];
            $('.order_id').each(function(e) {
                var checked = $(this).prop('checked');
                if (checked) {
                    orderIdArray.push($(this).val());
                    $(this).prop("checked", true);
                    $('#invoice_button_div').removeClass('d-none')
                } else {
                    orderIdArray.splice($(this).val(), 1)
                    $(this).prop("checked", false);
                    $('#invoice_button_div').removeClass('d-none')
                }
            })
            if (orderIdArray.length == 0) {
                $('#invoice_button_div').addClass('d-none')
            }
        }

        function generateInvoiceOrder() {
            window.open(generateBulkInvoice + '?orderId=' + orderIdArray, '_blank');
        }

        function exportCSVTransport() {
            var format = $('#export_format').val();
            $('#export_format_error').html('');
            if (format == "") {
                $('#export_format_error').html('Please Select Export Format.')
                return false;
            }
            window.open(exportUrl + '?format=' + format + '&orderId=' + orderIdArray, '_blank');
        }
    </script>
    <script src="{{ asset('public\assets\js\custom\admin\common_order.js') }}?{{ time() }}"></script>
    <script src="{{ asset('public/assets/js/custom/apps/user-management/users/list/export-users.js') }}"></script>
    <script src="{{ asset('public/assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
    <script src="{{ asset('public/assets/js/custom/utilities/modals/create-campaign.js') }}"></script>
    <script src="{{ asset('public/assets/js/custom/utilities/modals/users-search.js') }}"></script>
@endsection
