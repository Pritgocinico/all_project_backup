<table class="table table-hover table-striped table-sm table-nowrap table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Shift Detail</th>
            <th>Phone Number</th>
            <th>Department Name</th>
            <th>Designation Name</th>
            <th>Salary</th>
            <th>Role Name</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($userList as $key=>$user)
            <tr>
                <td>{{ $userList->firstItem() + $key }}</td>
                <td><a href="{{ route('user.show', $user->id) }}">{{ $user->name }}</a></td>
                <td>
                    {{ $user->email }}
                </td>
                <td>
                    {{ isset($user->shiftDetail) ? $user->shiftDetail->shift_name ." (". Utility::convertHIAFormat($user->shiftDetail->shift_start_time) ." - ".Utility::convertHIAFormat($user->shiftDetail->shift_end_time) .")" : '-' }}
                </td>
                <td>
                    {{ $user->phone_number }}
                </td>
                <td>
                    {{ isset($user->departmentDetail) ? $user->departmentDetail->name:"-" }}
                </td>
                <td>
                    {{ isset($user->designationDetail) ? $user->designationDetail->name:"-" }}
                </td>
                <td>
                    {{ $user->employee_salary }}
                </td>
                <td>{{ isset($user->roleDetail) ? ucfirst($user->roleDetail->name) : '' }}</td>
                <td>{{Utility::convertDmyAMPMFormat($user->created_at)}}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '2')->first()->status == 2)
                        <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#"
                                role="button" data-bs-toggle="dropdown" aria-haspopup="false"
                                aria-expanded="false"><button type="button"
                                    class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                        class="bi bi-three-dots"></i></button></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('user.edit', $user->id) }}"><i
                                        class="bi bi-pencil me-3"></i>Edit Employee</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"
                                    onclick="deleteUser({{ $user->id }})"><i
                                        class="bi bi-trash me-3"></i>Delete Employee </a>
                            </div>
                        </div>
                    @endif

                </td>

            </tr>
        @empty
            <tr>
                <td colspan="11" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end me-2 mt-2">
    {{ $userList->links() }}
</div>