<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px">Holiday Name</th>
            <th class="min-w-125px">Holiday Date</th> 
            <th class="min-w-125px">Status</th>
            <th class="min-w-125px">Created At</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($holidayList as $holiday)
            <tr>
                <td>{{ $holiday->holiday_name }}</td>
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
            </tr>
        @empty
            <tr class="text-center">
                <td colspan="4">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $holidayList->links('pagination::bootstrap-5') }}
</div>
