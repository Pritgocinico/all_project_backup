<table class="table table-hover table-striped table-sm table-nowrap table-responsive">
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
                <td>{{ $roleList->firstItem() + $key }}</td>
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
                        <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#"
                                role="button" data-bs-toggle="dropdown" aria-haspopup="false"
                                aria-expanded="false"><button type="button"
                                    class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                        class="bi bi-three-dots"></i></button></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('role.edit', $role->id) }}"><i
                                        class="bi bi-pencil me-3"></i>Edit Role</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"
                                    onclick="deleteRole({{ $role->id }})"><i
                                        class="bi bi-trash me-3"></i>Delete
                                    Role </a>
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
<div class="d-flex justify-content-end me-2 mt-2">
    {{ $roleList->links() }}
</div>