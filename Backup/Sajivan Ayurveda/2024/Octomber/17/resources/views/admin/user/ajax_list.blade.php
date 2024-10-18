<table class="table table-hover  table-scrolling table-sm table-nowrap table-responsive mt-6 border" id="employee_table">
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Role Name</th>
            <th>Department / Designation Name</th>
            @if (Auth::user()->role_id == 1)
                <th>Login Id</th>
                <th>Login Password</th>
            @endif
            <th>Status</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($userList as $key => $user)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td><a href="{{ route('user.show', $user->id) }}">{{ $user->name }}</a></td>
                <td>{{ isset($user->roleDetail) ? ucfirst($user->roleDetail->name) : '' }}</td>
                <td>
                    {{ isset($user->departmentDetail) ? $user->departmentDetail->name : '-' }} <br />

                    {{ isset($user->designationDetail) ? $user->designationDetail->name : '-' }}
                </td>
                @if (Auth::user()->role_id == 1)
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->original_password }}</td>
                @endif
                <td>
                    @php
                    $text = $user->status == 1 ? 'Active' : 'Inactive';
                    $color = $user->status == 1 ? 'success' : 'danger';
                    $status = $user->status == 1 ? 2 : 1;
                @endphp
                 <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" data-id="{{ $user->id }}" data-status="{{ $status }}" name="status" id="status" onchange="changeUserStatus(this)" {{ $user->status == 1 ? 'checked' : '' }}>
                 </div>
                </td>
                <td class="text-end">
                    <div class="icon-td">
                        @if (collect($accesses)->where('menu_id', '5')->first()->status == 2)
                            <div class="icon-td">
                                <a href="{{ route('user.edit', $user->id) }}" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Edit Employee"><i
                                        class="fa-solid fa-pen-to-square me-3"></i></a>
                                <a href="javscript:void(0)" onclick="deleteUser({{ $user->id }})"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Employee"><i
                                        class="fa fa-trash-can me-3"></i></a>
                            </div>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
