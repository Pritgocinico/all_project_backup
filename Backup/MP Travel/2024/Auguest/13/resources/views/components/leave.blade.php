@if (collect($accesses)->where('menu_id', '8')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '9')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '10')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '11')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '12')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '13')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '14')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '15')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '17')->first()->status != 0)

    <li class="nav-item my-1"><a
            class="nav-link d-flex align-items-center  {{ request()->routeIs('info_sheet.*') || request()->routeIs('holiday.*') || request()->routeIs('leave.*') || request()->routeIs('ticket.*') || request()->routeIs('attendance.*') || request()->routeIs('certificate.*') || request()->routeIs('shift-time.*') || request()->routeIs('salary-slip.*') || request()->routeIs('attendance.daily_attendance.*') ? 'active collapsed' : '' }}"
            href="#sidebar-account-hr" data-bs-toggle="collapse" role="button"
            aria-expanded="{{ request()->routeIs('info_sheet.*') || request()->routeIs('holiday.*') || request()->routeIs('leave.*') || request()->routeIs('ticket.*') || request()->routeIs('attendance.*') || request()->routeIs('certificate.*') || request()->routeIs('shift-time.*') || request()->routeIs('salary-slip.*') || request()->routeIs('attendance.daily_attendance.*') ? 'true' : 'false' }}"
            aria-controls="sidebar-account">
            <i class="bi bi-hr"></i>
            <span>Human Resource</span>
            <span class="badge badge-sm rounded-pill me-n2 bg-success-subtle text-success ms-auto"></span>
        </a>
        <div class="collapse {{ request()->routeIs('info_sheet.*') || request()->routeIs('holiday.*') || request()->routeIs('leave.*') || request()->routeIs('ticket.*') || request()->routeIs('attendance.*') || request()->routeIs('certificate.*') || request()->routeIs('shift-time.*') || request()->routeIs('salary-slip.*') || request()->routeIs('attendance.daily_attendance.*') ? 'show' : '' }}"
            id="sidebar-account-hr">
            <ul class="nav nav-sm flex-column mt-1">
                @if (collect($accesses)->where('menu_id', '8')->first() &&
                        collect($accesses)->where('menu_id', '8')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('info_sheet.index') }}" class="nav-link {{ request()->routeIs('info_sheet.*') ?"fw-bold":"" }}">Info Sheet</a>
                    </li>
                @endif
                @if (collect($accesses)->where('menu_id', '9')->first() &&
                        collect($accesses)->where('menu_id', '9')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('holiday.index') }}" class="nav-link {{ request()->routeIs('holiday.*') ?"fw-bold":"" }}">@if(Auth()->user()->role_id !== "1") Company @endif Holiday</a>
                    </li>
                @endif
                @if (collect($accesses)->where('menu_id', '10')->first() &&
                        collect($accesses)->where('menu_id', '10')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('leave.index') }}" class="nav-link {{ request()->routeIs('leave.*') ?"fw-bold":"" }}">@if(Auth()->user()->role_id !== "1") Apply @endif Leave</a>
                    </li>
                @endif
                @if (collect($accesses)->where('menu_id', '11')->first() &&
                        collect($accesses)->where('menu_id', '11')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('ticket.index') }}" class="nav-link {{ request()->routeIs('ticket.*') ?"fw-bold":"" }}">@if(Auth()->user()->role_id !== "1") Raised a @endif Ticket</a>
                    </li>
                @endif
                @if (collect($accesses)->where('menu_id', '12')->first() &&
                        collect($accesses)->where('menu_id', '12')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.*') ?"fw-bold":"" }}">@if(Auth()->user()->role_id !== "1") My @endif Attendance List</a>
                    </li>
                @endif
                @if (collect($accesses)->where('menu_id', '13')->first() &&
                        collect($accesses)->where('menu_id', '13')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('attendance.daily_attendance') }}" class="nav-link {{ request()->routeIs('attendance.daily_attendance.*') ?"fw-bold":"" }}">@if(Auth()->user()->role_id !== "1") My @endif Daily
                            Attendance</a>
                    </li>
                @endif
                @if (collect($accesses)->where('menu_id', '14')->first() &&
                        collect($accesses)->where('menu_id', '14')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('certificate.index') }}" class="nav-link {{ request()->routeIs('certificate.*') ?"fw-bold":"" }}">Certificate</a>
                    </li>
                @endif
                @if (collect($accesses)->where('menu_id', '15')->first() &&
                        collect($accesses)->where('menu_id', '15')->first()->status != 0)
                    <li class="nav-item"><a href="{{ route('salary-slip.index') }}" class="nav-link {{ request()->routeIs('salary-slip.*') ?"fw-bold":"" }}">Salary Slip</a>
                    </li>
                @endif
                @if (collect($accesses)->where('menu_id', '17')->first() &&
                        collect($accesses)->where('menu_id', '17')->first()->status != 0 && Auth()->user()->role_id == "1")
                    <li class="nav-item"><a href="{{ route('shift-time.index') }}" class="nav-link {{ request()->routeIs('shift-time.*') ?"fw-bold":"" }}">Shift Schedule</a>
                    </li>
                @endif
            </ul>
        </div>
    </li>
@endif
