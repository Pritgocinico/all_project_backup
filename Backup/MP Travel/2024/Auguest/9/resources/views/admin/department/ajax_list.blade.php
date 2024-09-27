<table class="table table-hover table-striped table-sm table-nowrap table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Department Name</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($departList as $key=>$depart)
            <tr>
                <td>{{ $departList->firstItem() + $key }}</td>
                <td>{{ $depart->name }}</td>
                <td>{{Utility::convertDmyAMPMFormat($depart->created_at)}}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '2')->first()->status == 2)
                        <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#"
                                role="button" data-bs-toggle="dropdown" aria-haspopup="false"
                                aria-expanded="false"><button type="button"
                                    class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                        class="bi bi-three-dots"></i></button></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#" onclick="editDepartment({{ $depart->id }})"><i
                                        class="bi bi-pencil me-3"></i>Edit Department</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"
                                    onclick="deleteDepartment({{ $depart->id }})"><i
                                        class="bi bi-trash me-3"></i>Delete Department </a>
                            </div>
                        </div>
                    @endif
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end me-2 mt-2">
    {{ $departList->links() }}
</div>