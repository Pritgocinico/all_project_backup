<table id="" class="table align-middle table-row-dashed fs-6 gy-5" style="width:100%">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px">Sr. no</th>
            <th class="min-w-125px">Holiday Name</th>
            <th class="min-w-125px">Holiday Date</th>
            <th class="w-100px">Status</th>
            <th class="w-125px">Created At</th>
            <th class="w-100px">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($holidayList as $key=>$holiday)
            <tr>
            <td>{{ $holidayList->firstItem() + $key }}</td>
                <td>{{$holiday->holiday_name}}</td>
                <td>{{ Utility::convertDmyWith12HourFormat($holiday->holiday_date) }}</td>
                <td>
                    @php $text = 'Inactive'; $class = 'danger'; @endphp
                    @if ($holiday->status == 1)
                        @php $text = 'Active'; $class = 'success'; @endphp
                    @endif
                    <div class="badge badge-light-{{ $class }} fw-bold">
                        {{ $text }}</div>
                </td>
                <td>{{ Utility::convertDmyWith12HourFormat($holiday->created_at) }}</td>
                <td class="btn-actions">
                    <a class="btn btn-icon btn-info w-30px h-30px me-3"
                    href="#" onclick="editHoliday({{$holiday->id}})" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Holiday">
                        <i class="fa-solid fa-edit"></i>
                    </a>
                    <a class="btn btn-icon btn-danger w-30px h-30px me-3"
                        onclick="deleteHoliday({{ $holiday->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Holiday">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                    
                </td>
            </tr>
        @empty
            <tr>
                <td class="text-center" colspan="4">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $holidayList->links('pagination::bootstrap-5') }}
</div>
