<table class="table table-hover table-sm table-nowrap table-scrolling table-responsive mt-6 border" id="role_table">
    <thead>
        <tr>
            <th>No</th>
            <th>Role Name</th>
            <th>Given Permission</th>
            <th>Not Given Permission</th>
            <th>Created At</th>
            <th>Status</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($roleList as $key => $role)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $role->name }}</td>
                <td>
                    @php
                        $count = 0;
                    @endphp
                    @foreach ($role->access as $key => $access)
                        @if (isset($access->menu->name))
                            <b>{{ str_replace('_', ' ', Str::ucfirst($access->menu->name)) }}</b>(
                            @if ($access->disable == 0)
                                Disabled,
                            @else
                                @if ($access->view == 1)
                                    View,
                                @endif
                                @if ($access->add == 1)
                                    Add,
                                @endif
                                @if ($access->edit == 1)
                                    Edit,
                                @endif
                                @if ($access->delete == 1)
                                    Delete,
                                @endif
                                @if ($access->export == 1)
                                    Export,
                                @endif
                            @endif
                            )
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
                <td>
                    @php
                        $count = 0;
                    @endphp
                    @foreach ($role->access as $key => $access)
                        @if (isset($access->menu->name))
                            <b>{{ str_replace('_', ' ', Str::ucfirst($access->menu->name)) }}</b>(
                            @if ($access->disable == 0)
                                Disabled,
                            @else
                                @if ($access->view == 0)
                                    View,
                                @endif
                                @if ($access->add == 0)
                                    Add,
                                @endif
                                @if ($access->edit == 0)
                                    Edit,
                                @endif
                                @if ($access->delete == 0)
                                    Delete,
                                @endif
                                @if ($access->export == 0)
                                    Export,
                                @endif
                            @endif
                            )
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
                <td>
                    @php
                        $text = $role->active ? 'Active' : 'Inactive';
                        $color = $role->active ? 'success' : 'danger';
                    @endphp
                    @if (collect($accesses)->where('menu_id', '4')->where('edit', 1)->first())
                        <div class="form-check form-switch">
                            <input class="form-check-input status-btn" type="checkbox" name="active" id="status" data-id="{{ $role->id }}"
                                onclick="changeStatus(this)" {{ $role->active == 1 ? 'checked' : '' }}>
                        </div>
                    @else
                        <div class="form-check form-switch">
                            <input class="form-check-input status-btn" type="checkbox" name="active" id="status"
                                {{ $role->active == 1 ? 'checked' : '' }}>
                        </div>
                    @endif
                </td>
                <td class="text-end">
                    <div class="icon-td">
                        @if (collect($accesses)->where('menu_id', '4')->where('edit', 1)->first())
                            <a href="{{ route('role.edit', $role->id) }}" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Edit Role"><i
                                    class="fa-solid fa-pen-to-square me-3"></i></a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
