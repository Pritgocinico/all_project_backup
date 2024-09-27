@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-fluid ">
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                            <div
                                class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" placeholder="Search Ticket"
                                            onkeyup="ticketAjaxList(1)" />
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
                                                <div class="px-7 py-5" data-kt-user-table-filter="form">
                                                    <div class="mb-10">
                                                        <label class="form-label fs-6 fw-semibold">Date:</label>
                                                        <input type="text" name="order_date" id="order_date"
                                                            class="form-control form-select-solid fw-bold search_ticket_date"
                                                            max="{{ date('Y-m-d') }}" placeholder="Select Date">
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <button type="reset"
                                                            class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" onclick="resetSearch()"
                                                            data-kt-user-table-filter="reset">Reset</button>
                                                        <button type="submit"
                                                            class="btn btn-primary fw-semibold px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                                            onclick="ticketAjaxList(1)">Apply</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 gap-lg-3">
                                            @if (Permission::checkPermission('ticket-add'))
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#add_ticket_modal"
                                                    class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                                                    <i class="ki-outline ki-plus fs-2"></i> New Ticket
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                </div>


                            </div>
                            <div class="card-body py-4 overflow-scroll" id="ticket_data_table">

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
    <div class="modal fade" id="add_ticket_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <label for="Status" class="required fs-6 fw-semibold mb-2">Ticket Type</label>
                        <select name="type" id="type" class="form-select" onchange="showEmployeeSystemCode()">
                            <option value="">Select Type</option>
                            <option value="Posh" @if (old('type') == 'Posh') selected @endif>Posh</option>
                            <option value="System Repair" @if (old('type') == 'System Repair') selected @endif>System Repair
                            </option>
                            <option value="Other Complaint " @if (old('type') == 'Other Complaint') selected @endif>Other
                                Complaint</option>
                        </select>
                        <span id="type_error" class="text-danger"></span>
                        <div class="text-danger d-none" id="show_system_code_div">User System Code:
                            {{ Auth()->user()->system_code }}</div>
                    </div>
                    <div class="row">
                        <label for="Status" class="required fs-6 fw-semibold mb-2">Ticket Subject</label>
                        <input type="text" name="subject" id="subject" class="form-control"
                            value="{{ old('subject') }}">
                        <span id="subject_error" class="text-danger"></span>
                    </div>
                    <div class="row">
                        <label for="Status" class="required fs-6 fw-semibold mb-2">Ticket Description</label>
                        <textarea name="description" class="form-control" id="description" cols="10" rows="5"></textarea>
                        <span id="description_error" class="text-danger"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="addTicket()">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit_ticket_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="department_edit_form">
                        <input type="hidden" name="id" value="" id="id">
                        <div class="row">
                            <label for="Status" class="required fs-6 fw-semibold mb-2">Ticket Type</label>
                            <select name="edit_type" id="edit_type" class="form-select">
                                <option value="">Select Type</option>
                                <option value="Posh" @if (old('type') == 'Posh') selected @endif>Posh</option>
                                <option value="System Repair" @if (old('type') == 'System Repair') selected @endif>System
                                    Repair</option>
                                <option value="Other Complentes" @if (old('type') == 'Other Complentes') selected @endif>Other
                                    Complentes</option>
                            </select>
                            <span id="edit_type_error" class="text-danger"></span>
                        </div>
                        <div class="row">
                            <label for="Status" class="required fs-6 fw-semibold mb-2">Ticket Subject</label>
                            <input type="text" name="edit_subject" id="edit_subject" class="form-control"
                                value="{{ old('subject') }}">
                            <span id="edit_subject_error" class="text-danger"></span>
                        </div>
                        <div class="row">
                            <label for="Status" class="required fs-6 fw-semibold mb-2">Ticket Description</label>
                            <textarea name="edit_description" class="form-control" id="edit_description" cols="10" rows="5"></textarea>
                            <span id="edit_description_error" class="text-danger"></span>
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
                    <button type="button" class="btn btn-primary" onclick="updateTicket()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="confrim_role_id" value="{{Auth()->user() !== null? Auth()->user()->role_id:''}}" id="confrim_role_id">
@endsection
@section('page')
    <script>
        var role_id = $('#confrim_role_id').val();
        var token = "{{ csrf_token() }}";
        var ajaxList = "{{ route('employee-ticket-ajax') }}";
        var storeURL = "{{ route('employee-ticket.store') }}";
        var edit = "{{ route('employee-ticket.edit', 'id') }}";
        var update = "{{ route('employee-ticket.update', 'id') }}";
        var deleteURL = "{{ route('employee-ticket.destroy', 'id') }}";
        var exportFile = "{{ route('employee-ticket-ajax-export') }}";
        if(role_id == '4'){
            ajaxList = "{{ route('confirm-ticket-ajax') }}";
            storeURL = "{{ route('confirm-ticket.store') }}";
            edit = "{{ route('confirm-ticket.edit', 'id') }}";
            update = "{{ route('confirm-ticket.update', 'id') }}";
            deleteURL = "{{ route('confirm-ticket.destroy', 'id') }}";
            exportFile = "{{ route('confirm-ticket-ajax-export') }}";
        }
        if(role_id == 7){
            ajaxList = "{{ route('transport-ticket-ajax') }}";
            storeURL = "{{ route('transport-ticket.store') }}";
            edit = "{{ route('transport-ticket.edit', 'id') }}";
            update = "{{ route('transport-ticket.update', 'id') }}";
            deleteURL = "{{ route('transport-ticket.destroy', 'id') }}";
            exportFile = "{{ route('transport-ticket-ajax-export') }}";
        }
        if(role_id == 8){
            ajaxList = "{{ route('warehouse-ticket-ajax') }}";
            storeURL = "{{ route('warehouse-ticket.store') }}";
            edit = "{{ route('warehouse-ticket.edit', 'id') }}";
            update = "{{ route('warehouse-ticket.update', 'id') }}";
            deleteURL = "{{ route('warehouse-ticket.destroy', 'id') }}";
            exportFile = "{{ route('warehouse-ticket-ajax-export') }}";
        }
        if(role_id == 9){
            ajaxList = "{{ route('sales-ticket-ajax') }}";
            storeURL = "{{ route('sales-ticket.store') }}";
            edit = "{{ route('sales-ticket.edit', 'id') }}";
            update = "{{ route('sales-ticket.update', 'id') }}";
            deleteURL = "{{ route('sales-ticket.destroy', 'id') }}";
            exportFile = "{{ route('sales-ticket-ajax-export') }}";
        }
        if(role_id == 10){
            ajaxList = "{{ route('sale-service-ticket-ajax') }}";
            storeURL = "{{ route('sale-service-ticket.store') }}";
            edit = "{{ route('sale-service-ticket.edit', 'id') }}";
            update = "{{ route('sale-service-ticket.update', 'id') }}";
            deleteURL = "{{ route('sale-service-ticket.destroy', 'id') }}";
            exportFile = "{{ route('sale-service-ticket-ajax-export') }}";
        }
    </script>
    <script src="{{ asset('public\assets\js\custom\employee\ticket.js') }}?{{ time() }}"></script>
@endsection
