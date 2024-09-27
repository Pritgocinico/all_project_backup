<table class="table table-hover table-sm table-nowrap table-responsive mt-6 border" id="certificate_table_list">
    <thead>
        <tr>
            <th>#</th>
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
        @forelse ($certificateList as $key=>$certificate)
            <tr>
                <td>{{ $key +1 }}</td>
                <td>{{ $certificate->title }}</td>
                <td>{{ isset($certificate->employee) ? $certificate->employee->name : "-" }}</td>
                <td> <a href="{{ asset('storage/' . $certificate->file_path) }}" download><img
                    src="{{ url('assets/img/user/file.png') }}" width="60px"></a> </td>
                    <td>{{ $certificate->month_name }}</td>
                    <td>{{ isset($certificate->userDetail)?$certificate->userDetail->name : "-" }}</td>
                <td>{{Utility::convertDmyAMPMFormat($certificate->created_at)}}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '14')->first()->status == 2)
                    <a href="javascript:void(0)" class="text-dark" onclick="deleteCertificate({{ $certificate->id }})"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Certificate"><i
                            class="bi bi-trash"></i></a>
                    @endif

                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>