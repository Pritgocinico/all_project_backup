<table class="table table-hover table-sm  table-scrolling table-nowraps mt-6 border" id="lead_table">
    <thead>
        <tr>
            <th>No</th>
            <th>Lead</th>
            <th>Customer Name</th>
            <th>Lead Assigned</th>
            <th>Created By</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($leads as $key=>$lead)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td><a href="{{ route('lead-view-edit', $lead->id) }}">{{ $lead->lead_id }}</a></td>
                <td>{{ isset($lead->customerDetail) ? $lead->customerDetail->name : '-' }}</td>
                <td>{{ $lead->employeeDetail->name }}</td>
                <td>{{ isset($lead->userDetail) ? $lead->userDetail->name :"-" }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($lead->created_at) }}</td>
                <td class="text-end">
                <div class="icon-td">
                    @if (collect($accesses)->where('menu_id', '15')->first()->status == 2)
                    <a href="{{ route('leads.show', $lead->id) }}" class="text-dark" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="View Lead"><i class="fa-solid fa-eye"></i></a>
                    <a href="{{ route('leads.edit', $lead->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit lead"><i class="fa-solid fa-pen-to-square"></i></a>
                    <a href="javascript:void(0)" onclick="deletelead({{$lead->id}})" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete lead"><i class="fa-solid fa-trash-can"></i></a>
                    @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>