<table class="table table-hover table-sm table-nowrap table-responsive mt-6 border" id="follow_up_table">
    <thead>
        <tr>
            <th>#</th>
            <th>Event Name</th>
            <th>Created By</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($followUpList as $key=>$follow)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td><a href="{{ route('follow-up.show', $follow->id) }}">{{ $follow->event_name }}</a></td>
                <td>{{ isset($follow->userDetail) ? $follow->userDetail->name : '-' }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($follow->created_at) }}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '19')->first()->status == 2)
                        <a href="{{ route('follow-up.edit', $follow->id) }}" class="text-dark" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Edit Follow Up"><i class="bi bi-pencil-square me-3"></i></a>
                        <a href="javascript:void(0)" class="text-dark" onclick="deleteFollow({{ $follow->id }})"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Follow Up"><i
                                class="bi bi-trash me-3"></i></a>
                    @endif
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
