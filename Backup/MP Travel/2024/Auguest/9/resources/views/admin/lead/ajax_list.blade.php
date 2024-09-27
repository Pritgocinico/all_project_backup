<table class="table table-hover table-striped table-sm table-nowrap table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Lead</th>
            <th>Customer Name</th>
            <th>Lead Amount</th>
            <th>Status</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($leads as $key=>$lead)
            <tr>
                <td>{{ $leads->firstItem() + $key }}</td>
                <td><a href="{{ route('leads.show', $lead->id) }}">{{ $lead->lead_id }}</a></td>
                <td>{{ isset($lead->customerDetail) ? $lead->customerDetail->name : '-' }}</td>
                <td>&#x20B9; {{ number_format($lead->lead_amount ?? 0,2) }}</td>
                <td>
                    @php
                        $status = 'warning';
                        $text = 'Pending Lead';
                        if ($lead->lead_status == 2) {
                            $status = 'info';
                            $text = 'Assigned Lead';
                        }
                        if ($lead->lead_status == 3) {
                            $status = 'secondary';
                            $text = 'Hold Lead';
                        }
                        if ($lead->lead_status == 4) {
                            $status = 'success';
                            $text = 'Complete Lead';
                        }
                        if ($lead->lead_status == 5) {
                            $status = 'warning';
                            $text = 'Extends Lead';
                        }
                        if ($lead->lead_status == 6) {
                            $status = 'danger';
                            $text = 'Cancel Lead';
                        }
                    @endphp
                    <span class="badge bg-{{ $status }}">{{ $text }}</span>
                </td>
                <td>{{ Utility::convertDmyAMPMFormat($lead->created_at) }}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '18')->first()->status == 2)
                        <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#"
                                role="button" data-bs-toggle="dropdown" aria-haspopup="false"
                                aria-expanded="false"><button type="button"
                                    class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                        class="bi bi-three-dots"></i></button></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('leads.edit', $lead->id) }}"><i
                                        class="bi bi-pencil me-3"></i>Edit lead</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"
                                    onclick="deletelead({{ $lead->id }})"><i
                                        class="bi bi-trash me-3"></i>Delete
                                    Lead </a>
                            </div>
                        </div>
                    @endif
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end me-2 mt-2">
    {{ $leads->links() }}
</div>