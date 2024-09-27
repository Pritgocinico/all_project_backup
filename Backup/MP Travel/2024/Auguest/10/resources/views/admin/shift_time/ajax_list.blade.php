<table class="table table-hover table-striped table-sm table-nowrap table-responsive">
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
                <td>{{ $shiftTimeList->firstItem() + $key }}</td>
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
                <td>{{ isset($shift->userDetail) ? $shift->userDetail->name :"-" }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($shift->created_at) }}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '17')->first()->status == 2)
                        <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#"
                                role="button" data-bs-toggle="dropdown" aria-haspopup="false"
                                aria-expanded="false"><button type="button"
                                    class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                        class="bi bi-three-dots"></i></button></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"
                                    onclick="editShift({{ $shift->id }})"><i
                                        class="bi bi-pencil me-3"></i>Edit Shift</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"
                                    onclick="deleteShift({{ $shift->id }})"><i
                                        class="bi bi-trash me-3"></i>Delete Shift </a>
                            </div>
                        </div>
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
<div class="d-flex justify-content-end me-2 mt-2">
    {{ $shiftTimeList->links() }}
</div>