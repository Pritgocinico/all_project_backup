<table class="table table-hover table-scrolling table-sm table-nowrap table-scrolling table-responsive mt-6 border" id="shift_time_table">
    <thead>
        <tr>
            <th>#</th>
            <th>Shift Name</th>
            <th>Shift Code</th>
            <th>Shift Start Time</th>
            <th>Shift End Time</th>
            <th>Created By</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($shiftTimeList as $key=>$shift)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>
                    @if (collect($accesses)->where('menu_id', '14')->first()->status == 2)
                        <a href="{{ route('user.index') }}?shift={{ $shift->id }}">{{ $shift->shift_name }}</a>
                    @else
                        {{ $shift->shift_name }}
                    @endif
                </td>
                <td>{{ $shift->shift_code }}</td>
                <td>{{ Utility::convertHIAFormat($shift->shift_start_time) }}</td>
                <td>{{ Utility::convertHIAFormat($shift->shift_end_time) }}</td>
                <td>{{ isset($shift->userDetail) ? $shift->userDetail->name : '-' }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($shift->created_at) }}</td>
                <td class="text-end">
                <div class="icon-td">
                    @if (collect($accesses)->where('menu_id', '14')->first()->status == 2)
                        <a href="javascript:void(0)" onclick="editShift({{ $shift->id }})" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Edit Shift Schedule"><i class="fa-solid fa-pen-to-square"></i></a>

                        <a href="javascript:void(0)" onclick="deleteShift({{ $shift->id }})"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Shift Schedule"><i
                                class="fa fa-trash-can"></i></a>
                    @endif
</div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
