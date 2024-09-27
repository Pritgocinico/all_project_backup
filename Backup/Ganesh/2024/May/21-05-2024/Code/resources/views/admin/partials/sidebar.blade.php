<div class="content-wrapper">
    <div class="navigation">
        <div class="navigation-header">
            <span>Navigation</span>
            <a href="#">
                <i class="ti-close"></i>
            </a>
        </div>
        <div class="navigation-menu-body">
            <ul>
                <li>
                    <a class="@if(\Request::route()->getName() == 'admin.dashboard') active @endif" href="{{route('admin.dashboard')}}">
                        <span class="nav-link-icon">
                            <i data-feather="pie-chart"></i>
                        </span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="nav-link-icon">
                            <i data-feather="file"></i>
                        </span>
                        <span>Users</span>
                    </a>
                    <ul>
                        <li>
                            <a class="@if(\Request::route()->getName() == 'admin.add.admin') active @endif" href="{{route('admin.add.admin')}}">Admins</a>
                        </li>
                       <li>
                            <a class="@if(\Request::route()->getName() == 'admin.add.measurement') active @endif" href="{{route('admin.add.measurement')}}">Measurement</a>
                        </li>
                        <li>
                            <a class="@if(\Request::route()->getName() == 'quotation.create') active @endif" href="{{route('quotation.create')}}">Quotation</a>
                        </li>
                        <li>
                            <a class="@if(\Request::route()->getName() == 'header.workshop.create') active @endif" href="{{route('header.workshop.create')}}">Workshop</a>
                        </li>
                        <li>
                            <a class="@if(\Request::route()->getName() == 'header.fitting.create') active @endif" href="{{route('header.fitting.create')}}">Fitting</a>
                        </li>
                    </ul>
                </li>
                <!-- <li>
                    <a class="@if(\Request::route()->getName() == 'admin.users') active @endif" href="{{route('admin.users')}}">
                        <span class="nav-link-icon">
                            <i data-feather="users"></i>
                        </span>
                        <span>Users</span>
                    </a>
                </li> -->
                <li>
                    <a class="@if(\Request::route()->getName() == 'admin.customers') active @endif" href="{{route('admin.customers')}}">
                        <span class="nav-link-icon">
                            <i data-feather="users"></i>
                        </span>
                        <span>Customer</span>
                    </a>
                </li>
                <!--<li>-->
                <!--    <a class="@if(\Request::route()->getName() == 'admin.add.admin') active @endif" href="{{route('admin.add.admin')}}">-->
                <!--        <span class="nav-link-icon">-->
                <!--            <i data-feather="users"></i>-->
                <!--        </span>-->
                <!--        <span>Admin</span>-->
                <!--    </a>-->
                <!--</li>-->
<!--                <li>-->
<!--    <a class="@if(\Request::route()->getName() == 'admin.add.measurement') active @endif" href="{{route('admin.add.measurement')}}">-->
<!--        <span class="nav-link-icon">-->
<!--            <svg data-feather="activity" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">-->
<!--            </svg>-->
<!--        </span>-->
<!--        <span>Measurement</span>-->
<!--    </a>-->
<!--</li>-->

<!--<li>-->
<!--    <a class="@if(\Request::route()->getName() == 'quotation.create') active @endif" href="{{route('quotation.create')}}">-->
<!--        <span class="nav-link-icon">-->
<!--            <svg data-feather="file-text" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">-->
<!--            </svg>-->
<!--        </span>-->
<!--        <span>Quotation</span>-->
<!--    </a>-->
<!--</li>-->

<!--<li>-->
<!--    <a class="@if(\Request::route()->getName() == 'header.workshop.create') active @endif" href="{{route('header.workshop.create')}}">-->
<!--        <span class="nav-link-icon">-->
<!--            <svg data-feather="settings" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">-->
<!--            </svg>-->
<!--        </span>-->
<!--        <span>Workshop</span>-->
<!--    </a>-->
<!--</li>-->

