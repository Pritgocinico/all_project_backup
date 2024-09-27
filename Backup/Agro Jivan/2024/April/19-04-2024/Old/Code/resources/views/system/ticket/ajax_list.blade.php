<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px">Ticket Type</th>
            <th class="min-w-125px">Subject</th>
            <th class="min-w-125px">Description</th>
            <th class="min-w-100px">Status</th>
            <th class="min-w-100px">Created By</th>
            <th class="min-w-100px">Created AT</th>
            <th class="min-w-100px">Action</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($ticketList as $key=>$ticket)
            <tr>
                <td>
                    @if (Permission::checkPermission('engineer-ticket-view'))
                        <a href="{{ route('engineer-ticket-view', $ticket->id) }}" class="pre-agro-emp">{{ $ticket->ticket_type }}</a>
                    @else
                        {{ $ticket->ticket_type }}
                    @endif
                </td>
                <td>{{ $ticket->subject }}</td>
                @php $description = $ticket->description @endphp
                @if (strlen($ticket->description) > 30)
                    @php $description = substr($ticket->description, 0, 30) . '...'; @endphp
                @endif
                <td title="{{ $ticket->description }}">{{ $description }}</td>
                <td>@php
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
                    @if (Permission::checkPermission('engineer-ticket-view'))
                        <a href="{{ route('engineer-ticket-view', $ticket->id) }}"
                            class="btn btn-primary btn-active-light-primary" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="View Ticket">View Ticket</a>
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
<div class="d-flex justify-content-end">
    {{ $ticketList->links('pagination::bootstrap-5') }}
</div>
