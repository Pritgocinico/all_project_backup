<table class="table table-hover table-sm table-nowrap table-scrolling table-responsive  mt-6 border" id="customer_table">
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Phone Number</th>
            <th>Created By</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customerList as $key => $customer)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>
                    @if (collect($accesses)->where('menu_id', '18')->where('view', 1)->first())
                        <a href="{{ route('customer.show', $customer->id) }}">{{ $customer->name }}</a>
                    @else
                        {{ $customer->name }}
                    @endif
                </td>
                <td>
                    {{ $customer->mobile_number }}
                </td>
                <td>{{ isset($customer->userDetail) ? $customer->userDetail->name : '-' }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($customer->created_at) }}</td>
                <td class="text-end">
                    <div class="icon-td">
                        @if (collect($accesses)->where('menu_id', '18')->where('view', 1)->first())
                            <a href="{{ route('customer.show', $customer->id) }}" class="text-dark"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="View Customer"><i
                                    class="fa-solid fa-eye"></i></a>
                        @endif
                        @if (collect($accesses)->where('menu_id', '18')->where('edit', 1)->first())
                            <a href="{{ route('customer.edit', $customer->id) }}" class="text-dark"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Customer"><i
                                    class="fa-solid fa-pen-to-square"></i></a>
                        @endif
                        @if (collect($accesses)->where('menu_id', '18')->where('delete', 1)->first())
                            <a href="javascript:void(0)" class="text-dark" onclick="deleteCustomer({{ $customer->id }})"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Customer"><i
                                    class="fa fa-trash-can me-3"></i></a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
