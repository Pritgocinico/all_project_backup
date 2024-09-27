<table class="table table-hover table-striped table-sm table-nowrap table-responsive">
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
                <td>{{ $salarySlipList->firstItem() + $key }}</td>
                <td>
                    @if($salary->employeeDetail && collect($accesses)->where('menu_id', '5')->first()->status == 2)
                        <a href="{{ route('user.show', $salary->employeeDetail->id) }}">
                            {{ $salary->employeeDetail->name }}</a>
                    @else
                        {{'-'}}
                    @endif
                </td>
                <td>{{ $salary->month }}</td>
                <td>{{ $salary->year }}</td>
                <td>{{ $salary->total_working_days }}</td>
                <td>{{ $salary->present_days }}</td>
                <td>{{ $salary->payable_salary }}</td>
                <td>{{ $salary->leave }}</td>
                <td>
                    @if (collect($accesses)->where('menu_id', '15')->first()->status == 2)
                        <a href="#" onclick="generateSlip({{ $salary->id }})"
                            class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-2"></i>
                            Download</a>
                        <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#"
                                role="button" data-bs-toggle="dropdown" aria-haspopup="false"
                                aria-expanded="false"><button type="button"
                                    class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                        class="bi bi-three-dots"></i></button></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item"
                                    href="{{ route('salary-slip.edit', $salary->id) }}"><i
                                        class="bi bi-pencil me-3"></i>Edit Salary Slip</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"
                                    onclick="deleteSalarySlip({{ $salary->id }})"><i
                                        class="bi bi-trash me-3"></i>Delete Salary Slip</a>
                            </div>
                        </div>
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
<div class="d-flex justify-content-end me-2 mt-2">
    {{ $salarySlipList->links() }}
</div>