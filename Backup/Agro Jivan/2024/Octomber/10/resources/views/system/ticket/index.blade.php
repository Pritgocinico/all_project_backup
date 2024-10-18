@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-fluid ">
                    <div id="kt_app_content" class="  flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                                <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                            <input type="text" data-kt-user-table-filter="search" id="search_data"
                                                class="form-control w-250px ps-13" placeholder="Search Ticket"
                                                onkeyup="ticketAjaxList(1)" />
                                        </div>
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="d-flex justify-content-end custom-responsive-div" data-kt-user-table-toolbar="base">
                                            <button type="button" class="btn btn-light-primary me-3" id="search_main_menu"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
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
                                                        <label class="form-label fs-6 fw-semibold">Search Employee:</label>
                                                        <select name="emp_id" id="emp_id" class="form-control">
                                                            <option value="">Select Employee</option>
                                                            
                                                            @foreach ($employeeList as $employee)
                                                              <option value="{{ $employee->id }}" {{ $id == $employee->id ? 'selected' : '' }}>{{$employee->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-10">
                                                        <label class="form-label fs-6 fw-semibold">Date:</label>
                                                        <input type="text" name="ticket_date" id="ticket_date"
                                                            class="form-control form-select-solid fw-bold search_ticket_date"
                                                            max="{{ date('Y-m-d') }}" placeholder="Select Date">
                                                    </div>
                                                    <div class="d-flex justify-content-end">
                                                        <button type="reset"
                                                            class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                                            data-kt-menu-dismiss="true"
                                                            data-kt-user-table-filter="reset">Reset</button>
                                                        <button type="submit" class="btn btn-primary fw-semibold px-6"
                                                            data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                                            onclick="ticketAjaxList(1)">Apply</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                                                data-bs-target="#kt_modal_export_users">
                                                <i class="ki-outline ki-exit-up fs-2"></i> Export
                                            </button>
                                            <div class="d-flex align-items-center gap-2 gap-lg-3">
                                                @if (Permission::checkPermission('ticket-add'))
                                                    <a href="#" data-bs-toggle="modal"
                                                    data-bs-target="#add_ticket_modal"
                                                        class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                                                        <i class="ki-outline ki-plus fs-2"></i> New Ticket
                                                    </a>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="modal fade" id="kt_modal_export_users" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="fw-bold">Export Ticket</h2>

                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                        <form id="" class="form" action="#">


                                                            <div class="fv-row mb-10">
                                                                <label
                                                                    class="required fs-6 fw-semibold form-label mb-2">Select
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
                            <div class="card-body py-4 table-responsive" id="ticket_data_table">

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
                        <label class="required form-label fs-6 fw-semibold my-2">Create Ticket For</label>
                        <input type="hidden" name="type"  id="type"  value="System Repair">
                        <select name="emp_ids" id="emp_ids" class="form-control">
                            <option value="">Select System</option>
                            @foreach ($employeeList as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->employee_code }} ( {{$employee->name}} )</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <label for="Status" class="required fs-6 fw-semibold my-2">Ticket Subject</label>
                        <input type="text" name="subject" id="subject" class="form-control" value="{{old('subject')}}">
                        <span id="subject_error" class="text-danger"></span>
                    </div>
                    <div class="row">
                        <label for="Status" class="required fs-6 fw-semibold my-2">Ticket Description</label>
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
                                <option value="Posh" @if(old('type') == "Posh") selected @endif>Posh</option>
                                <option value="System Repair" @if(old('type') == "System Repair") selected @endif>System Repair</option>
                                <option value="Other Complentes" @if(old('type') == "Other Complentes") selected @endif>Other Complentes</option>
                            </select>
                            <span id="edit_type_error" class="text-danger"></span>
                        </div>
                        <div class="row">
                            <label for="Status" class="required fs-6 fw-semibold mb-2">Ticket Subject</label>
                            <input type="text" name="edit_subject" id="edit_subject" class="form-control" value="{{old('subject')}}">
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
@endsection
@section('page')
<script src="{{asset('public/assets/js/custom/employee/ticket.js')}}"></script>
    <script>
        var storeURL = "{{ route('ticket.store') }}";
        var edit = "{{ route('ticket.edit', 'id') }}";
        var update = "{{ route('ticket.update', 'id') }}";
        var deleteURL = "{{ route('ticket.destroy', 'id') }}";
        var token = "{{ csrf_token() }}";

        $(function() {
            $('.search_ticket_date').daterangepicker({
                autoUpdateInput: false,
                maxDate: moment(),
            }, function(start, end, label) {
                $('.search_ticket_date').val(start.format('Y-0M-0D') + '/' + end.format('Y-0M-0D'));
                ticketAjaxList(1)
            });
        });
        $(document).ready(function(e) {
            ticketAjaxList(1)
        });

        function ticketAjaxList(page) {
            $.ajax({
                method:'get',
                url: "{{route('engineer-ticket-ajax')}}",
                data:{
                    page:page,
                    search:$('#search_data').val(),
                    date:$('#ticket_date').val(),
                    e_id:$('#emp_id').val(),
                },
                success: function(res){
                    $('#ticket_data_table').html('')
                    $('#ticket_data_table').html(res)
                    $('[data-bs-toggle="tooltip"]').tooltip();
                },
            })
        }
        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            ticketAjaxList(page);
        });
    </script>

@endsection
