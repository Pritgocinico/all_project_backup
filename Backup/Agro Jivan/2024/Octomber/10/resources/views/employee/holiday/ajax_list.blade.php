<table id="example" class="table align-middle table-row-dashed fs-6 gy-5" style="width:100%">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
        <th>Sr. no</th>
            <th class="min-w-100px">Holiday Name</th>
            <th class="min-w-100px">Holiday Date</th>
            <th class="min-w-125px">Status</th>
            <th class="min-w-100px">Created At</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($holidayList as $key=>$holiday)
            <tr>
            <td>{{ $holidayList->firstItem() + $key }}</td>
                <td>{{ $holiday->holiday_name }}</td>
                <td>{{ Utility::convertMDY($holiday->holiday_date) }}</td>
                <td>
                    @php $text = 'Inactive'; $class = 'danger'; @endphp
                    @if ($holiday->status == 1)
                        @php $text = 'Active'; $class = 'success'; @endphp
                    @endif
                    <div class="badge badge-light-{{ $class }} fw-bold">
                        {{ $text }}</div>
                </td>
                <td>{{ Utility::convertDmyWith12HourFormat($holiday->created_at) }}</td>
            </tr>
        @empty
            <tr class="text-center">
                <td colspan="3">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $holidayList->links('pagination::bootstrap-5') }}
</div>
