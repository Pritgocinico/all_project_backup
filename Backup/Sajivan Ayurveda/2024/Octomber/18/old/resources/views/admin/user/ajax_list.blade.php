<table class="table table-hover table-sm table-nowrap table-responsive mt-6 border" id="employee_table">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Department <br /> / Designation Name</th>
            <th>Shift Detail</th>
            <th>Phone Number</th>
            <!-- <th>Department / Designation Name</th> -->
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($userList as $key=>$user)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td><a href="{{ route('user.show', $user->id) }}">{{ $user->name }}</a></td>
                <td>{{ isset($user->departmentDetail) ? ucfirst($user->departmentDetail->name) : '' }} <br />  {{ isset($user->designationDetail) ? "/ " .ucfirst($user->designationDetail->name) : '' }}</td>
                <td>
                    {{ isset($user->shiftDetail) ? $user->shiftDetail->shift_name . ' (' . Utility::convertHIAFormat($user->shiftDetail->shift_start_time) . ' - ' . Utility::convertHIAFormat($user->shiftDetail->shift_end_time) . ')' : '-' }}
                </td>
                <td>
                    {{ $user->phone_number }}
                </td>
                <!-- <td>
                    {{ isset($user->departmentDetail) ? $user->departmentDetail->name : '-' }} <br />

                    {{ isset($user->designationDetail) ? $user->designationDetail->name : '-' }}
                </td> -->
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '2')->first()->status == 2)
                        <a href="{{ route('user.edit', $user->id) }}" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Edit Employee"><i class="fa-solid fa-pen-to-square me-3"></i></a>
                        <a href="javscript:void(0)" onclick="deleteUser({{ $user->id }})" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Delete Employee"><i class="fa fa-trash-can me-3"></i></a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
