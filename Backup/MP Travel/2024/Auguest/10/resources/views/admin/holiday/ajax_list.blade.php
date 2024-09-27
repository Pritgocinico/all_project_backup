<table class="table table-hover table-striped table-sm table-nowrap table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Holiday Name</th>
            <th>Holiday Date</th>
            <th>Description</th>
            <th>Status</th>
            <th>Created By</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($holidayList as $key=>$holiday)
            <tr>
                <td>{{ $holidayList->firstItem() + $key }}</td>
                <td>{{ $holiday->holiday_name }}</td>
                <td>{{Utility::convertDMYFormat($holiday->holiday_date)}}</td>
                @php $subject = $holiday->description @endphp
                    @if (strlen($holiday->description) > 30)
                        @php $subject = substr($holiday->description, 0, 30) . '...'; @endphp
                    @endif
                    <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $holiday->description }}">{{ $subject }}</td>
                <td>
                    @php
                        $text = 'Active';
                        $color = 'success';
                        if ($holiday->status == 0) {
                            $color = 'danger';
                            $text = 'Inactive';
                        }
                    @endphp
                    <span class="badge bg-{{ $color }}">{{ $text }}</span>
                </td>

                <td>{{ isset($holiday->userDetail) ? $holiday->userDetail->name : "-" }}</td>
                <td>{{Utility::convertDmyAMPMFormat($holiday->created_at)}}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '10')->first()->status == 2)
                        <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#"
                                role="button" data-bs-toggle="dropdown" aria-haspopup="false"
                                aria-expanded="false"><button type="button"
                                    class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                        class="bi bi-three-dots"></i></button></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"
                                    onclick="editHoliday({{ $holiday->id }})"><i
                                        class="bi bi-pencil me-3"></i>Edit Holiday</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"
                                    onclick="deleteHoliday({{ $holiday->id }})"><i
                                        class="bi bi-trash me-3"></i>Delete Holiday </a>
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
    {{ $holidayList->links() }}
</div>