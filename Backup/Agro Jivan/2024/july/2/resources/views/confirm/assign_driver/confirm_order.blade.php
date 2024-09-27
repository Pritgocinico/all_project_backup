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
                                Pending Order
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-fluid ">
                    <div id="kt_app_content" class="app-content  flex-column-fluid ">
                        <div id="kt_app_content_container" class="app-container  container-fluid ">
                            <div class="card">
                                <div class="card-header border-0 pt-6">
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                            <input type="text" data-kt-user-table-filter="search" id="search_data"
                                                class="form-control w-250px ps-13" placeholder="Search Order" />
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="card-body py-4 table-responsive" id="pending_ajax_list">
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
    <div class="modal fade" id="add_department_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Assign Driver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="order_id" id="order_id" />
                        <select name="assign_driver" id="assign_driver" class="form-select">
                            <option value="">Select Driver</option>
                            @foreach ($employeeList as $employee)
                                <option value="{{ $employee->id }}">{{ ucfirst($employee->name) }}</option>
                            @endforeach
                        </select>
                        <span id="department_name_error" class="text-danger"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="assignDriver()">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page')
    <script>
        var ajaxList = "{{ route('confirm-order-ajax') }}";
    </script>
    <script>
        $(document).ready(function(e) {
            pendingOrderAjax(1)
        })

        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            pendingOrderAjax(page);
        });

        function pendingOrderAjax(page) {
            var search = $('#search_data').val();
            $.ajax({
                'method': 'get',
                'url': ajaxList,
                data: {
                    page: page,
                    search: search,
                },
                success: function(res) {
                    $('#pending_ajax_list').html('');
                    $('#pending_ajax_list').html(res);
                    $('[data-bs-toggle="tooltip"]').tooltip()
                },
            });
        }

        function openAssignDriver(id) {
            $('#order_id').val(id);
            $('#add_department_modal').modal('show')
        }

        function assignDriver() {
            var order_id = $('#order_id').val();
            var driver_id = $('#assign_driver').val();
            $.ajax({
                'method': 'get',
                'url': "{{route('assign-driver')}}",
                data: {
                    order_id: order_id,
                    driver_id: driver_id,
                },
                success: function(res) {
                    toastr.success(res.message);
                    $('#add_department_modal').modal('hide')
                    pendingOrderAjax(1);
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message);
                }
            });
        }
    </script>
@endsection
