<table id="example" class="table align-middle table-row-dashed fs-6 gy-5" style="width:100%">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px">Month</th>
            <th class="min-w-125px">Employee Name</th>
            <th class="min-w-125px">Working Day</th>
            <th class="min-w-125px">Present Day</th>
            <th class="min-w-125px">Leave</th>
            <th class="min-w-125px">Professional Tax</th>
            <th class="min-w-125px">Status</th>
            <th class="min-w-125px">Created At</th>
            <th class="min-w-125px">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($salarySlipList as $salary)
            <tr>
                <td>{{ $salary->month }}</td>
                <td>{{ isset($salary->userDetail)?$salary->userDetail->name:"" }}</td>
                <td>{{ $salary->working_days }}</td>
                <td>{{ $salary->present_days }}</td>
                <td>{{ $salary->leaves }}</td>
                <td>{{ $salary->pt }}</td>
                <td>
                    @php $text = 'Inactive'; $class = 'danger'; @endphp
                    @if ($salary->status == 1)
                        @php $text = 'Active'; $class = 'success'; @endphp
                    @endif
                    <div class="badge badge-light-{{ $class }} fw-bold">
                        {{ $text }}</div>
                </td>
                <td>{{ Utility::convertDmyWith12HourFormat($salary->created_at) }}</td>
                <td>
                    @if (Permission::checkPermission('salary-slip-create'))
                    <button class="btn btn-primary" value="Generate PDF" name="Submit" type="button" onclick="generateSalarySlip({{$salary->id}},{{$salary->userDetail->id}})">Generate</button>
                    @endif
                </td>
            </tr>
        @empty
            <tr class="text-center">
                <td colspan="5">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $salarySlipList->links('pagination::bootstrap-5') }}
</div>
