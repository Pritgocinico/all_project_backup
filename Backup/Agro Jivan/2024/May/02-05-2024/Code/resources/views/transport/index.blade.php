@extends('layouts.main_layout')
@section('section')
<div class="app-main flex-column flex-row-fluid " id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar  pt-6 pb-2 ">

            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container  container-fluid d-flex align-items-stretch ">
                <!--begin::Toolbar wrapper-->
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="row">
                        <div class="col-xxl-8 col-xl-8 col-md-8 col-8 mb-2">
                            <input type="text" name="search_order_picker" id="search_order_picker" value="{{ date('Y-m-d') }}/{{ date('Y-m-d') }}" class="form-control search_order_picker">
                        </div>
                        <div class="col-xl-4 col-4 mb-2">
                            <input type="button" name="search_order" id="search_order" value="search" onclick="countAjaxList()" class="btn btn-primary">
                        </div>
                    </div>

                    <!--end::Actions-->
                </div>
                <!--end::Toolbar wrapper-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <div id="kt_app_content" class="app-content  flex-column-fluid ">
            <div id="kt_app_content_container" class="app-container container-fluid ">
                <div class="row gy-5 g-xl-10">
                    <div class="col-md-12">
                        <div class="row gy-5 g-xl-10 agro-parent-main-dashboard">
                            <div class="agro-main-dashboard-child">
                                <a href="{{ route('transport-order-list') }}?status=2">
                                    <div class="card h-lg-100 bg-grow-early">
                                        <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                            <div class="m-0">
                                                <i class="ki-outline ki-delivery-time fs-2hx text-gray-600"></i>
                                            </div>
                                            <div class="d-flex flex-column my-7">
                                                <span class="fw-semibold fs-3x lh-1 ls-n2 text-white" id="confirm_order_count"></span>
                                                <div class="m-0">
                                                    <span class="fw-semibold fs-2x text-white">
                                                        Confirmed Orders </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="agro-main-dashboard-child">
                                <a href="{{ route('batch-list') }}?status=1">
                                    <div class="card h-lg-100 bg-arielle-smile">
                                        <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                            <div class="m-0">
                                                <i class="ki-outline ki-delivery-door fs-2hx text-gray-600"></i>
                                            </div>
                                            <div class="d-flex flex-column my-7">
                                                <span class="fw-semibold fs-3x text-black-800 lh-1 ls-n2 text-white" id="total_batch_count"></span>
                                                <div class="m-0">
                                                    <span class="fw-semibold fs-2x text-white">
                                                        Total Batch</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="agro-main-dashboard-child">
                                <a href="{{ route('in-out-stock') }}">
                                    <div class="card h-lg-100 bg-midnight-bloom">
                                        <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                            <div class="m-0">
                                                <i class="ki-outline ki-delivery-door fs-2hx text-gray-600"></i>
                                            </div>
                                            <div class="d-flex flex-column my-7">
                                                <span class="fw-semibold fs-3x text-white lh-1 ls-n2" id="total_in_stock"></span>
                                                <div class="m-0">
                                                    <span class="fw-semibold fs-2x text-white">
                                                        Total Out Stock</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="agro-main-dashboard-child">
                                <a href="{{ route('in-out-stock') }}">
                                    <div class="card h-lg-100 bg-grow-ice">
                                        <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                            <div class="m-0">
                                                <i class="ki-outline ki-delivery-door fs-2hx text-gray-600"></i>
                                            </div>
                                            <div class="d-flex flex-column my-7">
                                                <span class="fw-semibold fs-3x text-white lh-1 ls-n2" id="total_out_stock"></span>
                                                <div class="m-0">
                                                    <span class="fw-semibold fs-2x text-white">
                                                        Total In Stock</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="kt_app_content" class="pt-5 flex-column-fluid">
                    <div id="kt_app_content_container" class="  container-fluid ">
                        <div id="kt_app_content" class="flex-column-fluid ">
                            <div id="kt_app_content_container" class="container-fluid ">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                            <input type="text" data-kt-user-table-filter="search" id="search_data" class="form-control w-250px ps-13" placeholder="Search" onkeyup="batchAjaxList(1)" />
                                        </div>
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                            <div class="parent-filter-menu">
                                                <button type="button" class="btn btn-light-primary me-3 order_filter_option" id="search_main_menu">
                                                    <i class="ki-outline ki-filter fs-2"></i> Filter
                                                </button>
                                                <div class="menu filter-menu w-300px w-md-325px" data-kt-menu="true">
                                                    <div class="px-7 py-5">
                                                        <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                                                    </div>
                                                    <div class="separator border-gray-200"></div>
                                                    <div class="px-7 py-5" data-kt-user-table-filter="form">
                                                        <div class="mb-10">
                                                            <label class="form-label fs-6 fw-semibold">Search
                                                                Driver:</label>
                                                            <select name="diver_id" id="diver_id" class="form-control">
                                                                <option value="">Select Driver</option>
                                                                @foreach ($driverList as $driver)
                                                                <option value="{{ $driver->id }}">{{ $driver->name }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-10">
                                                            <label class="form-label fs-6 fw-semibold">Date:</label>
                                                            <input type="text" name="batch_date" id="batch_date" class="form-control form-select-solid fw-bold search_batch_date" max="{{ date('Y-m-d') }}" placeholder="Select Date">
                                                        </div>

                                                        <div class="d-flex justify-content-end">
                                                            <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6 order_filter_option" data-kt-menu-dismiss="true" onclick="resetForm()" data-kt-user-table-filter="reset">Reset</button>
                                                            <button type="button" class="btn btn-primary fw-semibold px-6 order_filter_option" data-kt-menu-dismiss="true" data-kt-user-table-filter="filter" onclick="batchAjaxList(1)">Apply</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-body py-4 table-responsive" id="batch_data_table"></div>
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
</body>
@endsection
@section('page')
<script>
    $(function() {
        $('.search_batch_date').daterangepicker({
            autoUpdateInput: false,
            maxDate: moment(),
        }, function(start, end, label) {
            $('.search_batch_date').val(start.format('Y-0M-0D') + '/' + end.format('Y-0M-0D'));
        });
    });
    $(document).ready(function(e) {
        batchAjaxList(1)
        countAjaxList();
    });

    function resetForm() {
        $('#search_data').val('')
        $('#diver_id').val('')
        $('#batch_date').val('')
        batchAjaxList(1)
    }

    function batchAjaxList(page) {
        $.ajax({
            method: 'get',
            url: "{{ route('batch-list-ajax') }}",
            data: {
                page: page,
                search: $('#search_data').val(),
                diver_id: $('#diver_id').val(),
                batch_date: $('#batch_date').val(),
            },
            success: function(res) {
                $('#batch_data_table').html('')
                $('#batch_data_table').html(res)
            },
        })
    }
    $(function() {
        $('.search_order_picker').daterangepicker({
            autoUpdateInput: false,
            maxDate: moment(),
            startDate: moment(),
            endDate: moment(),
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                    'month').endOf(
                    'month')]
            }

        }, function(start, end, label) {
            $('.search_order_picker').val(start.format('Y-MM-DD') + '/' + end.format('Y-MM-DD'));
        });
    });

    function countAjaxList() {
        $.ajax({
            method: 'get',
            url: "{{ route('transport-dashboard-ajax') }}",
            data: {
                date: $('#search_order_picker').val(),
            },
            success: function(res) {
                var inStock = 0;
                if(res.inOutStockCount.total_in_stock !== null){
                    inStock = res.inOutStockCount.total_in_stock;
                }
                var outStock = 0;
                if(res.inOutStockCount.total_out_stock !== null){
                    outStock = res.inOutStockCount.total_out_stock;
                }
                $('#confirm_order_count').html(res.confirmCount)
                $('#total_batch_count').html(res.batchCount)
                $('#total_in_stock').html(inStock)
                $('#total_out_stock').html(outStock)
            },
        })
    }
    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        var page = $(this).attr('href').split('page=')[1];
        batchAjaxList(page);
    });
    function viewBacthDetail(id) {
            $.ajax({
                method: "get",
                url: "{{ route('batch-detail') }}",
                data: {
                    id: id,
                },
                success: function(res) {

                    var html = "";
                    var batchId = res.batch_id;
                    var product = "";
                    $('#batch_id').val(res.batch_id);
                    $('#batch_id_detail').html(res.batch_id);
                    $.each(res, function(i, v) {
                        if (v !== batchId && v !== null) {
                                html += `<tr >
                                    <td><b>` + v.variant_name + `:</b></td>
                                    <td>` + v.total_order + `</td>
                                    <td>` + v.quantity + `</td>
                                    </tr>`;
                            }
                    })
                    $('#batch_product_detail').html('')
                    $('#batch_product_detail').html(html)
                    $('#batch_product_detail_modal').modal('show')
                },
            })
        }
        function generatePDF() {
        var batchId = $('#batch_id').val();
        window.open("{{ route('single-batch-pdf') }}" + '?batchId=' + batchId, '_blank');
    }
</script>
@endsection