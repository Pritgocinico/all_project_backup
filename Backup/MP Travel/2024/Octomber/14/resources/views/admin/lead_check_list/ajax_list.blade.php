<table class="table table-hover table-sm table-nowrap table-responsive mt-6 border" id="department_table">
    <thead>
        <tr>
            <th>#</th>
            <th>Lead Type</th>
            <th>Check List Item</th>
            <th>Created By</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($leadCheckList as $key => $checkList)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $checkList->type }}</td>
                <td>
                    @if (isset($checkList->leadCheckListItems))
                        @foreach ($checkList->leadCheckListItems as $key => $item)
                            {{ $item->name }}@if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    @endif
                </td>
                <td>{{ isset($checkList->userDetail) ? $checkList->userDetail->name : '-' }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($checkList->created_at) }}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '21')->first()->status == 2)
                        <a href="{{ route('lead-checklist.edit', $checkList->id) }}" class="text-dark"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Lead Check List">
                            <i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="javascript:void(0)" class="text-dark" onclick="deleteDepartment({{ $checkList->id }})"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Lead Check List">
                            <i class="fa fa-trash-can me-3"></i>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
