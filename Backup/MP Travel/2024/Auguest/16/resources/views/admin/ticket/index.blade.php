@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Ticket</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if(Auth()->user()->role_id !== 2)
                        <a href="#" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_export_users" data-bs-toggle="modal"><i
                                class="bi bi-plus-lg me-2"></i>
                            Export</a>
                            @endif
                        @if (collect($accesses)->where('menu_id', '11')->first() !== null &&
                                collect($accesses)->where('menu_id', '11')->first()->status == 2 &&
                                Auth()->user()->role_id !== 1)
                            <a href="#" class="btn btn-sm btn-dark" data-bs-target="#ticketLiquidityModal"
                                data-bs-toggle="modal"><i class="bi bi-plus-lg me-2"></i>
                                New Ticket</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="px-6 px-lg-7 pt-6">
                <div id="ticket_table_ajax" class="mt-6 table-responsive custom-scrollbar">
                </div>
            </div>
        </main>
        <div class="modal fade" id="ticketLiquidityModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Add Ticket</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="vstack" method="POST" id="addForm">
                        @csrf
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Department</label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <select name="department" class="form-control" id="department">
                                            <option value="">Select Department</option>
                                            @foreach ($departmentList as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Ticket Type</label></div>
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
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Subject</label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <input type="text" name="subject" class="form-control"
                                            placeholder="Enter Subject" id="subject">
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Description</label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <textarea name="description" placeholder="Enter Description" class="form-control" id="description" cols="10"
                                            rows="5">{{ old('description') }}</textarea>
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
                            <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="submitBtn"
                                onclick="submitForm()">Submit</button>
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
                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Export Ticket</h1>
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
                            <button type="button" class="btn btn-primary" id="submitBtn"
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
        $(document).ready(function(e) {
            ticketAjax(1);
            $('[data-bs-toggle="tooltip"]').tooltip()
        })

        function ticketAjax(page) {
            var search = $('#search_data').val();
            $.ajax({
                'method': 'get',
                'url': "{{ route('ticket-ajax') }}",
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
                            $searchInput.attr('placeholder', 'Search Ticket');
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
            var exportFile = "{{ route('ticket-export') }}";
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

        function submitForm() {
            showLoader('submitBtn')
            $.ajax({
                url: "{{ route('ticket.store') }}",
                type: 'POST',
                data: $('#addForm').serialize(),
                success: function(data) {
                    $('#addForm').trigger("reset");
                    $('#depositLiquidityModal').modal('hide');
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#submitBtn').html('submit')
                            ticketAjax(1);
                        }
                    });
                },
                error: function(error) {
                    Swal.fire({
                        title: 'error!',
                        text: error.responseJSON.message,
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok'
                    })
                }
            });
        }

        function editTicket(id) {
            $.ajax({
                url: "{{ route('ticket.edit', ['ticket' => 'empid']) }}".replace('empid', id),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#name').val(data.data.name);
                    $('#description').val(data.data.description);
                    $('#ticket_type').val(data.data.ticket_type);
                    $('#subject').val(data.data.subject);
                    $('#department').val(data.data.department_id);
                    $('#status').prop('checked', data.data.status);
                    if (data.data.status == 0) {
                        $('#status').prop('checked', "");
                    }
                    $('#status_div').removeClass('d-none');
                    $('#depositLiquidityModalLabel').text('Edit Ticket');
                    $('#submitBtn').text('Update');
                    $('#submitBtn').attr('onclick', "updateTicket(" + id + ")");
                    $('#ticketLiquidityModal').modal('show');
                },
                error: function(error) {
                    Swal.fire({
                        title: 'error!',
                        text: error.responseJSON.message,
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok'
                    })
                }
            });
        }

        function deleteTicket(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the delete this Ticket?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('ticket.destroy', '') }}" + "/" + id,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    ticketAjax(1);
                                }
                            });
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'error!',
                                text: error.responseJSON.message,
                                icon: 'error',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            })
                        }
                    });
                }
            });
        }

        function updateTicket(id) {
            showLoader('submitBtn')

            $.ajax({
                url: "{{ route('ticket.update', ['ticket' => 'empid']) }}".replace('empid', id),
                type: 'PUT',
                data: $('#addForm').serialize(),
                success: function(data) {
                    $('#addForm').trigger("reset");
                    $('#depositLiquidityModal').modal('hide');
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#submitBtn').html('update')
                            ticketAjax(1);
                        }
                    });
                },
                error: function(error) {
                    Swal.fire({
                        title: 'error!',
                        text: error.responseJSON.message,
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok'
                    })
                }
            });
        }

        function changeTicketStatus(id, status) {
            var text = "close";
            if (status == 1) {
                text = "reopen";
            }
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to " + text + " this ticket?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('ticket.status', 'empid') }}".replace('empid', id),
                        type: 'GET',
                        data: {
                            _token: "{{ csrf_token() }}",
                            status: status
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    ticketAjax(1);
                                }
                            });
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'error!',
                                text: error.responseJSON.message,
                                icon: 'error',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            })
                        }
                    });
                }
            });
        }
    </script>
@endsection
