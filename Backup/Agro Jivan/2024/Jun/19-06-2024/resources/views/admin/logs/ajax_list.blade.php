<table id="example" class="table align-middle table-row-dashed fs-6 gy-5" style="width:100%">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th>Sr. no</th>
            <th class="min-w-125px">User</th>
            <th class="min-w-125px">Module</th>
            <th class="min-w-125px">Log</th>
            <th class="min-w-125px">Created At</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($logList as $key=>$log)
            <tr>
            <td>{{ $logList->firstItem() + $key}}</td>
                <td>{{ isset($log->userDetail)?ucfirst($log->userDetail->name):"" }}</td>
                <td>{{ $log->module }}</td>
                <td>{{ $log->log }}</td>
                <td>{{ Utility::convertDmyWithAMPMFormat($log->created_at) }}</td>
                
            </tr>
        @empty
            <tr class="text-center">
                <td colspan="4">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $logList->links('pagination::bootstrap-5') }}
</div>

