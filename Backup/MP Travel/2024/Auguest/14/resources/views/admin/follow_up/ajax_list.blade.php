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
                <td><a href="{{route('follow-up.show',$follow->id)}}">{{ $follow->event_name }}</a></td>
                <td>{{ isset($follow->userDetail) ? $follow->userDetail->name :"-" }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($follow->created_at) }}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '19')->first()->status == 2)
                        <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#"
                                role="button" data-bs-toggle="dropdown" aria-haspopup="false"
                                aria-expanded="false"><button type="button"
                                    class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                        class="bi bi-three-dots"></i></button></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item text-dark"
                                    href="{{ route('follow-up.edit', $follow->id) }}"><i
                                        class="bi bi-pencil-square me-3"></i>Edit Follow Up</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-dark" href="javascript:void(0)"
                                    onclick="deleteFollow({{ $follow->id }})"><i
                                        class="bi bi-trash me-3"></i>Delete Follow Up </a>
                            </div>
                        </div>
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