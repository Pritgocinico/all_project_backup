@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid">
                <div id="kt_app_content_container" class="container-fluid ">
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="app-container  container-fluid ">
                                <div class="row card-header mb-5">
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="fs-6 fw-semibold mb-2">Created At</label>
                                                <input type="text" class="form-control mb-3 mb-lg-0 seacrh_create_date"
                                                    placeholder="Search Created Date" name="seacrh_create_date"
                                                    id="seacrh_create_date" value="{{date('Y-m-d') . "/". date('Y-m-d')}}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="fs-6 fw-semibold mb-2">Created By</label>
                                                <select class="form-select form-control" name="created_by" id="created_by">
                                                    <option value="">Select Employee</option>
                                                    @foreach ($employeeList as $employee)
                                                        <option value="{{ $employee->id }}">
                                                            {{ ucfirst($employee->userDetail->name) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="fs-6 fw-semibold mb-2">Customer Name</label>
                                                <input type="text" class="form-control mb-3 mb-lg-0"
                                                    placeholder="Search Customer Name" name="customer_name"
                                                    id="customer_name">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="fs-6 fw-semibold my-2">District</label>
                                                <select class="form-select form-control" name="district" id="district" onchange="getDistrict()">
                                                    <option value="">Select District</option>
                                                    @foreach ($districtList as $district)
                                                        <option value="{{ $district->district }}">
                                                            {{ $district->district_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="fs-6 fw-semibold my-2">Sub District</label>
                                                <select name="sub_district[]" id="sub_district" onchange="getVillage()"
                                                    class="form-select js-example-basic-multiple" multiple>
                                                    <option value="">Select Sub District</option>

                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="fs-6 fw-semibold my-2">Village</label>
                                                <select name="village[]" id="village"
                                                    class="form-select js-example-basic-multiple" multiple>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="col-md-3 d-flex justify-content-start gap-5 custom-padding align-items-start">
                                        <input type="button" value="Search" class="btn btn-primary" name="submit"
                                            onclick="orderAjaxList(1)">
                                        <input type="reset" value="Reset" class="btn btn-danger" name="Reset"  onclick="resetForm()"><br />
                                    </div>
                                    @if (Permission::checkPermission('driver-delivery-list'))
                                        <div class="d-flex justify-content-end mt-2 d-none" id="bul_assign_driver_div">
                                        
                                        <a href="#" class="btn btn-primary" onclick="bulkAssignDriver()">Assign
                                            Driver & Create Batch</a><br/>
                                        </div>
                                        <div class="d-flex justify-content-end mt-2 d-none" id="order_count_div">
                                        
                                            <h5>Selected Orders :- <span class="selected_order_count">1</span></h5>
                                        </div>
                                    @endif

                                </div>
                            <div class="card-body py-4 table-responsive" id="ticket_data_table"></div>
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
                        <h6>Selected Orders: <span id="total_select_order" class="text-solid mb-4"></span></h6>
                    </div>
                    <div class="row">
                        <label for="Status" class="required fs-6 fw-semibold mb-2">Assign Driver</label>
                        <select name="assign_driver" id="assign_driver" class="form-control">
                            <option value="">Select Driver</option>
                            @foreach ($driverList as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                            @endforeach
                        </select>
                        <span id="assign_driver_error" class="text-danger"></span>
                    </div>
                    <div class="row mt-2">
                        <label for="Status" class="required fs-6 fw-semibold mb-2">Car Name</label>
                        <input type="text" name="car_name" id="car_name" class="form-control"
                            placeholder="Car Name">
                    </div>
                    <div class="row mt-2">
                        <label for="Status" class="required fs-6 fw-semibold mb-2">Car Number</label>
                        <input type="text" name="car_number" id="car_number" class="form-control"
                            placeholder="Car Number">
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
        $(document).ready(function(e) {
            $('#sub_district').select2();
            $('#village').select2();
            orderAjaxList(1)
        })
        $(function() {
            $('.seacrh_create_date').daterangepicker({
                autoUpdateInput: false,
                maxDate: moment(),
            }, function(start, end, label) {
                $('.seacrh_create_date').val(start.format('Y-0M-0D') + '/' + end.format('Y-0M-0D'));
            });
        });

        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            orderAjaxList(page);
        });
        function getDistrict(){
            var district = $('#district').val();
            $.ajax({
                url: "{{ route('get-subdistricts') }}",
                type: "get",
                data: {
                    id: district,
                },
                dataType: 'json',
                success: function(result) {
                    var html = '<option value="">Select Sub District</option>';
                    $.each(result, function(i, v) {
                        html += '<option value="' + v.sub_district + '">' + v
                            .sub_district_name + '</option>'
                    });

                    $('#sub_district').html(html);
                }
            })
        }

        function getVillage() {
            var subDistrict = $('#sub_district').val();
            var village = $('#village').val();
            $.ajax({
                url: "{{ route('village-detail') }}",
                type: "get",
                data: {
                    id: subDistrict,
                },
                dataType: 'json',
                success: function(result) {
                    var html = ' <option value="">Select Village</option>';
                    $.each(result, function(i, v) {
                        var select = "";
                        if (village.indexOf(v.village_code) !== -1) {
                            select = "selected"
                        }
                        html += ' <option value="' + v.village_code + '"' + select + '>' + v
                            .village_name + '</option>'
                    });
                    $('#village').html(html);
                }
            })
        }

        function orderAjaxList(page) {
            var date = $('#seacrh_create_date').val();
            var empID = $('#created_by').val();
            var customer_name = $('#customer_name').val();
            var district = $('#district').val();
            var subDistrict = $('#sub_district').val();
            var village = $('#village').val();
            $.ajax({
                method: 'get',
                url: "{{ route('transport-confirm-order-ajax') }}",
                data: {
                    page: page,
                    empID: empID,
                    district: district,
                    subDistrict: subDistrict,
                    date: date,
                    village: village,
                    customer_name: customer_name,
                },
                success: function(res) {
                    $('#ticket_data_table').html('');
                    $('#ticket_data_table').html(res);
                },
            })
        }
        function resetForm(){
            $('#seacrh_create_date').val("");
            $('#created_by').val("");
            $('#customer_name').val("");
            $('#district').val("");
            $('#village').val('1').trigger("change")
            $('#sub_district').val('1').trigger("change")
            orderAjaxList(1)
        }
        var orderIdArray = [];

        function checkCheckbox() {
            var checkBox = document.getElementById("all_order_id");
            if (checkBox.checked) {
                $('#bul_assign_driver_div').removeClass('d-none')
                $('#order_count_div').removeClass('d-none')
                $(".order_id").each(function() {
                    orderIdArray.push($(this).val());
                    $(this).prop("checked", true);
                });
                $('.selected_order_count').html(orderIdArray.length);
            } else {
                $(".order_id").each(function() {
                    $(this).prop("checked", false);
                });
                $('#bul_assign_driver_div').addClass('d-none')
                $('#order_count_div').addClass('d-none')
                orderIdArray = [];
                $('.selected_order_count').html(orderIdArray.length);
            }
        }

        function singleCheckbox() {
            orderIdArray = [];
            $('.order_id').each(function(e) {
                var checked = $(this).prop('checked');
                if (checked) {
                    orderIdArray.push($(this).val());
                    $(this).prop("checked", true);
                    $('.selected_order_count').html(orderIdArray.length);
                } else {
                    $('#bul_assign_driver_div').addClass('d-none')
                    orderIdArray.splice($(this).val(), 1)
                    $(this).prop("checked", false);
                    $('.selected_order_count').html(orderIdArray.length);
                }
            })
            $('#bul_assign_driver_div').removeClass('d-none')
            $('#order_count_div').removeClass('d-none')
            if (orderIdArray.length == 0) {
                $('#bul_assign_driver_div').addClass('d-none')
                $('#order_count_div').addClass('d-none')
            }
        }

        function bulkAssignDriver() {
            $('#total_select_order').html(orderIdArray.length)
            $('#assign_driver').val("")
            $('#car_name').val("")
            $('#car_number').val("")
            $('#add_department_modal').modal('show');
        }

        function assignDriver() {
            $.ajax({
                method: 'post',
                url: "{{ route('bulk-assign-driver') }}",
                dataType: 'json',
                data: {
                    driver: $('#assign_driver').val(),
                    orderId: orderIdArray,
                    car_name: $('#car_name').val(),
                    car_number: $('#car_number').val(),
                    _token: "{{ csrf_token() }}",
                },
                success: function(res) {
                    toastr.success(res.message)
                    $('#add_department_modal').modal('hide');
                    $('#bul_assign_driver_div').addClass('d-none')
                    orderAjaxList(1)
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message);
                }
            });
        }
    </script>
@endsection
