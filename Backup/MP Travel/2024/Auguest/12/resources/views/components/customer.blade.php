    <li class="nav-item my-1"><a
            class="nav-link d-flex align-items-center rounded-pill {{ request()->routeIs('customer.*') || request()->routeIs('general-insurance-customer') || request()->routeIs('travel-customer') ? 'active collapsed' : '' }}"
            href="#sidebar-account-cust" data-bs-toggle="collapse" role="button"
            aria-expanded="{{ request()->routeIs('customer.*') || request()->routeIs('general-insurance-customer') || request()->routeIs('travel-customer') ? 'true' : 'false' }}" aria-controls="sidebar-account">
            <i class="bi bi-person-square"></i>
            <span>Customer</span>
            <span class="badge badge-sm rounded-pill me-n2 bg-success-subtle text-success ms-auto"></span>
        </a>
        <div class="collapse {{ request()->routeIs('customer.*')|| request()->routeIs('general-insurance-customer') || request()->routeIs('travel-customer') ? 'show' : '' }}" id="sidebar-account-cust">
            <ul class="nav nav-sm flex-column mt-1">
                <li class="nav-item"><a href="{{ route('customer.index') }}"
                        class="nav-link {{ request()->routeIs('customer.*') ? 'fw-bold' : '' }}">investments</a>
                </li>
                <li class="nav-item"><a href="{{ route('general-insurance-customer') }}"
                        class="nav-link {{ request()->routeIs('general-insurance-customer') ? 'fw-bold' : '' }}">General Inurance</a>
                </li>
                <li class="nav-item"><a href="{{ route('travel-customer') }}"
                        class="nav-link {{ request()->routeIs('travel-customer') ? 'fw-bold' : '' }}">Travel</a>
                </li>
            </ul>
        </div>
    </li>