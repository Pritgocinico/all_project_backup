<table id="example" class="table align-middle table-row-dashed fs-6 gy-5" style="width:100%">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th>Sr. no</th>
            <th class="min-w-125px">Employee Name </th>
            <th class="min-w-125px">Action</th>
            <th class="min-w-125px">Leave Type</th>
            <th class="min-w-125px">Leave From</th>
            <th class="min-w-125px">Leave To</th>
            <th class="min-w-125px">Total Days</th>
            <th class="min-w-125px">Reason</th>
            <th class="min-w-125px">Leave Status / Reason</th>
            <th class="min-w-125px">Leave Feature</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($leaveList as $key=>$leave)
            <tr>
            <td>{{ $leaveList->firstItem() + $key }}</td>
                <td>
                    @php
                        $user = DB::table('users')
                            ->where('id', $leave->created_by)
                            ->first();
                    @endphp
                    {{ $user->name }}
                    @if (!isset($leave->userDetail))
                        <br />(<span class="text-danger">User Deleted</span>)
                    @endif
                </td>
                <td>
                    @if (Permission::checkPermission('leave-edit'))   
                    @php
                        $approve = '';
                        $cancel = '';
                    @endphp
                    @if ($leave->leave_status == 2)
                        @php $approve = "d-none" @endphp
                    @endif
                    @if ($leave->leave_status == 3)
                        @php $cancel = "d-none" @endphp
                    @endif
                    <a class="btn btn-icon btn-success w-30px h-30px me-3 {{ $approve }}" href="#"
                        onclick="updateLeaveStatus('approve',{{ $leave->id }})" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Leave Approve">
                        <i class="fa fa-check"></i>
                    </a>
                    <a class="btn btn-icon btn-danger w-30px h-30px me-3 {{ $cancel }}" href="#"
                        onclick="rejectLeaveStatus('reject',{{ $leave->id }})" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Leave Reject">
                        <i class="fa fa-close"></i>
                    </a>
                    @endif
                </td>
                <td>{{ $leave->leave_type }}</td>
                <td>{{ Utility::convertMDY($leave->leave_from) }}</td>
                <td>{{ Utility::convertMDY($leave->leave_to) }}</td>
                <td>{{ Utility::getDiffBetweenDates($leave->leave_from, $leave->leave_to) }}</td>
                <td>
                    @php $reason = $leave->reason @endphp
                    @if (strlen($leave->reason) > 30)
                        @php $reason = substr($leave->reason, 0, 30) . '...'; @endphp
                    @endif
                    <span data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $leave->reason }}">{{ $reason }}</span>
                </td>
                <td>
                    @php
                        $text = 'Pending';
                        $class = 'warning';
                    @endphp
                    @if ($leave->leave_status == '2')
                        @php
                            $text = 'Approved';
                            $class = 'success';
                        @endphp
                    @endif
                    @if ($leave->leave_status == '3')
                        @php
                            $text = 'Rejected';
                            $class = 'danger';
                        @endphp
                    @endif
                    <div class="badge badge-{{ $class }} fw-bold">
                        {{ $text }}</div>
                    <br />
                    {{ $leave->reject_reason }}
                </td>
                <td>@php $text = 'Half'; @endphp
                    @if ($leave->leave_feature == 1)
                        @php $text = 'Full'; @endphp
                    @endif
                    {{ $text }} Day
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
    {{ $leaveList->links('pagination::bootstrap-5') }}
</div>
