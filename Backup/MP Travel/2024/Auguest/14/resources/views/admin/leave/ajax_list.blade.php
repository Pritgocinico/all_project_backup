<table class="table table-hover table-sm table-nowrap table-responsive mt-6 border" id="leave_table_ajax_list">
    <thead>
        <tr>
            <th class="">#</th>
            <th>Employee Name</th>
            <th>Leave Type</th>
            <th>Leave From</th>
            <th>Leave To</th>
            <th>Leave Status</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($leaveList as $key=>$leave)
            <tr>
                <td>{{ $leaveList->firstItem() + $key }}</td>
                <td class="">
                    @if (isset($leave->userDetail))
                        <a class="text-capitalize"
                            href="{{ route('leave.show', $leave->id) }}">{{ ucfirst($leave->userDetail->name) }}</a>
                    @else
                        -
                    @endif
                </td>

                <td>
                    {{ ucwords(str_replace('_', ' ', $leave->leave_type)) }}
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($leave->leave_from)->format('d-m-Y') }}
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($leave->leave_to)->format('d-m-Y') }}
                </td>
                <td>
                    @php
                        $status = 'Pending';
                        $class = 'warning';
                        $reason = '';
                        if ($leave->leave_status == 1) {
                            $status = 'Approved';
                            $class = 'success';
                        } elseif ($leave->leave_status == 2) {
                            $status = 'Rejected';
                            $class = 'danger';
                            $reason = $leave->reject_reason;
                            if (strlen($leave->reject_reason) > 30) {
                                $reason = substr($leave->reject_reason, 0, 30) . '...';
                            }
                        }
                    @endphp
                    <span class="badge bg-{{ $class }} w-120">{{ $status }}</span><br />
                    <span data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $leave->reject_reason }}">{{ $reason }}</span>

                </td>
                <td class="text-end action">
                    @if (collect($accesses)->where('menu_id', '9')->first()->status == 2)
                        <a href="{{ route('leave.show', $leave->id) }}" class="text-dark" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="View Leave"><i class="bi bi-eye"></i></a>
                    @endif
                    @if (Auth()->user()->role_id == 1)
                        <a href="javascript:void(0)" class="text-dark @if ($leave->leave_status == 1) d-none @endif"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Approve Leave"
                            onclick="approveLeave({{ $leave->id }})"><i class="bi bi-check"></i></a>
                        <a href="javascript:void(0)" class="text-dark @if ($leave->leave_status == 2) d-none @endif"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Reject Leave"
                            onclick="rejectLeave({{ $leave->id }})"><i class="bi bi-x"></i></a>
                    @else
                        @if (collect($accesses)->where('menu_id', '9')->first()->status == 2)
                            <a href="{{ route('leave.edit', $leave->id) }}" class="text-dark" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Edit Leave"><i class="bi bi-pencil-square"></i></a>
                            <a href="javascript:void(0)" class="text-dark" onclick="deleteLeave({{ $leave->id }})"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Leave"><i
                                    class="bi bi-trash"></i></a>
                        @endif
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
    {{ $leaveList->links() }}
</div>
