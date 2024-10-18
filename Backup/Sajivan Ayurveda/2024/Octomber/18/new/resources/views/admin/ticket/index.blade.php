@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill scrollbar main-table bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Help</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if (collect($accesses)->where('menu_id', '9')->where('export', 1)->first())
                            <a href="#" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_export_users" data-bs-toggle="modal">
                                Export</a>
                        @endif
                        @if (collect($accesses)->where('menu_id', '9')->where('add', 1)->first() && 
                                Auth()->user()->role_id != 1)
                            <a href="#" class="btn btn-sm btn-dark" data-bs-target="#AddticketLiquidityModal"
                                data-bs-toggle="modal"><i class="fa fa-plus me-2"></i>
                                New Help</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="px-6 px-lg-7 pt-6">
                <div id="ticket_table_ajax" class="mt-6  custom-scrollbar">
                </div>
            </div>
        </main>
        <div class="modal fade" id="AddticketLiquidityModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
            data-bs-backdrop="static" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Add Help</h1>
                        <button type="button" class="btn-close close-ticket" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form class="vstack" method="POST" id="addForm">
                        @csrf
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Department <span
                                                class="text_danger_require">*</span></label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <select name="department" class="form-control" id="department">
                                            <option value="">Select Department</option>
                                            @foreach ($departmentList as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                        <span style="color: red;" id="department_error"></span>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Ticket Type <span
                                                class="text_danger_require">*</span></label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <select name="ticket_type" class="form-control" id="ticket_type">
                                            <option value="">Select Ticket Type</option>
                                            <option value="Posh" @if (old('ticket_type') == 'Posh') selected @endif>Posh
                                            </option>
                                            <option value="System Repair" @if (old('ticket_type') == 'System Repair') selected @endif>
                                                System Repair
                                            </option>
                                            <option value="Other Complaint"
                                                @if (old('ticket_type') == 'Other Complaint') selected @endif>Other
                                                Complaint</option>
                                        </select>
                                        <span style="color: red;" id="ticket_type_error"></span>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Subject <span
                                                class="text_danger_require">*</span></label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <input type="text" name="subject" class="form-control"
                                            placeholder="Enter Subject" id="subject">
                                        <span style="color: red;" id="subject_error"></span>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Description <span
                                                class="text_danger_require">*</span></label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <textarea name="description" placeholder="Enter Description" class="form-control" id="description" cols="10"
                                            rows="5">{{ old('description') }}</textarea>
                                        <span style="color: red;" id="description_error"></span>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6 d-none" id="status_div">
                                    <div class="col-md-3"><label class="form-label mb-0">Status</label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" id="status">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-neutral close-ticket"
                                data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="submitBtn"
                                onclick="submitForm()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editticketLiquidityModal" tabindex="-1"
            aria-labelledby="depositLiquidityModalLabel" data-bs-backdrop="static" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Edit Help</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="vstack" method="POST" id="updateForm">
                        @csrf
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <input type="hidden" name="ticket_id" id="ticket_id">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Department <span
                                                class="text_danger_require">*</span></label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <select name="department" class="form-control" id="edit_department">
                                            <option value="">Select Department</option>
                                            @foreach ($departmentList as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                        <span style="color: red;" id="edit_department_error"></span>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Ticket Type <span
                                                class="text_danger_require">*</span></label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <select name="ticket_type" class="form-control" id="edit_ticket_type">
                                            <option value="">Select Ticket Type</option>
                                            <option value="Posh" @if (old('ticket_type') == 'Posh') selected @endif>Posh
                                            </option>
                                            <option value="System Repair"
                                                @if (old('ticket_type') == 'System Repair') selected @endif>
                                                System Repair
                                            </option>
                                            <option value="Other Complaint"
                                                @if (old('ticket_type') == 'Other Complaint') selected @endif>Other
                                                Complaint</option>
                                        </select>
                                        <span style="color: red;" id="edit_ticket_type_error"></span>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Subject <span
                                                class="text_danger_require">*</span></label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <input type="text" name="subject" class="form-control"
                                            placeholder="Enter Subject" id="edit_subject">
                                        <span style="color: red;" id="edit_subject_error"></span>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Description <span
                                                class="text_danger_require">*</span></label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <textarea name="description" placeholder="Enter Description" class="form-control" id="edit_description"
                                            cols="10" rows="5">{{ old('description') }}</textarea>
                                        <span style="color: red;" id="edit_description_error"></span>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6 d-none" id="status_div">
                                    <div class="col-md-3"><label class="form-label mb-0">Status</label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status"
                                                id="status">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="submitBtnUpdate"
                                onclick="updateTicket()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- export modal --}}
        <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Export Help</h1>
                        <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="" class="form" action="#">
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Export Format:</label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <select name="format" class="form-control" id="export_format">
                                            <option value="">Select Format</option>
                                            <option value="excel">Excel</option>
                                            <option value="pdf">PDF</option>
                                            <option value="csv">CSV</option>
                                        </select>
                                        <span id="format_error" class="text-danger"></span>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6 d-none" id="status_div">
                                    <div class="col-md-2"><label class="form-label mb-0">Status</label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status"
                                                id="status">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="submitBtnCSV"
                                onclick="exportCSV()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('form').on('submit', function(e) {
            $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i>');
        });
        $(document).ready(function(e) {
            ticketAjax(1);
            $('[data-bs-toggle="tooltip"]').tooltip()
        })

        function ticketAjax(page) {
            var search = $('#search_data').val();
            $.ajax({
                'method': 'get',
                'url': "{{ route('help-ajax') }}",
                data: {
                    search: search,
                    page: page,
                },
                success: function(res) {
                    $('#ticket_table_ajax').html('');
                    $('#ticket_table_ajax').html(res);
                    $("#ticket_table").DataTable({
                        initComplete: function() {
                            var $searchInput = $('#ticket_table_filter input');
                            $searchInput.attr('id', 'ticket_search'); // Assign the ID
                            $searchInput.attr('placeholder', 'Search Help');
                        },
                        lengthChange: false,
                        "order": [
                            [0, 'asc']
                        ],
                        "columnDefs": [{
                            "orderable": false,
                            "targets": 0
                        }]
                    });
                    $('[data-bs-toggle="tooltip"]').tooltip()
                },
            });
        }
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            ticketAjax(page);
        });

        function exportCSV() {
            var exportFile = "{{ route('help-export') }}";
            var format = $('#export_format').val();
            var search = $('#ticket_search').val();
            $('#format_error').html('');
            if (format.trim() == "") {
                $('#format_error').html('Please Select Export Format.');
                return false;
            }
            var allowValues = ['csv', 'excel', 'pdf'];
            if (!allowValues.includes(format)) {
                $('#format_error').html('Please Select Valid Export Format.');
                return false;
            }
            window.open(exportFile + '?format=' + format + '&search=' + search, '_blank');
        }

        document.getElementById('addForm').addEventListener('submit', function(event) {
            event.preventDefault();

            submitForm();
        });

        function submitForm() {
            var Department = $('#department').val();
            var Tycket_type = $('#ticket_type').val();
            var Subject = $('#subject').val();
            var Description = $('#description').val();

            if (Department == '' && Tycket_type == '' && Subject == '' && Description == '') {
                $('#department_error').html('Please Select Department.')
                $('#ticket_type_error').html('Please Select Ticket Type.')
                $('#subject_error').html('Please Enter Subject.')
                $('#description_error').html('Please Enter Description.')
                return false;
            } else if (Department == '' && Tycket_type == '' && Subject == '') {
                $('#department_error').html('Please Select Department.')
                $('#ticket_type_error').html('Please Select Ticket Type.')
                $('#subject_error').html('Please Enter Subject.')
                return false;
            } else if (Department == '' && Tycket_type == '') {
                $('#department_error').html('Please Select Department.')
                $('#ticket_type_error').html('Please Select Ticket Type.')
                return false;
            } else if (Subject == '' && Tycket_type == '') {
                $('#subject_error').html('Please Enter Subject.')
                $('#ticket_type_error').html('Please Select Ticket Type.')
                return false;
            } else if (Description == '' && Tycket_type == '') {
                $('#description_error').html('Please Enter Description.')
                $('#ticket_type_error').html('Please Select Ticket Type.')
                return false;
            } else if (Department == '') {
                $('#department_error').html('Please Select Department.')
                return false;
            } else if (Tycket_type == '') {
                $('#ticket_type_error').html('Please Select Ticket Type.')
                return false;
            } else if (Subject == '') {
                $('#subject_error').html('Please Enter Subject.')
                return false;
            } else if (Description == '') {
                $('#description_error').html('Please Enter Description.')
                return false;
            } else {
                $('#department_error').html('')
                $('#ticket_type_error').html('')
                $('#subject_error').html('')
                $('#description_error').html('')
            }

            $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                url: "{{ route('help.store') }}",
                type: 'POST',
                data: $('#addForm').serialize(),
                success: function(data) {
                    $('#addForm').trigger("reset");
                    $('#AddticketLiquidityModal').modal('hide');
                    toastr.success(data.message);
                    $('#submitBtn').html('submit')
                    ticketAjax(1);
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message)
                    $('#submitBtn').html('submit')
                }
            });
        }

        $('#department').on('change', function() {
            if ($(this).val() != '') {
                $('#department_error').html('');
            }
        });

        $('#ticket_type').on('change', function() {
            if ($(this).val() != '') {
                $('#ticket_type_error').html('');
            }
        });

        $('#subject').on('keyup', function() {
            if ($(this).val() != '') {
                $('#subject_error').html('');
            }
        });

        $('#description').on('keyup', function() {
            if ($(this).val() != '') {
                $('#description_error').html('');
            }
        });

        $('.close-ticket').on('click', function() {
            $('#addForm').trigger("reset");
        });

        document.getElementById('addForm').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                submitForm();
            }
        });

        function editTicket(id) {
            $.ajax({
                url: "{{ route('help.edit', ['help' => 'empid']) }}".replace('empid', id),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#ticket_id').val(id);
                    $('#edit_description').val(data.data.description);
                    $('#edit_ticket_type').val(data.data.ticket_type);
                    $('#edit_subject').val(data.data.subject);
                    $('#edit_department').val(data.data.department_id);
                    $('#status').prop('checked', data.data.status);
                    if (data.data.status == 0) {
                        $('#status').prop('checked', "");
                    }
                    $('#editticketLiquidityModal').modal('show');
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message)
                }
            });
        }

        function deleteTicket(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the delete this help?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('help.destroy', '') }}" + "/" + id,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            toastr.success(data.message);
                            ticketAjax(1);
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message)
                        }
                    });
                }
            });
        }

        document.getElementById('updateForm').addEventListener('submit', function(event) {
            event.preventDefault();

            updateDesignation();
        });

        function updateTicket() {
            var id = $('#ticket_id').val();
            var Department = $('#edit_department').val();
            var Tycket_type = $('#edit_ticket_type').val();
            var Subject = $('#edit_subject').val();
            var Description = $('#edit_description').val();

            if (Department == '' && Tycket_type == '' && Subject == '' && Description == '') {
                $('#edit_department_error').html('Please Select Department.')
                $('#edit_ticket_type_error').html('Please Select Ticket Type.')
                $('#edit_subject_error').html('Please Enter Subject.')
                $('#edit_description_error').html('Please Enter Description.')
                return false;
            } else if (Department == '' && Tycket_type == '' && Subject == '') {
                $('#edit_department_error').html('Please Select Department.')
                $('#edit_ticket_type_error').html('Please Select Ticket Type.')
                $('#edit_subject_error').html('Please Enter Subject.')
                return false;
            } else if (Department == '' && Tycket_type == '') {
                $('#edit_department_error').html('Please Select Department.')
                $('#edit_ticket_type_error').html('Please Select Ticket Type.')
                return false;
            } else if (Subject == '' && Tycket_type == '') {
                $('#edit_subject_error').html('Please Enter Subject.')
                $('#edit_ticket_type_error').html('Please Select Ticket Type.')
                return false;
            } else if (Description == '' && Tycket_type == '') {
                $('#edit_description_error').html('Please Enter Description.')
                $('#edit_ticket_type_error').html('Please Select Ticket Type.')
                return false;
            } else if (Department == '') {
                $('#edit_department_error').html('Please Select Department.')
                return false;
            } else if (Tycket_type == '') {
                $('#edit_ticket_type_error').html('Please Select Ticket Type.')
                return false;
            } else if (Subject == '') {
                $('#edit_subject_error').html('Please Enter Subject.')
                return false;
            } else if (Description == '') {
                $('#edit_description_error').html('Please Enter Description.')
                return false;
            } else {
                $('#edit_department_error').html('')
                $('#edit_ticket_type_error').html('')
                $('#edit_subject_error').html('')
                $('#edit_description_error').html('')
            }

            $('#submitBtnUpdate').html('<i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                url: "{{ route('help.update', ['help' => 'empid']) }}".replace('empid', id),
                type: 'PUT',
                data: $('#updateForm').serialize(),
                success: function(data) {
                    $('#updateForm').trigger("reset");
                    $('#editticketLiquidityModal').modal('hide');
                    toastr.success(data.message);
                    $('#submitBtnUpdate').html('update')
                    ticketAjax(1);
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message)
                    $('#submitBtnUpdate').html('update')
                }
            });
        }

        $('#edit_department').on('change', function() {
            if ($(this).val() != '') {
                $('#edit_department_error').html('');
            }
        });

        $('#edit_ticket_type').on('change', function() {
            if ($(this).val() != '') {
                $('#edit_ticket_type_error').html('');
            }
        });

        $('#edit_subject').on('keyup', function() {
            if ($(this).val() != '') {
                $('#edit_subject_error').html('');
            }
        });

        $('#edit_description').on('keyup', function() {
            if ($(this).val() != '') {
                $('#edit_description_error').html('');
            }
        });

        document.getElementById('updateForm').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                updateTicket();
            }
        });

        function changeTicketStatus(id, status) {
            var text = "close";
            if (status == 1) {
                text = "reopen";
            }
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to " + text + " this help?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('help.status', 'empid') }}".replace('empid', id),
                        type: 'GET',
                        data: {
                            _token: "{{ csrf_token() }}",
                            status: status
                        },
                        success: function(data) {
                            toastr.success(data.message);
                            ticketAjax(1);
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message)
                        }
                    });
                }
            });
        }
    </script>
@endsection
