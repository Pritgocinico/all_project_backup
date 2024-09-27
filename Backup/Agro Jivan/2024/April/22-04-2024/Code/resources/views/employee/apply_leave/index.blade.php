@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-fluid ">
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                               <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                            <input type="text" data-kt-user-table-filter="search" id="search_data" onkeyup="leaveAjax(1)"
                                                class="form-control w-250px ps-13" placeholder="Search Leave" />
                                        </div>
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="d-flex justify-content-end custom-responsive-div" data-kt-user-table-toolbar="base">
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
                                                            <label class="form-label fs-6 fw-semibold">Leave Type:</label>
                                                            <select class="form-select form-select-solid fw-bold"
                                                                id="leave_feature" data-placeholder="Select option"
                                                                data-allow-clear="true" data-kt-user-table-filter="two-step"
                                                                data-hide-search="true">
                                                                <option value="">Select Leave Feature</option>
                                                                <option value="1">Full Day</option>
                                                                <option value="0">Half Day</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-10">
                                                            <label class="form-label fs-6 fw-semibold">Leave Status:</label>
                                                            <select class="form-select form-select-solid fw-bold"
                                                                id="leave_status" data-placeholder="Select option"
                                                                data-allow-clear="true" data-kt-user-table-filter="two-step"
                                                                data-hide-search="true">
                                                                <option value="">Select Leave Status</option>
                                                                <option value="1">Pending</option>
                                                                <option value="2">Approve</option>
                                                                <option value="3">Reject</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-10">
                                                            <label class="form-label fs-6 fw-semibold">Date:</label>
                                                            <input type="text" name="order_date" id="order_date" class="form-control form-select-solid fw-bold search_leave_date" max="{{date('Y-m-d')}}" placeholder="Select Date" onchange="leadAjaxList(1)">
                                                        </div>
    
                                                        <div class="d-flex justify-content-end">
                                                            <button type="reset"
                                                                class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6 order_filter_option"
                                                                data-kt-menu-dismiss="true" onclick="resetSearch()"
                                                                data-kt-user-table-filter="reset">Reset</button>
                                                            <button type="submit"
                                                                class="btn btn-primary fw-semibold px-6 order_filter_option"
                                                                data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                                                onclick="leaveAjax(1)">Apply</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if (Permission::checkPermission('leave-add'))
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#add_leave_modal"
                                                class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                                                <i class="ki-outline ki-plus fs-2"></i> New Leave
                                            </a>
                                        @endif
                                        </div>
                                    </div>
                                </div>
                            <div class="card-body py-4 table-responsive" id="leave_data_table">
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
    <div class="modal fade" id="add_leave_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Leave</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="department_edit_form">
                        <div class="row">
                            <label for="Status" class="required fs-6 fw-semibold mb-2">Leave Type</label>
                            <select name="leave_type" id="leave_type" class="form-select">
                                <option value="">Select Leave Type</option>
                                <option value="Casual Leave" @if (old('leave_type') == 'Casual Leave') selected @endif>Casual Leave
                                </option>
                                <option value="Sick Leave" @if (old('leave_type') == 'Casual Leave') selected @endif>Sick Leave
                                </option>
                            </select>
                            <span id="leave_type_error" class="text-danger"></span>
                        </div>
                        <div class="row">
                            <label for="Status" class="required fs-6 fw-semibold mb-2">Leave From</label>
                            <input type="date" name="leave_from" id="leave_from" class="form-control"
                                value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" onchange="minDateTo()">
                            <span id="leave_from_error" class="text-danger"></span>
                        </div>
                        <div class="row">
                            <label for="Status" class="required fs-6 fw-semibold mb-2">Leave To</label>
                            <input type="date" name="leave_to" id="leave_to" class="form-control"
                                value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
                            <span id="leave_to_error" class="text-danger"></span>
                        </div>
                        <div class="row d-none" id="leave_feature_div">
                            <label for="Status" class="required fs-6 fw-semibold mb-2">Leave Feature</label>
                            <select name="leave_feature" id="leave_feature" class="form-select">
                                <option value="">Select Leave Feature</option>
                                <option value="1" @if (old('leave_feature') == '1') selected @endif>Full Day</option>
                                <option value="0" @if (old('leave_feature') == '0') selected @endif>Half Day</option>
                            </select>
                            <span id="leave_feature_error" class="text-danger"></span>
                        </div>
                        <div class="row">
                            <label for="Status" class="required fs-6 fw-semibold mb-2">Leave Reason</label>
                            <textarea name="reason" class="form-control" id="reason" cols="10" rows="5"></textarea>
                            <span id="reason_error" class="text-danger"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="addLeave()">store</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit_leave_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Leave</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="department_edit_form">
                        <input type="hidden" id="id" name="id"/>
                        <div class="row">
                            <label for="Status" class="required fs-6 fw-semibold mb-2">Leave Type</label>
                            <select name="edit_leave_type" id="edit_leave_type" class="form-select">
                                <option value="">Select Leave Type</option>
                                <option value="Casual Leave">Casual Leave
                                </option>
                                <option value="Sick Leave">Sick Leave
                                </option>
                            </select>
                            <span id="edit_leave_type_error" class="text-danger"></span>
                        </div>
                        <div class="row">
                            <label for="Status" class="required fs-6 fw-semibold mb-2">Leave From</label>
                            <input type="date" name="edit_leave_from" id="edit_leave_from" class="form-control"
                                value="" min="{{ date('Y-m-d') }}" onchange="minDateTo()">
                            <span id="edit_leave_from_error" class="text-danger"></span>
                        </div>
                        <div class="row">
                            <label for="Status" class="required fs-6 fw-semibold mb-2">Leave To</label>
                            <input type="date" name="edit_leave_to" id="edit_leave_to" class="form-control"
                                value="" min="{{ date('Y-m-d') }}">
                            <span id="edit_leave_to_error" class="text-danger"></span>
                        </div>
                        <div class="row">
                            <label for="Status" class="required fs-6 fw-semibold mb-2">Leave Feature</label>
                            <select name="edit_leave_feature" id="edit_leave_feature" class="form-select">
                                <option value="">Select Leave Feature</option>
                                <option value="1">Full Day</option>
                                <option value="0">Half Day</option>
                            </select>
                            <span id="edit_leave_feature_error" class="text-danger"></span>
                        </div>
                        <div class="row">
                            <label for="Status" class="required fs-6 fw-semibold mb-2">Leave Reason</label>
                            <textarea name="edit_reason" class="form-control" id="edit_reason" cols="10" rows="5"></textarea>
                            <span id="edit_reason_error" class="text-danger"></span>
                        </div>
                        <div class="row">
                            <label for="Status" class="fs-6 fw-semibold mb-2">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="status">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateLeave()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="user_role_id" id="user_role_id"value="{{Auth()->user() !== null?Auth()->user()->role_id :""}}">
