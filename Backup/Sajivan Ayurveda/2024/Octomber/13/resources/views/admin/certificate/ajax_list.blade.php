<table class="table table-hover table-sm table-nowrap table-scrolling table-responsive mt-6 border" id="certificate_table_list">
    <thead>
        <tr>
            <th>No</th>
            <th>Title</th>
            <th>Employee</th>
            <th>Certificate</th>
            <th>Month Name</th>
            <th>Created By</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($certificateList as $key=>$certificate)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $certificate->title }}</td>
                <td>{{ isset($certificate->employee) ? $certificate->employee->name : '-' }}</td>
                <td> <a href="{{ asset('storage/' . $certificate->file_path) }}" download>
                    <img src="{{ url('assets/img/message/pdf.png') }}" width="40px"></a> </td>
                <td>{{ $certificate->month_name }}</td>
                <td>{{ isset($certificate->userDetail) ? $certificate->userDetail->name : '-' }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($certificate->created_at) }}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '12')->first()->status == 2)
                        <a href="javascript:void(0)" class="text-dark" onclick="deleteCertificate({{ $certificate->id }})"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Certificate"><i class="fa-solid fa-trash-can"></i></a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
