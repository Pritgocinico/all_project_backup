            <!-- ========== Left Sidebar Start ========== -->
            <div class="leftside-menu">

                <!-- Brand Logo Light -->
                <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
                    <span class="logo-lg">
                        <img src="{{ asset('/storage/' . $setting->logo) }}" alt="logo" height="70px">
                    </span>
                    <span class="logo-sm">
                        <img src="{{ url('/') }}/assets/images/logo-sm.png" alt="small logo">
                    </span>
                </a>

                <!-- Brand Logo Dark -->
                <a href="" class="logo logo-dark">
                    <span class="logo-lg">
                        <img src="{{ url('/') }}/assets/images/logo-dark.png" alt="dark logo">
                    </span>
                    <span class="logo-sm">
                        <img src="{{ url('/') }}/assets/images/logo-sm.png" alt="small logo">
                    </span>
                </a>

                <!-- Sidebar -left -->
                <div class="h-100" id="leftside-menu-container" data-simplebar>
                    <!--- Sidemenu -->
                    <ul class="side-nav">
                        <li class="side-nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="side-nav-link">
                                <i class="ri-dashboard-3-line"></i>
                                <span> Dashboard </span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('payment-detail') }}"
                                class="side-nav-link @if (\Request::route()->getName() == 'payment-detail') active @endif">
                                <i class="fa fa-credit-card" aria-hidden="true"></i>
                                <span>Payment Detail</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('admin.orders') }}"
                                class="side-nav-link @if (\Request::route()->getName() == 'admin.orders') active @endif">
                                <i class="fas fa-folder"></i>
                                <span>Orders</span>
                            </a>
                        </li>
                        {{-- <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarPages" aria-expanded="false" aria-controls="sidebarPages" class="side-nav-link">
                                <i class="ri-share-line"></i>
                                <span> Inquiries </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarPages">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="{{route('inquiries.index')}}">ContactUs Page Inquiry</a>
                                    </li>
                                    <li>
                                        <a href="{{route('homeinquiries.index')}}">Home Page Inquiries</a>
                                    </li>
                                    <li>
                                        <a href="">Help</a>
                                    </li>
                                </ul>
                            </div>
                        </li> --}}
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarProduct" aria-expanded="false" aria-controls="sidebarPagesAuth" class="side-nav-link">
                                <i class="fas fa-box"></i>
                                <span> Products </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarProduct">
                                <ul class="side-nav-second-level">
                                    <li class="side-nav-item">
                                        <a href="{{ route('admin.product-details.index') }}"
                                            class="side-nav-link @if (
                                                \Request::route()->getName() == 'admin.product.create' ||
                                                    \Request::route()->getName() == 'admin.product-details.edit') active @endif">
                                            <span>Our Products</span>
                                        </a>
                                    </li>
                                    <li class="side-nav-item">
                                        <a href="{{ route('categories.index') }}"
                                            class="side-nav-link @if (\Request::route()->getName() == 'categories.create' || \Request::route()->getName() == 'categories.edit') active @endif">
                                            {{-- <i class="fas fa-th-large"></i> <!-- Use the 'th-large' icon class for Categories --> --}}
                                            <span>Categories</span>
                                        </a>
                                    </li>
                                    <li class="side-nav-item">
                                        <a href="{{ route('dosage.index') }}"
                                            class="side-nav-link @if (\Request::route()->getName() == 'dosage.create' || \Request::route()->getName() == 'dosage.edit') active @endif">
                                            {{-- <i class="fas fa-pills"></i> <!-- Use the 'pills' icon class for Dosage Forms --> --}}
                                            <span>Dosage Forms</span>
                                        </a>
                                    </li>
                                    <li class="side-nav-item">
                                        <a href="{{ route('admin.lots.files') }}"
                                            class="side-nav-link @if (\Request::route()->getName() == 'admin.lots.files') active @endif">
                                            {{-- <i class="fas fa-folder"></i> --}}
                                            <span>Resource</span>
                                        </a>
                                    </li>

                                </ul>
                            </div>  
                        </li>
                        {{-- <li class="side-nav-item">
                            <a href="{{ route('inquiries.index') }}"
                                class="side-nav-link @if (\Request::route()->getName() == 'inquiries.show') active @endif">
                                <i class="ri-mail-line"></i>
                                <span> Contact Inquiry </span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('homeinquiries.index') }}"
                                class="side-nav-link @if (\Request::route()->getName() == 'homeinquiries.index') active @endif">
                                <i class="fas fa-home"></i>
                                <span> Home Inquiries </span>
                            </a>
                        </li> --}}
                        
                        <li class="side-nav-item">
                            <a href="{{ route('admin.doctors.index') }}"
                                class="side-nav-link @if (\Request::route()->getName() == 'admin.doctors.create' || \Request::route()->getName() == 'admin.doctor.edit') active @endif">
                                <i class="fas fa-user-md"></i>
                                <!-- Assuming 'fas fa-user-md' is the icon for Doctor Users -->
                                <span>Doctors</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarPagesAuth" aria-expanded="false" aria-controls="sidebarPagesAuth" class="side-nav-link">
                                <i class="ri-group-2-line"></i>
                                <span> Inquiries </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarPagesAuth">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="{{route('inquiries.index')}}">Contact Inquiry</a>
                                    </li>
                                    <li>
                                        <a href="{{route('homeinquiries.index')}}">Inquiries</a>
                                    </li>
                                    <li>
                                        <a href="{{route('helpinquiries.index')}}">Help</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('admin.lots.index') }}"
                                class="side-nav-link @if (
                                    \Request::route()->getName() == 'admin.lots.index' ||
                                        \Request::route()->getName() == 'admin.lots.create' ||
                                        \Request::route()->getName() == 'admin.lots.edit') active @endif">
                                <i class="fas fa-file"></i>
                                <span>Lots</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('admin.users.index') }}"
                                class="side-nav-link @if (\Request::route()->getName() == 'admin.users.create' || \Request::route()->getName() == 'admin.users.edit') active @endif">
                                <i class="ri-user-line"></i>
                                <span> Users </span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('coupon.index') }}"
                                class="side-nav-link @if (\Request::route()->getName() == 'coupon.create' || \Request::route()->getName() == 'coupon.edit') active @endif">
                                <i class="fa fa-certificate" aria-hidden="true"></i>
                                <span>Coupon Detail</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('permission.index') }}"
                                class="side-nav-link @if (\Request::route()->getName() == 'permission.create' || \Request::route()->getName() == 'permission.edit') active @endif">
                                <i class="fas fa-key" aria-hidden="true"></i>
                                <span>Permission</span>
                            </a>
                        </li>
                    </ul>
                    <!--- End Sidemenu -->

                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- ========== Left Sidebar End ========== -->