<!--<li>-->
<!--    <a class="@if(\Request::route()->getName() == 'header.fitting.create') active @endif" href="{{route('header.fitting.create')}}">-->
<!--        <span class="nav-link-icon">-->
<!--            <svg data-feather="scissors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">-->
<!--            </svg>-->
<!--        </span>-->
<!--        <span>Fitting</span>-->
<!--    </a>-->
<!--</li>-->

                <li>
                    <a class="@if(\Request::route()->getName() == 'leads' || \Request::route()->getName() == 'view.project'|| \Request::route()->getName() == 'view.measurement'|| \Request::route()->getName() == 'view.quotation'|| \Request::route()->getName() == 'view.workshop'|| \Request::route()->getName() == 'view.fitting' || \Request::route()->getName() == 'view.project' || \Request::route()->getName() == 'view.lead' || \Request::route()->getName() == 'addleads' || \Request::route()->getName() == 'edit.leads' || \Request::route()->getName() == 'view.lead.project') @if(isset($type) && $type == 'Lead') active @endif @endif" href="{{route('leads')}}">
                        <span class="nav-link-icon">
                            <i data-feather="list"></i>
                        </span>
                        <span>Leads</span>
                    </a>
                </li>
                <li>
                    <a class="@if(\Request::route()->getName() == 'projects'|| \Request::route()->getName() == 'addprojects'|| \Request::route()->getName() == 'edit.project'|| \Request::route()->getName() == 'view.project'|| \Request::route()->getName() == 'view.measurement'|| \Request::route()->getName() == 'view.quotation'|| \Request::route()->getName() == 'view.workshop'|| \Request::route()->getName() == 'view.fitting' || \Request::route()->getName() == 'view.material' || \Request::route()->getName() == 'view.lead' || \Request::route()->getName() == 'view.complete' || \Request::route()->getName() == 'view.completed.project') @if(isset($type) && $type == 'Project') active @endif @endif" href="{{route('projects')}}">
                        <span class="nav-link-icon">
                            <i data-feather="copy"></i>
                        </span>
                        <span>Projects</span>
                    </a>
                </li>
                {{-- <li>
                    <a class="" href="{{url('/')}}">
                        <span class="nav-link-icon">
                            <i class="ti-ruler-alt"></i>
                        </span>
                        <span>Measurement</span>
                    </a>
                </li> --}}
                <li>
                    <a href="{{route('task-management')}}" class="@if(\Request::route()->getName() == 'task-management' || \Request::route()->getName() == 'addTask' || \Request::route()->getName() == 'editTask') active @endif">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Task Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('feedbacks')}}" class="@if(\Request::route()->getName() == 'feedbacks' || \Request::route()->getName() == 'viewFeedback') active @endif">
                        <span class="nav-link-icon">
                            <i data-feather="corner-up-right"></i>
                        </span>
                        <span>Feedback</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('reports')}}" class="@if(\Request::route()->getName() == 'reports') active @endif">
                        <span class="nav-link-icon">
                            <i data-feather="file"></i>
                        </span>
                        <span>Reports</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('logs')}}" class="@if(\Request::route()->getName() == 'logs') active @endif">
                        <span class="nav-link-icon">
                            <i data-feather="file"></i>
                        </span>
                        <span>Logs</span>
                    </a>
                </li>
                {{-- <li>
                    <a href="#">
                        <span class="nav-link-icon">
                            <i data-feather="settings"></i>
                        </span>
                        <span>Settings</span>
                    </a>
                    <ul>
                        <li>
                            <a href="">Basic Forms</a>
                        </li>
                        <li>
                            <a href="">Custom Forms</a>
                        </li>
                        <li>
                            <a href="">Advanced Forms</a>
                        </li>
                        <li>
                            <a href="">Form Validation</a>
                        </li>
                        <li>
                            <a href="">Form Wizard</a>
                        </li>
                        <li>
                            <a href="">Form Repeater</a>
                        </li>
                        <li>
                            <a href="">File Upload</a>
                        </li>
                        <li>
                            <a href="">CKEditor</a>
                        </li>
                        <li>
                            <a href="">Datepicker</a>
                        </li>
                        <li>
                            <a href="">Timepicker</a>
                        </li>
                        <li>
                            <a href="">Colorpicker</a>
                        </li>
                    </ul>
                </li> --}}
            </ul>
        </div>
    </div>
    <div class="content-body">
        <div class="content ">
