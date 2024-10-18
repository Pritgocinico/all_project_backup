<table class="table table-hover table-sm table-nowrap table-scrolling table-responsive mt-6 border"
    id="designation_table">
    <thead>
        <tr>
            <th>No</th>
            <th>Designation Name</th>
            <th>Department Name</th>
            <th>Create By</th>
            <th>Create At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($designationList as $key => $designation)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $designation->name }}</td>
                <td>{{ isset($designation->departmentDetail) ? $designation->departmentDetail->name : '' }}</td>
                <td>{{ isset($designation->userDetail) ? $designation->userDetail->name : '-' }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($designation->created_at) }}</td>
                <td class="text-end">
                    <div class="icon-td">
                        @if (collect($accesses)->where('menu_id', '3')->where('edit',1)->first())
                            <a href="javascript:void(0)" onclick="editDesignation({{ $designation->id }})"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Designation"><i
                                    class="fa-solid fa-pen-to-square"></i></a>
                        @endif
                        @if (collect($accesses)->where('menu_id', '3')->where('delete',1)->first())
                            <a href="javascript:void(0)" onclick="deleteDesignation({{ $designation->id }})"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Designation">
                                <i class="fa fa-trash-can me-3"></i>
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
