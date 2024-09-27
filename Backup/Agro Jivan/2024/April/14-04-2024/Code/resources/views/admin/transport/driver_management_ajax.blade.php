<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-black fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px">User</th>
            <th class="min-w-125px">Email</th>
            <th class="min-w-125px">Phone Number</th>
            <th class="min-w-125px">On Delivery Batch</th>
            <th class="min-w-125px">Delivered Batch</th>
            <th class="min-w-125px">Completed Order</th>
            <th class="min-w-125px">Returned Order</th>
        </tr>
    </thead>
    <tbody class="text-black-600 fw-semibold">
        @forelse ($driverList as $key=>$driver)
            <tr>
                <td><a class="pre-agro-emp" href="{{ route('divert-transport')}}?driver_id={{$driver->id}}"> {{ $driver->name }}</a></td>
                <td>{{ $driver->email }}</td>
                <td>{{ $driver->phone_number }}</td>
                <td>{{$driver->on_deliver_batch_count}}</td>
                <td>{{$driver->delivered_batch_count}}</td>
                <td>{{$driver->confirm_order_count}}</td>
                <td>{{$driver->return_order_count}}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $driverList->links('pagination::bootstrap-5') }}
</div>
