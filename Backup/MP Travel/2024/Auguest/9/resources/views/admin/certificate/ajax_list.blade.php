<table class="table table-hover table-striped table-sm table-nowrap table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Employee</th>
            <th>Certificate</th>
            <th>Month Name</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($certificateList as $key=>$certificate)
            <tr>
                <td>{{ $certificateList->firstItem() + $key }}</td>
                <td>{{ $certificate->title }}</td>
                <td>{{ $certificate->employee->name }}</td>
                <td> <a href="{{ Storage::url($certificate->file_path) }}" download><img
                            src="{{ url('assets/img/user/file.png') }}" width="60px"></a> </td>
                <td>{{ $certificate->month_name }}</td>
                <td>{{Utility::convertDmyAMPMFormat($certificate->created_at)}}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end me-2 mt-2">
    {{ $certificateList->links() }}
</div>