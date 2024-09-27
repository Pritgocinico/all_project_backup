<table class="table table-hover table-sm table-nowrap table-responsive mt-6 border" id="department_table">
    <thead>
        <tr>
            <th>#</th>
            <th>Department Name</th>
            <th>Created By</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($departList as $key=>$depart)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $depart->name }}</td>
            <td>{{ isset($depart->userDetail) ? $depart->userDetail->name :"-"}}</td>
            <td>{{Utility::convertDmyAMPMFormat($depart->created_at)}}</td>
            <td class="text-end">
                @if (collect($accesses)->where('menu_id', '2')->first()->status == 2)
                <a href="javascript:void(0)" class="text-dark" onclick="editDepartment({{ $depart->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Department"><i
                        class="bi bi-pencil-square me-3"></i></a>
                <a href="javascript:void(0)" class="text-dark" onclick="deleteDepartment({{ $depart->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Department"><i
                        class="bi bi-trash me-3"></i></a>
                @endif
            </td>

        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">No Data Available.</td>
        </tr>
        @endforelse
    </tbody>
</table>