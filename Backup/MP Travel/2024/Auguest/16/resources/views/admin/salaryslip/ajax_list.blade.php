<table class="table table-hover table-sm table-nowrap table-responsive mt-6 border" id="salary_slip_table_list">
    <thead>
        <tr>
            <th>#</th>
            <th>Employee Name</th>
            <th>Month</th>
            <th>Year</th>
            <th>Working Days</th>
            <th>Present Days</th>
            <th>Payable Salary</th>
            <th>Leave</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($salarySlipList as $key=>$salary)
            <tr>
                <td>{{ $key +1 }}</td>
                <td>
                    @if ($salary->employeeDetail && collect($accesses)->where('menu_id', '5')->first()->status == 2)
                        <a href="{{ route('user.show', $salary->employeeDetail->id) }}">
                            {{ $salary->employeeDetail->name }}</a>
                    @else
                        {{ $salary->employeeDetail->name }}
                    @endif
                </td>
                <td>{{ $salary->month }}</td>
                <td>{{ $salary->year }}</td>
                <td>{{ $salary->total_working_days }}</td>
                <td>{{ $salary->present_days }}</td>
                <td>{{ $salary->payable_salary }}</td>
                <td>{{ $salary->leave }}</td>
                <td class="text-end">
                    <a href="#" onclick="generateSlip({{ $salary->id }})"><i class="bi bi-download"></i></a>
                    @if (collect($accesses)->where('menu_id', '15')->first()->status == 2)
                        <a href="{{ route('salary-slip.edit', $salary->id) }}" class="text-dark"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Salary Slip"><i
                                class="bi bi-pencil-square"></i></a>
                        <a href="javascript:void(0)" class="text-dark" onclick="deleteSalarySlip({{ $salary->id }})"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Salary Slip"><i
                                class="bi bi-trash"></i></a>
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
