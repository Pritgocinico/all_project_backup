<table class="table table-hover table-sm table-nowrap table-scrolling table-responsive mt-6 border" id="department_table">
    <thead>
        <tr>
            <th>No</th>
            <th>Department Name</th>
            <th>Created By</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($departList as $key => $depart)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $depart->name }}</td>
                <td>{{ isset($depart->userDetail) ? $depart->userDetail->name : '-' }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($depart->created_at) }}</td>
                <td class="text-end">
                    <div class="icon-td">
                        @if (collect($accesses)->where('menu_id', '2')->where('edit', 1)->first())
                            <a href="javascript:void(0)" class="text-dark" onclick="editDepartment({{ $depart->id }})"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Department">
                                <i class="fa-solid fa-pen-to-square"></i></a>
                        @endif
                        @if (collect($accesses)->where('menu_id', '2')->where('delete', 1)->first())
                            <a href="javascript:void(0)" class="text-dark"
                                onclick="deleteDepartment({{ $depart->id }})" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Delete Department">
                                <i class="fa fa-trash-can me-3"></i>
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
