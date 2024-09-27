@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_toolbar" class="app-toolbar  pt-6 pb-2 ">
                <div id="kt_app_toolbar_container" class="container-fluid d-flex align-items-stretch ">
                    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                        <div class="d-flex align-items-center gap-2 gap-lg-3">
                            <a href="{{ route('warehouse-stock-list') }}"
                                class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                                <i class="fa-solid fa-eye"></i>&nbsp; Stock
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                <div id="kt_app_content_container" class="app-container container-fluid ">
                    <div class="row gy-5 g-xl-10">
                        <div class="col-md-12">
                            <div class="row gy-5 agro-parent-main-dashboard">
                                <div class="agro-main-dashboard-child">
                                    <a href="{{ route('batch-list') }}">
                                        <div class="card h-lg-100 bg-grow-early">
                                            <div
                                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                                <div class="m-0">
                                                    <i class="ki-outline ki-delivery-time fs-2hx text-gray-600"></i>
                                                </div>
                                                <div class="d-flex flex-column my-7">
                                                    <span
                                                        class="fw-semibold fs-3x lh-1 ls-n2 text-white">{{ $batchCount }}</span>
                                                    <div class="m-0">
                                                        <span class="fw-semibold fs-2x text-white">
                                                            Batch List </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="agro-main-dashboard-child">
                                    <a href="{{ route('warehouse-stock-list') }}">
                                        <div class="card h-lg-100 bg-arielle-smile">
                                            <div
                                                class="card-body d-flex justify-content-between align-items-start flex-column">
                                                <div class="m-0">
                                                    <i class="fa-brands fa-2x"></i>
                                                </div>
                                                <div class="d-flex flex-column my-7">
                                                    <span
                                                        class="fw-semibold fs-3x lh-1 ls-n2 text-white">{{ $productCount }}</span>
                                                    <div class="m-0">
                                                        <span class="fw-semibold fs-2x text-white">
                                                            Total Products</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="kt_app_content" class="mt-sm-5 mt-3 flex-column-fluid">
                        <div id="kt_app_content_container" class="container-fluid px-0">
                            <div id="kt_app_content" class="flex-column-fluid ">
                                <div id="kt_app_content_container" class="container-fluid px-0">
                                    <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                        <div class="card-title">
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                                <input type="text" data-kt-user-table-filter="search" id="search_data"
                                                    class="form-control w-250px ps-13" placeholder="Search"
                                                    onkeyup="batchAjaxList(1)" />
                                            </div>
                                        </div>
                                        <div class="card-toolbar">
                                            <div class="d-flex justify-content-end custom-responsive-div" data-kt-user-table-toolbar="base">
                                                <button type="button" class="btn btn-light-primary me-3"
                                                    id="search_main_menu" data-kt-menu-trigger="click"
                                                    data-kt-menu-placement="bottom-end">
                                                    <i class="ki-outline ki-filter fs-2"></i> Filter
                                                </button>
                                                <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px"
                                                    id="search_sub_menu" data-kt-menu="true">
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
                                                            <input type="text" name="batch_date" id="batch_date"
                                                                class="form-control form-select-solid fw-bold search_batch_date"
                                                                max="{{ date('Y-m-d') }}" placeholder="Select Date">
                                                        </div>
                                                        <div class="d-flex justify-content-end">
                                                            <button type="reset"
                                                                class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                                                data-kt-menu-dismiss="true"
                                                                data-kt-user-table-filter="reset">Reset</button>
                                                            <button type="submit"
                                                                class="btn btn-primary fw-semibold px-6"
                                                                data-kt-menu-dismiss="true"
                                                                data-kt-user-table-filter="filter"
                                                                onclick="batchAjaxList(1)">Apply</button>
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
    <div class="modal fade" id="edit_batch_model" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Edit Batch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="edit_batch_id" id="edit_batch_id">
                <div class="row">
                    <label>Select Order Id</label>
                    <select name="batch_order_id[]" id="batch_order_id"class="form-select js-example-basic-multiple" multiple>
                        <option value="">Select Order</option>
                        @foreach($orderList as $order)
                            <option value="{{$order->id}}">{{$order->order_id}}</option>
                        @endforeach
                    </select>
                    <span id="order_id_error" class="text-danger"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="updateBatch()">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                    <div id="" class="table-responsive">
                        <input type="hidden" name="batch_id" id="batch_id">
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
            $('#order_id').focus();
            $('#batch_order_id').select2();   
        });

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
                    $('[data-bs-toggle="tooltip"]').tooltip();
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
                    var batchId = res.batch_id;
                    $('#batch_id').val(res.batch_id);
                    $('#batch_id_detail').html(res.batch_id);
                    $.each(res, function(i, v) {
                        if (v !== res.batch_id && v !== null){
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
        function editBatch(id) {
            $('#edit_batch_id').val(id);
            $('#edit_batch_model').modal('show') 
        }
        function updateBatch(){
            var orderIdArray = $('#order_id').val();
            var batchId = $('#edit_batch_id').val();
            $('#order_id_error').html("");
            if(orderIdArray.length == 0){
                $('#order_id_error').html("Please select Order id.");
                return false;
            }

            $.ajax({
                method: "post",
                url: "{{ route('update-batch') }}",
                data: {
                    batchId: batchId,
                    _token: "{{csrf_token()}}",
                    orderIdArray: orderIdArray,
                },
                success: function(res) {
                    toastr.success(res.message);
                    batchAjaxList(1);
                    $('#edit_batch_model').modal('hide')
                },
            })
        }
    </script>
@endsection
