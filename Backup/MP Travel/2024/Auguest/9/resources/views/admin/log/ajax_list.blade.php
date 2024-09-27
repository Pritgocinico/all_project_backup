<table class="table table-hover table-striped table-sm table-nowrap table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Module</th>
            <th>Description</th>
            <th>Created By</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($logList as $key=>$log)
            <tr>
                <td>{{ $logList->firstItem() + $key }}</td>
                <td>{{ $log->module }}</td>
                <td>
                    {{ $log->description }}
                </td>
                <td>
                    {{ isset($log->userDetail)? ucfirst($log->userDetail->name) : 'Admin' }}
                </td>
                <td>{{ Utility::convertDmyAMPMFormat($log->created_at) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end me-2 mt-2">
    {{ $logList->links() }}
</div>