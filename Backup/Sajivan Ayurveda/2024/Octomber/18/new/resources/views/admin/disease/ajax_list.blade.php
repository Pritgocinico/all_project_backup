<table class="table table-hover table-sm table-nowrap table-scrolling table-responsive mt-6 border" id="disease_table">
    <thead>
        <tr>
            <th>No</th>
            <th>Disease Name</th>
            <th>Created By</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($diseasesList as $key => $disease)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $disease->name }}</td>
                <td>{{ isset($disease->userDetail) ? $disease->userDetail->name : '-' }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($disease->created_at) }}</td>
                <td class="text-end">
                    <div class="icon-td">
                        @if (collect($accesses)->where('menu_id', '22')->where('edit', 1)->first())
                            <a href="javascript:void(0)" class="text-dark" onclick="editDisease({{ $disease->id }})"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Disease">
                                <i class="fa-solid fa-pen-to-square"></i></a>
                        @endif
                        @if (collect($accesses)->where('menu_id', '22')->where('delete', 1)->first())
                            <a href="javascript:void(0)" class="text-dark" onclick="deleteDisease({{ $disease->id }})"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Disease">
                                <i class="fa fa-trash-can me-3"></i>
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
