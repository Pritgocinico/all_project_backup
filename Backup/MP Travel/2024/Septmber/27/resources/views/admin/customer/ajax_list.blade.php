<table class="table table-hover table-sm table-nowrap table-responsive  mt-6 border" id="customer_table">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Phone Number</th>
            <th>Reference Of</th>
            <th>Created By</th>
            <th>Created At</th>
            <th>Last Modified By</th>
            <th>Last Modified At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customerList as $key=>$customer)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td><a href="{{ route('customer.show', $customer->id) }}">{{ $customer->name }}</a></td>
                <td>
                    {{ $customer->mobile_number }}
                </td>
                <td>{{$customer->reference ?? "-"}}</td>
                <td>{{ isset($customer->userDetail) ? $customer->userDetail->name : '-' }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($customer->created_at) }}</td>
                <td>{{ isset($customer->moduleModifiedLog) && isset($customer->moduleModifiedLog->userDetail) ? $customer->moduleModifiedLog->userDetail->name : '-' }}</td>
                <td>{{ isset($customer->moduleModifiedLog) ? Utility::convertDmyAMPMFormat($customer->moduleModifiedLog->created_at)  : "-"}}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '7')->first()->status == 2)
                        <a href="{{ route('customer.change-profile', $customer->id) }}" class="text-dark" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Service Preference Tagging"><i class="fa-solid fa-tags"></i></a>
                        <a href="{{ route('customer.view-profile', $customer->id) }}" class="text-dark" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Customer Profile"><i class="fa-solid fa-id-card-clip"></i></a>
                        <a href="{{ route('customer.edit', $customer->id) }}" class="text-dark" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Edit Customer"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="javascript:void(0)" class="text-dark" onclick="deleteCustomer({{ $customer->id }})"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Customer"><i
                                class="fa fa-trash-can me-3"></i></a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
