<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-100px">Leave Type</th>
            <th class="min-w-100px">Leave From</th>
            <th class="min-w-100px">Leave To</th>
            <th class="min-w-100px">Leave Feature</th>
            <th class="min-w-300px">Leave Reason</th>
            <th class="min-w-100px">Leave Status / Reason</th>
            <th class="min-w-100px">Created AT</th>
            <th class="min-w-50px">Total Days</th>
            <th class="min-w-100px">Action</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($leaveList as $key=>$leave)
            <tr>
                <td>{{ $leave->leave_type }}</td>
                <td>{{ Utility::convertMDY($leave->leave_from) }}</td>
                <td>{{ Utility::convertMDY($leave->leave_to) }}</td>
                <td>@php
                    $text = 'Half day';
                @endphp
                    @if ($leave->leave_feature == 1)
                        @php
                            $text = 'Full Day';
                        @endphp
                    @endif
                    {{ $text }}
                </td>
                <td title="{{ $leave->reason }}">
                    @php $reason = $leave->reason @endphp
                    @if (strlen($leave->reason) > 30)
                        @php $reason = substr($leave->reason, 0, 30) . '...'; @endphp
                    @endif
                    {{ $reason }}
                </td>
                <td>
                    @php
                        $status = 'warning';
                        $text = 'Pending';
                    @endphp
                    @if ($leave->leave_status == 2)
                        @php
                            $status = 'success';
                            $text = 'Approve';
                        @endphp
                    @endif
                    @if ($leave->leave_status == 3)
                        @php
                            $status = 'danger';
                            $text = 'Rejected';
                        @endphp
                    @endif
                    <span class="badge bg-{{ $status }}">{{ $text }}</span>
                    <br />
                    {{ $leave->reject_reason }}
                </td>

                <td>{{ Utility::convertDmyWith12HourFormat($leave->created_at) }}</td>
                <td>{{ Utility::getDiffBetweenDates($leave->leave_from, $leave->leave_to) }}</td>
                <td>
                    @if (Permission::checkPermission('leave-edit'))
                        <a class="btn btn-icon btn-info w-30px h-30px me-3" href="#"
                            onclick="editLeave({{ $leave->id }})" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Edit Leave">
                            <i class="fa-solid fa-edit"></i>
                        </a>
                    @endif
                    @if (Permission::checkPermission('leave-delete'))
                        <a class="btn btn-icon btn-info w-30px h-30px me-3" href="#"
                            onclick="deleteLeave({{ $leave->id }})" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Delete Leave">
                            <i class="fa-solid fa-trash"></i>
                        </a>
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
    {{ $leaveList->links('pagination::bootstrap-5') }}
</div>
