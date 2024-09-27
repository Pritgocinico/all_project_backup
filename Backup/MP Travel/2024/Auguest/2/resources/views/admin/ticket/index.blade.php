@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Ticket</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if (collect($accesses)->where('menu_id', '11')->first() !== null && collect($accesses)->where('menu_id', '11')->first()->status == 2)
                            <a href="#" class="btn btn-sm btn-primary" data-bs-target="#ticketLiquidityModal"
                                data-bs-toggle="modal"><i class="bi bi-plus-lg me-2"></i>
                                New Ticket</a>
                        @endif
                    </div>
                </div>
            </div>
            <div>
                <table class="table table-hover table-striped table-sm table-nowrap table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ticket Id</th>
                            <th>User Name</th>
                            <th>Department Name</th>
                            <th>Ticket Type</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Create At</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ticketList as $key=>$ticket)
                            <tr>
                                <td>{{ $ticketList->firstItem() + $key }}</td>
                                <td><a href="{{ route('ticket.show', $ticket->id) }}">{{ $ticket->ticket_id }}</a></td>
                                <td>{{ isset($ticket->userDetail) ? $ticket->userDetail->name : '' }}</td>
                                <td>{{ isset($ticket->departmentDetail) ? $ticket->departmentDetail->name : '' }}
                                <td>{{ $ticket->ticket_type }}</td>
                                @php $subject = $ticket->subject @endphp
                                @if (strlen($ticket->subject) > 30)
                                    @php $subject = substr($ticket->subject, 0, 30) . '...'; @endphp
                                @endif
                                <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $ticket->subject }}">{{ $subject }}</td>

                                @php $description = $ticket->description @endphp
                                @if (strlen($ticket->description) > 30)
                                    @php $description = substr($ticket->description, 0, 30) . '...'; @endphp
                                @endif
                                <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $ticket->description }}">{{ $description }}</td>
                                <td>
                                    @php
                                        $text = 'Open';
                                        $color = 'danger';
                                        if ($ticket->status == 0) {
                                            $color = 'success';
                                            $text = 'Close';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ $text }}</span>
                                </td>
                                <td>{{ Utility::convertDmyAMPMFormat($ticket->created_at) }}</td>
                                <td class="text-end">
                                    @if (Auth()->user()->role_id == 1)
                                        <a href="{{ route('ticket.show', $ticket->id) }}" class="btn btn-sm btn-primary"><i
                                                class="bi bi-plus-lg me-2"></i>
                                            View Ticket</a>
                                    @elseif (collect($accesses)->where('menu_id', 11)->first()->status == 2)
                                        <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#"
                                                role="button" data-bs-toggle="dropdown" aria-haspopup="false"
                                                aria-expanded="false"><button type="button"
                                                    class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                                        class="bi bi-three-dots"></i></button></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#"
                                                    onclick="editTicket({{ $ticket->id }})"><i
                                                        class="bi bi-pencil me-3"></i>Edit Ticket</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                    onclick="deleteTicket({{ $ticket->id }})"><i
                                                        class="bi bi-trash me-3"></i>Delete Ticket </a>
                                            </div>
                                        </div>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No Data Available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end me-2 mt-2">
                    {{ $ticketList->links() }}
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
                                            <option value="Other Complaint "
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
                                            <input class="form-check-input" type="checkbox" name="status"
                                                id="status">
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
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip()    
        });
        function submitForm() {
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
                            location.reload();
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
                                    location.reload();
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
                            location.reload();
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
    </script>
@endsection
