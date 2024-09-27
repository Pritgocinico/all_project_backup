<table class="table table-hover table-striped table-sm table-nowrap table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Employee Name</th>
            <th>Leave Type</th>
            <th>Leave From</th>
            <th>Leave To</th>
            <th>Reason</th>
            <th>Leave Statue</th>
            <th>Leave Feature</th>
            <th>Total Leave Day</th>
            <th>Attachment</th>
            <th>Created At</th>
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
                            href="{{ route('user.show', $leave->userDetail->id) }}">{{ ucfirst($leave->userDetail->name) }}</a>
                </td>
            @else
                -
        @endif
        <td>
            {{ $leave->leave_type }}
        </td>
        <td>
            {{ \Carbon\Carbon::parse($leave->leave_from)->format('d-m-Y') }}
        </td>
        <td>
            {{ \Carbon\Carbon::parse($leave->leave_to)->format('d-m-Y') }}
        </td>
        <td>
            {{ $leave->reason }}
        </td>
        <td>
            @php
                $status = 'pending';
                $class = 'warning';
                if ($leave->leave_status == 1) {
                    $status = 'Approved';
                    $class = 'success';
                } elseif ($leave->leave_status == 2) {
                    $status = 'Rejected';
                    $class = 'danger';
                }
            @endphp
            <span class="badge bg-{{ $class }}">{{ $status }}</span>
        </td>
        <td>
            @php
                $text = 'Full Day';
                $class = 'warning';
                if ($leave->leave_feature == 0) {
                    $text = 'Half Day';
                    $class = 'success';
                }
            @endphp
            <span class="badge bg-{{ $class }}">{{ $text }}</span>
        </td>
        <td>{{ $leave->total_leave_day }}</td>
        <td>
            @if (isset($leave->attachment))
                <a href="{{ Storage::url($leave->attachment) }}" download><img
                        src="{{ url('assets/img/user/file.png') }}" width="60px"></a>
            @endif
        </td>
        <td>{{ Utility::convertDmyAMPMFormat($leave->created_at) }}</td>
        <td class="text-end">
            @if (Auth()->user()->role_id == 1)
                <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><button
                            type="button" class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                class="bi bi-three-dots"></i></button></a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item @if ($leave->leave_status == 1) d-none @endif"
                            href="javascript:void(0)" onclick="approveLeave({{ $leave->id }})"><i
                                class="bi bi-pencil me-3"></i>Approve Leave</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item @if ($leave->leave_status == 2) d-none @endif"
                            href="javascript:void(0)" onclick="rejectLeave({{ $leave->id }})"><i
                                class="bi bi-trash me-3"></i>Reject Leave</a>
                    </div>
                </div>
            @else
                @if (collect($accesses)->where('menu_id', '9')->first()->status == 2)
                    <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#"
                            role="button" data-bs-toggle="dropdown" aria-haspopup="false"
                            aria-expanded="false"><button type="button"
                                class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                    class="bi bi-three-dots"></i></button></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{ route('leave.edit', $leave->id) }}"><i
                                    class="bi bi-pencil me-3"></i>Edit Leave</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:void(0)"
                                onclick="deleteLeave({{ $leave->id }})"><i
                                    class="bi bi-trash me-3"></i>Delete Leave</a>
                        </div>
                    </div>
                @endif
            @endif

        </td>

        </tr>
    @empty
        <tr>
            <td colspan="12" class="text-center">No Data Available.</td>
        </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end me-2 mt-2">
    {{ $leaveList->links() }}
</div>