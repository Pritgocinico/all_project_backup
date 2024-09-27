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
        @foreach ($roleList as $key=>$role)
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
                            <b>{{ str_replace("_"," ",Str::ucfirst($access->menu->name)) }}</b>
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
                        <a href="{{ route('role.edit', $role->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Role"><i class="fa-solid fa-pen-to-square me-3"></i></a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
