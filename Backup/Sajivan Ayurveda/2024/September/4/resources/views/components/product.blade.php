@if (collect($accesses)->where('menu_id', '19')->first()->status != 0 ||
        collect($accesses)->where('menu_id', '20')->first()->status != 0)

        <li class="nav-item my-1"><a
                class="nav-link d-flex align-items-center  {{ request()->routeIs('product.*') || request()->routeIs('category.*') ? 'active collapsed' : '' }}"
                href="#sidebar-account-product" data-bs-toggle="collapse" role="button"
                aria-expanded="{{ request()->routeIs('product.*') || request()->routeIs('category.*') ? 'true' : 'false' }}"
                aria-controls="sidebar-account">
                <i class="fa-solid fa-people-group"></i>
                <span>Product</span>
                <span class="badge badge-sm rounded-pill me-n2 bg-success-subtle text-success ms-auto"></span>
            </a>
            <div class="collapse {{ request()->routeIs('product.*') || request()->routeIs('category.*') ? 'show' : '' }}"
                id="sidebar-account-product">
                <ul class="nav nav-sm flex-column mt-1 inner-ul">
                    @if (collect($accesses)->where('menu_id', '19')->first() &&
                            collect($accesses)->where('menu_id', '20')->first()->status != 0)
                        <li class="nav-item"><a href="{{ route('product.index') }}"
                                class="nav-link {{ request()->routeIs('product.*') ? 'fw-bold' : '' }}"><i class="fa fa-circle-info" aria-hidden="true"></i>Product</a>
                        </li>
                    @endif
                    @if (collect($accesses)->where('menu_id', '20')->first() &&
                            collect($accesses)->where('menu_id', '20')->first()->status != 0)
                        <li class="nav-item"><a href="{{ route('category.index') }}"
                                class="nav-link {{ request()->routeIs('category.*') ? 'fw-bold' : '' }}"><i class="fa-solid fa-rectangle-list"></i>
                                Category
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>
@endif
