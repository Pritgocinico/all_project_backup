@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                <div id="kt_app_content_container" class="app-container  container-fluid ">
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                            <div
                                class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" onkeyup="orderReportAjax(1)"
                                            placeholder="Search Order" />
                                    </div>
                                </div>
                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end custom-responsive-div"
                                        data-kt-user-table-toolbar="base">

                                        <div class="parent-filter-menu">
                                            <button type="button" class="btn btn-light-primary me-3 order_filter_option">
                                                <i class="ki-outline ki-filter fs-2"></i> Filter
                                            </button>
                                            <div class="menu filter-menu w-300px w-md-325px" data-kt-menu="true">
                                                <div class="px-7 py-5">
                                                    <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                                                </div>
                                                <div class="separator border-gray-200"></div>
                                                <div class="px-7 py-5" data-kt-user-table-filter="form" id="filter_menu_option_form">
                                                    <div class="mb-5">
                                                        <label class="fs-6 fw-semibold mb-2">Status</label>
                                                        <select class="form-select" name="order_status" id="order_status">
                                                            <option value="">Select Status</option>
                                                            <option value="1">Pending</option>
                                                            <option value="2">Confirmed</option>
                                                            <option value="3">On Delivery</option>
                                                            <option value="4">Cancelled</option>
                                                            <option value="5">Returned</option>
                                                            <option value="6">Delivered</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-5">
                                                        <label class="fs-6 fw-semibold mb-2">User</label>
                                                        <select class="form-select" name="user_id" id="user_id">
                                                            <option value="">Select User</option>
                                                            @foreach ($userData as $user)
                                                                @if (isset($user->userDetail))
                                                                    <option value="{{ $user->created_by }}">
                                                                        {{ $user->userDetail->name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-5">
                                                        <label class="fs-6 fw-semibold mb-2">District</label>
                                                        <select class="form-select" name="order_district"
                                                            id="order_district" onchange="getSubDistrictDetail()">
                                                            <option value="">Select District</option>
                                                            @foreach ($orderDistricts as $districts)
                                                                @if (isset($districts->districtDetail))
                                                                    <option value="{{ $districts->district }}">
                                                                        {{ $districts->districtDetail->district_name }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-5">
                                                        <label class="fs-6 fw-semibold mb-2">Sub District</label>
                                                        <select class="form-select" name="order_sub_district"
                                                            id="order_sub_district">
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
                                                    <div class="mb-5">
                                                        <label class="fs-6 fw-semibold mb-2">Date</label>
                                                        <input type="text" placeholder="Select Date"
                                                            class="form-control search_date" id="search_date"
                                                            name="search_date" value="">&nbsp;
                                                    </div>
                                                    <div class="d-flex justify-content-end">
                                                        <button type="reset"
                                                            class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" onclick="resetForm()"
                                                            data-kt-user-table-filter="reset">Reset</button>
                                                        <button type="submit"
                                                            class="btn btn-primary fw-semibold px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                                            onclick="orderReportAjax(1)">Apply</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_export_users">
                                            <i class="ki-outline ki-exit-up fs-2"></i> Export
                                        </button>
                                    </div>

                                    <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="fw-bold">Export Sale Report</h2>

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
                                                            <span id="format_error" class="text-danger"></span>
                                                        </div>
                                                        <div class="text-center">
                                                            <button type="reset" class="btn btn-light me-3"
                                                                data-bs-dismiss="modal">
                                                                Discard
                                                            </button>

                                                            <button type="button" class="btn btn-primary"
                                                                onclick="exportCSV()">
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
                            <div id="sale_report_loader" class="d-none">
                                <i class="fa fa-spinner fa-spin"></i>
                            </div>
                            <div class="card-body py-4 table-responsive" id="product_table_ajax">
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
        var subDistrictData = "{{ route('get-sub-district-order') }}"
        $(document).ready(function(e) {
            orderReportAjax(1);
        });

        function orderReportAjax(page) {
            $('#sale_report_loader').removeClass('d-none')
            var date = $('#search_date').val();
            var search = $('#search_data').val();
            var order_status = $('#order_status').val();
            var user_id = $('#user_id').val();
            var order_district = $('#order_district').val();
            var order_sub_district = $('#order_sub_district').val();
            $.ajax({
                'method': 'get',
                'url': "{{ route('sales-report-ajax') }}",
                data: {
                    page: page,
                    search: search,
                    date: date,
                    order_status: order_status,
                    user_id: user_id,
                    order_district: order_district,
                    order_sub_district: order_sub_district,
                },
                success: function(res) {
                    $('#product_table_ajax').html('');
                    $('#product_table_ajax').html(res);
                    $('#sale_report_loader').addClass('d-none')
                },
            });
        }
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            orderReportAjax(page);
        });
        $(function() {
            $('.search_date').daterangepicker({
                autoUpdateInput: false,
                maxDate: moment(),
            }, function(start, end, label) {
                $('.search_date').val(start.format('Y-MM-DD') + '/' + end.format('Y-MM-DD'));
            });
        });

        function exportCSV() {
            var format = $('#export_format').val();
            var cnt = 0;
            $('#format_error').html('');

            if (format == "") {
                $('#format_error').html('Please Select Export Format.');
                return false;
            }
            var date_type = $('#date_type').val();
            var date = $('#search_date').val();
            var search = $('#search_data').val();
            window.open("{{ route('sales-report-export') }}" + '?format=' + format + '&date_type=' + date_type +
                '&search=' + search + '&date=' + date, '_blank');
        }

        function getSubDistrictDetail() {
            var district = $('#order_district').val()
            $.ajax({
                method: "get",
                url: subDistrictData,
                data: {
                    district: district
                },
                success: function(res) {
                    var html = "<option value=''>Select Sub District</option>";
                    $.each(res, function(i, v) {
                        html += "<option value='" + v.sub_district + "'>" + v.sub_district_detail
                            .sub_district_name + "</option>"
                    })
                    $('#order_sub_district').html('');
                    $('#order_sub_district').html(html);
                }
            })
        }
    </script>
@endsection
