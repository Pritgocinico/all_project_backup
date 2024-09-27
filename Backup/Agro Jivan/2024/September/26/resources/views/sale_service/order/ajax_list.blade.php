<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px">Order ID</th>
            <th class="min-w-125px">Customer</th>
            <th class="min-w-125px">Phone Number</th>
            <th class="min-w-125px">Amount</th>
            <th class="min-w-125px">District</th>
            <th class="min-w-125px">Sub District</th>
            <th class="min-w-125px">Created By</th>
            <th class="min-w-100px">Created AT</th>
            <th class="min-w-100px">Action</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($orderList as $key=>$order)
            <tr>
                <td class="align-middle">
                    <a href="{{route('service-order-view',$order->id)}}" class="pre-agro-emp">{{ $order->order_id }}</a>
                    {!! DNS1D::getBarcodeHTML($order->order_id, 'C128', 1.4, 22) !!}
                </td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->phoneno }}</td>
                <td>&#x20B9; {{ $order->amount }}</td>
                <td>{{ isset($order->districtDetail)?$order->districtDetail->district_name:"" }}</td>
                <td>{{ isset($order->subDistrictDetail)?$order->subDistrictDetail->sub_district_name:"" }}</td>

                <td>{{ isset($order->userDetail) ? $order->userDetail->name : '' }}</td>

                <td>{{ Utility::convertDmyWith12HourFormat($order->created_at) }}</td>
                <td>
                    
                    <button class="btn-sm btn btn-primary" name="button" id="button" value="Write Feedback" onclick="openFeedbackModal({{$order->id}})" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Write Feedback">Write Feedback</button> 
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $orderList->links('pagination::bootstrap-5') }}
</div>
