<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px">Order ID</th>
            <th class="min-w-125px">Customer</th>
            <th class="min-w-125px">Phone Number</th>
            <th class="min-w-125px">Amount</th>
            <th class="min-w-125px">District</th>
            <th class="min-w-125px">Created By</th>
            <th class="min-w-125px">Lead Status</th>
            <th class="min-w-100px">Created AT</th>
            <th class="min-w-100px">Action</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($leadList as $key=>$lead)
            <tr>
                <td class="align-middle">
                    <a href="{{route('employee-lead.show',$lead->id)}}" class="pre-agro-emp">
                    {{ $lead->lead_id }}
                    </a>
                </td>
                <td>{{ $lead->customer_name }}</td>
                <td>{{ $lead->phone_no }}</td>
                <td>&#x20B9; {{ $lead->amount }}</td>
                <td>{{ isset($lead->districtDetail)?$lead->districtDetail->district_name:"" }}</td>

                <td>{{ isset($lead->userDetail) ? $lead->userDetail->name : '' }}</td>
                <td>
                    @php
                        $text = 'Inactive';
                        $class = 'danger';
                    @endphp
                    @if ($lead->status == 1)
                        @php
                            $text = 'Active';
                            $class = 'success';
                        @endphp
                    @endif
                    <div class="badge badge-light-{{ $class }} fw-bold">
                        {{ $text }}</div>
                </td>

                <td>{{ Utility::convertDmyWith12HourFormat($lead->created_at) }}</td>
                <td>
                    @if (Permission::checkPermission('lead-add'))
                    <a class="btn btn-icon btn-info w-30px h-30px me-3" href="#"
                        onclick="convertOrder({{ $lead->id }})" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Covert to Order">
                        <i class="fa-solid fa-rotate"></i>
                        </a>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $leadList->links('pagination::bootstrap-5') }}
</div>
