<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th>Sr. no</th>
            <th class="min-w-125px">Employee Name</th>
            <th class="min-w-125px">Employee Email</th>
            <th class="min-w-125px">Phone Number</th>
            <th class="min-w-125px">Total Orders</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($userList as $key=>$order)
        <tr>
        <td>{{ $key +1 }}</td>
            <td class="align-middle">
                {{$order->name}}
            </td>
            <td>{{ $order->email }}</td>
            <td>{{ $order->phone_number }}</td>
            <td>{{ $order->confirm_order_count }}</td>

        </tr>
        @empty
        <tr>
            <td colspan="9" class="text-center">No Data Available.</td>
        </tr>
        @endforelse
    </tbody>
</table>