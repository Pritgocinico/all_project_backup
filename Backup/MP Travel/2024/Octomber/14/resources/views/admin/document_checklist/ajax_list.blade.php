<table class="table table-hover table-sm table-nowrap table-responsive mt-6 border" id="department_table">
    <thead>
        <tr>
            <th>#</th>
            <th>Country Name</th>
            <th>Visa Type</th>
            <th>Document File</th>
            <th>Created By</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($documentCheckList as $key => $document)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $document->country_code }}</td>
                <td>{{ $document->visa_type }}</td>
                <td> <a href="{{ asset('storage/'.$document->document_file) }}" download><img src="{{ url('assets/img/message/pdf.png') }}" width="40px"></a> </td>
                <td>{{ isset($document->userDetail) ? $document->userDetail->name : '-' }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($document->created_at) }}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '20')->first()->status == 2)
                        <a href="javascript:void(0)" class="text-dark" onclick="editDepartment({{ $document->id }})"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Department">
                            <i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="javascript:void(0)" class="text-dark" onclick="deleteDepartment({{ $document->id }})"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Department">
                            <i class="fa fa-trash-can me-3"></i>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>