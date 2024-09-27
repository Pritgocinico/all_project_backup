<table class="table table-hover table-striped table-sm table-nowrap table-responsive">
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
                <td>{{ $certificateList->firstItem() + $key }}</td>
                <td>{{ $certificate->title }}</td>
                <td>{{ isset($certificate->employee) ? $certificate->employee->name : "-" }}</td>
                <td> <a href="{{ asset('storage/' . $certificate->file_path) }}" download><img
                    src="{{ url('assets/img/user/file.png') }}" width="60px"></a> </td>
                    <td>{{ $certificate->month_name }}</td>
                    <td>{{ isset($certificate->userDetail)?$certificate->userDetail->name : "-" }}</td>
                <td>{{Utility::convertDmyAMPMFormat($certificate->created_at)}}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '5')->first()->status == 2)
                        <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#"
                                role="button" data-bs-toggle="dropdown" aria-haspopup="false"
                                aria-expanded="false"><button type="button"
                                    class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                        class="bi bi-three-dots"></i></button></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="javascript:void(0)"
                                    onclick="deleteCertificate({{ $certificate->id }})"><i
                                        class="bi bi-trash me-3"></i>Delete Certificate </a>
                            </div>
                        </div>
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
<div class="d-flex justify-content-end me-2 mt-2">
    {{ $certificateList->links() }}
</div>