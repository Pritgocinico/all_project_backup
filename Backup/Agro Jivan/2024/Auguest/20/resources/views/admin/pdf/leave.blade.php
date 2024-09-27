<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leave PDF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <style>
        .table {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <div style="text-align: center">
        <img alt="Logo" src="{{ asset('public/assets/media/svg/AgroJivanLogoDash.png') }}" class="theme-light-show"
            style="width: 150px;" />
    </div>
    <h5 style="text-align: center">Leave List</h5>

    <table class="table" id="kt_table_users">
        <thead>
            <tr class="table">
                <th class="table">Employee Name</th>
                <th class="table">Leave Type</th>
                <th class="table">Leave From</th>
                <th class="table">Leave To</th>
                <th class="table">Leave Feature</th>
                <th class="table">Leave Reason</th>
                <th class="table">Leave Status</th>
                <th class="table">Created AT</th>
                <th class="table">Total Days</th>
            </tr>
        </thead>
        <tbody class="text-gray-600">
            @forelse ($leaveList as $key=>$leave)
                <tr>
                    <td class="table">{{ $leave->userDetail->name }}</td>
                    <td class="table">{{ $leave->leave_type }}</td>
                    <td class="table">{{ Utility::convertMDY($leave->leave_from) }}</td>
                    <td class="table">{{ Utility::convertMDY($leave->leave_to) }}</td>
                    <td class="table">@php
                        $text = 'Half day';
                    @endphp
                        @if ($leave->leave_feature == 1)
                            @php
                                $text = 'Full Day';
                            @endphp
                        @endif
                        {{ $text }}
                    </td>
                    <td class="table" title="{{ $leave->reason }}">
                        @php $reason = $leave->reason @endphp
                        @if (strlen($leave->reason) > 30)
                            @php $reason = substr($leave->reason, 0, 30) . '...'; @endphp
                        @endif
                        {{ $reason }}
                    </td>
                    <td class="table">
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
                                $status = 'info';
                                $text = 'Conditional Approve';
                            @endphp
                        @endif
                        @if ($leave->leave_status == 4)
                            @php
                                $status = 'danger';
                                $text = 'Reject';
                            @endphp
                        @endif
                        @if ($leave->leave_status == 5)
                            @php
                                $status = 'danger';
                                $text = 'Cancel';
                            @endphp
                        @endif
    
                        <span class="badge bg-{{ $status }}">{{ $text }}</span>
                    </td>
    
                    <td class="table">{{ Utility::convertDmyWith12HourFormat($leave->created_at) }}</td>
                    <td class="table">{{ Utility::getDiffBetweenDates($leave->leave_from,$leave->leave_to) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
