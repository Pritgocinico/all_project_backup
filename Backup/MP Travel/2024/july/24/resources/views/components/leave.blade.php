@if (collect($accesses)->where('menu_id', '8')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '9')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '10')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '11')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '12')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '13')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '14')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '15')->first()->status != 0)
    <li class="nav-item my-1"><a class="nav-link d-flex align-items-center rounded-pill" href="#sidebar-account-hr"
            data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebar-account">
            <i class="bi bi-hr"></i>
            <span>Human Resource</span>
            <span class="badge badge-sm rounded-pill me-n2 bg-success-subtle text-success ms-auto"></span>
        </a>
        <div class="collapse" id="sidebar-account-hr">
            <ul class="nav nav-sm flex-column mt-1">
                @if (collect($accesses)->where('menu_id', '8')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('infosheet.index') }}" class="nav-link">Info Sheet</a>
                    </li>
                @endif
                @if (collect($accesses)->where('menu_id', '9')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('holiday.index') }}" class="nav-link">Holiday</a>
                    </li>
                @endif
                @if (collect($accesses)->where('menu_id', '10')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('leave.index') }}" class="nav-link">Leave</a>
                    </li>
                @endif
                @if (collect($accesses)->where('menu_id', '11')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('ticket.index') }}" class="nav-link">Ticket</a>
                    </li>
                @endif
                @if (collect($accesses)->where('menu_id', '12')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('attendance.index') }}" class="nav-link">Attendance</a>
                    </li>
                @endif
                @if (collect($accesses)->where('menu_id', '13')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('attendance.daily_attendance') }}" class="nav-link">Daily
                            Attendance</a>
                    </li>
                @endif
                @if (collect($accesses)->where('menu_id', '14')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('certificate.index') }}" class="nav-link">Certificate</a>
                    </li>
                @endif
                @if (collect($accesses)->where('menu_id', '15')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('salary-slip.index') }}" class="nav-link">Salary Slip</a>
                    </li>
                @endif
            </ul>
        </div>
    </li>
@endif
