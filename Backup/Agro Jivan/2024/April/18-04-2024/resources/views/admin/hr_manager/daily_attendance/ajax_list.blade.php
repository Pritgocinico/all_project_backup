<table id="example" class="table align-middle table-row-dashed fs-6 gy-5" style="width:100%">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th>Sr. no</th>
            <th>User</th>
            <th>Status</th>
            <th>Login Time</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($attendanceList as $key=>$employee)
            <tr>
            <td>{{ $attendanceList->firstItem() + $key }}</td>
                <td>
                    <a href="{{ route('employees.show', $employee->userDetail->id) }}"
                        class="text-primary">{{ isset($employee->userDetail) ? $employee->userDetail->name : '' }}</a>
                </td>
                <td>
                    @php
                        $text = 'Absent';
                        $class = 'danger';
                    @endphp
                    @if ($employee->status == '1')
                        @php
                            $text = 'Present';
                            $class = 'primary';
                        @endphp
                    @endif
                    @if ($employee->status == '2')
                        @php
                            $text = 'Half Day';
                            $class = 'warning';
                        @endphp
                    @endif
                    <span class="badge bg-{{ $class }}">{{ $text }}</span>
                </td>
                <td>
                    @php $date ="-" @endphp
                    @if($employee->login_time !== null)
                        @php $date =Utility::convertDmyWithAMPMFormat($employee->login_time) @endphp
                    @endif
                    {{ $date }}
                </td>
            </tr>
        @empty
            <tr class="text-center">
                <td colspan="3">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $attendanceList->links('pagination::bootstrap-5') }}
</div>
