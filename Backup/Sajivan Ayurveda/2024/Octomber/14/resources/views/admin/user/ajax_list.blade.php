
    <table class="table table-hover  table-scrolling table-sm table-nowrap table-responsive mt-6 border" id="employee_table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Role Name</th>
                <th>Phone Number</th>
                <th>Department / Designation Name</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($userList as $key=>$user)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td><a href="{{ route('user.show', $user->id) }}">{{ $user->name }}</a></td>
                    <td>{{ isset($user->roleDetail) ? ucfirst($user->roleDetail->name) : '' }}</td>
                    <td>
                        {{ $user->phone_number }}
                    </td>
                    <td>
                        {{ isset($user->departmentDetail) ? $user->departmentDetail->name : '-' }} <br />

                        {{ isset($user->designationDetail) ? $user->designationDetail->name : '-' }}
                    </td>
                    <td class="text-end">
                    <div class="icon-td">
                        @if (collect($accesses)->where('menu_id', '5')->first()->status == 2)
                            <div class="icon-td">
                                <a href="{{ route('user.edit', $user->id) }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Edit Employee"><i class="fa-solid fa-pen-to-square me-3"></i></a>
                                <a href="javscript:void(0)" onclick="deleteUser({{ $user->id }})" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Delete Employee"><i class="fa fa-trash-can me-3"></i></a>
                            </div>
                        @endif
                    </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
