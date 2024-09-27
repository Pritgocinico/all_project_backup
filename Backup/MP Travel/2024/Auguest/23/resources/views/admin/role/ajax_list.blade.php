<table class="table table-hover table-sm table-nowrap table-responsive mt-6 border" id="role_table">
    <thead>
        <tr>
            <th>#</th>
            <th>Role Name</th>
            <th>Permission</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($roleList as $key=>$role)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $role->name }}</td>
                <td>
                    @php
                        $subDistrictIdArray = [];
                        $count = 0;
                    @endphp
                    @foreach ($role->access as $key => $access)
                        @if (isset($access->menu->name))
                            <b>{{ Str::ucfirst($access->menu->name) . ' - ' }}</b>
                            @php
                                $status = 'All';
                                if ($access->status == 0) {
                                    $status = 'Disabled';
                                } elseif ($access->status == 1) {
                                    $status = 'View';
                                }
                            @endphp
                            {{ $status }},
                            @php $count++; @endphp
                            @if ($count == 4)
                                <br>
                                @php
                                    $count = 0;
                                @endphp
                            @endif
                        @endif
                    @endforeach
                </td>
                <td>{{ Utility::convertDmyAMPMFormat($role->created_at) }}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '2')->first()->status == 2)
                        <a href="{{ route('role.edit', $role->id) }}"><i class="bi bi-pencil-square me-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Role"></i></a>
                        {{-- <a href="javascript:void(0)"><i class="bi bi-trash me-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit "></i></a> --}}
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
