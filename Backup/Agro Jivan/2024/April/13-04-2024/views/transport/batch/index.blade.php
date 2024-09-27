@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-fluid ">
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                            <div
                                class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" placeholder="Search"
                                            onkeyup="batchAjaxList(1)" />
                                    </div>
                                </div>
                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end custom-responsive-div"
                                        data-kt-user-table-toolbar="base">
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
                                                        <label class="form-label fs-6 fw-semibold">Search Driver:</label>
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
                                                        <input type="text" name="batch_date" id="batch_date"
                                                            class="form-control form-select-solid fw-bold search_batch_date"
                                                            max="{{ date('Y-m-d') }}" placeholder="Select Date">
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <button type="reset"
                                                            class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" onclick="resetForm()"
                                                            data-kt-user-table-filter="reset">Reset</button>
                                                        <button type="button"
                                                            class="btn btn-primary fw-semibold px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                                            onclick="batchAjaxList(1)">Apply</button>
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
                                                    <h2 class="fw-bold">Export Batch</h2>

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

    </body>
    <div class="modal fade" id="batch_product_detail_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="batch_id_detail"></h5>
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
@endsection
@section('page')
    <script>
        var exportFile = "{{ route('export-batch-list') }}"
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
        });

        function exportCSV() {
            var format = $('#export_format').val();
            $('#export_format_error').html('');
            if (format == "") {
                $('#export_format_error').html('Please Select Export Format.')
                return false;
            }
            var diver_id = $('#diver_id').val();
            var search = $('#search_data').val();
            var batch_date = $('#batch_date').val();
            window.open(exportFile + '?format=' + format + '&diver_id=' + diver_id + '&search=' + search + '&batch_date=' +
                batch_date, '_blank');
        }
        function resetForm(){
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
                    $('[data-bs-toggle="tooltip"]').tooltip()
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

        function updateBatchStatus(id) {
            new swal({
                title: 'Are you sure change status Delivered this Leave?',
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes Delivered it!'
            }).then(function(isConfirm) {
                if (isConfirm.isConfirmed) {
                    $.ajax({
                        method: "get",
                        url: "{{ route('update-batch') }}",
                        data: {
                            id: id,
                        },
                        success: function(res) {
                            toastr.success(res.message);
                            batchAjaxList(1);
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message);
                        }
                    })
                }
            });
        }

        function viewBacthDetail(id) {
            $.ajax({
                method: "get",
                url: "{{ route('batch-detail') }}",
                data: {
                    id: id,
                },
                success: function(res) {
                    var html = "";
                    var batchId = res.batch_id
                    $('#batch_id_detail').html(res.batch_id);
                    var product = "";
                    var quantity = 0;
                    $('#batch_id').val(res.batch_id);
                    $.each(res, function(i, v) {
                        quantity = v.quantity + v.quantity;
                        if (v !== batchId) {
                            if (v.product_id !== product) {
                                html += `<tr >
                                    <td><b>` + v.product_name + `:</b></td>
                                    <td>` + v.total_order + `</td>
                                    <td>` + quantity + `</td>
                                    </tr>`;
                            }
                        }
                        product = v.product_id;
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
