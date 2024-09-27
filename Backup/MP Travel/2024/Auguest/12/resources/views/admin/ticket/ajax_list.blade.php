<table class="table table-hover table-striped table-sm table-nowrap table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Ticket Id</th>
            <th>User Name / Department</th>
            <th>Department Name</th>
            <th>Ticket Type</th>
            <th>Subject</th>
            <th>Description</th>
            <th>Status</th>
            <th>Create At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($ticketList as $key=>$ticket)
            <tr>
                <td>{{ $ticketList->firstItem() + $key }}</td>
                <td data-bs-toggle="tooltip" data-bs-placement="top" title="View"><a href="{{ route('ticket.show', $ticket->id) }}">{{ $ticket->ticket_id }}</a></td>
                <td>{{ isset($ticket->userDetail) ? $ticket->userDetail->name : '' }} <br />
                    {{ isset($ticket->userDetail->departmentDetail) ? $ticket->userDetail->departmentDetail->name : '' }}
                </td>
                <td>{{ isset($ticket->departmentDetail) ? $ticket->departmentDetail->name : '' }}</td>
                <td>{{ $ticket->ticket_type }}</td>
                @php $subject = $ticket->subject @endphp
                @if (strlen($ticket->subject) > 30)
                    @php $subject = substr($ticket->subject, 0, 30) . '...'; @endphp
                @endif
                <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $ticket->subject }}">{{ $subject }}
                </td>

                @php $description = $ticket->description @endphp
                @if (strlen($ticket->description) > 30)
                    @php $description = substr($ticket->description, 0, 30) . '...'; @endphp
                @endif
                <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $ticket->description }}">{{ $description }}
                </td>
                <td>
                    @php
                        $text = 'Open';
                        $color = 'success';
                        if ($ticket->status == 0) {
                            $color = 'danger';
                            $text = 'Close';
                        }
                    @endphp
                    <span class="badge bg-{{ $color }}">{{ $text }}</span>
                </td>
                <td>{{ Utility::convertDmyAMPMFormat($ticket->created_at) }}</td>
                <td class="text-end">
                    @if (Auth()->user()->role_id == 1)
                    @if ($ticket->status == 0)
                            <a href="javascript:void(0)" class="btn btn-success btn-sm" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Ticket Reopen" onclick="changeTicketStatus({{$ticket->id}},1)"><i class="bi bi-arrow-counterclockwise"></i></a>
                        @else
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Ticket Close" onclick="changeTicketStatus({{$ticket->id}},0)"><i class="bi bi-x"></i></a>
                        @endif
                        <a href="{{ route('ticket.show', $ticket->id) }}" class="btn btn-sm btn-primary"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="View"><i
                                class="bi bi-eye"></i></a>
                    @elseif (collect($accesses)->where('menu_id', 11)->first()->status == 2)
                        <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><button
                                    type="button" class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                        class="bi bi-three-dots"></i></button></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#" onclick="editTicket({{ $ticket->id }})"><i
                                        class="bi bi-pencil me-3"></i>Edit Ticket</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"
                                    onclick="deleteTicket({{ $ticket->id }})"><i class="bi bi-trash me-3"></i>Delete
                                    Ticket </a>
                            </div>
                        </div>
                    @endif
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end me-2 mt-2">
    {{ $ticketList->links() }}
</div>
