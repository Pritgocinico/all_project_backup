<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px">Sr. No</th>
            <th class="min-w-125px">Customer Name</th>
            <th class="min-w-125px">Phone Number</th>
            <th class="min-w-125px">State</th>
            <th class="min-w-125px">District</th>
            <th class="min-w-125px">Sub District</th>
            <th class="min-w-125px">Village</th>
            <th class="min-w-125px">Total Orders</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($customerList as $key=>$order)
            <tr>
            <td>{{ $customerList->firstItem() + $key }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->phoneno }}</td>
                <td>{{ isset($order->stateDetail) ? $order->stateDetail->name : '-' }}</td>
                <td>{{ isset($order->districtDetail) ? $order->districtDetail->district_name : '-' }}</td>
                <td>{{ isset($order->subDistrictDetail) ? $order->subDistrictDetail->sub_district_name : '-' }}</td>
                <td>{{ isset($order->villageDetail) ? $order->villageDetail->village_name : '-' }}</td>
                <td>{{ $order->total }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $customerList->links('pagination::bootstrap-5') }}
</div>