@endsection
@section('page')
    <script>
        var role_id = $('#user_role_id').val()
        var token = "{{ csrf_token() }}";
        var storeURL = "{{ route('employee-leave.store') }}";
        var ajax = "{{ route('employee-leave-ajax') }}";
        var edit = "{{ route('employee-leave.edit', 'id') }}";
        var update = "{{ route('employee-leave.update', 'id') }}";
        var deleteURL = "{{ route('employee-leave.destroy', 'id') }}";
        var exportFile = "{{route('employee-leave-export')}}"
        if(role_id == "4"){
            storeURL = "{{ route('confirm-leave.store') }}";
            ajax = "{{ route('confirm-leave-ajax') }}";
            edit = "{{ route('confirm-leave.edit', 'id') }}";
            update = "{{ route('confirm-leave.update', 'id') }}";
            deleteURL = "{{ route('confirm-leave.destroy', 'id') }}";
            exportFile = "{{route('confirm-leave-export')}}" 
        }
        if(role_id == 7){
            storeURL = "{{ route('transport-leave.store') }}";
            ajax = "{{ route('transport-leave-ajax') }}";
            edit = "{{ route('transport-leave.edit', 'id') }}";
            update = "{{ route('transport-leave.update', 'id') }}";
            deleteURL = "{{ route('transport-leave.destroy', 'id') }}";
            exportFile = "{{route('transport-leave-export')}}" 
        }
        if(role_id == 8){
            storeURL = "{{ route('warehouse-leave.store') }}";
            ajax = "{{ route('warehouse-leave-ajax') }}";
            edit = "{{ route('warehouse-leave.edit', 'id') }}";
            update = "{{ route('warehouse-leave.update', 'id') }}";
            deleteURL = "{{ route('warehouse-leave.destroy', 'id') }}";
            exportFile = "{{route('warehouse-leave-export')}}" 
        }
        if(role_id == 9){
            storeURL = "{{ route('sales-leave.store') }}";
            ajax = "{{ route('sales-leave-ajax') }}";
            edit = "{{ route('sales-leave.edit', 'id') }}";
            update = "{{ route('sales-leave.update', 'id') }}";
            deleteURL = "{{ route('sales-leave.destroy', 'id') }}";
            exportFile = "{{route('sales-leave-export')}}" 
        }
        if(role_id == 10){
            storeURL = "{{ route('sale-service-leave.store') }}";
            ajax = "{{ route('sale-service-leave-ajax') }}";
            edit = "{{ route('sale-service-leave.edit', 'id') }}";
            update = "{{ route('sale-service-leave.update', 'id') }}";
            deleteURL = "{{ route('sale-service-leave.destroy', 'id') }}";
            exportFile = "{{route('sale-service-leave-export')}}" 
        }
    </script>
    <script src="{{ asset('public\assets\js\custom\employee\leave.js') }}?{{ time() }}"></script>
@endsection
