<table class="table table-hover table-sm table-nowrap table-responsive mt-6 border" id="designation_table">
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
                <td>{{ $key + 1 }}</td>
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
                    <a href="javascript:void(0)" onclick="editDesignation({{$designation->id}})" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Designation"><i
                        class="bi bi-pencil-square"></i></a>
                    <a href="javascript:void(0)" onclick="deleteDesignation({{$designation->id}})" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Designation"><i
                        class="bi bi-trash"></i></a>
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