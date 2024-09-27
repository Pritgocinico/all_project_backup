<table class="table table-hover table-striped table-sm table-nowrap table-responsive mt-6 border" id="department_table">
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
            <td>{{ $departList->firstItem() + $key }}</td>
            <td>{{ $depart->name }}</td>
            <td>{{ isset($depart->userDetail) ? $depart->userDetail->name :"-"}}</td>
            <td>{{Utility::convertDmyAMPMFormat($depart->created_at)}}</td>
            <td class="text-end">
                @if (collect($accesses)->where('menu_id', '2')->first()->status == 2)
                <a href="javascript:void(0)" onclick="editDepartment({{ $depart->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Department"><i
                        class="bi bi-pencil me-3"></i></a>
                <a href="javascript:void(0)" onclick="deleteDepartment({{ $depart->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Department"><i
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
<div class="d-flex justify-content-end me-2 mt-2">
    {{ $departList->links() }}
</div>