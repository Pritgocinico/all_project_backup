<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px">ID</th>
            <th class="min-w-125px">Date</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($dateList as $key=>$date)
            <tr>
                <td>{{ $dateList->firstItem() + $key }}</td>
                <td><a href="#" class="pre-agro-emp"
                        onclick="showAttedanceDetail({{ $key }})">{{ $date }}</a>
                </td>
            </tr>
            <input type="hidden" id="date-{{ $key }}" value="{{ $date }}">
        @empty
            <tr>
                <td colspan="2" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $dateList->links('pagination::bootstrap-5') }}
</div>
