@if (collect($accesses)->where('menu_id', '6')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '7')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '8')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '9')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '10')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '11')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '12')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '13')->first()->status != 0)

    
        <li class="nav-item my-1"><a
                class="nav-link d-flex align-items-center  {{ request()->routeIs('incentive.*') || request()->routeIs('holiday.*') || request()->routeIs('leave.*') || request()->routeIs('ticket.*') || request()->routeIs('attendance.*') || request()->routeIs('certificate.*') || request()->routeIs('salary-slip.*') || request()->routeIs('daily_attendance') ? 'active collapsed' : '' }}"
                href="#sidebar-account-hr" data-bs-toggle="collapse" role="button"
                aria-expanded="{{ request()->routeIs('incentive.*') || request()->routeIs('holiday.*') || request()->routeIs('leave.*') || request()->routeIs('ticket.*') || request()->routeIs('attendance.*') || request()->routeIs('certificate.*') || request()->routeIs('salary-slip.*') || request()->routeIs('daily_attendance') ? 'true' : 'false' }}"
                aria-controls="sidebar-account">
                <i class="fa-solid fa-people-group"></i>
                <span>Human Resource</span>
                <span class="badge badge-sm rounded-pill me-n2 bg-success-subtle text-success ms-auto"></span>
            </a>
            <div class="collapse {{ request()->routeIs('incentive.*') || request()->routeIs('holiday.*') || request()->routeIs('leave.*') || request()->routeIs('ticket.*') || request()->routeIs('attendance.*') || request()->routeIs('certificate.*') || request()->routeIs('salary-slip.*') || request()->routeIs('daily_attendance') ? 'show' : '' }}"
                id="sidebar-account-hr">
                <ul class="nav nav-sm flex-column mt-1 inner-ul">
                    @if (collect($accesses)->where('menu_id', '6')->first() &&
                            collect($accesses)->where('menu_id', '6')->first()->status != 0)
                        <li class="nav-item"><a href="{{ route('incentive.index') }}"
                                class="nav-link {{ request()->routeIs('incentive.*') ? 'fw-bold' : '' }}"><i class="fa fa-circle-info" aria-hidden="true"></i>Incentive</a>
                        </li>
                    @endif
                    @if (collect($accesses)->where('menu_id', '8')->first() &&
                            collect($accesses)->where('menu_id', '8')->first()->status != 0)
                        <li class="nav-item"><a href="{{ route('holiday.index') }}"
                                class="nav-link {{ request()->routeIs('holiday.*') ? 'fw-bold' : '' }}"><i class="fa-solid fa-wand-sparkles"></i>
                                @if (Auth()->user()->role_id !== '1')
                                    Company
                                @endif Holiday
                            </a>
                        </li>
                    @endif
                    @if (collect($accesses)->where('menu_id', '7')->first() &&
                            collect($accesses)->where('menu_id', '7')->first()->status != 0)
                        <li class="nav-item"><a href="{{ route('leave.index') }}"
                                class="nav-link {{ request()->routeIs('leave.*') ? 'fw-bold' : '' }}"><i class="fa-solid fa-person-walking-arrow-right"></i>
                                @if (Auth()->user()->role_id !== '1')
                                    Apply
                                @endif Leave
                            </a>
                        </li>
                    @endif
                    @if (collect($accesses)->where('menu_id', '9')->first() &&
                            collect($accesses)->where('menu_id', '9')->first()->status != 0)
                        <li class="nav-item"><a href="{{ route('ticket.index') }}"
                                class="nav-link {{ request()->routeIs('ticket.*') ? 'fw-bold' : '' }}"><i class="fa-solid fa-ticket"></i>
                                @if (Auth()->user()->role_id !== '1')
                                    Raised a
                                @endif Ticket
                            </a>
                        </li>
                    @endif
                    @if (collect($accesses)->where('menu_id', '10')->first() &&
                            collect($accesses)->where('menu_id', '10')->first()->status != 0)
                        <li class="nav-item"><a href="{{ route('attendance.index') }}"
                                class="nav-link {{ request()->routeIs('attendance.*') ? 'fw-bold' : '' }}"><i class="fa-solid fa-clipboard-user"></i>
                                @if (Auth()->user()->role_id !== '1')
                                    My
                                @endif Attendance List
                            </a>
                        </li>
                    @endif
                    @if (collect($accesses)->where('menu_id', '11')->first() &&
                            collect($accesses)->where('menu_id', '11')->first()->status != 0)
                        <li class="nav-item"><a href="{{ route('daily_attendance') }}"
                                class="nav-link {{ request()->routeIs('daily_attendance') ? 'fw-bold' : '' }}"><i class="fa-solid fa-elevator"></i>
                                @if (Auth()->user()->role_id !== '1')
                                    My
                                @endif Daily
                                Attendance
                            </a>
                        </li>
                    @endif
                    @if (collect($accesses)->where('menu_id', '12')->first() &&
                            collect($accesses)->where('menu_id', '12')->first()->status != 0)
                        <li class="nav-item"><a href="{{ route('certificate.index') }}"
                                class="nav-link {{ request()->routeIs('certificate.*') ? 'fw-bold' : '' }}"><i class="fa-solid fa-certificate"></i> Certificate</a>
                        </li>
                    @endif
                    @if (collect($accesses)->where('menu_id', '13')->first() &&
                            collect($accesses)->where('menu_id', '13')->first()->status != 0)
                        <li class="nav-item"><a href="{{ route('salary-slip.index') }}"
                                class="nav-link {{ request()->routeIs('salary-slip.*') ? 'fw-bold' : '' }}"><i class="fa-solid fa-clipboard-check"></i>Salary
                                Slip</a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>
@endif
