<table class="table table-hover table-sm table-nowrap table-responsive mt-6 border" id="holiday_table">
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
        @foreach ($holidayList as $key => $holiday)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $holiday->holiday_name }}</td>
                <td>{{ Utility::convertDMYFormat($holiday->holiday_date) }}</td>
                @php $subject = $holiday->description @endphp
                @if (strlen($holiday->description) > 30)
                    @php $subject = substr($holiday->description, 0, 30) . '...'; @endphp
                @endif
                <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $holiday->description }}">
                    {{ $subject }}</td>
                <td>
                    @php
                        $text = 'Active';
                        $color = 'success';
                        if ($holiday->status == 0) {
                            $color = 'danger';
                            $text = 'Inactive';
                        }
                    @endphp
                    <span class="badge bg-{{ $color }} w-120">{{ $text }}</span>
                </td>

                <td>{{ isset($holiday->userDetail) ? $holiday->userDetail->name : '-' }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($holiday->created_at) }}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '10')->first()->status == 2)
                        <a href="javascript:void(0)" onclick="editHoliday({{ $holiday->id }})" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Edit Holiday"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="javascript:void(0)" onclick="deleteHoliday({{ $holiday->id }})"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Holidayt"><i
                                class="fa-solid fa-trash-can"></i></a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
