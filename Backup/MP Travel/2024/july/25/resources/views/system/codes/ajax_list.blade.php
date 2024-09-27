<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="">ID</th>
            <th class="">Syestem Code</th>
            <th class="">Employee Name</th>
            <th class="">Employee Role</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
         @forelse ($SystemCodeList as $key => $emp)
                <tr>
                <td>{{ $SystemCodeList->firstItem() + $key}}</td>
                    <td> <a href="{{ route('engineer-ticket', $emp->id) }}" class="pre-agro-emp">{{  $emp->employee_code }}</a></td>
                    <td>{{ $emp->name }}</td>
                    <td>{{ isset($emp->roleDetail)?$emp->roleDetail->name:"" }}</td>
                </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $SystemCodeList->links('pagination::bootstrap-5') }}
</div>
