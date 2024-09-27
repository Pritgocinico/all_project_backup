<table id="example" class="table align-middle table-row-dashed fs-6 gy-5" style="width:100%">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px">Ticket Type</th>
            <th class="min-w-125px">Subject</th>
            <th class="min-w-125px">Description</th>
            <th class="min-w-125px">Status</th>
            <th class="min-w-125px">Created By</th>
            <th class="min-w-125px">Created At</th>
            <td class="min-w-125px">Action</td>
        </tr>
    </thead>
    <tbody>
        @forelse ($ticketList as $ticket)
            <tr>
                <td>
                    @if (Permission::checkPermission('ticket-view'))
                    <a href="{{ route('hr-ticket-view', $ticket->id) }}">
                        {{ $ticket->ticket_type }}</a>
                    @else
                    {{ $ticket->ticket_type }}
                    @endif
                </td>
                <td>{{ $ticket->subject }}</td>
                <td>
                    @php $reason = $ticket->description @endphp
                    @if (strlen($ticket->description) > 30)
                        @php $reason = substr($ticket->description, 0, 30) . '...'; @endphp
                    @endif
                    <span data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $ticket->description }}">{{ $reason }}</span>
                </td>
                <td>
                    @php
                        $text = 'Inactive';
                        $class = 'danger';
                    @endphp
                    @if ($ticket->status == 1)
                        @php
                            $text = 'Active';
                            $class = 'success';
                        @endphp
                    @endif
                    <div class="badge badge-light-{{ $class }} fw-bold">
                        {{ $text }}</div>
                </td>
                <td>
                    @php
                        $user = DB::table('users')
                            ->where('id', $ticket->created_by)
                            ->first();
                    @endphp
                    {{ $user->name }}
                    @if (!isset($ticket->userDetail))
                        <br />(<span class="text-danger">User Deleted</span>)
                    @endif
                </td>
                <td>{{ Utility::convertDmyWith12HourFormat($ticket->created_at) }}</td>
                <td>
                    @if (Permission::checkPermission('ticket-view'))   
                    <a href="{{ route('hr-ticket-view', $ticket->id) }}"
                        class="btn btn-primary btn-active-light-primary">View Ticket</a>
                    @endif
                    </td>
            </tr>
        @empty
            <tr class="text-center">
                <td colspan="10">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $ticketList->links('pagination::bootstrap-5') }}
</div>
