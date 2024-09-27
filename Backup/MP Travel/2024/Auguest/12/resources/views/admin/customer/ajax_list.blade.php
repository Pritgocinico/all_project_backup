<table class="table table-hover table-striped table-sm table-nowrap table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Role Name</th>
            <th>Department</th>
            <th>Created By</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($customerList as $key=>$customer)
            <tr>
                <td>{{ $customerList->firstItem() + $key }}</td>
                <td><a href="{{ route('customer.show', $customer->id) }}">{{ $customer->name }}</a></td>
                <td>
                    {{ $customer->email }}
                </td>
                <td>
                    {{ $customer->mobile_number }}
                </td>
                <td>{{ isset($customer->roleDetail) ? $customer->roleDetail->name : '-' }}</td>
                <td>
                    @php
                    $type = "Investement";
                    if($customer->insurance_type == "1"){
                        $type = "General Insurance";
                    }
                    if($customer->insurance_type == "2"){
                        $type = "Travel";
                    }
                    @endphp
                    {{$type}}
                </td>
                <td>{{ isset($customer->userDetail) ? $customer->userDetail->name : '-' }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($customer->created_at) }}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '2')->first()->status == 2)
                        <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><button
                                    type="button" class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                        class="bi bi-three-dots"></i></button></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('customer.edit', $customer->id) }}"><i
                                        class="bi bi-pencil me-3"></i>Edit Customers</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"
                                    onclick="deleteCustomer({{ $customer->id }})"><i class="bi bi-trash me-3"></i>Delete
                                    Customers</a>
                            </div>
                        </div>
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
<div class="d-flex justify-content-end me-2 mt-2">
    {{ $customerList->links() }}
</div>
