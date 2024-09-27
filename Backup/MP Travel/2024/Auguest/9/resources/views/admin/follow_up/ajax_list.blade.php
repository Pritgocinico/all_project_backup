<table class="table table-hover table-striped table-sm table-nowrap table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Event Name</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($followUpList as $key=>$follow)
            <tr>
                <td>{{ $followUpList->firstItem() + $key }}</td>
                <td><a href="{{route('follow-up.show',$follow->id)}}">{{ $follow->event_name }}</a></td>
                <td>{{ Utility::convertDmyAMPMFormat($follow->created_at) }}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '19')->first()->status == 2)
                        <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#"
                                role="button" data-bs-toggle="dropdown" aria-haspopup="false"
                                aria-expanded="false"><button type="button"
                                    class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                        class="bi bi-three-dots"></i></button></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item"
                                    href="{{ route('follow-up.edit', $follow->id) }}"><i
                                        class="bi bi-pencil me-3"></i>Edit Follow Up</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"
                                    onclick="deleteFollow({{ $follow->id }})"><i
                                        class="bi bi-trash me-3"></i>Delete Follow Up </a>
                            </div>
                        </div>
                    @endif
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end me-2 mt-2">
    {{ $followUpList->links() }}
</div>