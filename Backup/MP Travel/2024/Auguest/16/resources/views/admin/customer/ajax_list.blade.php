<table class="table table-hover table-sm table-nowrap table-responsive  mt-6 border" id="customer_table">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Phone Number</th>
            <th>Service Preference</th>
            <th>Reference Of</th>
            <th>Created By</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($customerList as $key=>$customer)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td><a href="{{ route('customer.show', $customer->id) }}">{{ $customer->name }} ({{ucfirst($customer->customer_department)}})</a></td>
                <td>
                    {{ $customer->mobile_number }}
                </td>
                <td>{{$customer->service_preference ?? "-"}}</td>
                <td>{{$customer->reference ?? "-"}}</td>
                <td>{{ isset($customer->userDetail) ? $customer->userDetail->name : '-' }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($customer->created_at) }}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '7')->first()->status == 2)
                        <a href="{{ route('customer.edit', $customer->id) }}" class="text-dark" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Edit Customer"><i class="bi bi-pencil-square me-3"></i></a>
                        <a href="javascript:void(0)" class="text-dark" onclick="deleteCustomer({{ $customer->id }})"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Customer"><i
                                class="bi bi-trash me-3"></i></a>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
