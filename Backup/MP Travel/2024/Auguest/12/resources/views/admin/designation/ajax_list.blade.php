<table class="table table-hover table-striped table-sm table-nowrap table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Designation Name</th>
            <th>Department Name</th>
            <th>Status</th>
            <th>Create By</th>
            <th>Create At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($designationList as $key=>$designation)
            <tr>
                <td>{{ $designationList->firstItem() + $key }}</td>
                <td>{{ $designation->name }}</td>
                <td>{{ isset($designation->departmentDetail) ? $designation->departmentDetail->name : '' }}</td>
                <td>
                    @php
                        $text = 'Active';
                        $color = 'success';
                        if ($designation->status == 0) {
                            $color = 'danger';
                            $text = 'Inactive';
                        }
                    @endphp
                    <span class="badge bg-{{ $color }}">{{ $text }}</span>
                </td>
                <td>{{ isset($designation->userDetail) ? $designation->userDetail->name : '-' }}</td>
                <td>{{Utility::convertDmyAMPMFormat($designation->created_at)}}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '3')->first()->status == 2)
                        <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#"
                                role="button" data-bs-toggle="dropdown" aria-haspopup="false"
                                aria-expanded="false"><button type="button"
                                    class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                        class="bi bi-three-dots"></i></button></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"
                                    onclick="editDesignation({{ $designation->id }})"><i
                                        class="bi bi-pencil me-3"></i>Edit Designation</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"
                                    onclick="deleteDesignation({{ $designation->id }})"><i
                                        class="bi bi-trash me-3"></i>Delete Designation </a>
                            </div>
                        </div>
                    @endif
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end me-2 mt-2">
    {{ $designationList->links() }}
</div>