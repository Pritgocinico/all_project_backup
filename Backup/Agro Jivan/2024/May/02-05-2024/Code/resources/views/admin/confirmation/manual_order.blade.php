@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">

            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-fluid ">
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
                                        @if (Auth()->user() !== null && (Auth()->user()->role_id == '1' || Auth()->user()->role_id == '4'))
                                            <button type="button" class="btn btn-light-primary me-3"
                                                onclick="showPendingOrderItem()">
                                                <i class="fa fa-eye "></i> View Item
                                            </button>
                                        @endif
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
                                                        <select class="form-select form-select-solid fw-bold"
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
                                                    <h2 class="fw-bold">Export Orders</h2>

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
                                                                onclick="exportCSV1()">
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
    <div class="modal fade" id="batch_product_detail_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="batch_id_detail">Pending Order Item List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="batch_id" id="batch_id">
                    <div class="modal-body">
                        <div id="" class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                                <thead>
                                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-125px">Product</th>
                                        <th class="min-w-125px">Order</th>
                                        <th class="min-w-125px">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-semibold" id="batch_product_detail">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="generatePDF()">Download</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="confirm_role_id" name="confirm_role_id"
        value="{{ Auth()->user() !== null ? Auth()->user()->role_id : '' }}">
    </body>
@endsection

@section('page')
    <script>
        var ajaxList = "{{ route('manual-orders-ajax') }}";
        var role = $('#confirm_role_id').val();
        var exportUrl = "{{ route('manual-orders-export') }}";
        var deleteURL = "{{ route('confirm-department-manual-orders-cancel') }}";
        var confirmURL = "{{ route('confirm-department-manual-orders-confirm') }}";
        var pendingItemDetail = "{{ route('confirm-pending-order-item') }}";
        var pdfUrl = "{{ route('confirm-generate-pending-order-item') }}"
        if (role == 1) {
            deleteURL = "{{ route('manual-orders-cancel') }}";
            confirmURL = "{{ route('manual-orders-confirm') }}";
            pendingItemDetail = "{{ route('admin-pending-order-item') }}";
            pdfUrl = "{{ route('admin-generate-pending-order-item') }}"
        }
        var token = "{{ csrf_token() }}";
        var token1 = "{{ csrf_token() }}";
        var type = "manual";

        function exportCSV1() {
            var format = $('#export_format').val();
            var search = $('#search_data').val();
            $('#export_format_error').html('');
            if (format == "") {
                $('#export_format_error').html('Please Select Export Format.')
                return false;
            }
            var status = $('#order_status').val();
            var driverId = $('#order_id').val();
            var date = $('#search_date').val();
            var order_district = $('#order_district').val();
            var order_sub_district = $('#order_sub_district').val();
            var userId = $('#user_id').val();
            window.open("{{ route('manual-orders-export') }}" + '?format=' + format + '&search=' + search + '&type=' +
                type+"&status="+status+"&driverId="+driverId+"&date="+date+"&order_district="+order_district+"&order_sub_district="+order_sub_district+"&userId="+userId, '_blank');
        }

        function showPendingOrderItem() {
            var search = $('#search_data').val();
            var order_district = $('#order_district').val();
            var user_id = $('#user_id').val();
            var date = $('#search_date').val();
            $.ajax({
                method: "get",
                url: pendingItemDetail,
                data: {
                    search: search,
                    order_district: order_district,
                    userId: user_id,
                    date: date,
                },
                success: function(res) {
                    var html = "";
                    var batchId = res.batch_id
                    $('#batch_id_detail').html(res.batch_id);

                    $('#batch_id').val(res.batch_id);
                    $.each(res, function(i, v) {
                        html += `<tr >
                                <td><b>` + v.variant_name + `:</b></td>
                                <td>` + v.total_order + `</td>
                                <td>` + v.quantity + `</td>
                                </tr>`;
                    })
                    $('#batch_product_detail').html('')
                    $('#batch_product_detail').html(html)
                    $('#batch_product_detail_modal').modal('show')
                },
            })
        }

        function generatePDF() {
            var search = $('#search_data').val();
            var order_district = $('#order_district').val();
            var user_id = $('#user_id').val();
            var date = $('#search_date').val();
            window.open(pdfUrl + '?search=' + search + "&order_district=" + order_district + "&userId=" + user_id +
                "&date=" + date, '_blank');
        }
    </script>
    <script src="{{ asset('public\assets\js\custom\admin\common_order.js') }}?{{ time() }}"></script>
    <script src="{{ asset('public/assets/js/custom/apps/user-management/users/list/export-users.js') }}"></script>
    <script src="{{ asset('public/assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
    <script src="{{ asset('public/assets/js/custom/utilities/modals/create-campaign.js') }}"></script>
    <script src="{{ asset('public/assets/js/custom/utilities/modals/users-search.js') }}"></script>
@endsection
