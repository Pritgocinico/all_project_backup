<table class="table table-hover table-sm table-scrolling  table-nowrap table-responsive mt-6 border" id="ticket_table">
    <thead>
        <tr>
            <th>#</th>
            <th>Ticket Id</th>
            <th>User / Department Name</th>
            <th>Ticket Raised For Department</th>
            <th>Ticket Type</th>
            <th>Status</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ticketList as $key=>$ticket)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td data-bs-toggle="tooltip" data-bs-placement="top" title="View"><a
                        href="{{ route('ticket.show', $ticket->id) }}">{{ $ticket->ticket_id }}</a></td>
                <td>{{ isset($ticket->userDetail) ? $ticket->userDetail->name : '' }} <br />
                    {{ isset($ticket->userDetail->departmentDetail) ? $ticket->userDetail->departmentDetail->name : '' }}
                </td>
                <td>{{ isset($ticket->departmentDetail) ? $ticket->departmentDetail->name : '' }}</td>
                <td>{{ $ticket->ticket_type }}</td>
                <td>
                    @php
                        $text = 'Open';
                        $color = 'success';
                        if ($ticket->status == 0) {
                            $color = 'danger';
                            $text = 'Close';
                        }
                    @endphp
                    <span class="badge bg-{{ $color }} w-120">{{ $text }}</span>
                </td>
                <td class="text-end">
                <div class="icon-td">
               
                    @if (Auth()->user()->role_id == 1)
                        @if ($ticket->status == 0)
                            <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Reopen Ticket" onclick="changeTicketStatus({{ $ticket->id }},1)"><i class="fa-solid fa-rotate"></i>
                        @else
                            <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Close Ticket" onclick="changeTicketStatus({{ $ticket->id }},0)"><i
                                    class="fa fa-x"></i></a>
                        @endif
                        <a href="{{ route('ticket.show', $ticket->id) }}" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="View Ticket"><i class="fa fa-eye"></i></a>
                    @elseif (collect($accesses)->where('menu_id', 9)->first()->status == 2)
                        <a href="javascript:void(0)" onclick="editTicket({{ $ticket->id }})" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Edit Ticket"><i class="fa-solid fa-pen-to-square me-3"></i></a>
                        <a href="javascript:void(0)" onclick="deleteTicket({{ $ticket->id }})"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Ticket"><i
                                class="fa fa-trash-can"></i></a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>




<div class="icon-td">







