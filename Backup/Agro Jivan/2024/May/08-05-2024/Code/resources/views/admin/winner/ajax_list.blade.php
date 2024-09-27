<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th>Sr. no</th>
            <th class="min-w-125px">User Name</th>
            <th class="min-w-125px">Orders</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($winnerData as $key=>$winner)
        <tr>
            <td>{{$key + 1}}</td>
            <td>{{ $winner['winner'] }}</td>
            <td>{{ $winner['order'] }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="text-center">No Data Available.</td>
        </tr>
        @endforelse
    </tbody>
</table>