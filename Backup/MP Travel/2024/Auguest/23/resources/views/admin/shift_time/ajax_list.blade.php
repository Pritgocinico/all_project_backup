<table class="table table-hover table-sm table-nowrap table-responsive mt-6 border" id="shift_time_table">
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
        @forelse ($shiftTimeList as $key=>$shift)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>
                    @if (collect($accesses)->where('menu_id', '17')->first()->status == 2)
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
                    @if (collect($accesses)->where('menu_id', '17')->first()->status == 2)
                        <a href="javascript:void(0)" onclick="editShift({{ $shift->id }})" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Edit Shift Schedule"><i class="bi bi-pencil-square"></i></a>

                        <a href="javascript:void(0)" onclick="deleteShift({{ $shift->id }})"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Shift Schedule"><i
                                class="bi bi-trash"></i></a>
                    @endif
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
